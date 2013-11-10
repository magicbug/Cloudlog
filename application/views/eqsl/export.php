<div id="container">

<h2><?php echo $page_title; ?></h2>
<?php $this->load->view('layout/messages'); ?>

<?php
	if (isset($eqsl_table))
	{
    	echo "<p>The following QSOs have not been sent to eQSL.cc</p>";
    	echo $eqsl_table;
    	echo "<p>Clicking \"Upload QSOs\" will send QSO information to eQSL.cc.</p>";
		echo form_open('eqsl/export');
		echo "<input type=\"hidden\" name=\"eqslexport\" id=\"eqslexport\" value=\"export\" />";
		echo "<input class=\"btn primary\" type=\"submit\" value=\"Upload QSOs\" /></form>";
	}
	else
	{
		if (isset($eqsl_results_table))
		{
			echo "<p>The following QSOs were sent to eQSL.cc</p>";
			echo $eqsl_results_table;
		}
		else
		{
			echo "<p>There are no QSOs that need to be sent to eQSL.cc at this time. Go log some more QSOs!</p>";
		}
	}
?>
</div>
