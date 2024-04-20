<style>
    /*Legend specific*/
.legend {
  padding: 6px 8px;
  font: 14px Arial, Helvetica, sans-serif;
  background: white;
  background: rgba(255, 255, 255, 0.8);
  line-height: 24px;
  color: #555;
}
.legend h4 {
  text-align: center;
  font-size: 16px;
  margin: 2px 12px 8px;
  color: #555;
}
.legend span {
  position: relative;
  bottom: 3px;
  color: #555;
}
.legend i {
  width: 18px;
  height: 18px;
  float: left;
  margin: 0 8px 0 0;
  opacity: 0.7;
  color: #555;
}
</style>
<div class="container">
    <!-- Award Info Box --> 
    <br>
    <div id="awardInfoButton">
        <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_wab_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_wab_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_wab_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_wab_description_ln4'); ?>";
        </script>
        
        <h2><?php echo $page_title; ?></h2>
        <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
    </div>

    <div id="map" style="width: 100%; height: 100vh;"></div>

</div>