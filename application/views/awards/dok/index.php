
<div class="container">
	<h2><?php echo $page_title; ?></h2>

    <form class="form" action="<?php echo site_url('awards/dok'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <div class="form-group row">
                <label class="col-md-2 control-label" for="dok">Type</label>
                <div class="col-md-2">
                    <select id="doks" name="doks" class="form-control custom-select-sm">
                        <option value="both" <?php if ($this->input->post('doks') == "both" || $this->input->method() !== 'post') echo ' selected'; ?> >DOK + SDOK</option>
                        <?php echo '<option value="dok"';
                            if ($this->input->post('doks') == 'dok') echo ' selected';
                            echo '>DOK</option>'."\n";
                        ?>
                        <?php echo '<option value="sdok"';
                            if ($this->input->post('doks') == 'sdok') echo ' selected';
                            echo '>SDOK</option>'."\n";
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <button id="button2id" type="reset" name="button2id" class="btn-sm btn-warning">Reset</button>
                    <button id="button1id" type="submit" name="button1id" class="btn-sm btn-primary">Show</button>
                </div>
            </div>
        </fieldset>
    </form>
	<table class="table table-sm table-striped table-hover">
        <?php
        if ($worked_bands) {
?>
  
	<thead>
        <tr>
        <td style="width:225px">DOKs (<?php echo count($doks)?>)</td>
    <?php
	foreach ($worked_bands as $slot) {
		echo "          <td style=\"text-align: center;\">$slot</td>\n";
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
	                     print("<td style=\"text-align: right; padding-right: 2em\">&nbsp;</td>");
	                }else{
	                    printf("<td style=\"text-align: right; padding-right: 2em\"><a href='javascript:displayDokContacts(\"%s\",\"%s\")'>%d</a></td>", str_replace("&", "%26", $dok), $band, $count);
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
