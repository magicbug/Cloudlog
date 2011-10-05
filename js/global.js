	$(function(){
		// Accordion
		$("#tabs").tabs();
		$( "button, input:submit", ".wrap_content" ).button();
		$( "button, input:submit", ".contest_wrap" ).button();
		$( "#admin" ).click(function() {
			$( "#submenu" ).toggle( 'blinds', null, 500 );
			$( "#clear" ).toggle( 'blinds', null, 500 );
			return false;
		});
	});