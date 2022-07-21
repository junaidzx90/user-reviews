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

});
