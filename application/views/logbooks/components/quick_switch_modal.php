<?php
$CI =& get_instance();
$CI->load->model('logbooks_model');
$CI->load->model('stations');

$quick_switch_logbooks = $CI->logbooks_model->show_all();
$quick_switch_locations = $CI->stations->all_of_user();
$active_logbook_id = $CI->session->userdata('active_station_logbook');
$active_location_id = $CI->stations->find_active();
?>

<div class="modal fade" id="logbookQuickSwitchModal" tabindex="-1" aria-labelledby="logbookQuickSwitchModalLabel" aria-hidden="true"
	data-active-logbook="<?php echo htmlspecialchars((string) $active_logbook_id); ?>"
	data-active-location="<?php echo htmlspecialchars((string) $active_location_id); ?>"
	data-last-location-url="<?php echo site_url('logbooks/last_location'); ?>">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<form method="post" action="<?php echo site_url('logbooks/quick_switch'); ?>" id="logbookQuickSwitchForm">
				<div class="modal-header">
					<h5 class="modal-title" id="logbookQuickSwitchModalLabel"><?php echo lang('station_logbooks_quick_switch_title'); ?></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div id="quickSwitchStepLogbook">
						<h6 class="mb-3"><?php echo lang('station_logbooks_quick_switch_step_logbook'); ?></h6>
						<input type="search" class="form-control mb-3" id="quickSwitchLogbookSearch"
							placeholder="<?php echo lang('station_logbooks_quick_switch_search'); ?>" autocomplete="off">
						<div class="list-group" id="quickSwitchLogbookList">
							<?php if ($quick_switch_logbooks->num_rows() > 0) {
								foreach ($quick_switch_logbooks->result() as $logbook) { ?>
									<label class="list-group-item list-group-item-action d-flex align-items-center gap-2 quick-switch-logbook-item"
										data-name="<?php echo htmlspecialchars(strtolower($logbook->logbook_name)); ?>">
										<input class="form-check-input flex-shrink-0" type="radio" name="logbook_id"
											value="<?php echo $logbook->logbook_id; ?>"
											<?php if ((string) $active_logbook_id === (string) $logbook->logbook_id) { echo 'checked'; } ?>>
										<span><?php echo htmlspecialchars($logbook->logbook_name); ?></span>
										<?php if ((string) $active_logbook_id === (string) $logbook->logbook_id) { ?>
											<span class="badge bg-success ms-auto"><?php echo lang('station_logbooks_active_logbook'); ?></span>
										<?php } ?>
									</label>
								<?php }
							} ?>
						</div>
					</div>

					<div id="quickSwitchStepLocation" class="d-none">
						<p class="mb-2">
							<a href="#" id="quickSwitchBackLink"><?php echo lang('station_logbooks_quick_switch_back'); ?></a>
						</p>
						<h6 class="mb-3"><?php echo lang('station_logbooks_quick_switch_step_location'); ?></h6>
						<input type="search" class="form-control mb-3" id="quickSwitchLocationSearch"
							placeholder="<?php echo lang('station_logbooks_quick_switch_search'); ?>" autocomplete="off">
						<div class="list-group" id="quickSwitchLocationList">
							<?php if ($quick_switch_locations->num_rows() > 0) {
								foreach ($quick_switch_locations->result() as $location) { ?>
									<label class="list-group-item list-group-item-action d-flex align-items-center gap-2 quick-switch-location-item"
										data-name="<?php echo htmlspecialchars(strtolower($location->station_profile_name . ' ' . $location->station_callsign)); ?>">
										<input class="form-check-input flex-shrink-0" type="radio" name="station_id"
											value="<?php echo $location->station_id; ?>"
											<?php if ((string) $active_location_id === (string) $location->station_id) { echo 'checked'; } ?>>
										<span><?php echo htmlspecialchars($location->station_profile_name); ?></span>
										<?php if ((string) $active_location_id === (string) $location->station_id) { ?>
											<span class="badge bg-success ms-auto"><?php echo lang('station_location_active'); ?></span>
										<?php } ?>
									</label>
								<?php }
							} ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo lang('general_word_cancel'); ?></button>
					<button type="button" class="btn btn-primary" id="quickSwitchNextBtn"><?php echo lang('station_logbooks_quick_switch_next'); ?></button>
					<button type="submit" class="btn btn-success d-none" id="quickSwitchSetActiveBtn"><?php echo lang('station_logbooks_quick_switch_set_active'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>
