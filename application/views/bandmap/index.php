<script>
	var dxcluster_provider="/index.php/dxcluster";
</script>

<div class="container">

<br>

<h2><?php echo $page_title; ?></h2>
                <div class="form-group col-md-6">
                  <label for="band"><?php echo lang('gen_hamradio_band'); ?></label>

                  <select id="band" class="form-control form-control-sm" name="band">
                  <?php 
			$bands = $this->bands->get_user_bands_for_qso_entry();	
			foreach($bands as $key=>$bandgroup) {
                          echo '<optgroup label="' . strtoupper($key) . '">';
                          foreach($bandgroup as $band) {
                            echo '<option value="' . $band . '"';
                              if ($band == "20m") echo ' selected';
                              echo '>' . $band . '</option>'."\n";
                          }
                          echo '</optgroup>';
                        }
                  ?>
                  </select>
                </div>
	
<figure class="highcharts-figure">
    <div id="bandmap"></div>
    <p class="highcharts-description">
    </p>
</figure>
</div>
