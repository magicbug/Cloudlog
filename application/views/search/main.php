<h2>Search</h2>
<div class="wrap_content">
<form method="post" action="" id="search_box" name="test">
	Callsign: <input type="text" id="callsign" name="callsign" value="" />
</form>

<div id="partial_view"></div>

</div>


<script type="text/javascript">
i=0;
$(document).ready(function(){
  $("#callsign").keyup(function(){
	if ($(this).val()) {
	$('#partial_view').load("logbook/search_result/" + $(this).val()).fadeIn("slow");
	}

  });
});
</script>