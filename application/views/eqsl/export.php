
<div class="container eqsl">
<h2><?php echo $page_title; ?></h2>
<div class="card">
  <div class="card-header">
  	<div class="card-title">eQSL QSO Upload</div>
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/import');?>">Download QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo site_url('eqsl/Export');?>">Upload QSOs</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/tools');?>">Tools</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/download');?>">Download eQSL cards</a>
      </li>
    </ul>
  </div>

  <div class="card-body">
  <?php $this->load->view('layout/messages'); ?>

<?php
	if (isset($eqsl_table))
	{
?>
    	<p>Below is a table of QSOs that have not yet been sent to eQSL.</p>

    	<p><span class="badge text-bg-info">Info</span> Please make sure the "eQSL QTH Nickname" field is set in your station profile and that the value matches the QTH Nickname you set within eQSL.</p>
 <?php

    	echo $eqsl_table;
    	echo "<p>Clicking \"Upload QSOs\" will send QSO information to eQSL.cc.</p>";
		echo form_open('eqsl/export');
		echo "<input type=\"hidden\" name=\"eqslexport\" id=\"eqslexport\" value=\"export\" />";
		echo "<input class=\"btn btn-primary\" type=\"submit\" value=\"Upload QSOs\" /></form>";
	}
	else
	{
		if (isset($eqsl_results_table))
		{
			echo "<p>The following QSOs were sent to eQSL.</p>";
			echo $eqsl_results_table;
		}
		else
		{
			echo "<p>There are no QSOs that need to be sent to eQSL at this time. Go log some more QSOs!</p>";
		}
	}
?>
</div>

</div>
</div>
