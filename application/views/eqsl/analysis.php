
<div class="container eqsl">
<div class="card">
  <div class="card-header">
    <h5 class="card-title"><?php echo $page_title; ?></h5>
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/import');?>">Download QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo site_url('eqsl/Export');?>">Upload QSOs</a>
      </li>
    </ul>
  </div>

  <div class="card-body">
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
</div>

</div>



<div class="container">
<h2><?php echo $page_title; ?></h2>



</div>
