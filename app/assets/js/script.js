$(document).ready( () => {

	// function myFunction() {
	// 	myVar = setTimeout(showPage, 3000);
	// }

	// function showPage() {
	// 	document.getElementById("loader").style.display = "none";
	// 	document.getElementById("myDiv").style.display = "block";
	// }
	// $('.lds-roller').fadeOut(3000);
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
								// } else if ($.parseJSON(dataFromPHP)) {
									// let data = $.parseJSON(dataFromPHP);
									// location.href="profile.php?id=" + data.id;
								} else if (dataFromPHP == "success") {
									alert('Hooray! You are now a registered Mamaroo user! Please login with your email or username and password.');
									// location.href="index.php";
									
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
						window.location.reload();
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
	// $(document).on('submit', '#form_edit_user', function(e){
	// 	e.preventDefault();
	// 	processEditForm();
	// });

	$(document).on('click', '#btn_edit_user', function(){

	
		let fname = $("#fname").val();
		let lname = $("#lname").val();
		let email = $("#email").val();
		let username = $("#username").val();
		let password = $("#password-profile").val();
		let id = $("#id").val();
		let error_flag = 0;

		if(email == "" || username == "" || password == "") {
			$("#edit_user_error").css("color", "#f64f59");
			$("#edit_user_error").text("Please fill out all fields with asterisk."); 
			error_flag = 1;
		} else {

			let countU = username.length;
			// let countP = password.length;

			$("#edit_user_error").text(""); 

			if (countU < 5) {
				$("#username-validation").css("color", "#f64f59");
				$("#username-validation").text("Username needs at least 5 characters.");
				error_flag = 1;
			} else {
				$("#username-validation").text("");
			}
	
			// if (countP < 8) {
			// 	$("#registration_password_validation").css("color", "##f64f59");
			// 	$("#registration_password_validation").text("Password needs at least 8 characters.");
			// 	error_flag = 1;
			// } else {
			// 	$("#registration_password_validation").text("");
				
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
						$("#email-validation").css("color", "#f64f59");
						$("#email-validation").text("Please enter a valid email."); 
					} else if (dataFromPHP == "emailExists") {
						$("#email-validation").css("color", "#f64f59");
						$("#email-validation").text("Email address is already taken."); 
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
									$("#username-validation").css("color", "#f64f59");
									$("#username-validation").text("User exists."); 
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
												$("#edit_user_error").css("color", "#f64f59");
												$("#edit_user_error").text("Your password is incorrect."); 
											
											} else if (dataFromPHP == "success") {
												
												alert('Your changes have been saved.');
												window.location.reload();
												
											} else {
												$("#edit_user_error").css("color", "#f64f59");
												$("#edit_user_error").text("Error in password validation encountered.");	
											} 
										}
									});
								} else {
									$("#edit_user_error").css("color", "#f64f59");
									$("#edit_user_error").text("Error in username validation encountered."); 
								}	
							}
							});

						


					} else {
						$("#edit_user_error").css("color", "#f64f59");
						$("#edit_user_error").text("Error in email validation encountered."); 
					}	
				}
			});
		}
	})


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
		let password = $("#password-address").val();

		if(password == "" || regionId == "..." || provinceId == "..." || cityMunId == "..." || brgyId == "..." || streetBldgUnit == "" || addressType == "...") {
			$("#address_error_message").css("color", "red");
			$("#address_error_message").text('Please fill out all fields with asterisk.');
			
		} else {
			$.post("../controllers/process_save_profile_address.php", {
				password, password,
				regionId:regionId,
				provinceId:provinceId,
				cityMunId:cityMunId,
				brgyId:brgyId,
				streetBldgUnit:streetBldgUnit,
				landmark:landmark,
				addressType:addressType
			}, function(data) {

				if(data == "success") {
					alert("Your changes have been saved.");
					window.location.reload();
				}else if(data=='fail') {
					$("#address_error_message").css("color", "red");
					$("#address_error_message").text('Your password is incorrect.');
				}else{
					$("#address_error_message").css("color", "red");
					$("#address_error_message").text('Please try again.');
				}
				

				// $(document).on('click', '.save_address_edit', function(){
				// 	$(this).attr('data-dismiss','modal');
				// });
					
			});
		}		
	});

	// VIEWING PASSWORD
	$(document).on('click', '.btn_view_profile_password', function(){

		let x = document.getElementById("password-profile");

		if (x.type === "password") {
			x.type = "text";
		  	$('.eye').removeClass('fa-eye-slash');
			$('.eye').addClass('fa-eye');
		} else {
		  x.type = "password";
		  	$('.eye').removeClass('fa-eye');
			$('.eye').addClass('fa-eye-slash');
		}

	})
	
		
	

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
	$(document).on('click', '.modal-link', function(e){
		e.preventDefault();
		const url = $(this).data('url');
		const id = $(this).data('id');

		$.get(url, {'id': id},function(response){
			$('#modalContainer .modal-content').html("");
			$('#modalContainer .modal-content').html(response);
			$('#modalContainer').modal();
		});
	});



	// DISPLAYING MODAL - BIG
	$(document).on('click', '.modal-link-big', function(e){
		e.preventDefault();
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

	$('#profileDropdown').click(()=>{
		$('.dropdown-menu').toggle();
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
						"<div id='header-empty-cart'></div>" +
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
	$(document).on('click', '#btn_order_confirmation', function(e){
		e.preventDefault();

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

		if(!addressId || addressId == "") {	
			
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

	$(document).on('change', '#modeOfPayment', function() {
		if(this.value == 3) {
			// $('#btn_order_confirmation').hide();
			$('#billing_info_buttons').hide();
			// $('#stripe_pay_button').show();
			$('#stripe_buttons').css('display','block');
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

	//REVIEW PRODUCT MODAL ON PROFILE PAGE
	$(document).on('click', '.btn_review_product', function(){
		let productId = $(this).data('productid');
		let url = $(this).data('url');
		//$(this).addClass('modal-link');
		
		$.post(url, {
			productId: productId
		}, function(response){
			$('#modalContainer .modal-content').html(response);
			$('#modalContainer').modal('show');
			// $("#modalContainer").on('shown.bs.modal', function(){
			// 	$('#modalContainer .modal-content').html(response);
			// });

			$("#fileBox").on('change', function(){
				$('#fileBox__label').find('span').text('1 file chosen');	
			});

			$("#fileBoxSave").on('click', function(){
				
				var file_data = $('#fileBox').prop('files')[0]; 
				var form_data = new FormData();       
				var ratingId = $(this).data('ratingid');           
				form_data.append('upload', file_data);
				form_data.append('rating_id', ratingId);
				$.ajax({
					url: '../../app/controllers/process_upload_review_images.php', // point to server-side PHP script 
					dataType: 'text',  // what to expect back from the PHP script, if anything
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'post',
					success: function(php_script_response){
						$.get('../../app/controllers/process_get_review_images.php', {ratingId: ratingId}, function(response){
							$("#review_images_container").html(response);
						});
					}
				});
			});
		});
	});
	
	//reader.onloadstart = ...
	//reader.onprogress = ... <-- Allows you to update a progress bar.
	//reader.onabort = ...
	//reader.onerror = ...
	//reader.onloadend = ...
	
	// UPLOADING IMAGES ON REVIEW PRODUCT MODAL
	// https://stackoverflow.com/questions/4006520/using-html5-file-uploads-with-ajax-and-jquery
	function shipOff(event) {
		var file_data = event.target.result;
		var fileName = document.getElementById('fileBox').files[0].name; //Should be 'picture.jpg';
	
		//$.post('../../app/controllers/process_upload_review_images.php', { data: result, name: fileName }, function(){

		var form_data = new FormData();                  
		form_data.append('upload', urlencode(file_data));
		$.ajax({
			url: '../../app/controllers/process_upload_review_images.php', // point to server-side PHP script 
			dataType: 'text',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'post',
			success: function(php_script_response){
				alert(php_script_response); // display response from the PHP script, if any
			}
		});
	}

	// FETCHING RATING SCORE ON REVIEW PRODUCT MODAL ON PROFILE PAGE
	$(document).on('click', '.product_rating_score', function(){
		let ratingId = $('#rating_id_hidden').val();
		let ratingScore = $(this).val();
		ratingScore = parseInt(ratingScore);
		$('#rating_score_hidden').val(ratingScore);

		let answer = confirm("Would you like to save this rating? You won't be able to change this rating afterwards if you click OK.");

		if(answer == true) {
			if(ratingScore == 1) {
				$('#product_rating_score').replaceWith(
					"<div>" +
					"<span class='star2x'>★</span>" +
					"<span class='star2x-gray'>★</span>" +
					"<span class='star2x-gray'>★</span>" +
					"<span class='star2x-gray'>★</span>" +
					"<span class='star2x-gray'>★</span>" +
					"</div>");
			}else if(ratingScore == 2) {
				$('#product_rating_score').replaceWith(
					"<div>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x-gray'>★&nbsp;</span>" +
					"<span class='star2x-gray'>★&nbsp;</span>" +
					"<span class='star2x-gray'>★</span>" +
					"</div>");
			}else if(ratingScore == 3) {
				$('#product_rating_score').replaceWith(
					"<div>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x-gray'>★&nbsp;</span>" +
					"<span class='star2x-gray'>★</span>" +
					"</div>");
			}else if(ratingScore == 4) {
				$('#product_rating_score').replaceWith(
					"<div>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x-gray'>★</span>" +
					"</div>");
			}else{
				$('#product_rating_score').replaceWith(
					"<div>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★&nbsp;</span>" +
					"<span class='star2x'>★</span>" +
					"</div>");
			}

			$.post('../../app/controllers/process_save_rating_as_final.php', {
				ratingId:ratingId,
				ratingScore:ratingScore
			});
		} 

	})

	// SAVING DATE IN REVIEW PRODUCT MODAL
	$(document).on('click', '#btn_submit_review', function(){
		let productId = $(this).data('productid');
		let ratingId = $('#rating_id_hidden').val();
		let ratingScore = $('#rating_score_hidden').val();
		let productReview = $('#product_review').val();

		$.post('../../app/controllers/process_save_rating_and_review.php', {
			ratingId:ratingId,
			ratingScore:ratingScore,
			productReview:productReview
		}, function(response){
			if(response=='success'){
				alert('Thanks! Your review has been submitted.');
				window.location.reload();
				$('.btn_products_to_review'+productId).html("<small class='text-gray font-weight-light'>REVIEWED</small>");
			}
		})

	})

	//SENDING MESSAGES FROM PROFILE PAGE
	// $(document).on('keypress', '#messageTextarea', function(e){
	// 	if(e.which == 13) {
	// 		let message = $(this).val();
	// 		if(message != ""){
				
	// 			$(this).val();
	// 		}
	// 	}
	// });

	// FETCHING MESSAGES IN CHATBOX
	$('#messageBox__button').on('click', function(e){
		e.preventDefault();
		$(".conversations").toggle();
		let data = {
			sellerId: $(this).data('sellerid')
		};

		$.get("../../app/controllers/process_generate_conversation.php", data, function(response){
			// update message item list to show seller at the top

			let data = $.parseJSON(response);
			$('#message_box .message_items').html(data.messageItemSelected);
			$('#message_box .message_details-container').html(data.messageDetails);
			$('#message_details-conversationId').val(data.conversationId);
			// update the message details to show the text conversation with seller

			let container = $('#message_box .message_details-container');
			container.scrollTop(container[0].scrollHeight);
		});
	});

	// FETCHING MESSAGES IN PROFILE INBOX
	$(document).on('click', '.selected_conversation', function(e){
		e.preventDefault();
		let data = {
			sellerId: $(this).data('sellerid'),
			conversationId: $(this).data('conversationid')
		};
		
		$.get("../../app/controllers/process_fetch_conversations.php", data, function(response){
			let data = $.parseJSON(response);

			$('#profile_conversation_id').val(data.conversationId);
			$('#profile_message_container').html(data.messageDetails);
			let container = $('#profile_message_container');
			container.scrollTop(container[0].scrollHeight);


		});
	})


	//SENDING MESSAGES THROUGH CHATBOX
	$(document).on('keyup', '#message_input', function(e) {
		if(e.keyCode == 13) {
			let data = {
				sellerId: $(this).data('sellerid'),
				conversationId: $('#message_details-conversationId').val(),
				message: $(this).val()
			}
			$.post('../../app/controllers/process_send_message.php', data,
				function(response){
				// let data = $.parseJSON(response);

					
				let container = $('#message_box .message_details-container');
				container.html(response);
				container.scrollTop(container[0].scrollHeight);
				
			});

			$(this).val("");
		}
	})

	//SENDING MESSAGES THROUGH PROFILE INBOX
	$(document).on('keyup', '#profile_message_input', function(e) {
		if(e.keyCode == 13) {
			let data = {
				sellerId: $(this).data('sellerid'),
				conversationId: $('#profile_conversation_id').val(),
				message: $(this).val()
			}
			$.post('../../app/controllers/process_send_message.php', data,
				function(response){
				// let data = $.parseJSON(response);
				
				let container = $('#profile_message_container');
				container.html(response);
				container.scrollTop(container[0].scrollHeight);
					
				
			});

			$(this).val("");
		}
	})

	// SEARCHING FOR STORENAME IN MESSAGE BOX
	$(document).on('keypress', '#search_store_name', function(e) {
		
		// if(e.keyCode == 13) {
			let storeName = $(this).val();

			$.get('../../app/controllers/process_search_store_message.php', {storeName:storeName},
				function(response){
				// let data = $.parseJSON(response);
				
				if(response == 'fail'){
					$('#sender_container').html("<tr><td><small>Sorry. There is no store with this name in your inbox.</small></td></tr>");
					setTimeout(function(){window.location.reload()}, 2000);
				}else{
					$('#sender_container').html(response);
				}
					
			});
		// }
		
	})

	// ==================================== STORE ================================= //
	// =============================================================================== //
	// =============================================================================== //

	//HEADER
	$(document).on('click', '.underline', function(){
		// e.preventDefault(e);
		//setTimeout(function(){window.location.reload()}, 300);
		$('.underline').removeClass('border-bottom');
		$(this).addClass('border-bottom');
	})

	//TOOLTIP
	$(function () {
        $('[data-toggle="tooltip"]').tooltip()
	})
	
	//EDITING DESCRIPTION 
	$(document).on('click','#btn_edit_store_description',function(){
		let data = {
			storeId: $(this).data('storeid'),
			description: $('#store_description').val()
		}

		$.post('../../app/controllers/process_edit_store_description.php', data,
				function(response){
				$('#store_profile_description').html(response);
			});

	})
	
	//EDITING STORE DETAILS
	$(document).on('click','.btn_edit_store_details',function(){
		let data = {
			storeId: $(this).data('storeid'),
			address: $('#store_address').val(),
			hours: $('#store_hours').val(),
			standard: $('#store_standard_fee').val(),
			free: $('#store_free_shipping').val()
		}

		$.post('../../app/controllers/process_edit_store_details.php', data,
			function(response){
			let dataFromPHP = $.parseJSON(response);

			if(dataFromPHP.where == 'details'){
				$('#store_profile_address').html(dataFromPHP.address);
				$('#store_profile_hours').html(dataFromPHP.hours);
			}
			
			if(dataFromPHP.where == 'fees'){
				$('#store_profile_standard_fee').html(dataFromPHP.standard);
				$('#store_profile_free_shipping').html(dataFromPHP.free);
				$('.store_profile_standard_fee').html(dataFromPHP.standard);
				$('.store_profile_free_shipping').html(dataFromPHP.free);
			}
		});
	})

	//SEARCHING FOR PRODUCTS IN STORE PROFILE
	$(document).on('keypress', '#store_page_search', function(e) {
		e.preventDefault;

		if(e.keyCode == 13) {
			let data = {
				storeId: $(this).data('storeid'),
				searchkey: $(this).val()
			}

			$.get('../../app/controllers/process_search_store_products.php', data,
				function(response){
				$('#store_page_product_container').html(response);

				if(response == "fail") {
					$('#store_page_product_container').html("Sorry. No items were found.");
					setTimeout(function(){window.location.reload()}, 2000);
				}
				
			});

		}
	})

	//FOLLOW A SHOP
	$(document).on('click', '#btn_follow', function(){
		 storeId = $(this).data('id');
	
		$.post('../../app/controllers/process_follow_store.php', { storeId:storeId },
			function(response){

				if(response == "followed"){
					$('#btn_follow_container').html("<button class='btn border text-gray' id='btn_follow' data-id='"+storeId+"'>&#8722; Unfollow</button");
					window.location.reload();
				} else if(response == "unfollowed") {
					$('#btn_follow_container').html("<button class='btn btn-purple text-light' id='btn_follow' data-id='"+storeId+"'>&#8722; Follow</button");
					window.location.reload();
				} else {
					alert("fail");
				}
		});
	})


	// SEARCHING FOR CLIENT NAME IN MESSAGE BOX
	$(document).on('keypress', '#search_client_name', function(e) {
	
		if(e.keyCode == 13) {
			let keypress = $(this).val();

			$.get('../../app/controllers/process_search_client_name.php', {keypress:keypress},
				function(response){
				// let data = $.parseJSON(response);
				
				if(response == 'fail'){
					$('#sender_container').html("<tr><td><small>Sorry. There is no client with this name in your inbox.</small></td></tr>");
					setTimeout(function(){window.location.reload()}, 2000);
				}else{
					$('#sender_container').html(response);
				}
					
			});
		}
		
	})


	//FETCHING PARENT CATEGORY AND POSTING SUBCATEGORIES
	$(document).on("change", "#product_category", function(){
		let categoryId = $(this).val();
		
		$.post("../controllers/process_display_subcategories.php", {categoryId:categoryId},function(data){
			let selected = "<option selected>Choose...</option>";
			$('#product_subcategory').empty().append(data);
			$('#product_subcategory').prepend(selected);
		});
	});

	
	  
	// FETCHING SUBCATEGORIES AND POSTING BRAND NAME
	$(document).on("change", "#product_subcategory", function(){
		let subcategoryId = $(this).val();

		$.post("../controllers/process_display_brands.php", {subcategoryId:subcategoryId},function(data){
			let selected = "<option selected>Choose...</option>";
			$('#product_brand').empty().append(data);
			$('#product_brand').prepend(selected);
		});
	  });
	
	
	// SAVE & EDIT NEW PRODUCT
	$(document).on('click', '.save_new_product', function(){
		let data = {
			'newProductId' : $(this).data('productid'),
			'storeId' : $(this).data('id'),
			'name' : $("#product_name").val(),
			'categoryId' : $("#product_category").val(),
			'subcategoryId' : $("#product_subcategory").val(),
			'brandId' : $("#product_brand").val(),
			'price' : $("#product_price").val()
		} 

		let flag = 0;

		if(data.name.length < 1){
			$('#basic_info_error').text('Please fill out all fields.');
			setTimeout(function(){$('#basic_info_error').empty("")}, 1500);
			flag = 0;
		}

		if(data.subcategoryId == "Choose...") {
			$('#basic_info_error').text('Please fill out all fields.');
			setTimeout(function(){$('#basic_info_error').empty("")}, 1500);
			flag = 0;
		}

		if(data.brandId == "Choose..."){
			$('#basic_info_error').text('Please fill out all fields.');
			setTimeout(function(){$('#basic_info_error').empty("")}, 1500);
			flag = 0;
		}

		if(data.price.length < 1) {
			$('#basic_info_error').text('Please fill out all fields.');
			setTimeout(function(){$('#basic_info_error').empty("")}, 1500);
			flag = 0;
		}

		if(flag == 0 && data.name.length > 1 && data.price.length > 1 ) {
			$.post("../controllers/process_add_new_product.php", data ,function(response){
				
				let dataFromPHP = $.parseJSON(response);
				// let selected = "<option selected>Choose...</option>";
				if(dataFromPHP.status == 'duplicate'){
					$('#basic_info_error').text('Please use a different name to avoid duplication.');
					setTimeout(function(){$('#basic_info_error').empty("")}, 1500);
				} else {
					$("#product_name").val(dataFromPHP.name);
					$("#product_price").val(dataFromPHP.price);

					$("#product_category").prepend(dataFromPHP.category);
					// $("#product_category").val(dataFromPHP.category);
					$('#product_subcategory').prepend(dataFromPHP.subcategory);
					// $("#product_subcategory").val(dataFromPHP.subcategory);
					$("#product_brand").prepend(dataFromPHP.brand);
					// $("#product_brand").val(dataFromPHP.brand);
					$("#new_product_id").val(dataFromPHP.id);
					alert("Saved!");
					window.location.reload();
				}
			});
		}
	})

	//SAVE & EDIT PRODUCT DETAIL
	$(document).on('click', '#btn_save_product_detail', function(){

		let promiseSaveDetails = [];
		$(".product_description").each(function(i, el){

			promiseSaveDetails[i] = new Promise(function(resolve,reject){
				let descriptionId = $(el).data('descriptionid');
				let data = { 
					'description' : $(el).val(),
					'descriptionId' : descriptionId,
					'productId' : $(el).data('id')
				}

				let flag = 0;

				if(data['description'] == "" || data['description'] == null && typeof description !== "undefined" ){
					reject("Please write a description before first.");
					flag = 1;
				}

				if(flag == 0 && data.description.length > 0 ){

					$.post('../controllers/process_add_new_product_details.php', data, function(response){
						if(response == 'duplicate') {
							reject('Please write a different description.');
						} else {
							resolve('Saved!');
						}
					})
				} else {
					resolve('continue saving others');
				}
			})		
		})

		Promise.all(promiseSaveDetails).then(function(results){
			alert("Saved!");
			window.location.reload();
		}).catch(function(error){
			$('#description_error').text(error);
			setTimeout(function(){$('#description_error').empty("")}, 1500);
		});
	})

	//ADD PRODUCT DETAIL ROW
	$(document).on('click', '#btn_add_product_detail',function(){
		let productId = $(this).data('id');
		$('.product_detail').append("<div class='input-group mb-4'>"+
		"<div class='input-group-prepend' style='background:white;'>"+
		"<span class='input-group-text border-0 text-secondary' style='background:white;'>&#9679;</span></div>"+
		"<textarea class='form-control product_description'"+
		"data-id='"+productId+"' aria-label='With textarea'></textarea></div>");
	})

	//DELETE PRODUCT DETAIL
	$(document).on('click', '.btn_delete_new_detail',function(){
		let descriptionId = $(this).data('descriptionid');

		$.post('../controllers/process_delete_description.php',{descriptionId:descriptionId},function(response){
			window.location.reload();
		})
	})

	//SAVE & EDIT PRODUCT VARIATION
	$(document).on('click','.btn_save_product_variation',function(){

			let promiseSaveVariations = [];

			$(".new_variation_name").each(function(i, el){
				
				promiseSaveVariations[i] = new Promise(function(resolve, reject){
					let variationId = $(el).data('variationid');
					let data = {
						'productId': $(el).data('id'),
						'variationId': variationId,
						'variationName': $(el).val(),
						'variationStock': $(el).next().val()
					}

					let flag = 0;

					if(data['variationName'] == "" || data['variationName'] == null || data['variationName'] == 0 && typeof variationId !== "undefined" ){
						reject("Please fill out both sides.");
						flag = 1;
					}

					if (data['variationStock'] == "" || data['variationStock'] == null || data['variationStock'] == 0 && typeof variationId !== "undefined"  ){
						flag = 1;
						reject("Please fill out both sides.");
					}
					
					if(flag == 0 && data.variationName.length > 0 && data.variationStock > 0) {

						$.post('../controllers/process_add_new_product_variation.php', data, function(response){
							
							if(response == 'duplicate') {
								reject('Please use a different variation name.');
							} else {
								resolve('Saved!');
							}
						})
					} else {
						resolve('continue saving others');
					}
				});
			});

			Promise.all(promiseSaveVariations).then(function(results){
				alert("Saved!");
				window.location.reload();
			}).catch(function(error){
				$('#variation_error').text(error);
				setTimeout(function(){$('#variation_error').empty("")}, 1500);
			});
	});

	
	//ADD PRODUCT VARIATION ROW
	$(document).on('click', '#btn_add_product_variation',function(){
		let productId = $(this).data('id');
	$('.product_variation').append("<div class='input-group mb-4'>"+
		"<div class='input-group-prepend'>" +
		"<span class='input-group-text border-0 text-secondary' style='background:white;'>&#9679;</span></div>"+
		"<input type='text' class='form-control new_variation_name' data-id='"+productId+"' placeholder='Name'>"+
		"<input type='number' class='form-control new_variation_stock' data-id='"+productId+"' placeholder='Available Stock'></div>");
	})

	//DELETE PRODUCT VARIATION ROW
	$(document).on('click', '.btn_delete_new_variation',function(){
		let variationId = $(this).data('variationid');

		$.post('../controllers/process_delete_variation.php',{variationId:variationId},function(response){
			window.location.reload();
		})
	})


	//SAVE & EDIT PRODUCT FAQs
	$(document).on('click','.btn_save_product_faq',function(){

		let promiseSaveFaqs = [];
			
		$(".new_question").each(function(i,el){

			promiseSaveFaqs[i] = new Promise(function(resolve,reject){
			let faqId = $(el).data('faqid');
			let data = {
				'productId' : $(el).data('id'),
				'faqId' : faqId,
				'question' : $(el).val(),
				'answer' : $(el).next().val()
			}

				let flag = 0;

				if(data['question'] == "" || data['question'] == null && typeof faqId !== "undefined" ){
					flag = 1;
					reject("Please fill out both sides.");
				}

				if(data['answer'] == "" || data['answer'] == null && typeof faqId !== "undefined" ){
					flag = 1;
					reject("Please fill out both sides.");
				}

				if(flag == 0 && data.question.length > 0 && data.answer.length > 0){

					$.post('../controllers/process_add_new_product_faq.php', data, function(response){
						
						if(response == 'duplicate') {
							reject('Please ask a different question.');
						} else {
							resolve('Saved!');
						}
					})

				} else {
					resolve('continue saving others');
				}
			});
		});

		Promise.all(promiseSaveFaqs).then(function(results){
			alert("Saved!");
			window.location.reload();
		}).catch(function(error){
			$("#faq_error").text(error);
			setTimeout(function(){$('#faq_error').empty("")}, 1500);
		});
	});




	//ADD PRODUCT FAQ ROW
	$(document).on('click', '.btn_add_product_faq',function(){
		let productId = $(this).data('id');
	$('.product_faq').append("<div class='input-group mb-4'>"+
		"<div class='input-group-prepend'>" +
		"<span class='input-group-text border-0 text-secondary' style='background:white;'>&#9679;</span></div>"+
		"<input type='text' class='form-control new_question' data-id='"+productId+"' placeholder='Question' maxlength='50'>"+
		"<input type='text' class='form-control new_answer' data-id='"+productId+"' placeholder='Answer' maxlength='50'></div>");
	})

	//DELETE PRODUCT VARIATION ROW
	$(document).on('click', '.btn_delete_new_faq',function(){
		let faqId = $(this).data('faqid');
		$.post('../controllers/process_delete_faq.php',{faqId:faqId},function(response){
			window.location.reload();
		})
	})

	//DELETE PRIMARY PICTURE OF PRODUCT
	$(document).on('click', '.btn_delete_primary_pic',function(e){
		
		let id = $(this).data('id');
		$.post('../controllers/process_delete_primary_pic.php',{id:id},function(response){
			e.preventDefault();
			window.location.reload();
		})
	})

	//DELETE OTHER PICTURES IN PRODUCT
	$(document).on('click', '.btn_delete_other_pic',function(e){
		
		let id = $(this).data('id');
		$.post('../controllers/process_delete_other_pic.php',{id:id},function(response){
			window.location.reload();
		})
	})

	// $(window).on('unload', function() {
	// 	$(window).scrollTop(0);
	//  });

	//  $(window).on('beforeunload', function() {
	// 	$(window).scrollTop(0);
	// });

	//END NEW PRODUCT SESSION AND REDIRECT TO PRODUCT PAGE
	$(document).on('click','#btn_unset_new_product',function(){
		let url = $(this).data('url');
		$.post(url,function(response){
			window.location.reload();
			window.open(response);
		})

	})


	// DELETE PRODUCT
	$(document).on('click', '.btn_delete_product', function(){
		let productId = $(this).data('productid');

		let answer = window.confirm("Would you like to delete this product? This cannot be undone.");

		if(answer == true) {
			$.post('../controllers/process_delete_product.php',{productId:productId},function(response){
				window.location.reload();
			})
		}
	});

	//VIEW PRODUCT
	$(document).on('click', '.btn_store_products_view', function(){
		let url = $(this).data('href');
	
		$.get(url,function(response){
			$("#modalContainerBig .modal-content").html(response);
			$("#modalContainerBig").modal('show');

			let averageRating = $("#average_product_rating").val();
			averageRating = averageRating*2;
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
	});


	$(document).on('click', '.sort_inventory', function(){
		let data = {
			'columnName' : $(this).data('column'),
			'storeId' : $(this).data('storeid'),
			'order' : $(this).data('order')
		}

		$.post('../controllers/process_sort_inventory.php',data,function(response){
			$("#data-container").html(response);
		})
	})

	// SEARCH INVENTORY
	$(document).on('keypress', '#btn_search_inventory', function(e){
		if(e.which == 13) {
			let data = {
				'storeId' : $(this).data('storeid'),
				'searchkey' : $(this).val()
			}

			$.post('../controllers/process_search_inventory.php',data,function(response){
				if(response == 'fail' ){
					$('#data-container').html("<tr class='mt-5 pt-5'><td class='mt-5 pt-5 font-weight-light'>Sorry. The search key doesn't match anything in your inventory.</td></tr>");
					setTimeout(function(){window.location.reload()}, 2000);
				} else {
					$("#data-container").html(response);
				}
			})
		}

	})



	//VIEW PRODUCT
	$(document).on('click', '.btn_view_new_order', function(){
		let url = $(this).data('href');
	
		$.get(url,function(response){
			$("#modalContainerBig .modal-content").html(response);
			$("#modalContainerBig").modal('show');
		});
	});

	// CANCEL ORDER
	$(document).on('click', '.btn_cancel_order', function(){
		let answer = confirm('Do you want to cancel this order? This cannot be undone.');
			
			if(answer == true) {
				let data = {
					"cartSession" : $(this).data('cartsession'),
					"storeId" : $(this).data('storeid')
			}

			$.post('../controllers/process_cancel_order.php',data,function(response){

				if(response == 'success') {
					alert("Order has been cancelled!");
					setTimeout(function(){window.location.reload()}, 1000);
				}
			})
		}
	});

	// CONFIRM ORDER
	$(document).on('click', '.btn_confirm_order', function(){
		let answer = confirm('Do you want to confirm this order?');
			
			if(answer == true) {
				let data = {
					"cartSession" : $(this).data('cartsession'),
					"storeId" : $(this).data('storeid'),
					"storeName" : $(this).data('storename')
			}

			// alert(data.storeName);

			$.post('../controllers/process_confirm_order.php',data,function(response){

				if(response == 'success') {
					alert("Order has been confirmed!");
					setTimeout(function(){window.location.reload()}, 1000);
				}
			})
		}
	});


	// SEARCH INVENTORY
	$(document).on('keypress', '#btn_search_orders', function(e){
		if(e.which == 13) {
			let data = {
				'storeId' : $(this).data('storeid'),
				'searchkey' : $(this).val()
			}

			$.post('../controllers/process_search_orders.php',data,function(response){
				if(response == 'fail' ){
					$('#data-container').html("<tr class='mt-5 pt-5'><td class='mt-5 pt-5 font-weight-light'>Sorry. The search key doesn't match anything in your inventory.</td></tr>");
					setTimeout(function(){window.location.reload()}, 2000);
				} else {
					$("#data-container").html(response);
				}
			})
		}

	})


	// CONFIRM ORDER
	$(document).on('click', '.btn_complete_order', function(){
		let answer = confirm('Do you want to mark this order as complete?');
			
			if(answer == true) {
				let data = {
					"cartSession" : $(this).data('cartsession'),
					"storeId" : $(this).data('storeid'),
					"storeName" : $(this).data('storename')
			}

			// alert(data.storeName);

			$.post('../controllers/process_complete_order.php',data,function(response){

				if(response == 'success') {
					alert("Order transaction has been completed!");
					setTimeout(function(){window.location.reload()}, 1000);
				}
			})
		}
	});
	

	// SEARCH INVENTORY
	$(document).on('keypress', '#btn_search_shipping', function(e){
		if(e.which == 13) {
			let data = {
				'storeId' : $(this).data('storeid'),
				'searchkey' : $(this).val()
			}

			$.post('../controllers/process_search_shipping.php',data,function(response){
				if(response == 'fail' ){
					$('#data-container').html("<tr class='mt-5 pt-5'><td class='mt-5 pt-5 font-weight-light'>Sorry. The search key doesn't match anything in your inventory.</td></tr>");
					setTimeout(function(){window.location.reload()}, 2000);
				} else {
					$("#data-container").html(response);
				}
			})
		}

	})

	// $(document).on('click', '#cartModal',function(){
	// 	let url = $(this).data('url');
	
	// 	$.get(url,function(response){
	// 		$("#modalContainerBig .modal-content").html(response);
	// 		$("#modalContainerBig").modal('show');
	// 	});
	// })
	

});




