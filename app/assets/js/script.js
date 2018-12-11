$(document).ready( () => {


	// ================================ REGISTRATION  ================================ //
	// =============================================================================== //
	// =============================================================================== //
	
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


	// ==================================== LOGIN  =================================== //
	// =============================================================================== //
	// =============================================================================== //


	// LOGIN
	$(document).on("click", "#btn_login", ()=> {
		
		let username = $("#login_username").val();
		let password = $("#login_password").val();
		let url = $('#form_login').attr('action');
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
				"url": url,
				"data": {"username" : username,
						  "password" : password},
				"type": "POST",
				"success": (dataFromPHP) => {
					let response = $.parseJSON(dataFromPHP);

					if(response.status == "loggedIn") {
						let data = $.parseJSON(dataFromPHP);
						location.href="profile.php?id=" + data.id;
					} else if (response.status == "adminLogIn") {
						data = $.parseJSON(dataFromPHP);
						location.href="users.php?id=" + data.id;
					} else if (response.status == "loginFailed") {
						$("#error_message").css("color", "red");
						$("#error_message").html(response.message); 
					} else if(response.status == 'redirect') {
						$('#cartModal').click();

						// update header reload navbar-nav contents
						$.get('../partials/navbar-nav.php', function(response){
							$('#navbar-nav').replaceWith(response);
						});
					} else {
						$("#error_message").css("color", "red");
						$("#error_message").html(response.message); 
					}
				}
			});
		} 
	});


	// ==================================== PROFILE  ================================= //
	// =============================================================================== //
	// =============================================================================== //


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


	// DISPLAYING ADDRESS WINDOW IN PROFILE
	$(".btn_view_addresses").on("click",function(){
		let userId = $(this).attr("data-id");

		$.ajax({
			url: "shipping_address.php",
			method: "POST",
			data: {userId:userId},
			success: (data) => {
				
				$('#main-wrapper').html('');
				$('#main-wrapper').html(data);
			}
		});	
	});

	// EDITING ADDRESSES
	$(document).on("click", "#btn_edit_address", function(){
		
		let regionId = $('#region').val(); 
		let provinceId = $('#province').val(); 
		let cityMunId = $('#cityMun').val(); 
		let brgyId = $('#barangay').val();
		let streetBldgUnit = $('#streetBldgUnit').val();
		let landmark = $('#landmark').val();
		let addressType = $('#addressType').val();

		if(regionId == "..." || provinceId == "..." || cityMunId == "..." || brgyId == "..." || streetBldgUnit == "" || addressType == "...") {
			$("#shipping_error_message").css("color", "red");
			$("#shipping_error_message").text('Please fill out required fields.');
			
		} else {
			$.post("../controllers/process_save_address.php", {
				regionId:regionId,
				provinceId:provinceId,
				cityMunId:cityMunId,
				brgyId:brgyId,
				streetBldgUnit:streetBldgUnit,
				landmark:landmark,
				addressType:addressType
			}, function(data) {
				$("#shipping_error_message").css("color", "green");
				$("#shipping_error_message").text(data);

				$(document).on('click', '.save_address_edit', function(){
					$(this).attr('data-dismiss','modal');
				});
					
			});
		}		
	});

	// ================================ CATALOG PAGE  ================================ //
	// =============================================================================== //
	// =============================================================================== //


	// SEARCH BAR
	$("#search").keyup(function(){
		let word = $(this).val();

		$.post("../controllers/search_items.php", {word:word},function(data){
			$('#products').html('');
			$("#products").html(data);
		});
	});


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
		});
	}

	//ARRANGING ITEMS ACCORDING TO PRICE
  	$(document).on("change", "#priceOrder", function(){
  		let value = $(this).val();

  		//AJAX
  		$.post("../controllers/search_price.php", {value:value},function(data){
  			$("#products").html(data);
  		});
	});
	
	

	// ==================================== GENERAL USE ============================== //
	// =============================================================================== //
	// =============================================================================== //


	// DISPLAYING MODAL
	$(document).on('click', '.modal-link', function(){
		const url = $(this).data('url');
		const id = $(this).data('id');

		$.get(url, {'id': id},function(response){
			$('#modalContainer .modal-body').html("");
			$('#modalContainer .modal-body').html(response);
			$('#modalContainer').modal();
		});
	});


	// ROUNDING OFF NUMBERS
	function round(value, exp) {
		if (typeof exp === 'undefined' || +exp === 0) {
		  return Math.round(value);
		}

		value = +value;
		exp = +exp;
	  
		if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
		  return NaN;
		}
	  
		// Shift
		value = value.toString().split('e');
		value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));
	  
		// Shift back
		value = value.toString().split('e');
		return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
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

	// ======================================= CART ================================== //
	// =============================================================================== //
	// =============================================================================== //
	

	// ADDING ITEMS TO CART
	$(document).on("click", "#btn_add_to_cart" ,function(){
		let productId = $(this).attr("data-id");

		$(this).replaceWith(
			"<button class='btn btn-outline-secondary mt-3 flex-fill mr-2' data-id='" + productId + "' role='button'" + 
			"id='btn_delete_from_cart' disabled>" +
			"<i class=\"fas fa-cart-plus\"></i> Item added to cart!</button>");

		$.ajax({
			url: "../controllers/process_add_to_cart.php",
			method: "POST",
			data: {
				productId: productId
			},
			dataType: "text",
			success: function(data) {
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
					// update button
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


	// ==================================== WISHLIST ================================= //
	// =============================================================================== //
	// =============================================================================== //

	// FETCHING WISHES FROM PRODUCT.PHP
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
				
				let currentNumberOfWishes = $("#wish-count-header span").text();
				currentNumberOfWishes = parseInt(currentNumberOfWishes) + 1;

				if (currentNumberOfWishes < 0 || currentNumberOfWishes == "" || currentNumberOfWishes == 'NaN') {
					$("#wish-count-header").html("");
					$("#wish-count-profile").html("");
				} else {
					$("#wish-count-header").html("<span class='badge badge-danger text-light'>" 
						+ currentNumberOfWishes + "</span>");	
					$("#wish-count-profile").html("<span class='badge badge-danger text-light'>" 
					+ currentNumberOfWishes + "</span>");	
				}
			}
		});
	});

	// FETCHING WISHES FROM OTHER PAGES IN VIEW FOLDER
	$(document).on("click", ".btn_add_to_wishlist_view", function() {
		let productId = $(this).attr('data-id');
		let productWish = $(".product-wish-count"+productId).text();
		productWish = parseInt(productWish) + 1;
		$('.product-wish-count'+productId).text(productWish);

		$(this).replaceWith(
			"<a class='mt-3 btn_already_in_wishlist_view' data-id='"+ productId +"' disabled>" +
				"<i class='fas fa-heart' style='color:red'></i>&nbsp;"+
				"<span class='product-wish-count"+productId+"'>"+ productWish+"</span>" +
				"</a>");

		$.ajax({
			url: '../controllers/process_add_wishlist.php', 
			method: 'POST',
			data: {productId: productId}, 
			success: function() {
				
				let currentNumberOfWishes = $("#wish-count-header span").text();
				currentNumberOfWishes = parseInt(currentNumberOfWishes) + 1;

				if (currentNumberOfWishes < 0 || currentNumberOfWishes == "" || currentNumberOfWishes == 'NaN') {
					$("#wish-count-header").html("");
					$("#wish-count-profile").html("");
				} else {
					$("#wish-count-header").html("<span class='badge badge-danger text-light'>" 
						+ currentNumberOfWishes + "</span>");	
					$("#wish-count-profile").html("<span class='badge badge-danger text-light'>" 
					+ currentNumberOfWishes + "</span>");	
				}
			}
		});
	});

	// DISPLAYING WISHLIST
	$(".btn_view_wishList").on("click",function(){
		let userId = $(this).attr("data-id");

		$.ajax({
			url: "wishlist.php",
			method: "POST",
			data: {userId:userId},
			success: (data) => {
				$('#main-wrapper').html('');
				$('#main-wrapper').html(data);
			}
		});	
	});

	// DELETING WISHLIST FROM PRODUCT PAGE
	$(document).on("click", ".btn_delete_wish", function(){
		let productId = $(this).data('productid');

		$.post('../controllers/process_delete_wish.php', {
			productId: productId 
			},function(response){
				$.post("wishlist.php", function(response) {
					$("#wish-row"+productId).remove();
					
					let currentNumberOfWishes = $("#wish-count-header span").text();
					currentNumberOfWishes = parseInt(currentNumberOfWishes) - 1;

					if (currentNumberOfWishes < 0 || currentNumberOfWishes == "" ) {
						$("#wish-count-header").html("");
						$("#wish-count-profile").html("");
					} else {
						$("#wish-count-header").html("<span class='badge badge-danger text-light'>" 
							+ currentNumberOfWishes + "</span>");	
						$("#wish-count-profile").html("<span class='badge badge-danger text-light'>" 
						+ currentNumberOfWishes + "</span>");	
					}

					$("#btn_already_in_wishlist").replaceWith(
						"<a class='btn btn-outline-danger mt-3 flex-fill' data-id='"+ productId +"' role='button' id='btn_add_to_wishlist'>" +
						+ "<i class='far fa-heart'></i> Add to Wish List </a>");
				});
			});
	});

	// DELETING WISHLIST FROM OTHER PAGES IN VIEW FOLDER
	$(document).on("click", ".btn_already_in_wishlist_view", function() {
		let productId = $(this).attr('data-id');

		let currentNumberOfWishes = $(".product-wish-count"+productId).text();
		currentNumberOfWishes = parseInt(currentNumberOfWishes) -1 ;
		$('.product-wish-count'+productId).text(currentNumberOfWishes);
		

		$(this).replaceWith(
			"<a class='mt-3 btn_add_to_wishlist_view' data-id='"+ productId +"' disabled>" +
				"<i class='far fa-heart' style='color:red'></i>&nbsp;"+
				"<span class='product-wish-count"+productId+"'>"+ currentNumberOfWishes+"</span>" +
				"</a>");

			$.post('../controllers/process_delete_wish.php', {
				productId: productId 
				},function(response){
						
				let currentNumberOfWishes = $("#wish-count-header span").text();
				currentNumberOfWishes = parseInt(currentNumberOfWishes) - 1;

				if (currentNumberOfWishes < 0 || currentNumberOfWishes == "" || currentNumberOfWishes == 'NaN') {
					$("#wish-count-header").html("");
					$("#wish-count-profile").html("");
				} else {
					$("#wish-count-header").html("<span class='badge badge-danger text-light'>" 
						+ currentNumberOfWishes + "</span>");	
					$("#wish-count-profile").html("<span class='badge badge-danger text-light'>" 
					+ currentNumberOfWishes + "</span>");	
				}

			});
		});
	

	// ==================================== RATING ================================= //
	// =============================================================================== //
	// =============================================================================== //

	// FETCHING PRODUCT RATING
	$(document).on("click", ".star", function() {
		let productId = $(this).attr('data-productId');
		productId = parseInt(productId);
		let starId = $(this).attr('id');
		let rating = $(this).attr('data-value');
		rating = parseInt(rating);

		//automatically update average
		let ratingCount = $('.rating-count'+productId).text();
		newRatingCount = parseInt(ratingCount) + 1;

		let ratingAverage = $('.rating-average'+productId).text();  
		let sumOfratings = rating * newRatingCount;
		let newRatingAverage = sumOfratings/newRatingCount;
		//$('.rating-average'+productId).text(newRatingAverage);  

			
		let oneStar = 
		"<div id='star-container'>" +
			"<i class='fas fa-star fa-2x star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
			"<i class='far fa-star fa-2x star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
			"<i class='far fa-star fa-2x star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
			"<i class='far fa-star fa-2x star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
			"<i class='far fa-star fa-2x star' id='star5' data-productId='"+ productId +"' data-value='5'></i>" +
		"</div>";

		let twoStar = 
		"<div id='star-container'>" +
			"<i class='fas fa-star fa-2x star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
			"<i class='fas fa-star fa-2x star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
			"<i class='far fa-star fa-2x star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
			"<i class='far fa-star fa-2x star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
			"<i class='far fa-star fa-2x star' id='star5' data-productId='"+ productId +"' data-value='5'></i>" +
		"</div>";

		let threeStar = 
		"<div id='star-container'>" +
			"<i class='fas fa-star fa-2x star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
			"<i class='fas fa-star fa-2x star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
			"<i class='fas fa-star fa-2x star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
			"<i class='far fa-star fa-2x star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
			"<i class='far fa-star fa-2x star' id='star5' data-productId='"+ productId +"' data-value='5'></i>" +
		"</div>";

		let fourStar = 
		"<div id='star-container'>" +
			"<i class='fas fa-star fa-2x star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
			"<i class='fas fa-star fa-2x star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
			"<i class='fas fa-star fa-2x star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
			"<i class='fas fa-star fa-2x star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
			"<i class='far fa-star fa-2x star' id='star5' data-productId='"+ productId +"' data-value='5'></i>" +
		"</div>";

		let fiveStar = 
		"<div id='star-container'>" +
			"<i class='fas fa-star fa-2x star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
			"<i class='fas fa-star fa-2x star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
			"<i class='fas fa-star fa-2x star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
			"<i class='fas fa-star fa-2x star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
			"<i class='fas fa-star fa-2x star' id='star5' data-productId='"+ productId +"' data-value='5'></i>" +
		"</div>";

		if(rating == 1) {
			$('#star-container').replaceWith(oneStar);
		} else if (rating == 2) {
			$('#star-container').replaceWith(twoStar);
		} else if (rating == 3) {
			$('#star-container').replaceWith(threeStar);
		} else if (rating == 4) {
			$('#star-container').replaceWith(fourStar);
		} else {
			$('#star-container').replaceWith(fiveStar);
		}

		$.ajax({
			url: '../controllers/process_add_product_rating.php', 
			method: 'POST',
			data: {	productId: productId,
					rating: rating
				  }, 
			success: function(data) {
				let response = $.parseJSON(data);
				// UPDATE NUMBER OF REVIEWS 
				if(response.reviewCount == 0 ) {
					//let response = $.parseJSON(data);
					$(".rating-count"+productId).empty();
					$(".rating-word").text('No reviews yet');
				}else if(response.reviewCount == 1) {
					//let response = $.parseJSON(data);
					$(".rating-count"+productId).text(response.reviewCount);
					$(".rating-word").text('Review');
					$('.rating-average'+productId).text(response.aveRating); 
				}else {
					//let response = $.parseJSON(data);
					$(".rating-count"+productId).text(response.reviewCount);
					$(".rating-word").text('Reviews');
					$('.rating-average'+productId).text(response.aveRating); 
				}

				if(response.aveRating >= 1 && response.aveRating < 1.5){
					$('.rating-average-in-stars'+productId).html(
							"<i class='fas fa-star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
							"<i class='far fa-star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
							"<i class='far fa-star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
							"<i class='far fa-star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
							"<i class='far fa-star' id='star5' data-productId='"+ productId +"' data-value='5'></i>");
					
				} else if (response.aveRating >= 1.5 && response.aveRating < 2) {
					$('.rating-average-in-stars'+productId).html(
							"<i class='fas fa-star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
							"<i class='fas fa-star-half-alt' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
							"<i class='far fa-star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
							"<i class='far fa-star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
							"<i class='far fa-star' id='star5' data-productId='"+ productId +"' data-value='5'></i>");

				} else if (response.aveRating >= 2 && response.aveRating < 2.5) {
					$('.rating-average-in-stars'+productId).html(
							"<i class='fas fa-star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
							"<i class='fas fa-star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
							"<i class='far fa-star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
							"<i class='far fa-star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
							"<i class='far fa-star' id='star5' data-productId='"+ productId +"' data-value='5'></i>");

				} else if (response.aveRating >= 2.5 && response.aveRating < 3) {
				$('.rating-average-in-stars'+productId).html(
						"<i class='fas fa-star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
						"<i class='fas fa-star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
						"<i class='fas fa-star-half-alt' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
						"<i class='far fa-star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
						"<i class='far fa-star' id='star5' data-productId='"+ productId +"' data-value='5'></i>");

				} else if (response.aveRating >= 3 && response.aveRating < 3.5) {
					$('.rating-average-in-stars'+productId).html(
							"<i class='fas fa-star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
							"<i class='fas fa-star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
							"<i class='fas fa-star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
							"<i class='far fa-star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
							"<i class='far fa-star' id='star5' data-productId='"+ productId +"' data-value='5'></i>");

				} else if (response.aveRating >= 3.5 && response.aveRating < 4) {
					$('.rating-average-in-stars'+productId).html(
							"<i class='fas fa-star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
							"<i class='fas fa-star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
							"<i class='fas fa-star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
							"<i class='fas fa-star-half-alt' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
							"<i class='far fa-star' id='star5' data-productId='"+ productId +"' data-value='5'></i>");

				} else if (response.aveRating >= 4 && response.aveRating < 4.5) {
					$('.rating-average-in-stars'+productId).html(
							"<i class='fas fa-star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
							"<i class='fas fa-star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
							"<i class='fas fa-star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
							"<i class='fas fa-star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
							"<i class='far fa-star' id='star5' data-productId='"+ productId +"' data-value='5'></i>");

				} else if (response.aveRating >= 4.5 && response.aveRating < 5){
					$('.rating-average-in-stars'+productId).html(
							"<i class='fas fa-star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
							"<i class='fas fa-star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
							"<i class='fas fa-star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
							"<i class='fas fa-star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
							"<i class='fas fa-star-half-alt' id='star5' data-productId='"+ productId +"' data-value='5'></i>");

				} else {
					$('.rating-average-in-stars'+productId).html(
							"<i class='fas fa-star star' id='star1' data-productId='"+ productId +"' data-value='1'></i>" +
							"<i class='fas fa-star star' id='star2' data-productId='"+ productId +"' data-value='2'></i>" +
							"<i class='fas fa-star star' id='star3' data-productId='"+ productId +"' data-value='3'></i>" +
							"<i class='fas fa-star star' id='star4' data-productId='"+ productId +"' data-value='4'></i>" +
							"<i class='fas fa-star star' id='star5' data-productId='"+ productId +"' data-value='5'></i>");
				}
				
			}
		});
	});

	

	// DISPLAYING PRODUCT RATING
	// DELETING PRODUCT RATING
	// FETCHING COMMENT


	// ==================================== CHECKOUT ================================= //
	// =============================================================================== //
	// =============================================================================== //


	//FETCHING REGION THEN DISPLAYING PROV OPTIONS
	$(document).on("change", "#region", function(){
		let regionId = $(this).val();

		//AJAX
		$.post("../controllers/process_display_address_db.php", {regionId:regionId},function(data){

			let selected = "<option value='...' selected='...'>...</option>";
			$('#province').empty().append(data);
			$('#province').prepend(selected);
		});
	});
	  
	// FETCHING PROVINCE THEN DISPLAYING CITYMUN OPTIONS
	$(document).on("change", "#province", function(){
		let provinceId = $(this).val();
		
		//AJAX
		$.post("../controllers/process_display_address_db.php", {provinceId:provinceId},function(data){

			let selected = "<option value='...' selected='...'>...</option>";
			$('#cityMun').empty().append(data);
			$('#cityMun').prepend(selected);
		});
	  });
	
	// FETCHING CITYMUN THEN DISPLAYING BARANGAY OPTIONS
	$(document).on("change", "#cityMun", function(){
		let cityMunId = $(this).val();
		
		//AJAX
		$.post("../controllers/process_display_address_db.php", {cityMunId:cityMunId},function(data){

			let selected = "<option value='...' selected='...'>...</option>";
			$("#barangay").empty().append(data);
			$("#barangay").prepend(selected);
		});
	  });


	
	// FETCHING ADDRESS IN SHIPPING_INFO_MODAL, SAVING IN DB
	$(document).on("click", "#btn_add_address", function(){
		let regionId = $('#region').val(); 
		let provinceId = $('#province').val(); 
		let cityMunId = $('#cityMun').val(); 
		let brgyId = $('#barangay').val();
		let streetBldgUnit = $('#streetBldgUnit').val();
		let landmark = $('#landmark').val();
		let addressType = $('#addressType').val();

		if(regionId == "..." || provinceId == "..." || cityMunId == "..." || brgyId == "..." || streetBldgUnit == "" || addressType == "...") {
			$("#shipping_error_message").css("color", "red");
			$("#shipping_error_message").text('Please fill out required fields.');
			
		} else {
			$(this).attr('class', 'modal-link');
			$.post("../controllers/process_save_address.php", {
				regionId:regionId,
				provinceId:provinceId,
				cityMunId:cityMunId,
				brgyId:brgyId,
				streetBldgUnit:streetBldgUnit,
				landmark:landmark,
				addressType:addressType
			}
			// , function(response) {

				// let address = $.parseJSON(response);

				// $("#address_id").val(address.id);
				// $("#region").val(address.region_id); 

				// // let option = document.createElement('option');
				// // option.value = address.province_id;
				// // option.text = address.province_name;
				// // $("#province")[0].appendChild(option);
				// $("#province").val(address.province_id); 

				// // option = document.createElement('option');
				// // option.value = address.city_id;
				// // option.text = address.city_name;
				// // $("#cityMun")[0].appendChild(option);
				// $("#cityMun").val(address.city_id);

				// // option = document.createElement('option');
				// // option.value = address.brgy_id;
				// // option.text = address.barangay_name;
				// // $('#barangay')[0].appendChild(option);
				// $("#barangay").val(address.brgy_id);

				// $("#streetBldgUnit").val(address.street_bldg_unit);
				// $("#landmark").val(address.landmark);
				// $("#addressType").val(address.addressType);

				// }
			);
		}		
	});

	// DISPLAYING SAVED ADDRESS BASED ON SELECTED ADDRESS TYPE
	$(document).on("click", ".user_addressTypes", function(){
		let thisRadioButtonValue = $(this).val();
		$.get("../controllers/process_get_address.php", {id: thisRadioButtonValue}, function(response){
			let address = $.parseJSON(response);

			$("#address_id").val(address.id);
			$("#region").val(address.region_id); // Select the added option

			let option = document.createElement('option');
			option.value = address.province_id;
			option.text = address.province_name;
			$("#province")[0].appendChild(option);
			$("#province").val(address.province_id); 

			option = document.createElement('option');
			option.value = address.city_id;
			option.text = address.city_name;
			$("#cityMun")[0].appendChild(option);
			$("#cityMun").val(address.city_id);

			option = document.createElement('option');
			option.value = address.brgy_id;
			option.text = address.barangay_name;
			$('#barangay')[0].appendChild(option);
			$("#barangay").val(address.brgy_id);

			$("#streetBldgUnit").val(address.street_bldg_unit);
			$("#landmark").val(address.landmark);
			$("#addressType").val(address.addressType);
			
		});
	});

	// ADDING NEW ADDRESSES OR OVEWRITING SELECTED ADDRESS TYPES
	$(document).on('click', '#btn_add_new_address', function(){
	
		$("#address_id").val("");
		$("#region").val("");
		$("#province").val("");
		$("#cityMun").val("");
		$("#barangay").val("");
		$("#streetBldgUnit").val("");
		$("#landmark").val("");
		$("#addressType").val("");

	});

	// ==================================== ORDER CONFIRMATION ================================= //
	// =============================================================================== //
	// =============================================================================== //

	// ORDER CONFIRMATION
	$(document).on('click', '#btn_order_confirmation', function(){
		let modeOfPaymentId = $('#modeOfPayment').val();

		if(modeOfPaymentId == "" || modeOfPaymentId == "...") {
			$("#order_summary_error_message").css("color", "red");
			$("#order_summary_error_message").text('Please select mode of payment.');
		} else {
	
			$.post('../controllers/process_get_payment_mode.php', {
				modeOfPaymentId: modeOfPaymentId
			}, function(response){
				// reload the modal with the new quantity reflected
				$.get("../partials/templates/confirmation_modal.php", function(response) {
					$('.modal .modal-body').html(response);
				});
			});
		}

		
	});




});



