
<div class="container">
	<h1><?php echo $page_title; ?></h1>

	<!-- Sub Nav for Awards -->
	
    <?php $this->load->view("awards/nav_bar")?>
    
	<table class="table table-striped table-hover">
	<thead>
        <tr>
        <td style="width:225px">Country (<?php echo count($dxcc)?>)</td>
    <?php
	foreach ($worked_bands as $slot) {
		echo "          <td>$slot</td>\n";
	}
    ?>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($dxcc as $country=>$val){
            print("<tr><td>$country</td>");
            foreach($val as $band=>$count){
		if (in_array($band, $worked_bands)) {
	                if ($count == 0){
	      	             print("<td>&nbsp;</td>");
	                }else{
	                    printf("<td><a href='dxcc_details?Country=\"%s\"&Band=\"%s\"'>%d</a></td>", str_replace("&", "%26", $country), $band, $count);
	                }
		} 
            }
            print("</tr>");
        }

    ?>
    </tbody>

	</table>
	<style>
        #table-fixed{
            position: fixed;
            top: 40px;
            display: none;
            background-color: white;
            border: 1px solid black;
        }
	</style>
	<script>
	var tableOffset = $(".zebra-striped").offset().top-40;
	$('#table-fixed').width($(".zebra-striped").width());
	var $header = $(".zebra-striped > thead").clone();
	var $fixedHeader = $("#table-fixed").append($header);

	$(window).bind("scroll", function() {
	    var offset = $(this).scrollTop();

        if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
	        $fixedHeader.show();
	    }
	    else if (offset < tableOffset) {
	        $fixedHeader.hide();
	    }
	});
	</script>
</div>
