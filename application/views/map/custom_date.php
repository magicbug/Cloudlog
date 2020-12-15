<div class="container map-QSOs">

	<h2><?php echo $station_profile->station_profile_name; ?> Station Profile QSOs (Custom Date)</h2>

	<?php if($this->session->flashdata('notice')) { ?>
	<div class="alert alert-info" role="alert">
	  <?php echo $this->session->flashdata('notice'); ?>
	</div>
	<?php } ?>

<form method="post" action="<?php echo site_url('map/custom');?>">
    <p class="card-text">From date:</p>
    <div class="row">
        <div class="input-group date col-md-3" id="datetimepicker1" data-target-input="nearest">
            <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                <div class="input-group-append"  data-target="#datetimepicker1" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>

        <p class="card-text">To date:</p>
        
        <div class="row">
            <div class="input-group date col-md-3" id="datetimepicker2" data-target-input="nearest">
                <input name="to" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
        </div>
    </div>

    <input type="submit" value="Load Map">
    <br><br>
</form>

</div>

	<!-- Map -->
	<div id="map" style="width: 100%; height: 700px;"></div> 

    <div class="alert alert-success" role="alert">Showing QSOs for Custom Date for Active Station Profile - <?php echo $station_profile->station_profile_name; ?> </div>