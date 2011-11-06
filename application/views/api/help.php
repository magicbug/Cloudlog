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

There are a number of API calls you can make from other applications.

<h2>search</h2>
<h3>Description</h3>
Query the logbook
<h3>Syntax</h3>
<li><pre>/search/query[&lt;field&gt;&lt;=|~&gt;&lt;value&gt;{(and|or)...]}/limit[&lt;num&gt;]/fields[&lt;field1&gt;,{&lt;field2&gt;}]/order[&lt;field&gt;]</pre>
<h3>Example</h3>
Search for entries with a call beginning with <b>M0</b> and a locator beginning with <b>I</b> or <b>J</b>, show the callsign and locator fields, order it by callsign and limit the results to <b>10</b>.
<li><pre>/search/query[Call~M0*(and)(Locator~I*(or)Locator~J*)]/limit[10]/fields[distinct(Call),Locator]/order[Call(asc)]</pre>
<li><a href="/index.php/api/search/query[Call~M0*(and)(Locator~I*(or)Locator~J*)]/limit[10]/fields[distinct(Call),Locator]/order[Call(asc)]">Run it!</a>

</div>
