jQuery(function( $ ) {
	'use strict';

	$(document).on("click", ".ur_images ul li", function(){
		$(this).siblings("li.active").removeClass("active");
		$(this).parents(".ur_review").find(".preview_image img").attr("src", "");
		
		$(this).toggleClass("active");
		if($(this).hasClass("active")){
			$(this).parents(".ur_review").find(".preview_image img").attr("src", $(this).children("img").attr("src"));
		}
	});

	$(".ur_stars li").hover(function(){

		$(this).css("color", "#ff9800");
		$(this).prevAll().css("color", "#ff9800");
		$(this).nextAll().css("color", "#ddd");

		$(this).on("click", function () {
			$(".ur_stars li.selected").removeClass("selected");
			$(this).addClass("selected");
			$("#selected_star").val($(this).data("star"));
		});
	}, function(){
		$(".ur_stars li").css("color", "#ddd");
		$(".ur_stars li.selected").css("color", "#ff9800");
		$(".ur_stars li.selected").prevAll().css("color", "#ff9800");
	});

	function formatBytes(bytes, decimals = 2) {
		if (bytes === 0) return '0 Bytes';
		const k = 1024;
		const dm = decimals < 0 ? 0 : decimals;
		const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		const i = Math.floor(Math.log(bytes) / Math.log(k));
		return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
	}
	
	let insertImage = function (input, label) {
		if (input.files && input.files[0]) {
		  let reader = new FileReader();
		  reader.onload = function (e) {
			label.css('background-image', "url("+e.target.result+")");
		  };
		  reader.readAsDataURL(input.files[0]);
		}
	};

	$(".ur_img_file").on("change", function(){
		if ($(this).val() !== '') {
			let imgName = $(this)
			  .val()
			  .replace(/.*(\/|\\)/, '');
			let exten = imgName.substring(imgName.lastIndexOf('.') + 1);
			let expects = ['jpg', 'jpeg', 'png', 'PNG', 'JPG', 'gif'];
	  
			if (expects.indexOf(exten) == -1) {
			  $(this).parent().css("background-image", "url()");
			  alert('Invalid Image!');
			  return false;
			}
	  
			if ($(this)[0].files[0].size > ajax_data.max_upload) {
			  alert(
				'You can upload maximum ' + formatBytes(ajax_data.max_upload) + '!'
			  );
			  return false;
			}
	  
			insertImage(this, $(this).parent());
		  } else {
			$(this).parent().css("background-image", "url()");
		  }
	});

	$(".review_submit").on("click", function(e){
		if($("#ur_yourname").val() == ""){
			e.preventDefault();
			alert("Per favore scrivi il tuo nome.");
			return false;
		}
		if($("#ur_youremail").val() == ""){
			e.preventDefault();
			alert("Per favore scrivi la tua email.");
			return false;
		}
		if($("#selected_star").val() == 0){
			e.preventDefault();
			alert("Si prega di aggiungere almeno 1 stella.");
			return false;
		}
	});

});
