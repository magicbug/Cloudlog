<script type="text/javascript">
	$(document).ready(function(){
		setInterval(function() {
			// Get Mode
			$.get('radio/status/', function(result) {
					//$('.status').append(result);
					$('.status').html(result);
			});		
		}, 1000);
 });
</script>

<div id="container">

	<h2><?php echo $page_title; ?></h2>

	<div class="row show-grid">
	  <div class="span15">
	  
		<table class="status">
		
		</table>


	  </div>
	</div>

</div>