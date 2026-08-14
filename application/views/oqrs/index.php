<div class="container visitor-page">

    <h2><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></h2>

    <div class="card">
        <div class="card-header">
            <?php echo lang('visitor_request_qsl'); ?>
        </div>
        <div class="card-body">

            <div class="stationinfo">

                <?php
		if ($global_oqrs_text) {
			echo $global_oqrs_text;
			echo '<br /><br />';
		}
		if (!empty($slug)) { ?>
			<script>var oqrs_public_slug = <?php echo json_encode($slug); ?>;</script>
		<?php }
		if ($groupedSearch == 'on') {
			$grouped_hint = !empty($slug) ? lang('visitor_oqrs_grouped_hint') : lang('visitor_oqrs_grouped_hint_global');
			echo htmlspecialchars($grouped_hint, ENT_QUOTES, 'UTF-8');
			echo '<br /><br /><form class="d-flex align-items-center" onsubmit="return false;"><label class="my-1 me-2" for="oqrssearch">Enter your callsign: </label>
			<input class="form-control me-sm-2 w-auto" id="oqrssearch" type="search" name="callsign" placeholder="Search Callsign" aria-label="Search" required="required">
			<button onclick="searchOqrsGrouped();" class="btn btn-sm btn-primary" id="stationbuttonsubmit" type="button"><i class="fas fa-search"></i> Search</button>
			</form>';
			echo '<div class="searchinfo"></div>';
			?>
                <script>
                var input = document.getElementById("oqrssearch");

                input.addEventListener("keypress", function(event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        document.getElementById("stationbuttonsubmit").click();
                    }
                });
                </script>
				</div>
                <?php
			} else {
		  if ($stations->result() != NULL) { ?>

                <form class="d-flex align-items-center" enctype="multipart/form-data">
                    <label class="my-1 me-2" for="station">Select station: </label>
                    <select id="station" class="form-select w-auto my-1 me-sm-2" name="station">
                        <?php foreach($stations->result() as $station) {
									echo '<option value="' . $station->station_id . '">' . htmlspecialchars($station->station_profile_name, ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($station->station_callsign, ENT_QUOTES, 'UTF-8') . '</option>'."\n";
								} ?>
                    </select>
                    <button id="button1id" type="button" onclick="loadStationInfo();" name="button1id" class="btn btn-sm btn-primary"> Proceed</button>
                </form>
            </div>
			<div class="resulttable"></div>

            <div class="searchinfo"></div>
            <?php
		}

		else {
			echo 'No stations found that are using Cloudlog OQRS.';
		}
	}
		?>

        </div>
    </div>
</div>
