<div hx-target="this" hx-swap="outerHTML">
<input aria-describedat="slugHelp" pattern="[a-zA-Z0-9-]+" class="form-control <?php if($slugAvailable == true) { echo "is-valid"; } else { echo "is-invalid"; } ?>" name="public_slug" id="publicSlugInput" hx-post="<?php echo site_url('logbooks/publicslug_validate/'); ?>"  value="<?php echo $this->input->post('public_slug'); ?>" hx-trigger="keyup changed delay:500ms" required>
<?php 
if($slugAvailable == true) { ?>
    <div class="valid-feedback">Looks good! <?php echo $this->input->post('public_slug'); ?> is available</div>
<?php } else { ?>
    <div class="invalid-feedback">Please choose a public slug. <?php echo $this->input->post('public_slug'); ?> is not available</div>
<?php } ?>

</div>