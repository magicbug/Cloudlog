<?php
$cq = 0;
$dok = 0;
$dxcc = 0;
$iota = 0;
$pota = 0;
$sig = 0;
$sota = 0;
$uscounties = 0;
$vucc = 0;
$was = 0;
$wwff = 0;
?>
<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<h2><?php echo lang('options_bands'); ?></h2>

<div class="card">
<div class="card-header">
	<?php echo lang('options_bands'); ?>
  </div>
  <div class="card-body">
    <p class="card-text">
		<?php echo lang('options_bands_text_ln1'); ?>
	</p>
    <p class="card-text">
		<?php echo lang('options_bands_text_ln2'); ?>
	</p>
    <div class="table-responsive">
		
    <table style="width:100%" class="bandtable table table-sm table-striped">
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('gen_hamradio_band'); ?></th>
					<th><?php echo lang('gen_hamradio_cq'); ?></th>
                    <th><?php echo lang('gen_hamradio_dok'); ?></th>
                    <th><?php echo lang('gen_hamradio_dxcc'); ?></th>
                    <th><?php echo lang('gen_hamradio_iota'); ?></th>
					<th><?php echo lang('gen_hamradio_pota'); ?></th>
					<th><?php echo lang('gen_hamradio_sig'); ?></th>
                    <th><?php echo lang('gen_hamradio_sota'); ?></th>
                    <th><?php echo lang('gen_hamradio_county_reference'); ?></th>
                    <th><?php echo lang('menu_vucc'); ?></th>
                    <th><?php echo lang('menu_was'); ?></th>
					<th><?php echo lang('gen_hamradio_wwff'); ?></th>
					<th><?php echo lang('gen_hamradio_bandgroup'); ?></th>
					<th><?php echo lang('options_bands_ssb_qrg'); ?></th> 
					<th><?php echo lang('options_bands_data_qrg'); ?></th>
					<th><?php echo lang('options_bands_cw_qrg'); ?></th>
					<?php if($this->session->userdata('user_type') == '99') { ?>
                    <th></th>
                    <th></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($bands as $band) { ?>
				<tr>
                    <td style="text-align: center; vertical-align: middle;" class='band_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->active == 1) {echo 'checked';}?>></td>
					<td style="text-align: center; vertical-align: middle;" ><?php echo $band->band;?></td>
					<td style="text-align: center; vertical-align: middle;" class='cq_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->cq == 1) {echo 'checked'; $cq++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='dok_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->dok == 1) {echo 'checked'; $dok++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='dxcc_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->dxcc == 1) {echo 'checked'; $dxcc++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='iota_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->iota == 1) {echo 'checked'; $iota++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='pota_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->iota == 1) {echo 'checked'; $pota++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='sig_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->sig == 1) {echo 'checked'; $sig++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='sota_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->sota == 1) {echo 'checked'; $sota++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='uscounties_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->uscounties == 1) {echo 'checked'; $uscounties++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='vucc_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->vucc == 1) {echo 'checked'; $vucc++;}?>></td>
                    <td style="text-align: center; vertical-align: middle;" class='was_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->was == 1) {echo 'checked'; $was++;}?>></td>
					<td style="text-align: center; vertical-align: middle;" class='wwff_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->wwff == 1) {echo 'checked'; $wwff++;}?>></td>
					<td style="text-align: center; vertical-align: middle;" ><?php echo $band->bandgroup;?></td>
					<td style="text-align: center; vertical-align: middle;" ><?php echo $band->ssb;?></td>
					<td style="text-align: center; vertical-align: middle;" ><?php echo $band->data;?></td>
					<td style="text-align: center; vertical-align: middle;" ><?php echo $band->cw;?></td>
					<?php if($this->session->userdata('user_type') == '99') { ?>
					<td style="text-align: center; vertical-align: middle;" >
						<a href="javascript:editBandDialog('<?php echo $band->bandid ?>');" class="btn btn-outline-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
					</td>
					<td style="text-align: center; vertical-align: middle;" >
						<a href="javascript:deleteBand('<?php echo $band->id . '\',\'' . $band->band ?>');" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></a>
                    </td>
					<?php } ?>
				</tr>

				<?php } ?>
			</tbody>
			<tfoot>
					<th><?php echo lang('general_word_all'); ?></th>
					<th></th>
					<th class="master_cq"><input type="checkbox" <?php if ($cq > 0) echo 'checked';?>></th>
					<th class="master_dok"><input type="checkbox" <?php if ($dok > 0) echo 'checked';?>></th>
					<th class="master_dxcc"><input type="checkbox" <?php if ($dxcc > 0) echo 'checked';?>></th>
					<th class="master_iota"><input type="checkbox" <?php if ($iota > 0) echo 'checked';?>></th>
					<th class="master_pota"><input type="checkbox" <?php if ($pota > 0) echo 'checked';?>></th>
					<th class="master_sig"><input type="checkbox" <?php if ($sig > 0) echo 'checked';?>></th>
					<th class="master_sota"><input type="checkbox" <?php if ($sota > 0) echo 'checked';?>></th>
					<th class="master_uscounties"><input type="checkbox" <?php if ($uscounties > 0) echo 'checked';?>></th>
					<th class="master_vucc"><input type="checkbox" <?php if ($vucc > 0) echo 'checked';?>></th>
					<th class="master_was"><input type="checkbox" <?php if ($was > 0) echo 'checked';?>></th>
					<th class="master_wwff"><input type="checkbox" <?php if ($wwff > 0) echo 'checked';?>></th>
					<th></th>
					<th></th>
					<th></th>
                    <th></th>
                    <th></th>
					<th></th>
			</tfoot>
		<table>
	</div>
  <br/>
  <p>
		<?php if($this->session->userdata('user_type') == '99') { ?>
		<script>
			var lang_options_bands_edit = '<?php echo lang('options_bands_edit'); ?>';
			var lang_options_bands_create = '<?php echo lang('options_bands_create'); ?>';
			var lang_admin_close = '<?php echo lang('admin_close'); ?>';
			var lang_options_bands_delete_warning = '<?php echo lang('options_bands_delete_warning'); ?>';
			var lang_options_bands_activateall_warning = '<?php echo lang('options_bands_activateall_warning'); ?>';
			var lang_options_bands_deactivateall_warning = '<?php echo lang('options_bands_deactivateall_warning'); ?>';
		</script>
	  	<button onclick="createBandDialog();" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> <?php echo lang('options_bands_create'); ?></button>
  		<button onclick="activateAllBands();" class="btn btn-primary btn-sm"><?php echo lang('options_bands_activate_all'); ?></button>
		<button onclick="deactivateAllBands();" class="btn btn-primary btn-sm"><?php echo lang('options_bands_deactivate_all'); ?></button>
		<?php } ?>
	</p>
</div>
</div>
