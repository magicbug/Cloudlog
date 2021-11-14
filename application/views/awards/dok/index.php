
<div class="container">
	<h2><?php echo $page_title; ?></h2>

	<table class="table table-sm table-striped table-hover">
        <?php
        if ($worked_bands) {
?>
  
	<thead>
        <tr>
        <td style="width:225px">DOKs (<?php echo count($doks)?>)</td>
    <?php
	foreach ($worked_bands as $slot) {
		echo "          <td>$slot</td>\n";
	}
    ?>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($doks as $dok=>$val){
            print("<tr><td>$dok</td>");
            foreach($val as $band=>$count){
		if (in_array($band, $worked_bands)) {
	                if ($count == 0){
	      	             print("<td>&nbsp;</td>");
	                }else{
	                    printf("<td><a href='javascript:displayDokContacts(\"%s\",\"%s\")'>%d</a></td>", str_replace("&", "%26", $dok), $band, $count);
	                }
		} 
            }
            print("</tr>");
        }

    ?>
    </tbody>

	</table>
<?php } else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    } ?>
</div>
