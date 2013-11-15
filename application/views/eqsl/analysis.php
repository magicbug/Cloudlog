<div id="container">
<h2><?php echo $page_title; ?></h2>

<?php $this->load->view('layout/messages'); ?>

<?php
	if (isset($eqsl_results_table_headers))
	{
    	echo "<p>The following QSLs have been received from eQSL.cc</p>";
    	echo $eqsl_results_table_headers;
    	echo $eqsl_results_table;
    }
    else
    {
    	echo "<p>There are no QSO confirmations waiting for you at eQSL.cc</p>";
    }
?>

</div>
