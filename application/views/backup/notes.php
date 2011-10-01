<notes>
<?php foreach ($list_note->result() as $row) { //print_r($row);?>
	<note>
		<id><?php echo $row->id; ?></id>
		<category><?php echo $row->cat; ?></category>
		<title><?php echo $row->title; ?></title>
		<contents><![CDATA[<?php echo $row->note; ?>]]></contents>
	</note>
<?php } ?>
</notes>