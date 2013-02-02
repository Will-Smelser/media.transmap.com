$(document).ready(function(){

	var $img = $('#image-show-img');
	
	// image previewer
	$("#image-show").dialog({
		modal : true,
		buttons : [],
		resizable : false,
		show : "fade",
		draggable : false,
		width : ($(window).width() - 40),
		height : ($(window).height() - 40),
		autoOpen : false,
		close : function() {
			$('body').css('overflow', 'auto');
		}
	});
	
	$('#image-container').click(
			function() {
				$('body').css('overflow', 'hidden');
				$("#image-show").dialog('open');
				$img.attr('width', '100%').css({
					'top' : '0px',
					'left' : '0px'
				}).attr(
						'src',
						$(this).find('img').attr('src').replace(/percent=[\d]+/i,
								'percent=100')).draggable();
			});
	
	$("#zoom-in").button({
		icons : {
			primary : 'ui-icon-circle-plus'
		}
	}).click(function() {
		var inc = 10;
		var width = parseInt($img.attr('width').replace('%', '')) + inc;
	
		if (width === inc) {
			width = 100 + inc;
		}
		$img.attr('width', width + '%');
	});
	$('#zoom-out').button({
		icons : {
			primary : 'ui-icon-circle-minus'
		}
	}).click(function() {
		var inc = -10;
		var width = parseInt($img.attr('width').replace('%', '')) + inc;
	
		if (width === inc) {
			width = 100 + inc;
		}
		$img.attr('width', width + '%');
	});
});