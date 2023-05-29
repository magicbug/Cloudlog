<div class="container eqsl">
<h2><?php echo $page_title; ?></h2>
<div class="card">
  <div class="card-header">
  	<div class="card-title">eQSL Tools</div>
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/import');?>">Download QSOs</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/Export');?>">Upload QSOs</a>
      </li>

      <li class="nav-item">
        <a class="nav-link active" href="<?php echo site_url('eqsl/tools');?>">Tools</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/download');?>">Download eQSL cards</a>
      </li>
    </ul>
  </div>

  <div class="card-body">
		<?php $this->load->view('layout/messages'); ?>

        <p><a class="btn btn-primary" href="<?php echo site_url('eqsl/mark_all_sent'); ?>">Mark All QSOs as Sent to eQSL</a> use this if you have lots of QSOs to upload to eQSL it will save the server timing out.</p>
  </div>
</div>

</div>
