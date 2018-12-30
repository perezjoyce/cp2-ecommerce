$(document).ready( () => {

	


	// ================================ REGISTRATION  ================================ //
	// =============================================================================== //
	// =============================================================================== //
	
	// VIEWING LOGIN PASSWORD
	window.showRegistrationPassword = function() {
		let x = document.getElementById("register_password");

		if (x.type === "password") {
			x.type = "text";
		  	$('.eye').removeClass('fa-eye-slash');
			$('.eye').addClass('fa-eye');
		} else {
		  x.type = "password";
		  	$('.eye').removeClass('fa-eye');
			$('.eye').addClass('fa-eye-slash');
			$('#btn_view_registration_password').css("border-bottom-color", "#c471ed");
		}
	}

	// REGISTRATION
	$(document).on('click', '#btn_register', ()=>{
		
		//get values
		// let fname = $("#fname").val();
		// let lname = $("#lname").val();
		// let address = $("#address").val();
		let email = $("#register_email").val();
		let username = $("#register_username").val();
		let password = $("#register_password").val();
		// let cpass = $("#cpass").val();
		let error_flag = 0;

		//First name verification
		// if(fname == ""){
		// 	$("#fname").next().html("First name is required.");
		// 	error_flag = 1;
		// } else {
		// 	$("#fname").next().html("");
		// }

		//Last name verification
		// if(lname == ""){
		// 	$("#lname").next().html("Last name is required.");
		// 	error_flag = 1;
		// } else {
		// 	$("#lname").next().html("");
		// }

		//address verification
		// if(address == ""){
		// 	$("#address").next().html("Address is required.");
		// 	error_flag = 1;
		// } else {
		// 	$("#address").next().html("");
		// }

		//email verification
		if(email == "" || username == "" || password == "") {
			$("#registration_error_message").css("color", "#c471ed");
			$("#registration_error_message").text("All fields are required."); 
			error_flag = 1;
		} else {

			let countU = username.length;
			let countP = password.length;

			$("#registration_error_message").text(""); 

			if (countU < 5) {
				$("#registration_username_validation").css("color", "#c471ed");
				$("#registration_username_validation").text("Username needs at least 5 characters.");
				error_flag = 1;
			} else {
				$("#registration_username_validation").text("");
			}
	
			if (countP < 8) {
				$("#registration_password_validation").css("color", "#c471ed");
				$("#registration_password_validation").text("Password needs at least 8 characters.");
				error_flag = 1;
			} else {
				$("#registration_password_validation").text("");
				// let x = $('#cpass_hidden').hasClass('d-none');

				// alert(x);

				// 	if (x) {
				// 		$('#cpass_hidden').removeClass('.d-none');
				// 		$('#cpass_hidden').show();
				// 		error_flag = 1;
				// 	}
			}

			// if (password !== cpass) {
			// 	$("#registration_cpass_validation").text("Passwords don't match.");
			// 	error_flag = 1;
			// } else {
			// 	$("#registration_cpass_validation").text("");
			// }

		}


		if(error_flag == 0) {
		
			//EMAIL
			$.ajax({
				"url": "../controllers/process_email.php",
				"data": { "email" : email },
				"type": "POST",
				"success": (dataFromPHP) => {

					if (dataFromPHP == "invalidEmail") {
						$("#registration_email_validation").text("color", "#c471ed");
						$("#registration_email_validation").text("Please enter a valid email."); 
					 
					} else if (dataFromPHP == "emailExists") {
						$("#registration_email_validation").text("color", "#c471ed");
						$("#registration_email_validation").text("Email already exists."); 

					} else {
						
						$.ajax({

							"url": "../controllers/process_register2.php",
							"data": {
									// "fname" : fname,
									// "lname" : lname,
									// "address" : address,
									"email" : email,
									"username" : username,
									"password" : password
									},
							"type": "POST",
							"success": (dataFromPHP) => {
								if (dataFromPHP == "userExists") {
									$("#registration_username_validation").text("color", "#c471ed");
									$("#registration_username_validation").text("User exists."); 
								} else if ($.parseJSON(dataFromPHP)) {
									let data = $.parseJSON(dataFromPHP);
									location.href="profile.php?id=" + data.id;
								} else {
									$("#registration_error_message").css("color", "#c471ed");
									$("#registration_error_message").text("Error encountered. Please try again."); 
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

	// VIEWING LOGIN PASSWORD
	window.showLoginPassword = function() {
		let x = document.getElementById("login_password");

		if (x.type === "password") {
			x.type = "text";
		  	$('.eye').removeClass('fa-eye-slash');
			$('.eye').addClass('fa-eye');
		} else {
		  x.type = "password";
		  	$('.eye').removeClass('fa-eye');
			$('.eye').addClass('fa-eye-slash');
			$('#btn_view_login_password').css("border-bottom-color", "#c471ed");
		}
	}
	

	// ADJUSTING BORDER OF EYE BUTTON -- not working
	$(document).on('focus', '#login_password', ()=> {
		$('#btn_view_login_password').css("border-bottom-color", "#c471ed");
	});

	$(document).on('blur', '#login_password', ()=> {
		$('#btn_view_login_password').css("border-bottom-color", "lightgray");
	});

	// LOGIN
	$(document).on("click", "#btn_login", ()=> {
		
		let username_email = $("#login_username_email").val();
		let password = $("#login_password").val();
		let url = $('#form_login').attr('action');
		let error_flag = 0;


		//username verification
		if(username_email == ""){
			$("#login_error_message").html(""); 
			$("#login_username_email").next().css("color", "#c471ed");
			$("#login_username_email").next().html("Email or Username is required.");
			$('#login_username_email').css("border-bottom-color", "#c471ed");
			error_flag = 1;
		} else {
			$("#login_username_email").next().html("");
			$('#login_username_email').css("border-bottom-color", "black");
		}

		//password verification
		if(password == ""){
			$("#login_error_message").html(""); 
			$("#login_password_validation").css("color", "#c471ed");
			$("#login_password_validation").text("Password is required.");
			$("#login_password_validation").css("border-bottom-color", "#c471ed");
			$('#login_password').css("border-bottom-color", "#c471ed");
			$('#btn_view_login_password').css("border-bottom-color", "#c471ed");
			error_flag = 1;
		} else {
			$("#login_password_validation").text("");
			$('#login_password').css("border-bottom-color", "black");
			$('#btn_view_login_password').css("border-bottom-color", "black");
		}


		if(error_flag == 0) {
			
			$.ajax({
				"url": url,
				"data": {"username_email" : username_email,
						  "password" : password},
				"type": "POST",
				"success": (dataFromPHP) => {
					let response = $.parseJSON(dataFromPHP);

					if(response.status == "loggedIn") {
						let data = $.parseJSON(dataFromPHP);
						location.href="index.php?id=" + data.id;
					} else if (response.status == "adminLogIn") {
						data = $.parseJSON(dataFromPHP);
						location.href="users.php?id=" + data.id;
					} else if (response.status == "loginFailed") {
						$("#login_error_message").css("color", "#c471ed");
						$("#login_error_message").html(response.message); 
						$('#login_password').css("border-bottom-color", "#c471ed");
						$('#login_username_email').next().css("color", "#c471ed");
						$('#login_username_email').css("border-bottom-color", "#c471ed");
						$('#btn_view_login_password').css("border-bottom-color", "#c471ed");
					} else if(response.status == 'redirect') {
						$('#cartModal').click();
						// update header reload navbar-nav contents
						$.get('../partials/navbar-nav.php', function(response){
							$('#navbar-nav').replaceWith(response);
						});
					} else {
						$("#login_error_message").css("color", "#c471ed");
						$("#login_error_message").html(response.message); 
						$('#login_password').css("border-bottom-color", "#c471ed");
						$('#login_username_email').next().css("color", "#c471ed");
						$('#login_username_email').css("border-bottom-color", "#c471ed");
						$('#btn_view_login_password').css("border-bottom-color", "#c471ed");
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
			$("#shipping_error_message").text('Please fill out all fields with asterisk.');
			
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
	$(document).on("click", ".sub_category_btn", function(e){
		e.preventDefault();
		let parentLink = this;
		let categoryId = $(this).data('id');
		let brandId = $(this).data('brandid');

		if($(this).hasClass('level-2')) {
			$("#selectedCategoryId").val(categoryId);
			
			$.post("../controllers/process_show_level_2_breadcrumb.php", 
				{categoryId: categoryId}, 
				function(data){
				$("#level_2_breadcrumb").html(data);
			});

			if(!brandId) {
				$("#level_3_breadcrumb").html("");
			}
			
		}

		if(brandId) {
			$("#selectedBrandId").val(brandId);
			$.post("../controllers/process_show_brand_items.php", 
				{categoryId: categoryId, brandId: brandId}, 
				function(response){
				// let x = $.parseJSON(data);
				//alert(data);
					$("#products_view").html(response);
			});

			$.post("../controllers/process_show_level_3_breadcrumb.php", 
				{brandId: brandId}, 
				function(data){
				$("#level_3_breadcrumb").html(data);
			});


		} else {
			$.post("../controllers/process_show_items.php", {categoryId: categoryId}, function(data){
				// let x = $.parseJSON(data);
				//alert(data);
				$("#products_view").html(data);
	
				// get all brands of this category via ajax
				$.get("../controllers/process_get_category_brands.php", {id: categoryId}, 
					function(response){
						$('.level-3').remove();
						$(response).insertAfter($(parentLink));
				});
	
			});
		}
  	});
  

	//ARRANGING ITEMS ACCORDING TO PRICE
  	$("#sort_products").on("change", function(){
		  let value = $(this).val();
		  let categoryId = $("#selectedCategoryId").val();
		  let brandId = $("#selectedBrandId").val();

		  $.post("../controllers/process_sort_products.php", 
			  {value:value, categoryId:categoryId, brandId: brandId}, 
			  function(data){
  			$("#products_view").html(data);
  		});
	});

	//SORTING PRODUCTS BY RATING
	$(".sort_by_rating").on("click", function(){
		let rating = $(this).data('rating');
		let categoryId = $("#selectedCategoryId").val();
		let brandId = $("#selectedBrandId").val();

		$.post("../controllers/process_sort_by_rating.php", 
			{rating:rating, categoryId:categoryId, brandId: brandId}, 
			function(data){
			$("#products_view").html(data);
		});
	  });
	  
	
	
	// SORTING BY PRICE RANGE
	$("#btn_price_range").on("click", function(){
		let categoryId = $("#selectedCategoryId").val();
		let brandId = $("#selectedBrandId").val();
		let minPrice = $("#price_range_min").val();
		let maxPrice = $("#price_range_max").val();

		$.post("../controllers/process_sort_by_price_range.php", 
			{minPrice:minPrice, maxPrice:maxPrice, categoryId:categoryId, brandId: brandId}, 
			function(data){
			$("#products_view").html(data);
		});

	});

	// =================================== PRODUCT.PHP ============================== //
	// =============================================================================== //
	// =============================================================================== //
	
	// THUMBNAILS
	$(document).on('click', '.product_thumbnail', function(){
		// $(this).css({'border-style': 'solid', 'border-color' : '#c471ed'});
		$id = $(this).data('id');
		$url = $(this).data('url');
		$('#iframeId').val($id);
		$('#product_iframe').html(
			"<img src='"+ $url + "' style='width:100%;height:450px;' id='"+$id+"'>"
		);
	})

	$(document).on('mouseout', '.review_thumbnail', function(){
		$id = $(this).data('id');
		$url = $(this).data('url');
		$clientId = $(this).data('clientid');
		$("#review_iframe"+$clientId).html("");
	})

	$(document).on('mouseover', '.review_thumbnail', function(){
		$id = $(this).data('id');
		$url = $(this).data('url');
		$clientId = $(this).data('clientid');
		$('#review_iframe'+$clientId).html(
			"<img src='"+ $url + "' style='width:100%;height:450px;' id='"+$id+"'>"
		);
	})

	
	
	// AVERAGE PRODUCT RATING AS STARS ON PRODUCT PAGE
	$(function() {

	let averageRating = $("#average_product_rating").val();
		// averageRating = averageRating*2;
		// alert(averageRating)

		function addScore(score, $domElement) {
			// score = averageRating * 2;
			var starWidth = "<style>.stars-container:after { width: " + score*2 + "%} </style>";
			$("<span class='stars-container'>")
			.text("★★★★★")
			.append($(starWidth))
			.appendTo($domElement);
		}

		
		function addScore2(score2, $domElement2) {
			score2 = score2 / 2;
			var starWidth2 = "<style>.stars-container-big:after { width: " + score2 + "%} </style>";
			$("<span class='stars-container-big'>")
			.text("★★★★★")
			.append($(starWidth2))
			.appendTo($domElement2);
		}
		addScore2(averageRating, $("#average_product_stars_big"));
		addScore(averageRating, $("#average_product_stars"));
	});


	// STARS IN PRODUCT CARDS
	function productRatingAsStars2(rating, id, $domElement3) {

		function addScore3(score3) {
			let starWidth3 = "<style>#" + id + " .stars-container-2:after { width: " + score3 + "%; color: gold} </style>";
			$("<span class='stars-container-2'>")
			.text("★★★★★")
			.append($(starWidth3))
			.appendTo($domElement3);
		}
		addScore3(rating, $(id));
	}


	$('.stars-outer').each(function(i, element){
		let rating = $(this).data('productrating');
		let id = $(this).attr('id');
		$('#' +id + ' .stars-inner')[0].style.width = (rating/5*85) + '%';
	});


	

	

	// PRODUCT RATING AS STARS
	//$(function() {
	function productRatingAsStars(rating, $domElement) {
		//let rating = $(this).data('rating');
		// let className = $(this).attr('id');
		//let unique = $(this).data('id');
		letHasDecimal = rating % 1;

		for(i=1;i<=parseInt(rating);i++) {
			$('<div class="star">★</div>').appendTo($domElement);
		}

		if(i <= 5) {
			// print the grey stars
			for(c=i;c<=5;c++) {
				$('<div class="star" style="color:lightgray!important">★</div>').appendTo($domElement);
			}
		}

		//function displayRating(score, $domElement) {
			// var starWidth = "<style>.stars-container:after { width: " + score + "%} </style>";
			// $("<span class='stars-container'>")
			// .text("★★★★★")
			// .append($(starWidth))
			// .appendTo($domElement);
	}
		//}
		//	displayRating(rating, $("#test-container"+unique));
	//})

	$('.test-container').each(function(i, element){
		let rating = $(this).data('rating');
		//let className = $(this).attr('class');
		productRatingAsStars(rating, $(element));
	});

	//SORTING RATINGS
	$("#sort_ratings").on("change", function(){
		let rating = $(this).val();

		// if(rating < 6){
			let storeId = $(this).data('storeid');
			let productId = $(this).data('id');
			$("#ratings_view").html("");

			$.post("../controllers/process_sort_ratings.php", 
				{rating:rating, productId:productId, storeId:storeId}, 
				function(data){
				$("#ratings_view").html(data);

				$('.test-container').each(function(i, element){
					let rating = $(this).data('rating');
					//let className = $(this).attr('class');
					productRatingAsStars(rating, $(element));
				});
			});
		// }
	  });

	// FETCHING STOCK OF PRODUCT VARIATIONS AND UPDATING DISPLAYED STOCK   
	$(document).on('click', '.btn_variation',function(){
		// let variationName = $(this).data('name');
		let variationStock = $(this).attr('data-variationStock');
		let variationId = $(this).data('id');
		//reset settings and values
		$('#variation_quantity').val('1');
		$('.variation_display').css('color','rgba(0,0,0,.8)');
		$('#variation_error').html("<small style='color:#f5f5f5;'>I'm invisible</small>");
		//update max of quantity input field   
		$('#variation_quantity').attr('max',variationStock);
		//update displayed available stock 
		$('#variation_stock').text(variationStock);
		//punt in hidden field to be fetched by btn plus later
		$('#variation_stock_hidden').val(variationStock);
		$('#variation_id_hidden').val(variationId);
		// $('#variation_name_hidden').val(variationName);
	})

	  // PLUS AND MINUS BUTTONS
	$(document).on('click', '.btn_plus', ()=>{

		let value = $('#variation_quantity').val();
		let variationStock = $("#variation_stock_hidden").val();
		variationStock = parseInt(variationStock);

		if(value >= variationStock) {
			$('.variation_display').css('color','#f64f59');
			$(this).attr('disabled',true);
		} else {
			value = parseInt(value) + 1;
			$('#variation_quantity').val(value);
			$('.variation_display').css('color','rgba(0,0,0,.8)');
		}

	})   

	

	

	$('.btn_minus').click(()=>{
		let value = $('#variation_quantity').val();

		if(value > 0) {
			value = parseInt(value) - 1;
			$('#variation_quantity').val(value);
			$('.variation_display').css('color','rgba(0,0,0,.8)');
		}else{
			$('#variation_quantity').val('1');
			$('.variation_display').css('color','rgba(0,0,0,.8)');
		}
	})   


	  
	
	// TABS
	window.openTab = function(evt, content) {
		let i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
		  tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
		  tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(content).style.display = "block";
		evt.currentTarget.className += " active";

		// console.log(evt.target.textContent);

		if(evt.target.textContent.indexOf("Reviews") != -1){
				// RATING BARS
		
			let elem = document.getElementById("rating_bar1");   
			let width = 1;
			let x = setInterval(frame, 10);
		
			function frame() {
				if(document.getElementById("rating_bar1_hidden")) {
					let aveScorePerStar = document.getElementById("rating_bar1_hidden").value;
					if (width >= aveScorePerStar) {
						clearInterval(x);
					} else {
						width++; 
						elem.style.width = width + '%'; 
					}
				}	
			}
		

			let elem2 = document.getElementById("rating_bar2");   
			let width2 = 1;
			let x2 = setInterval(frame2, 10);
		
			function frame2() {
				if(document.getElementById("rating_bar2_hidden")) {
					let aveScorePerStar2 = document.getElementById("rating_bar2_hidden").value;
					if (width2 >= aveScorePerStar2) {
						clearInterval(x2);
					} else {
						width2++; 
						elem2.style.width = width2 + '%'; 
					}
				}
			}
		

			let elem3 = document.getElementById("rating_bar3");   
			let width3 = 1;
			let x3 = setInterval(frame3, 10);
		
			function frame3() {
				if(document.getElementById("rating_bar3_hidden")){
					let aveScorePerStar3 = document.getElementById("rating_bar3_hidden").value;
					if (width3 >= aveScorePerStar3) {
						clearInterval(x3);
					} else {
						width3++; 
						elem3.style.width = width3 + '%'; 
					}
				}
			}
		

			let elem4 = document.getElementById("rating_bar4");   
			let width4 = 1;
			let x4 = setInterval(frame4, 10);
		
			function frame4() {
				if(document.getElementById("rating_bar4_hidden")){
					let aveScorePerStar4 = document.getElementById("rating_bar4_hidden").value;
					if (width4 >= aveScorePerStar4) {
						clearInterval(x4);
					} else {
						width4++; 
						elem4.style.width = width4 + '%'; 
					}
				}
			}
	

			let elem5 = document.getElementById("rating_bar5");   
			let width5 = 1;
			let x5 = setInterval(frame5, 10);
		
			function frame5() {
				if(document.getElementById("rating_bar5_hidden")){
					let aveScorePerStar5 = document.getElementById("rating_bar5_hidden").value;
					if (width5 >= aveScorePerStar5) {
						clearInterval(x5);
					} else {
						width5++; 
						elem5.style.width = width5 + '%'; 
					}
				}
				
			}
		
		}
		

	}


	// POSTING QUESTIONS
	$(document).on('click', '#btn_ask_question', function(){

		let userId = $(this).data('userid');
		let productId = $(this).data('productid');
		let question = $('#product_question').val();

		if(question == null || question == "") {
			$('#post_question_notification').text("Please type your question first.");
		} else {
			$.post('../controllers/process_ask_about_product.php', {
				question:question,
				userId: userId,
				productId:productId 
			},function(data){
				$('#post_questioin_notification').text(data);
			})
		}

		

	})
	

	
	

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



	// DISPLAYING MODAL - BIG
	$(document).on('click', '.modal-link-big', function(){
		const url = $(this).data('url');
		const id = $(this).data('id');

		$.get(url, {'id': id},function(response){
			$('#modalContainerBig .modal-content').html("");
			$('#modalContainerBig .modal-content').html(response);
			$('#modalContainerBig').modal();
		});
	});

	$("#modalContainer").on('shown.bs.modal', function(){
		$("#modalContainerBig").modal('hide'); 
		$('body').addClass('modal-open'); // so modal can be scrolled
	});

	$("#modalContainerBig").on('shown.bs.modal', function(){
		$("#modalContainer").modal('hide');
		$('body').addClass('modal-open');
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


	// // CLEAR
	// $("#btn_clear").click(()=>{

	// 	window.confirm("you sure?"); 
	
	// 		if(confirm == "ok") {
	// 			  $("#fname").next().html("");
	// 			  $("#lname").next().html("");
	// 			  $("#adress").next().html("");
	// 			  $("#email").next().html("");
	// 			  $("#username").next().html("");
	// 			$("#password").next().html("");
	// 			$("#cpass").next().html("");
	// 		}
			
	// 	});

	// ======================================= HEADER ================================ //
	// =============================================================================== //
	// =============================================================================== //
	
	$('#cartDropdown').mouseover(()=>{
		$('#cartDropdown_menu').show();
		$('#profileDropdown_menu').hide();
	});

	$('#profileDropdown').mouseover(()=>{
		$('#profileDropdown_menu').show();
		$('#cartDropdown_menu').hide();
	});

	$('#profileDropdown_menu').mouseover(()=>{
		$('#profileDropdown_menu').show();
	});

	$('#profileDropdown_menu').mouseout(()=>{
		$('#profileDropdown_menu').hide();
	});
	

	$('#cartDropdown_menu').mouseover(()=>{
		$('#cartDropdown_menu').show();
	});

	$('#cartDropdown_menu').mouseout(()=>{
		$('#cartDropdown_menu').hide();
	});

	$('#search_form').on('submit', function(e) {
		e.preventDefault();
		let str = $('#search-header').val();
		window.location.href="catalog.php?searchKey="+str;
	});

	$('#search-header').mouseout(()=> {
		$('#livesearch').hide();
	})

	$('#livesearch').mouseover(()=> {
		$('#livesearch').show();
	})

	$('#livesearch').mouseout(()=> {
		$('#livesearch').hide();
	})

	$('#search-header').mouseover(()=> {
		$('#livesearch').show();
	})
		

	$('#search-header').keyup(function(e) {
		let str = $(this).val();

		if (str.length==0) { 
		  document.getElementById("livesearch").innerHTML="";
		//   document.getElementById("livesearch").style.border="0px";
		  return;
		}

		if (window.XMLHttpRequest) {
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		} else {  // code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange=function() {
		  if (this.readyState==4 && this.status==200) {
			document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
		  }
		}

		xmlhttp.open("GET","../controllers/process_search.php?searchKey="+str,true);
		xmlhttp.send();
	});

	// ======================================= INDEX ================================== //
	// =============================================================================== //
	// =============================================================================== //

	$('.carousel').carousel({
		interval: 1500
	  })

	// SLICK 
	$('.autoplay').slick({
		// slidesToShow: 6,
		// slidesToScroll: 3,
		accessibility: true,
		// dots: true,
		slidesToShow: 6,
		slidesToScroll: 3,
		// autoplay: true,
		// autoplaySpeed: 2000,
		responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 4,
				slidesToScroll: 2,
				infinite: true,
				dots: true
			  }
			},
			{
			  breakpoint: 600,
			  settings: "unslick"
			},
			{
			  breakpoint: 480,
			  settings: "unslick"
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		  ]
	  });
		  



	// ======================================= CART ================================== //
	// =============================================================================== //
	// =============================================================================== //
	

	// ADDING ITEMS TO CART
	$(document).on("click", "#btn_add_to_cart" ,function(){

		let variationName = $(this).data('name');
		let productId = $(this).attr("data-id"); //not passed because variation id is already present

		let variationStock = $("#variation_stock_hidden").val();
			variationStock = parseInt(variationStock);
		let variationId = $('#variation_id_hidden').val();

		let quantity = $("#variation_quantity").val();
		let cartItems = $("#item-count").text();	

		let that = this;
		let flag = 0;
		
		if(!variationStock && variationName != 'None') {
			//REQUIRE USER TO CHOOSE VARIATION
			$('#variation_error').html("<small class='text-red'>Please select variation first.</small>");
			flag = 1;
		}

		if(flag == 0) {

			// REMOVE ERROR MESSAGE
			$('#variation_error').html("<small style='color:#f5f5f5;'>I'm invisible</small>");
			
			$.ajax({
				url: "../controllers/process_add_to_cart.php",
				method: "POST",
				data: {
					// productId: productId,
					variationId:variationId,
					quantity:quantity
				},
				success: function(data) {
					let response = $.parseJSON(data);
					
					// UPDATE HEADER BADGE
					$("#item-count").text(response.itemsInCart);

					// UPDATE CART DROPDOWN
					if(cartItems == 0 || cartItems == "") {
						$('#cartDropdown_menu').html("");
						$('#cartDropdown_menu').append(response.button);
					}
	
						$('#cartDropdown_menu').prepend(response.newProduct);
					
					// UPDATE ADD TO CART BUTTON
					$(that).replaceWith("<button class='btn btn-lg btn-disabled py-3' data-id='" + productId + "'role='button'" + 
						"id='btn_add_to_cart_again' disabled>" +
						"Item Added To Cart!</button>");
					
				}
			});
		}

		

		
	});

	// ADDING ITEMS TO CART AGAIN (DIFFERENT VARIATION)
	$(document).on("click", "#btn_add_to_cart_again" ,function(){

		let variationName = $(this).data('name');
		let productId = $(this).attr("data-id"); //not passed because variation id is already present

		let variationStock = $("#variation_stock_hidden").val();
			variationStock = parseInt(variationStock);
		let variationId = $('#variation_id_hidden').val();

		let quantity = $("#variation_quantity").val();
		let cartItems = $("#item-count").text();	

		let that = this;
		let flag = 0;
		
		if(!variationStock && variationName != 'None') {
			//REQUIRE USER TO CHOOSE VARIATION
			$('#variation_error').html("<small class='text-red'>Please select a different variation first.</small>");
			flag = 1;
		}

		if(variationName == 'None'){
			window.confirm("This item is already in your cart. Would you like to proceed anyway?"); 
	
			if(confirm == 'ok') {
				flag = 0;
			} else {
				flag = 1;
			}
		}

		if(flag == 0) {

			// REMOVE ERROR MESSAGE
			$('#variation_error').html("<small style='color:#f5f5f5;'>I'm invisible</small>");
			
			$.ajax({
				url: "../controllers/process_add_to_cart.php",
				method: "POST",
				data: {
					// productId: productId,
					variationId:variationId,
					quantity:quantity
				},
				success: function(data) {
					let response = $.parseJSON(data);
					
					// UPDATE HEADER BADGE
					$("#item-count").text(response.itemsInCart);

					// UPDATE CART DROPDOWN
					if(cartItems == 0 || cartItems == "") {
						$('#cartDropdown_menu').html("");
						$('#cartDropdown_menu').append(response.button);
					}
	
						$('#cartDropdown_menu').prepend(response.newProduct);
					
					// UPDATE ADD TO CART BUTTON
					$(that).replaceWith("<button class='btn btn-lg btn-disabled py-3' data-id='" + productId + "'role='button'" + 
						"disabled>" +
						"Item Added To Cart!</button>");
					
				}
			});
		}

	});

	// ADDING ITEM ON WISHLIST TO CART -- later
	$(document).on('click', '#btn_add_to_cart_profile', function(){
		let productId = $(this).attr("data-id");

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
				$("#item-count").html("<span class='badge border-0 circle'>" + sum + "</span>");
			}
		});

		$.post('../controllers/process_delete_wish.php', {
			productId: productId 
			},function(response){

			$.post("wishlist.php", function(response) {
				$("#wish-row"+productId).remove();
				
				let currentNumberOfWishes = $("#wish-count-header span").text();
				currentNumberOfWishes = parseInt(currentNumberOfWishes) - 1;

				if (currentNumberOfWishes < 0 || currentNumberOfWishes == "" || currentNumberOfWishes == 'NaN') {
					$("#wish-count-header").html("");
					$("#wish-count-profile").html("");
				} else {
					$("#wish-count-header").html("<span class='badge border-0'>" 
						+ currentNumberOfWishes + "</span>");	
					$("#wish-count-profile").html("<span class='badge border-0'>" 
					+ currentNumberOfWishes + "</span>");	
				}

			});
		});


	})

	// DELETING ITEMS IN CART
	$(document).on("click", ".btn_delete_item", function(){
		let variationId = $(this).data('variationid');
		let variationName = $(this).data('vname');
		let productId = $(this).data('productid');
		let quantity = $(this).data('quantity');	

		$.post('../controllers/process_delete_in_cart.php', {
			variationId: variationId,
			quantity, quantity
			},function(data){
				//REMOVE ITEM IN HEADER DROPDOWN
				$("#product-row"+variationId).remove();

				// update button
				$("#btn_add_to_cart_again").replaceWith(
					"<a class='btn btn-lg btn-purple py-3' style='height:50px;'" +  
					"data-variationid='"+ variationId +"' role='button'" +
					"data-id='"+ productId +"' id='btn_add_to_cart'" +
					"data-name='"+variationName+"'>" + "Add to Cart</a>");

				//UPDATE ITEM COUNT
				if (data == 0) {
					$("#item-count").text(data);
					$('#cartDropdown_menu').html("<a class='dropdown-item pb-5 text-center' href='#'>" +
						"<img src='http://www.aimilayurveda.com/catalog/view/theme/aimil/assets/images/empty.png' alt='empty_cart' style='width:10em;'>" +
						"<div><small>Your shopping cart is empty</small></div></a>");
				} else {
					$("#item-count").text(data);			
				}

					// RELOAD CART WITH THE NEW QUANTITY REFLECTED
					$.get("../partials/templates/cart_modal.php", function(response) {
						//$('.modal .modal-body').html(response);
						$("#modalContainerBig .modal-content").html(response);

				});
			});
	});


	let checkClick;
	window.passVariationIdToAdd = function(variationId) {
		// alert(variationId);

		clearTimeout(checkClick);
		checkClick = setTimeout(function(){
			// update the cart modal
			$.get('../partials/templates/cart_modal.php', function(response){
				$("#modalContainerBig .modal-content").html(response);
			});
			
		}, 800);

		$('#variation_id_hidden_modal').val(variationId);		
			let value = $('#variation_quantity'+variationId).val();
			let price = $('.unitPrice'+variationId).text();
			price = parseFloat(price.replace(/[^0-9-.]/g, '')); 

			let subtotalPrice = $('.subtotal_price'+variationId).text();
			subtotalPrice = parseFloat(subtotalPrice.replace(/[^0-9-.]/g, '')); 

			let grandTotalPrice = $('#grand_total_price').text();
			// this.alert(grandTotalPrice);
			grandTotalPrice = parseFloat(grandTotalPrice.replace(/[^0-9-.]/g, '')); 

			let variationStock = $('#variation_quantity'+variationId).attr('max');
			variationStock = parseInt(variationStock);

			
			if(value >= variationStock) {
				$('.variation_display'+variationId).css('color','#f64f59');
				$(this).attr('disabled',true);
			} else {
				value = parseInt(value) + 1;
				$('#variation_quantity'+variationId).val(value);
				$('.variation_display'+variationId).css('color','rgba(0,0,0,.8)');

				// UPDATE DATABASE AND RELOAD MODAL
				$.post('../controllers/change_product_quantity.php', {
					value: value,
					variationId: variationId
				}, function(response){
					if(response == 'success') {
						//UPDATE HEADER CART
						$('#quantity_header'+variationId).text(value);

						//UPDATE SUBTOTAL PRICE
						value = value * price;
						value = value.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2});
						$('.subtotal_price'+variationId).text(value);
			
						//UPDATE GRANDTOTAL
						grandTotalPrice = grandTotalPrice + price;
						grandTotalPrice = grandTotalPrice.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2});
						$('#grand_total_price').text(grandTotalPrice);

					}
				})
				
			}
	}

	window.passVariationIdToSubtract = function(variationId) {
		// alert(variationId);
		$('#variation_id_hidden_modal').val(variationId);

		clearTimeout(checkClick);
		checkClick = setTimeout(function(){
			// update the cart modal
			$.get('../partials/templates/cart_modal.php', function(response){
				$("#modalContainerBig .modal-content").html(response);
			});
		}, 800);

		let value = $('#variation_quantity'+variationId).val();
		let price = $('.unitPrice'+variationId).text();
		// this.alert(price);
		let subtotalPrice = $('.subtotal_price'+variationId).text();
		subtotalPrice = parseFloat(subtotalPrice.replace(/[^0-9-.]/g, '')); 

		let grandTotalPrice = $('#grand_total_price').text();
		grandTotalPrice = parseFloat(grandTotalPrice.replace(/[^0-9-.]/g, '')); 

		let variationStock = $('#variation_quantity'+variationId).attr('max');
		variationStock = parseInt(variationStock);

		
		if(value > 1) {
			value = parseInt(value) - 1;
			$('#variation_quantity'+variationId).val(value);
			$('.variation_display'+variationId).css('color','rgba(0,0,0,.8)');

			$.post('../controllers/change_product_quantity.php', {
				value: value,
				variationId: variationId
				}, function(response){
					if(response == 'success') {
	
					//UPDATE SUBTOTAL PRICE
					subtotalPrice = subtotalPrice - price;
					subtotalPrice = subtotalPrice.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2});
					$('.subtotal_price'+variationId).text(subtotalPrice);
		
					//UPDATE GRANDTOTAL
					grandTotalPrice = grandTotalPrice - price;
					grandTotalPrice = grandTotalPrice.toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2});
					$('#grand_total_price').text(grandTotalPrice);

					//UPDATE HEADER CART
					$('#quantity_header'+variationId).text(value);

				}
			})


		} else {
			$('#variation_quantity'+variationId).val('1');
			$('.variation_display'+variationId).css('color','rgba(0,0,0,.8)');

			$('.subtotal_price'+variationId).text(price);
		}

		
	}



	// ==================================== WISHLIST ================================= //
	// =============================================================================== //
	// =============================================================================== //

	// FETCHING WISHES FROM PRODUCT.PHP
	var enabled = null;

	$(document).on("click", ".heart-toggler", function(e) {
		let productId = $(this).attr('data-id');
		enabled = enabled == null ? $(this).data('enabled') : enabled;
		e = $(this);
		
		if(!enabled) {
			
			$.ajax({
				url: '../controllers/process_add_wish.php', 
				method: 'POST',
				data: {productId: productId}, 
				success: function(data) {
					enabled = !enabled;
					let response = $.parseJSON(data);					
					$('.user_wish_count').text(response.userWishCount);
					$('.product_wish_count').text(response.productWishCount);
					$('.wish_heart').html("<i class='fas fa-heart text-red'></i>");
					e.id = "btn_delete_wish";
				}
			});
		} else {
			
			$.ajax({
				url: '../controllers/process_delete_wish.php', 
				method: 'POST',
				data: {productId: productId}, 
				success: function(data) {
					let response = $.parseJSON(data);
					enabled = !enabled;
					$('.user_wish_count').text(response.userWishCount);
					$('.product_wish_count').text(response.productWishCount);
					$('.wish_heart').html("<i class='far fa-heart text-red'></i>");
					$("#wish-row"+productId).remove();
	
				}
			});
		}
	});


	$(document).on("click", ".btn_delete_wish", function() {
		let productId = $(this).data('productid');
	
			$.ajax({
				url: '../controllers/process_delete_wish.php', 
				method: 'POST',
				data: {productId: productId}, 
				success: function(data) {
					let response = $.parseJSON(data);
					$('.user_wish_count').text(response.userWishCount);
					$('.product_wish_count').text(response.productWishCount);
					$('.wish_heart').html("<i class='far fa-heart text-red'></i>");
					$("#wish-row"+productId).remove();
	
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

	// DELETING WISHLIST FROM OTHER PAGES IN VIEW FOLDER
	$(document).on("click", ".btn_already_in_wishlist_view", function() {
		let productId = $(this).attr('data-id');

		let currentNumberOfWishes = $(".product-wish-count"+productId).text();
		currentNumberOfWishes = parseInt(currentNumberOfWishes) -1 ;
		$('.product-wish-count'+productId).text(currentNumberOfWishes);
		

		$(this).replaceWith(
			"<a class='mt-3 btn_add_to_wishlist_view' data-id='"+ productId +"' disabled>" +
				"<i class='far fa-heart' style='color:red;'></i>&nbsp;"+
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
					$("#wish-count-header").html("<span class='badge border-0 circle'>" 
						+ currentNumberOfWishes + "</span>");	
					$("#wish-count-profile").html("<span class='badge border-0 circle'>" 
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
		let addressId = $('#address_id').val();
		let name = $('#name').val();
		let url = $(this).data('url');
		let flag = 0;
		

		if(name == "" || regionId == "..." || provinceId == "..." || cityMunId == "..." || brgyId == "..." || streetBldgUnit == "" || addressType == "...") {
			$("#shipping_error_message").css("color", "#f64f59");
			$("#shipping_error_message").text('Please fill out required fields.');
			flag = 1;
			
		} else {
			flag = 0;
		}	
		
		if(flag == 0) {
			let data = {
				regionId :$('#region').val(),
				provinceId :$('#province').val(),
				cityMunId : $('#cityMun').val(),
				brgyId : $('#barangay').val(),
				streetBldgUnit : $('#streetBldgUnit').val(),
				landmark : $('#landmark').val(),
				addressType : $('#addressType').val(),
				addressId : $('#address_id').val(),
				name : $('#name').val()
			}

			$.post('../controllers/process_save_address.php', data, function(response){
				if(response == 'success') {

					$.get(url, function(data){
						$('#modalContainerBig .modal-content').html(data);
					});

				}
				
			});
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
			$("#name").val(address.name);
			
			
		});
	});

	// // // RETRIEVE SHIPPING ADDRESS AND SAVE AS BILLING
	// $(document).on('click', '#btn_save_shipping_as_billing', function(){
	// 	let savedShippingAddress = $(this).val();

	// 	// $.post('../controllers/process_save_shipping_as_billing.php', {
	// 	// 	savedShippingAddress: savedShippingAddress
	// 	// }, function(response){
	// 	// 	// reload the modal with the new quantity reflected
	// 	// 	$.get("../partials/templates/confirmation_modal.php", function(response) {
	// 	// 		$('.modal .modal-body').html(response);
	// 	// 	});
	// 	// });

	// })

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
		$("#name").val("");

	});

	// ==================================== ORDER CONFIRMATION ================================= //
	// =============================================================================== //
	// =============================================================================== //

	// ORDER CONFIRMATION
	$(document).on('click', '#btn_order_confirmation', function(){

		// DELETE ERROR MESSAGE IN CASE MERON
		$("#billing_info_error").text("");

		// FETCH VARIABLES
		let modeOfPaymentId = $('#modeOfPayment').val();
		let regionId = $('#region').val(); 
		let provinceId = $('#province').val(); 
		let cityMunId = $('#cityMun').val(); 
		let brgyId = $('#barangay').val();
		let streetBldgUnit = $('#streetBldgUnit').val();
		let landmark = $('#landmark').val();
		let addressType = $('#addressType').val();
		let name = $('#name').val();

		let addressId = $('#address_id').val();
		let url = $(this).data('url');
		let flag = 0;

		if(modeOfPaymentId == "" || modeOfPaymentId == "..." || name == "" || regionId == "..." || provinceId == "..." || cityMunId == "..." || brgyId == "..." || streetBldgUnit == "" || addressType == "...") {
			$("#billing_info_error").css("color", "#f64f59");
			$("#billing_info_error").text('Please fill out required fields.');
			flag = 1;
		} 

		if(!addressId || addressId == ""){	
			
			$.post('../controllers/process_save_new_billing_address.php', {
				regionId:regionId, 
				provinceId:provinceId,
				cityMunId:cityMunId,
				brgyId:brgyId,
				streetBldgUnit:streetBldgUnit,
				landmark:landmark,
				addressType:addressType,
				name:name

			}, function(response){
				if(response == 'success') {
					flag = 0;
				}
			});
		

		} else {
			$.post('../controllers/process_save_shipping_as_billing.php', {
				addressId: addressId
			}, function(response){
				flag = 0;
			});
		}

		if(flag == 0){
			
			$.post('../controllers/process_get_payment_mode.php', {
				modeOfPaymentId: modeOfPaymentId
			}, function(response){

				if(response == "success"){
					$.get(url, function(data){
						$('#modalContainerBig .modal-content').html(data);
					});
				}
				
			});
			

		}
		
	});


	// PRINT CONFIRMATION PAGE 
	// http://jsfiddle.net/95ezN/121/
	$(document).on('click', "#btnPrint", function () {
		printElement(document.getElementById("printThis"));
		// var modThis = document.querySelector("#printSection .modifyMe");
		// modThis.appendChild(document.createTextNode(" new"));
		window.print();

		// RELOAD CART WITH THE NEW QUANTITY REFLECTED
		$.get("../controllers/process_unset_session.php", function(data) {
			let response = $.parseJSON(data);
			if(response.message == 'success'){
				$("#modalContainerBig").modal('hide'); 
				location.href="index.php?id=" + response.userId; 
			
			}
		});

	});

	window.printElement = function(elem) {
		var domClone = elem.cloneNode(true);
		
		var $printSection = document.getElementById("printSection");
		
		if (!$printSection) {
			var $printSection = document.createElement("div");
			$printSection.id = "printSection";
			document.body.appendChild($printSection);
		}
		
		$printSection.innerHTML = "";
		
		$printSection.appendChild(domClone);
	}


	//VIEW ORDER HISTORY
	$(document).on('click', '.btn_view_order_history', function(){
		let orderHistoryCartSession = $(this).data('id');
		let url = $(this).data('url');
		$(this).addClass('modal-link');
		
		$.post(url, {
			orderHistoryCartSession: orderHistoryCartSession
		}, function(response){
			$('#modalContainer .modal-content').html(response);
			
			// $("#modalContainer").on('shown.bs.modal', function(){
			// 	$('#modalContainer .modal-content').html(response);
			// });
			
		});
	});

	//PRINT ORDER COPY
	// http://jsfiddle.net/95ezN/121/
	$(document).on('click', "#btn_print_order_copy", function () {
		printElement(document.getElementById("printThis"));
		// var modThis = document.querySelector("#printSection .modifyMe");
		// modThis.appendChild(document.createTextNode(" new"));
		window.print();

	});

	//VIEW ORDER HISTORY
	$(document).on('click', '.btn_review_product', function(){
		let productId = $(this).data('productid');
		let url = $(this).data('url');
		$(this).addClass('modal-link');
		
		$.post(url, {
			productId: productId
		}, function(response){
			$('#modalContainer .modal-content').html(response);
			
			// $("#modalContainer").on('shown.bs.modal', function(){
			// 	$('#modalContainer .modal-content').html(response);
			// });
			
		});
	});
		
	


});



