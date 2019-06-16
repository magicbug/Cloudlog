
<div class="container">
	<h1><?php echo $page_title; ?></h1>

	<!-- Sub Nav for Awards -->
	
    <?php $this->load->view("awards/nav_bar")?>
    

	<table class="table table-striped table-hover">
	<thead>
        <tr>
        <td style="width:225px">Country (<?php echo count($dxcc)?>)</td>
        <td>160m</td>
        <td>80m</td>
        <td>60m</td>
        <td>40m</td>
        <td>30m</td>
        <td>20m</td>
        <td>17m</td>
        <td>15m</td>
        <td>12m</td>
        <td>10m</td>
        <td>6m</td>
        <td>4m</td>
        <td>2m</td>
        <td>70cm</td>
        <td>23cm</td>
        <td>13cm</td>
        <td>9cm</td>
        <td>6cm</td>
        <td>3cm</td>
        <td>1.25cm</td>
        </tr>
    </thead>
    <tbody>
    <?php

        foreach($dxcc as $country=>$val){
            print("<tr><td>$country</td>");
            foreach($val as $band=>$count){
                if ($count == 0){
                    print("<td>&nbsp;</td>");
                }else{
                    printf("<td><a href='dxcc_details?Country=\"%s\"&Band=\"%s\"'>%d</a></td>", str_replace("&", "%26", $country), $band, $count);
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
