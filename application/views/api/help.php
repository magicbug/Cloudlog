<div id="container">

<h2>API</h2>

	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

	<script type="text/javascript">

		$(document).ready(function() {
			$(".qsobox").fancybox({
				'width'				: '75%',
				'height'			: '50%',
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});


		});

	</script>



<?php if($this->session->flashdata('notice')) { ?>
	<div id="message" >
    	<?php echo $this->session->flashdata('notice'); ?>
	</div>
<?php } ?>

<h3>API Keys</h3>

<?php if ($api_keys->num_rows() > 0) { ?>

	<table>

	<tr>
		<td>API Key</td>
		<td>Rights</td>
		<td>Status</td>
	</tr>

	<?php foreach ($api_keys->result() as $row) { ?>

		<tr>
			<td><?php echo $row->key; ?></td>
			<td>

				<?php
					
					if($row->rights == "rw") {
						echo "Read & Write";
					} elseif($row->rights == "r") {
						echo "Read Only";
					} else {
						echo "Unknown";
					}
	
				?>

			</td>
			<td><?php echo ucfirst($row->status); ?></td>
		</tr>

	<?php } ?>

	</table>	

<?php } else { ?>
	<p>You have no API Keys.</p>
<?php } ?>

	<h4>Generate API Key</h4>

	<ul>
		<li><a href="<?php echo site_url('api/generate/rw'); ?>">Key with Read & Write Access</a></li>
		<li><a href="<?php echo site_url('api/generate/r'); ?>">Key with Read Only Access</a></li>
	</ul>
There are a number of API calls you can make from other applications, with output available in either XML or JSON.
<h3>API Guide</h3>
<h4>Description</h4>
Query the logbook, and output in XML format.
<h4>Syntax</h4>
<li><pre>/search/format[xml]/query[&lt;field&gt;&lt;=|~&gt;&lt;value&gt;{(and|or)...]}/limit[&lt;num&gt;]/fields[&lt;field1&gt;,{&lt;field2&gt;}]/order[&lt;field&gt;]</pre>
<h4>Example</h4>
Search for entries with a call beginning with <b>M0</b> and a locator beginning with <b>I</b> or <b>J</b>, show the callsign and locator fields, order it by callsign and limit the results to <b>10</b>.
<li><pre>/search/format[xml]/query[Call~M0*(and)(Locator~I*(or)Locator~J*)]/limit[10]/fields[distinct(Call),Locator]/order[Call(asc)]</pre>
<li><a href="<?php echo site_url('/api/search/format[xml]/query[Call~M0*(and)(Locator~I*(or)Locator~J*)]/limit[10]/fields[distinct(Call),Locator]/order[Call(asc)]'); ?>">Run it! (XML)</a> or <a href="<?php echo site_url('/api/search/format[json]/query[Call~M0*(and)(Locator~I*(or)Locator~J*)]/limit[10]/fields[distinct(Call),Locator]/order[Call(asc)]'); ?>">Run it! (JSON)</a>

</div>
