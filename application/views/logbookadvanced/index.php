<script type="text/javascript">
/*
 * Define custom date format and user column options
 */
var custom_date_format = "<?php echo $custom_date_format ?>";
<?php
if (!isset($options)) {
	$options = "{\"datetime\":{\"show\":\"true\"},\"de\":{\"show\":\"true\"},\"dx\":{\"show\":\"true\"},\"mode\":{\"show\":\"true\"},\"rstr\":{\"show\":\"true\"},\"rsts\":{\"show\":\"true\"},\"band\":{\"show\":\"true\"},\"myrefs\":{\"show\":\"true\"},\"refs\":{\"show\":\"true\"},\"name\":{\"show\":\"true\"},\"qslvia\":{\"show\":\"true\"},\"qsl\":{\"show\":\"true\"},\"lotw\":{\"show\":\"true\"},\"eqsl\":{\"show\":\"true\"},\"qslmsg\":{\"show\":\"true\"},\"dxcc\":{\"show\":\"true\"},\"state\":{\"show\":\"true\"},\"cqzone\":{\"show\":\"true\"},\"iota\":{\"show\":\"true\"},\"pota\":{\"show\":\"true\"},\"operator\":{\"show\":\"true\"},\"stationLocation\":{\"show\":\"true\"}}";
}
$options_obj = json_decode($options);
if (!is_object($options_obj)) {
	$options_obj = new stdClass();
}

foreach (['pota', 'operator', 'stationLocation'] as $new_option) {
	if (!isset($options_obj->{$new_option}) || !is_object($options_obj->{$new_option}) || !isset($options_obj->{$new_option}->show)) {
		$options_obj->{$new_option} = (object)['show' => 'true'];
	}
}

echo 'var user_options = ' . json_encode($options_obj) . ';';
?>
</script>
<script>
	const CSRF_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
	const CSRF_HASH = '<?= $this->security->get_csrf_hash(); ?>';
</script>
<style>
/* Map legend */
.legend {
	padding: 6px 10px;
	font: 13px system-ui, -apple-system, sans-serif;
	background: var(--bs-body-bg, #fff);
	line-height: 22px;
	color: var(--bs-body-color, #333);
	border: 1px solid var(--bs-border-color, #dee2e6);
	border-radius: 0.5rem;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
}

.legend h4 {
	text-align: center;
	font-size: 0.85rem;
	font-weight: 600;
	margin: 2px 6px;
}

.qso_manager {
	padding-top: 1.15rem;
	padding-bottom: 2.5rem;
}

.adv-header {
	padding-bottom: 0.75rem;
	margin-bottom: 1rem;
	border-bottom: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.22));
}

.adv-status-pill {
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
	padding: 0.22rem 0.65rem;
	border-radius: 999px;
	font-size: 0.8rem;
	font-weight: 500;
	border: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.28));
	font-variant-numeric: tabular-nums;
}

.adv-toolbar-card {
	border-radius: 0.5rem;
	margin-bottom: 1rem;
	position: relative;
	z-index: 20;
	overflow: visible;
}

.adv-toolbar-card .collapse.show {
	overflow: visible;
}

.adv-toolbar-card .dropdown-menu {
	z-index: 1040;
}

.adv-toolbar-bar {
	padding: 0.65rem 0.9rem;
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: space-between;
	gap: 0.5rem;
}

.adv-panel-toggle {
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
}

.adv-panel-toggle .toggle-chevron {
	font-size: 0.7rem;
	opacity: 0.7;
	transition: transform 0.15s ease;
}

.adv-panel-toggle[aria-expanded="true"] {
	background-color: rgba(var(--bs-primary-rgb, 13, 110, 253), 0.12);
	border-color: var(--bs-primary, #0d6efd);
	color: var(--bs-primary, #0d6efd);
	font-weight: 600;
}

.adv-panel-toggle[aria-expanded="true"] .toggle-chevron {
	transform: rotate(180deg);
}

.adv-drawer {
	border-top: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.2));
	padding: 0.9rem 1rem;
	background-color: rgba(127, 127, 127, 0.03);
}

.adv-drawer-title {
	font-size: 0.72rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.05em;
	opacity: 0.65;
	margin-bottom: 0.5rem;
	display: flex;
	align-items: center;
	gap: 0.4rem;
}

.adv-drawer .form-label {
	font-size: 0.78rem;
	font-weight: 600;
	margin-bottom: 0.2rem;
	white-space: nowrap;
}

.adv-actions-row {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 0.45rem;
}

.adv-actions-row.no-selection .adv-selection-action > .btn,
.adv-actions-row.no-selection > .btn.adv-selection-action {
	opacity: 0.6;
}

.adv-quickfilter-row {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 0.35rem;
}

.adv-quickfilter-row.no-selection button {
	opacity: 0.55;
}

.bootstrap-dialog.options .modal-header .btn-close,
.bootstrap-dialog.options .bootstrap-dialog-close-button {
	color: #fff;
	opacity: 0.9;
}

.adv-table-card {
	border-radius: 0.5rem;
	overflow: hidden;
	position: relative;
	z-index: 1;
}

.adv-table-card .card-body {
	padding: 0;
}

.adv-table-card #qsoList_wrapper {
	padding: 0;
}

.adv-table-card #qsoList_info {
	padding: 0.55rem 0.9rem;
	font-size: 0.82rem;
	opacity: 0.78;
}

#qsoList {
	font-size: 0.85rem;
	font-variant-numeric: tabular-nums;
	margin-top: 0 !important;
	margin-bottom: 0 !important;
	border-left: 0;
	border-right: 0;
}

#qsoList thead th {
	font-size: 0.74rem;
	text-transform: uppercase;
	letter-spacing: 0.03em;
	font-weight: 600;
	padding: 0.5rem 0.6rem;
	vertical-align: middle;
	white-space: nowrap;
}

#qsoList tbody td {
	padding: 0.38rem 0.6rem;
	vertical-align: middle;
	white-space: nowrap;
}

#qsoList .form-check {
	margin: 0;
	padding-left: 0;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: auto;
}

#qsoList .form-check-input {
	margin: 0;
	float: none;
	cursor: pointer;
	border-color: rgba(108, 117, 125, 0.55);
}

#qsoList .qso_call {
	float: none;
	width: auto;
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
	white-space: nowrap;
}

#qsoList .qso_icons {
	float: none;
	display: inline-flex;
	align-items: center;
	gap: 0.2rem;
}

#qsoList .qso-dx-call {
	font-weight: 600;
}

#qsoList .qsl-red,
#qsoList .eqsl-red,
#qsoList .lotw-red,
#qsoList .qrz-red {
	color: rgba(127, 127, 127, 0.34) !important;
}

#advancedmap {
	height: calc(100vh - 260px) !important;
	min-height: 420px;
	max-height: 860px !important;
	border-radius: 0.5rem;
	border: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.25));
}

.adv-filter-badge {
	font-size: 0.68rem;
	padding: 0.15em 0.45em;
	border-radius: 999px;
}
</style>

<?php
$options = $options_obj;
?>

<div class="container-fluid qso_manager px-3 px-md-4">
	<?php if ($this->session->flashdata('message')) { ?>
		<div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert">
			<i class="fas fa-exclamation-triangle me-1"></i>
			<?php echo $this->session->flashdata('message'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- Page Header -->
	<div class="adv-header d-flex flex-wrap align-items-center justify-content-between gap-2">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<h1 class="h4 mb-0 fw-bold">Advanced Logbook</h1>
			<span id="advancedSearchStatus" class="adv-status-pill text-muted">Loading...</span>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-2">
			<div class="btn-group btn-group-sm" role="group" aria-label="View mode">
				<button type="button" class="btn btn-outline-secondary active" id="tableViewButton" aria-pressed="true">
					<i class="fas fa-table me-1"></i>Table
				</button>
				<button type="button" class="btn btn-outline-secondary" id="mapButton" aria-pressed="false" onclick="mapQsos(document.getElementById('searchForm'));">
					<i class="fas fa-map-marked-alt me-1"></i><?php echo lang('filter_map'); ?>
				</button>
			</div>
			<button type="button" class="btn btn-sm btn-outline-secondary" id="optionButton">
				<i class="fas fa-columns me-1"></i><?php echo lang('filter_options'); ?>
			</button>
		</div>
	</div>

	<!-- Unified Search, Filters & Bulk Actions Card -->
	<form id="searchForm" name="searchForm" action="<?php echo base_url() . "index.php/logbookadvanced/search"; ?>" method="post">
		<input type="hidden" id="dupes" name="dupes" value="">

		<div class="card adv-toolbar-card">
			<!-- Primary Toolbar (always anchored at top so drawers open below) -->
			<div class="adv-toolbar-bar">
				<div class="d-flex flex-wrap align-items-center gap-1">
					<button type="button" class="btn btn-sm btn-outline-secondary adv-panel-toggle" data-bs-toggle="collapse" data-bs-target=".filterbody" aria-expanded="false">
						<i class="fas fa-filter"></i>
						<span><?php echo lang('filter_filters'); ?></span>
						<span id="filterCountBadge" class="badge text-bg-primary adv-filter-badge d-none">0</span>
						<i class="fas fa-chevron-down toggle-chevron"></i>
					</button>
					<button type="button" class="btn btn-sm btn-outline-secondary adv-panel-toggle" data-bs-toggle="collapse" data-bs-target=".qslfilterbody" aria-expanded="false">
						<i class="fas fa-envelope-open-text"></i>
						<span><?php echo lang('filter_qsl_filters'); ?></span>
						<span id="qslFilterCountBadge" class="badge text-bg-primary adv-filter-badge d-none">0</span>
						<i class="fas fa-chevron-down toggle-chevron"></i>
					</button>
					<button type="button" class="btn btn-sm btn-outline-secondary adv-panel-toggle" data-bs-toggle="collapse" data-bs-target=".actionbody" aria-expanded="false">
						<i class="fas fa-tasks"></i>
						<span><?php echo lang('filter_actions'); ?></span>
						<span id="selectedActionsBadge" class="badge text-bg-success adv-filter-badge d-none">0</span>
						<i class="fas fa-chevron-down toggle-chevron"></i>
					</button>
					<button type="button" class="btn btn-sm btn-outline-secondary adv-panel-toggle" data-bs-toggle="collapse" data-bs-target=".quickfilterbody" aria-expanded="false">
						<i class="fas fa-bolt"></i>
						<span><?php echo lang('filter_quickfilters'); ?></span>
						<i class="fas fa-chevron-down toggle-chevron"></i>
					</button>
				</div>

				<div class="d-flex flex-wrap align-items-center gap-2">
					<div class="d-inline-flex align-items-center gap-1">
						<label for="qsoResults" class="small text-muted mb-0 text-nowrap"><?php echo lang('filter_results'); ?></label>
						<select id="qsoResults" name="qsoResults" class="form-select form-select-sm w-auto">
							<option value="250" selected="selected">250</option>
							<option value="1000">1000</option>
							<option value="2500">2500</option>
							<option value="5000">5000</option>
						</select>
					</div>
					<button type="button" class="btn btn-sm btn-outline-secondary" id="dupeButton" title="Find duplicate QSOs">
						<i class="fas fa-clone me-1"></i><?php echo lang('filter_dupes'); ?>
					</button>
					<button type="reset" class="btn btn-sm btn-outline-danger" id="resetButton">
						<i class="fas fa-undo me-1"></i><?php echo lang('filter_reset'); ?>
					</button>
					<button type="submit" class="btn btn-sm btn-primary" id="searchButton">
						<i class="fas fa-search me-1"></i><?php echo lang('filter_search'); ?>
					</button>
				</div>
			</div>

			<!-- Drawer 1: General Filters -->
			<div class="filterbody collapse">
				<div class="adv-drawer">
					<div class="adv-drawer-title"><i class="fas fa-calendar-alt"></i> Date, Stations &amp; Signal</div>
					<div class="row g-2 mb-3">
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="dateFrom"><?php echo lang('filter_general_from'); ?></label>
							<input name="dateFrom" id="dateFrom" type="date" class="form-control form-control-sm">
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="dateTo"><?php echo ucfirst(lang('filter_general_to')); ?></label>
							<input name="dateTo" id="dateTo" type="date" class="form-control form-control-sm">
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="de"><?php echo lang('gen_hamradio_de'); ?></label>
							<select id="de" name="de" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<?php foreach ($deOptions as $deOption) { ?>
									<option value="<?php echo htmlentities($deOption); ?>"><?php echo htmlspecialchars($deOption); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="dx"><?php echo lang('gen_hamradio_dx'); ?></label>
							<input type="text" name="dx" id="dx" class="form-control form-control-sm" placeholder="Callsign..." value="">
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="band"><?php echo lang('gen_hamradio_band'); ?></label>
							<select id="band" name="band" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<?php foreach ($bands as $band) { ?>
									<option value="<?php echo htmlentities($band); ?>"><?php echo htmlspecialchars($band); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="mode"><?php echo lang('gen_hamradio_mode'); ?></label>
							<select id="mode" name="mode" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<?php foreach ($modes as $modeId => $mode) { ?>
									<option value="<?php echo htmlspecialchars($mode); ?>"><?php echo htmlspecialchars($mode); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="selectPropagation"><?php echo lang('filter_general_propagation'); ?></label>
							<select id="selectPropagation" name="selectPropagation" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="AS">Aircraft Scatter</option>
								<option value="AUR">Aurora</option>
								<option value="AUE">Aurora-E</option>
								<option value="BS">Back scatter</option>
								<option value="ECH">EchoLink</option>
								<option value="EME">Earth-Moon-Earth</option>
								<option value="ES">Sporadic E</option>
								<option value="FAI">Field Aligned Irregularities</option>
								<option value="F2">F2 Reflection</option>
								<option value="INTERNET">Internet-assisted</option>
								<option value="ION">Ionoscatter</option>
								<option value="IRL">IRLP</option>
								<option value="MS">Meteor scatter</option>
								<option value="RPT">Terrestrial or atmospheric repeater or transponder</option>
								<option value="RS">Rain scatter</option>
								<option value="SAT">Satellite</option>
								<option value="TEP">Trans-equatorial</option>
								<option value="TR">Tropospheric ducting</option>
							</select>
						</div>
						<div hidden class="sats_dropdown col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="sats"><?php echo lang('general_word_satellite'); ?></label>
							<select class="form-select form-select-sm" id="sats">
								<option value="All"><?php echo lang('general_word_all'); ?></option>
								<?php foreach ($sats as $sat) {
									echo '<option value="' . htmlentities($sat) . '">' . htmlentities($sat) . '</option>' . "\n";
								} ?>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="operator"><?php echo lang('gen_hamradio_operator'); ?></label>
							<input type="text" name="operator" id="operator" class="form-control form-control-sm" placeholder="Operator..." value="">
						</div>
					</div>

					<div class="adv-drawer-title"><i class="fas fa-globe"></i> Location &amp; Awards</div>
					<div class="row g-2">
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="dxcc"><?php echo lang('gen_hamradio_dxcc'); ?></label>
							<select class="form-select form-select-sm" id="dxcc" name="dxcc">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="0"><?php echo lang('filter_general_none'); ?></option>
								<?php foreach ($dxccarray as $dxcc) {
									echo '<option value="' . $dxcc->adif . '">' . $dxcc->prefix . ' - ' . ucwords(strtolower($dxcc->name), "- (/");
									if ($dxcc->Enddate != null) {
										echo ' - (Deleted DXCC)';
									}
									echo '</option>';
								} ?>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="gridsquare"><?php echo lang('gen_hamradio_gridsquare'); ?></label>
							<input type="text" name="gridsquare" id="gridsquare" class="form-control form-control-sm" placeholder="e.g. IO87" value="">
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="state"><?php echo lang('gen_hamradio_state'); ?></label>
							<input type="text" name="state" id="state" class="form-control form-control-sm" placeholder="State..." value="">
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="cqzone">CQ Zone</label>
							<select id="cqzone" name="cqzone" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<?php for ($i = 1; $i <= 40; $i++) {
									echo '<option value="' . $i . '">' . $i . '</option>';
								} ?>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="iota"><?php echo lang('gen_hamradio_iota'); ?></label>
							<select class="form-select form-select-sm" id="iota" name="iota">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<?php foreach ($iotaarray as $iota) {
									echo '<option value="' . $iota->tag . '">' . $iota->tag . ' - ' . $iota->name . '</option>';
								} ?>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="sota"><?php echo lang('gen_hamradio_sota'); ?></label>
							<input type="text" name="sota" id="sota" class="form-control form-control-sm" placeholder="SOTA ref..." value="">
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="pota"><?php echo lang('gen_hamradio_pota'); ?></label>
							<input type="text" name="pota" id="pota" class="form-control form-control-sm" placeholder="POTA ref..." value="">
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="wwff"><?php echo lang('gen_hamradio_wwff'); ?></label>
							<input type="text" name="wwff" id="wwff" class="form-control form-control-sm" placeholder="WWFF ref..." value="">
						</div>
					</div>
				</div>
			</div>

			<!-- Drawer 2: QSL Filters -->
			<div class="qslfilterbody collapse">
				<div class="adv-drawer">
					<div class="adv-drawer-title"><i class="fas fa-envelope"></i> Paper QSL &amp; Media</div>
					<div class="row g-2 mb-3">
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="qslSent"><?php echo lang('filter_qsl_sent'); ?></label>
							<select id="qslSent" name="qslSent" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="Y"><?php echo lang('general_word_yes'); ?></option>
								<option value="N"><?php echo lang('general_word_no'); ?></option>
								<option value="R"><?php echo lang('general_word_requested'); ?></option>
								<option value="Q"><?php echo lang('general_word_queued'); ?></option>
								<option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="qslReceived"><?php echo lang('filter_qsl_recv'); ?></label>
							<select id="qslReceived" name="qslReceived" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="Y"><?php echo lang('general_word_yes'); ?></option>
								<option value="N"><?php echo lang('general_word_no'); ?></option>
								<option value="R"><?php echo lang('general_word_requested'); ?></option>
								<option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
								<option value="V"><?php echo lang('filter_qsl_verified'); ?></option>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="qslSentMethod"><?php echo lang('filter_qsl_sent_method'); ?></label>
							<select id="qslSentMethod" name="qslSentMethod" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="B"><?php echo lang('general_word_qslcard_bureau'); ?></option>
								<option value="D"><?php echo lang('general_word_qslcard_direct'); ?></option>
								<option value="E"><?php echo lang('general_word_qslcard_electronic'); ?></option>
								<option value="M"><?php echo lang('general_word_qslcard_manager'); ?></option>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="qslReceivedMethod"><?php echo lang('filter_qsl_recv_method'); ?></label>
							<select id="qslReceivedMethod" name="qslReceivedMethod" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="B"><?php echo lang('general_word_qslcard_bureau'); ?></option>
								<option value="D"><?php echo lang('general_word_qslcard_direct'); ?></option>
								<option value="E"><?php echo lang('general_word_qslcard_electronic'); ?></option>
								<option value="M"><?php echo lang('general_word_qslcard_manager'); ?></option>
							</select>
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="qslvia"><?php echo lang('filter_qsl_via'); ?></label>
							<input type="search" id="qslvia" name="qslviainput" class="form-control form-control-sm" placeholder="Manager / via...">
						</div>
						<div class="col-6 col-sm-4 col-md-3 col-xl-2">
							<label class="form-label" for="qslimages"><?php echo lang('filter_qsl_images'); ?></label>
							<select class="form-select form-select-sm" id="qslimages" name="qslimages">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="Y"><?php echo lang('general_word_yes'); ?></option>
								<option value="N"><?php echo lang('general_word_no'); ?></option>
							</select>
						</div>
					</div>

					<div class="adv-drawer-title"><i class="fas fa-sync-alt"></i> Electronic Confirmations (LoTW &amp; eQSL)</div>
					<div class="row g-2">
						<div class="col-6 col-sm-3 col-xl-2">
							<label class="form-label" for="lotwSent"><?php echo lang('filter_lotw_sent'); ?></label>
							<select id="lotwSent" name="lotwSent" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="Y"><?php echo lang('general_word_yes'); ?></option>
								<option value="N"><?php echo lang('general_word_no'); ?></option>
								<option value="R"><?php echo lang('general_word_requested'); ?></option>
								<option value="Q"><?php echo lang('general_word_queued'); ?></option>
								<option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
							</select>
						</div>
						<div class="col-6 col-sm-3 col-xl-2">
							<label class="form-label" for="lotwReceived"><?php echo lang('filter_lotw_recv'); ?></label>
							<select id="lotwReceived" name="lotwReceived" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="Y"><?php echo lang('general_word_yes'); ?></option>
								<option value="N"><?php echo lang('general_word_no'); ?></option>
								<option value="R"><?php echo lang('general_word_requested'); ?></option>
								<option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
								<option value="V"><?php echo lang('filter_qsl_verified'); ?></option>
							</select>
						</div>
						<div class="col-6 col-sm-3 col-xl-2">
							<label class="form-label" for="eqslSent"><?php echo lang('filter_eqsl_sent'); ?></label>
							<select id="eqslSent" name="eqslSent" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="Y"><?php echo lang('general_word_yes'); ?></option>
								<option value="N"><?php echo lang('general_word_no'); ?></option>
								<option value="R"><?php echo lang('general_word_requested'); ?></option>
								<option value="Q"><?php echo lang('general_word_queued'); ?></option>
								<option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
							</select>
						</div>
						<div class="col-6 col-sm-3 col-xl-2">
							<label class="form-label" for="eqslReceived"><?php echo lang('filter_eqsl_recv'); ?></label>
							<select id="eqslReceived" name="eqslReceived" class="form-select form-select-sm">
								<option value=""><?php echo lang('general_word_all'); ?></option>
								<option value="Y"><?php echo lang('general_word_yes'); ?></option>
								<option value="N"><?php echo lang('general_word_no'); ?></option>
								<option value="R"><?php echo lang('general_word_requested'); ?></option>
								<option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
								<option value="V"><?php echo lang('filter_qsl_verified'); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<!-- Drawer 3: Bulk Actions (grouped into clean Bootstrap dropdowns) -->
			<div class="actionbody collapse">
				<script>
					var lang_filter_actions_delete_warning = '<?php echo lang('filter_actions_delete_warning'); ?>';
				</script>
				<div class="adv-drawer">
					<div class="adv-actions-row no-selection" id="bulkActionsRow">
						<span class="small fw-semibold text-muted me-1" id="bulkSelectionHint">
							<i class="fas fa-check-square me-1"></i><?php echo lang('filter_actions_w_selected'); ?> <span id="bulkSelectedCountText">(select rows below)</span>
						</span>

						<!-- Update QSOs -->
						<div class="dropdown adv-selection-action">
							<button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fas fa-edit me-1"></i>Update QSOs
							</button>
							<ul class="dropdown-menu">
								<li><button type="button" class="dropdown-item" id="btnUpdateFromCallbook"><i class="fas fa-address-book me-2 opacity-75"></i><?php echo lang('filter_actions_update_f_callbook'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="changeStationLocation"><i class="fas fa-map-marker-alt me-2 opacity-75"></i>Change Station Location</button></li>
								<li><button type="button" class="dropdown-item" id="changeSatellite"><i class="fas fa-satellite me-2 opacity-75"></i>Assign Satellite</button></li>
							</ul>
						</div>

						<!-- QSL Sent / Queue -->
						<div class="dropdown adv-selection-action">
							<button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fas fa-paper-plane me-1"></i>QSL Sent / Queue
							</button>
							<ul class="dropdown-menu">
								<li><h6 class="dropdown-header">Queue for Sending</h6></li>
								<li><button type="button" class="dropdown-item" id="queueBureau"><?php echo lang('filter_actions_queue_bureau'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="queueDirect"><?php echo lang('filter_actions_queue_direct'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="queueElectronic"><?php echo lang('filter_actions_queue_electronic'); ?></button></li>
								<li><hr class="dropdown-divider"></li>
								<li><h6 class="dropdown-header">Mark as Sent</h6></li>
								<li><button type="button" class="dropdown-item" id="sentBureau"><?php echo lang('filter_actions_sent_bureau'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="sentDirect"><?php echo lang('filter_actions_sent_direct'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="sentElectronic"><?php echo lang('filter_actions_sent_electronic'); ?></button></li>
								<li><hr class="dropdown-divider"></li>
								<li><h6 class="dropdown-header">Other Status</h6></li>
								<li><button type="button" class="dropdown-item" id="dontSend"><?php echo lang('filter_actions_not_sent'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="notRequired"><?php echo lang('filter_actions_qsl_n_required'); ?></button></li>
							</ul>
						</div>

						<!-- QSL Received -->
						<div class="dropdown adv-selection-action">
							<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fas fa-inbox me-1"></i>QSL Received
							</button>
							<ul class="dropdown-menu">
								<li><button type="button" class="dropdown-item" id="receivedBureau"><?php echo lang('filter_actions_recv_bureau'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="receivedDirect"><?php echo lang('filter_actions_recv_direct'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="receivedElectronic"><?php echo lang('filter_actions_recv_electronic'); ?></button></li>
							</ul>
						</div>

						<!-- Export, Labels & Media -->
						<div class="dropdown adv-selection-action">
							<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fas fa-file-export me-1"></i>Export &amp; Print
							</button>
							<ul class="dropdown-menu">
								<li><button type="button" class="dropdown-item" id="exportAdif"><i class="fas fa-download me-2 opacity-75"></i><?php echo lang('filter_actions_create_adif'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="printLabel"><i class="fas fa-print me-2 opacity-75"></i><?php echo lang('filter_actions_print_label'); ?></button></li>
								<li><button type="button" class="dropdown-item" id="qslSlideshow"><i class="fas fa-images me-2 opacity-75"></i><?php echo lang('filter_actions_qsl_slideshow'); ?></button></li>
							</ul>
						</div>

						<!-- Delete (separated to prevent misclicks) -->
						<button type="button" class="btn btn-sm btn-outline-danger ms-md-auto adv-selection-action" id="deleteQsos">
							<i class="fas fa-trash-alt me-1"></i><?php echo lang('filter_actions_delete'); ?>
						</button>
					</div>
				</div>
			</div>

			<!-- Drawer 4: Quickfilters (filter by 1 selected QSO) -->
			<div class="quickfilterbody collapse">
				<div class="adv-drawer">
					<div class="adv-quickfilter-row no-selection" id="quickFilterRow">
						<span class="small fw-semibold text-muted me-1">
							<i class="fas fa-bolt me-1"></i><?php echo lang('filter_quicksearch_w_sel'); ?>
						</span>
						<?php if (($options->dx->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchCallsign"><?php echo lang('filter_search_callsign'); ?></button>
						<?php } ?>
						<?php if (($options->dxcc->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchDxcc"><?php echo lang('filter_search_dxcc'); ?></button>
						<?php } ?>
						<?php if (($options->state->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchState"><?php echo lang('filter_search_state'); ?></button>
						<?php } ?>
						<?php if (($options->refs->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchGridsquare"><?php echo lang('filter_search_gridsquare'); ?></button>
						<?php } ?>
						<?php if (($options->cqzone->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchCqZone"><?php echo lang('filter_search_cq_zone'); ?></button>
						<?php } ?>
						<?php if (($options->mode->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchMode"><?php echo lang('filter_search_mode'); ?></button>
						<?php } ?>
						<?php if (($options->band->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchBand"><?php echo lang('filter_search_band'); ?></button>
						<?php } ?>
						<?php if (($options->iota->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchIota"><?php echo lang('filter_search_iota'); ?></button>
						<?php } ?>
						<?php if (($options->refs->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchSota"><?php echo lang('filter_search_sota'); ?></button>
						<?php } ?>
						<?php if (($options->refs->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchPota"><?php echo lang('filter_search_pota'); ?></button>
						<?php } ?>
						<?php if (($options->refs->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchWwff"><?php echo lang('filter_search_wwff'); ?></button>
						<?php } ?>
						<?php if (($options->operator->show ?? "true") == "true") { ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" id="searchOperator"><?php echo lang('filter_search_operator'); ?></button>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</form>

	<!-- Results Table Card -->
	<div class="card adv-table-card" id="qsoTableCard">
		<div class="card-body">
			<table style="width:100%" class="table-sm table table-hover table-striped align-middle text-start" id="qsoList">
				<thead>
					<tr>
						<th class="text-center">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="checkBoxAll" aria-label="Select all QSOs" />
							</div>
						</th>
						<?php if (($options->datetime->show ?? "true") == "true") {
							echo '<th>' . lang('general_word_datetime') . '</th>';
						} ?>
						<?php if (($options->de->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_de') . '</th>';
						} ?>
						<?php if (($options->stationLocation->show ?? "true") == "true") {
							echo '<th>Station Location</th>';
						} ?>
						<?php if (($options->dx->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_dx') . '</th>';
						} ?>
						<?php if (($options->mode->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_mode') . '</th>';
						} ?>
						<?php if (($options->rsts->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_rsts') . '</th>';
						} ?>
						<?php if (($options->rstr->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_rstr') . '</th>';
						} ?>
						<?php if (($options->band->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_band') . '</th>';
						} ?>
						<?php if (($options->myrefs->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_myrefs') . '</th>';
						} ?>
						<?php if (($options->refs->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_refs') . '</th>';
						} ?>
						<?php if (($options->name->show ?? "true") == "true") {
							echo '<th>' . lang('general_word_name') . '</th>';
						} ?>
						<?php if (($options->qslvia->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_qslvia') . '</th>';
						} ?>
						<?php if (($options->qsl->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_qsl') . '</th>';
						} ?>
						<?php if ($this->session->userdata('user_eqsl_name') != "" && ($options->eqsl->show ?? "true") == "true") {
							echo '<th class="eqslconfirmation">eQSL</th>';
						} ?>
						<?php if ($this->session->userdata('user_lotw_name') != "" && ($options->lotw->show ?? "true") == "true") {
							echo '<th class="lotwconfirmation">LoTW</th>';
						} ?>
						<?php if (($options->qslmsg->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_qslmsg') . '</th>';
						} ?>
						<?php if (($options->dxcc->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_dxcc') . '</th>';
						} ?>
						<?php if (($options->state->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_state') . '</th>';
						} ?>
						<?php if (($options->cqzone->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_cq_zone') . '</th>';
						} ?>
						<?php if (($options->iota->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_iota') . '</th>';
						} ?>
						<?php if (($options->pota->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_pota') . '</th>';
						} ?>
						<?php if (($options->operator->show ?? "true") == "true") {
							echo '<th>' . lang('gen_hamradio_operator') . '</th>';
						} ?>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
