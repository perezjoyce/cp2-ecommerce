$(document).ready( () => {

	// SHOWING ITEMS THAT CORRESPOND TO THE SELECTED CATEGORY
	function showCategories(categoryId) {
			// alert(categoryId);
		$.ajax({
			url: "../controllers/show_items.php",
			method: "POST",
			data: {categoryId:categoryId},
			success: (data) => {
				$('#products').html('');
				$('#products').html(data);
			}
		})
	}


  	// SEARCH BAR
  	$("#search").keyup(function(){
  		let word = $(this).val();

  		//AJAX
  		//parameters: saan ibabato, ano ibabato, what will happen afterwards
  		$.post("../controllers/search_items.php", {word:word},function(data){
  			$('#products').html('');
  			$("#products").html(data);
  		})
  	});

	
	//ARRANGING ITEMS ACCORDING TO PRICE
  	$(document).on("change", "#priceOrder", function(){
  		let value = $(this).val();

  		//AJAX
  		$.post("../controllers/search_price.php", {value:value},function(data){
  			$("#products").html(data);
  		})
	});

	

	// ROUNDING OFF
	function round(value, exp) {
		if (typeof exp === 'undefined' || +exp === 0)
		  return Math.round(value);
	  
		value = +value;
		exp = +exp;
	  
		if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
		  return NaN;
	  
		// Shift
		value = value.toString().split('e');
		value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));
	  
		// Shift back
		value = value.toString().split('e');
		return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
	}

  	
    // REGISTRATION
	$("#btn_register").click(()=>{
		
		//get values
		let fname = $("#fname").val();
		let lname = $("#lname").val();
		let address = $("#address").val();
		let email = $("#email").val();
		let username = $("#username").val();
		let password = $("#password").val();
		let cpass = $("#cpass").val();
		let countU = username.length;
		let countP = password.length;
		

		let error_flag = 0;

		//First name verification
		if(fname == ""){
			$("#fname").next().html("First name is required!");
			error_flag = 1;
		} else {
			$("#fname").next().html("");
		}

		//Last name verification
		if(lname == ""){
			$("#lname").next().html("Last name is required!");
			error_flag = 1;
		} else {
			$("#lname").next().html("");
		}

		//address verification
		if(address == ""){
			$("#address").next().html("Address is required!");
			error_flag = 1;
		} else {
			$("#address").next().html("");
		}

		//email verification
		if(email == ""){
			$("#email").next().html("Email address is required!");
			error_flag = 1;
		} else {
			$("#email").next().html("");
		}

		//username verification
		if(username == ""){
			$("#username").next().html("Username is required!");
			error_flag = 1;
		} else if (countU < 5) {
			$("#username").next().html("Username should at least 5 characters!");
			error_flag = 1;
		} else {
			$("#username").next().html("");
		}

		//password verification
		if(password == ""){
			$("#password").next().html("Password is required!");
			error_flag = 1;
		} else if (countP < 8) {
			$("#password").next().html("Password should have more than 8 characters!");
			error_flag = 1;
		} else {
			$("#password").next().html("");
		}

		//password and cpass verification
		if (password !== cpass) {
			$("#cpass").next().html("Password don't match!");
			error_flag = 1;
		} else {
			$("#password").next().html("");
		}

		if(error_flag == 0) {
		
			//EMAIL
			$.ajax({
				"url": "../controllers/process_email.php",
				"data": { "email" : email },
				"type": "POST",
				"success": (dataFromPHP) => {

					if (dataFromPHP == "invalidEmail") {
						$("#email").next().css("color", "red");
						$("#email").next().html("Please enter a valid email."); 
					 
					} else if (dataFromPHP == "emailExists") {
						$("#email").next().css("color", "red");
						$("#email").next().html("Email address already taken."); 

					} else {
						
						$.ajax({

						"url": "../controllers/process_register.php",
						"data": {
								"fname" : fname,
								"lname" : lname,
								"address" : address,
								"email" : email,
								"username" : username,
								"password" : password
								},
						"type": "POST",
						"success": (dataFromPHP) => {
							if (dataFromPHP == "userExists") {
								$("#username").next().css("color", "red");
								$("#username").next().html("User exists."); 
							} else if ($.parseJSON(dataFromPHP)) {
								let data = $.parseJSON(dataFromPHP);
								location.href="profile.php?id=" + data.id;
							} else {
								$("#username").next().css("color", "red");
								$("#username").next().html("Error encountered. Pls try again."); 
							}	
						}
						});

					} 
				}
			});
		}

	});


	// LOGIN
	$(document).on("click", "#btn_login", ()=> {
		
		let username = $("#username").val();
		let password = $("#password").val();

		let error_flag = 0;


		//username verification
		if(username == ""){
			$("#username").next().html("Username is required!");
			error_flag = 1;
		} else {
			$("#username").next().html("");
		}

		//password verification
		if(password == ""){
			$("#password").next().html("Password is required!");
			error_flag = 1;
		} else {
			$("#password").next().html("");
		}


		if(error_flag == 0) {
			
			$.ajax({
				"url": "../controllers/process_login.php",
				"data": {"username" : username,
						  "password" : password},
				"type": "POST",
				"success": (dataFromPHP) => {
					let response = $.parseJSON(dataFromPHP);

					if(response.status == "loggedIn") {
						let data = $.parseJSON(dataFromPHP);
						location.href="profile.php?id=" + data.id;
					} else if (response.status == "loginFailed") {
						$("#error_message").css("color", "red");
						$("#error_message").html(response.message); 
					} else {
						$("#error_message").css("color", "red");
						$("#error_message").html(response.message); 
					}
				}

			});

		} 

	});

	// EDITING USER PROFILE
	$(document).on('submit', '#form_edit_user', function(e){
		e.preventDefault();
		processEditForm();
	});


	function processEditForm() {
		//get values
		let fname = $("#fname").val();
		let lname = $("#lname").val();
		let email = $("#email").val();
		let username = $("#username").val();
		let password = $("#password").val();
		let id = $("#id").val();
		let countU = username.length;
		let countP = password.length;
		
		// console.log('id');

		let error_flag = 0;

		//First name verification
		if(fname == ""){
			$("#fname").next().html("First name is required!");
			error_flag = 1;
		} else {
			$("#fname").next().html("");
		}

		//Last name verification
		if(lname == ""){
			$("#lname").next().html("Last name is required!");
			error_flag = 1;
		} else {
			$("#lname").next().html("");
		}

		//email verification
		if(email == ""){
			$("#email").next().html("Email address is required!");
			error_flag = 1;
		} else {
			$("#email").next().html("");
		}

		//username verification
		if(username == ""){
			$("#username").next().html("Username is required!");
			error_flag = 1;
		} else {
			$("#username").next().html("");
		}

		//password verification
		if(password == ""){
			$("#password").next().html("Password is required!");
			error_flag = 1;
		} else {
			$("#password").next().html("");
		}


		if(error_flag == 0) {
		
			//CHECK EMAIL VALIDITY AND AVAILABILITY
			//WHERE id != $id coz it might count its current id..u might need SELECT then if else
			$.ajax({
				"url": "../controllers/process_edit_email.php",
				"data": { 
						"email" : email,
						"id": id
					},
				"type": "POST",
				"success": (dataFromPHP) => {

					if (dataFromPHP == "invalidEmail") {
						$("#email").next().css("color", "red");
						$("#email").next().html("Please enter a valid email."); 
					} else if (dataFromPHP == "emailExists") {
						$("#email").next().css("color", "red");
						$("#email").next().html("Email address already taken."); 
					} else if (dataFromPHP == "sameEmail" || dataFromPHP == "success"){
						
						// CHECK USERNAME AVAILABILITY
						$.ajax({
						"url": "../controllers/process_edit_uname.php",
						"data": {
								"username" : username,
								"id": id
								},
						"type": "POST",
						"success": (dataFromPHP) => {
							if (dataFromPHP == "userExists") {
								$("#username").next().css("color", "red");
								$("#username").next().html("User exists."); 
							} else if (dataFromPHP == "success" || dataFromPHP == "sameUser") {
								
								// CHECK CORRECTNESS OF PASSWORD AND IF CORRECT UPDATE DATA
								$.ajax({
									"url": "../controllers/process_edit_user.php",
									"data": { 
											"id" : id,
											"fname" : fname,
											"lname" : lname,
											"email" : email,
											"username" : username,
											"password" : password
											 },
									"type": "POST",
									"success": (dataFromPHP) => {
					
										if (dataFromPHP == "incorrectPassword") {
											$("#password").next().css("color", "red");
											$("#password").next().html("Incorrect password."); 
										 
										} else if ($.parseJSON(dataFromPHP)) {
											let data = $.parseJSON(dataFromPHP);
											location.href="profile.php?id=" + data.id; 
					
										} else {
											$("#edit_user_error").css("color", "red");
											$("#edit_user_error").append("Error in password validation encountered.");	
										} 
									}
								});


							} else {
								$("#edit_user_error").css("color", "red");
								$("#edit_user_error").append("Error in username validation encountered."); 
							}	
						}
						});

					} else {
						$("#edit_user_error").css("color", "red");
						$("#edit_user_error").append("Error in email validation encountered."); 
					}	
				}
			});
		}
	}
	
	
	// CLEAR
	$("#btn_clear").click(()=>{

	window.confirm("you sure?"); 

  		if(confirm == "ok") {
  			$("#fname").next().html("");
  			$("#lname").next().html("");
  			$("#adress").next().html("");
  			$("#email").next().html("");
  			$("#username").next().html("");
			$("#password").next().html("");
			$("#cpass").next().html("");
  		}
		

	});


	// DISPLAYING MODAL
	$('.modal-link').on('click', function(){
		const url = $(this).data('url');
		const id = $(this).data('id');

		$.get(url, {'id': id},function(response){
			//put edit_user.php content inside modal-body
			$('#modalContainer .modal-body').html("");
			$('#modalContainer .modal-body').html(response);
			$('#modalContainer').modal();
		})
	});



	// ADDING ITEMS TO CART
	$(document).on("click", "#btn_add_to_cart" ,function(){
		let productId = $(this).attr("data-id");

		$.ajax({
			url: "../controllers/process_add_to_cart.php",
			method: "POST",
			data: {
				productId: productId
			},
			dataType: "text",
			success: function(data) {
				$("#btn_add_to_cart").replaceWith(
					"<button class='btn btn-outline-secondary mt-3 flex-fill mr-2' data-id='" + productId + "' role='button'" + 
					"id='btn_delete_from_cart' disabled>" +
					"<i class=\"fas fa-cart-plus\"></i> Item added to cart!</button>");
				let sum = "";
				sum += data;
				$("#item-count").html("<span class='badge badge-primary text-light'>" + sum + "</span>");
			}
		});

	});


	// DELETING ITEMS IN CART
	$(document).on("click", ".btn_delete_item", function(){
		let productId = $(this).data('productid');

		$.post('../controllers/process_delete_in_cart.php', {
			productId: productId 
			},function(response){
				// reload the modal with the new quantity reflected
				$.get("../partials/templates/cart_modal.php", function(response) {
					$('.modal .modal-body').html(response);
					let currentNumberOfItems = $("#item-count span").text();
					currentNumberOfItems = currentNumberOfItems-1;

					if (currentNumberOfItems == 0) {
						$("#item-count").html("");
					} else {
						$("#item-count").html("<span class='badge badge-primary text-light'>"
						+ currentNumberOfItems + "</span>");					
					}

					$("#btn_delete_from_cart").replaceWith(
						"<a class='btn btn-outline-primary mt-3 flex-fill mr-2' data-id='"+ productId +"' role='button' id='btn_add_to_cart'>" +
						"<i class='fas fa-cart-plus'></i> Add to Cart</a>");
				});
			});

	});

	// ADDING PRODUCT QUANTITY
	$(document).on("change", ".itemQuantity", function(){		
		let quantity = $(this).val();		
		let productId = $(this).data('productid');
		$.post('../controllers/add_product_quantity.php', {
			quantity: quantity,
			productId: productId
		}, function(response){
			// reload the modal with the new quantity reflected
			$.get("../partials/templates/cart_modal.php", function(response) {
				$('.modal .modal-body').html(response);
			});
		});
	});


	// FETCHING WISHES
	$(document).on("click", "#btn_add_to_wishlist", function() {
		let productId = $(this).attr('data-id');
		$.ajax({
			url: '../controllers/process_add_wishlist.php', 
			method: 'POST',
			data: {productId: productId}, 
			success: function() {
				$("#btn_add_to_wishlist").replaceWith(
					"<button class='btn btn-outline-secondary mt-3 flex-fill mr-2' data-id='"+ productId +"' disabled>" +
					"<i class='far fa-heart'></i> Item added to your wishlist. </button>");
			}
		});
	});


	// DISPLAYING WISHLIST
	$("#btn_view_wishList").on("click",function(){
		let userId = $(this).attr("data-id");

		$.ajax({
			url: "wishlist.php",
			method: "POST",
			data: {userId:userId},
			success: (data) => {
				$('#main-wrapper').html('');
				$('#main-wrapper').html(data);
			}
		})
			
	});

	// DELETING WISHLIST 
	$(document).on("click", ".btn_delete_wish", function(){
		let productId = $(this).data('productid');

		$.post('../controllers/process_delete_wish.php', {
			productId: productId 
			},function(response){
				$.post("wishlist.php", function(response) {
					$("#wish-row"+productId).remove();
					
					let currentNumberOfWishes = $("#wish-count span").text();
					currentNumberOfWishes = currentNumberOfWishes - 1;

					if (currentNumberOfWishes == 0) {
						$("#wish-count").html("");
					} else {
					$("#wish-count").html("<span class='badge badge-danger text-light'>" 
						+ currentNumberOfWishes + "</span>");					
					}

					$("#btn_already_in_wishlist").replaceWith(
						"<a class='btn btn-outline-danger mt-3 flex-fill' data-id='"+ productId +"' role='button' id='btn_add_to_wishlist'>" +
						+ "<i class='far fa-heart'></i> Add to Wish List </a>"
					);
				});
			});

	});


	




});
