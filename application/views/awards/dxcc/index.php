
<div id="container">
	<h1><?php echo $page_title; ?></h1>

	<!-- Sub Nav for Awards -->
	
	<ul class="tabs">
	  <li class="active"><a href="<?php echo site_url('awards/dxcc'); ?>">DXCC</a></li>
	  <li><a href="<?php echo site_url('awards/wab'); ?>">WAB</a></li>
	  <li><a href="<?php echo site_url('awards/sota'); ?>">SOTA</a></li>
	  <li><a href="<?php echo site_url('awards/wacral'); ?>">WACRAL</a></li>
	</ul>

	<table width="100%" class="zebra-striped">
	<thead>
        <tr>
        <td>Country</td>
        <td>160m</td>
        <td>80m</td>
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
        </tr>
    </thead>
    <tbody>
    <?php

        foreach($dxcc as $country=>$val){
            print("<tr><td>$country</td>");
            foreach($val as $band=>$count){
                $count = ($count == 0)?"&nbsp;":$count;
                print("<td>$count</td>");
            }
            print("</tr>");
        }

    ?>
    </tbody>

	</table>
</div>
