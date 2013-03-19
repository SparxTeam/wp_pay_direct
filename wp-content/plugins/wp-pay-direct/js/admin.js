(function($) {
	"use strict";
	$(function() {
		// Administration-specific JavaScript

		/* ================================ */
		/* Form Loading */
		/* ================================ */

		var source_field = $("#source").length;

		if (source_field) {

			$.ajax({
				type : "post",
				url : wppd_ajax.ajaxurl,
				data : {
					action : "wppd_load_form"
				},
				success : function(response) {
					// alert(response);
					$("#source").val(response);
				}

			});

		}

		/* ============================== */
		/* Form Updation */
		/* ============================== */

		$(".pay-sub").on('click', function() {

			var form_source = genrateSource();
			if (form_source != false) {
				var form_builder_source = $('#target').html();
				$.ajax({
					type : "post",
					url : wppd_ajax.ajaxurl,
					data : {
						action : "wppd_update_form",
						'form_source' : form_source,
						'form_builder_source' : form_builder_source
					},
					success : function(response) {
						//alert(response);

					}

				});
			}
		});

		/**
		 * Replicated function from fb.js file (Bootstrap form builder)
		 * 
		 * original function name: genSource() defined in : fb.js
		 */
		var genrateSource = function() {
			var $counter = 0;
			var $slug = '';
			$("#build").find(".control-label").each(function() {
				$slug = generateSlug($(this).html());
				// alert($(this).html().trim().length);
				if (!$slug) {
					$(this).addClass('label-required');
					//alert($counter);
					$counter++;
				} else {
					$(this).siblings('.controls').find('input,select,textarea').attr('name',$slug);
					$(this).siblings('.controls').find('input,select,textarea').attr('id',$slug);
				}
			})
			var $temptxt = $("<div>").html($("#build").html());
			if($counter <= 0){
			$($temptxt).find(".component").attr({
				"title" : null,
				"data-original-title" : null,
				"data-type" : null,
				"data-content" : null,
				"rel" : null,
				"trigger" : null,
				"style" : null
			});
			$($temptxt).find(".valtype").attr("data-valtype", null)
					.removeClass("valtype");
			// $($temptxt).find(".component").removeClass("component");
			$($temptxt).find("form #legend").remove().attr({
				"id" : null,
				"style" : null
			});
			/**
			 * Amended this line from the original function to return the value
			 */
			// $("#source").val($temptxt.html().replace(/\n\ \ \ \ \ \ \ \ \ \ \
			// \ /g,"\n"));
			return $temptxt.html().replace(/\n\ \ \ \ \ \ \ \ \ \ \ \ /g,"\n");
			}else{
				return false;
			}
			
		}
		function generateSlug($phrase) {
			var $result = '';
			$result = $phrase.toLowerCase();

			$result = $result.replace(/[^a-z0-9\s-]/, "");
			$result = $result.trim().replace(/[\s-]+/, " ");
			//alert($result);
			// $result = trim(substr($result, 0, $maxLength));
			$result = $result.replace(/\s/, "-");

			return $result;
		}

	});
}(jQuery));