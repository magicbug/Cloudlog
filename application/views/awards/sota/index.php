<div class="container">
	<br>
	<div id="awardInfoButton">
		<script>
		var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
		var lang_award_info_ln1 = "<?php echo lang('awards_sota_description_ln1'); ?>";
		var lang_award_info_ln2 = "<?php echo lang('awards_sota_description_ln2'); ?>";
		var lang_award_info_ln3 = "<?php echo lang('awards_sota_description_ln3'); ?>";
		var lang_award_info_ln4 = "<?php echo lang('awards_sota_description_ln4'); ?>";
		</script>
		<h2><?php echo $page_title; ?></h2>
		<button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
	</div>

	<div class="card mt-3">
		<div class="card-body">
			<form id="sotaFilters" class="row gy-2 gx-3 align-items-end">
				<div class="col-auto">
					<label class="form-label"><?php echo lang('general_word_date'); ?> (from)</label>
					<input type="date" name="from" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
					<label class="form-label"><?php echo lang('general_word_date'); ?> (to)</label>
					<input type="date" name="to" class="form-control form-control-sm">
				</div>
				<div class="col-auto">
					<label class="form-label"><?php echo lang('gen_hamradio_band'); ?></label>
					<select name="band" class="form-select form-select-sm">
						<option value="All">All</option>
						<?php if (!empty($worked_bands)) { foreach ($worked_bands as $band) { echo '<option value="'.htmlspecialchars($band).'">'.htmlspecialchars($band).'</option>'; } } ?>
					</select>
				</div>
				<div class="col-auto">
					<label class="form-label"><?php echo lang('gen_hamradio_mode'); ?></label>
					<select name="mode" class="form-select form-select-sm">
						<option value="All">All</option>
						<?php if (!empty($modes)) { foreach ($modes->result() as $mode) { $val = $mode->submode ?: $mode->mode; echo '<option value="'.htmlspecialchars($val).'">'.htmlspecialchars($val).'</option>'; } } ?>
					</select>
				</div>
				<div class="col-auto">
					<label class="form-label">Association</label>
					<select name="association" class="form-select form-select-sm">
						<option value="">All</option>
						<?php if (!empty($associations)) { foreach ($associations as $assoc) { echo '<option value="'.htmlspecialchars($assoc).'">'.htmlspecialchars($assoc).'</option>'; } } ?>
					</select>
				</div>
				<div class="col-auto">
					<label class="form-label">Region</label>
					<select name="region" class="form-select form-select-sm">
						<option value="">All</option>
						<?php if (!empty($regions)) { foreach ($regions as $region) { echo '<option value="'.htmlspecialchars($region).'">'.htmlspecialchars($region).'</option>'; } } ?>
					</select>
				</div>
				<div class="col-auto form-check mt-4">
					<input class="form-check-input" type="checkbox" id="sotaConfirmed" name="confirmed" value="1">
					<label class="form-check-label" for="sotaConfirmed">Confirmed only</label>
				</div>
				<div class="col-auto mt-4">
					<button type="button" class="btn btn-sm btn-primary" onclick="htmx.trigger('#sotaStats','refresh'); htmx.trigger('#sotaTable','refresh'); htmx.trigger('#sotaMap','refresh');">Apply</button>
				</div>
			</form>
		</div>
	</div>

	<div id="sotaStats" class="mt-3" hx-get="<?php echo site_url('awards/sota_stats'); ?>" hx-trigger="load, refresh from:#sotaFilters" hx-include="#sotaFilters"></div>

	<div class="row g-2 mt-3" id="sotaTableSearch">
		<div class="col-md-3 col-12">
			<label class="form-label mb-1">Search SOTA Reference</label>
			<input type="text" id="sotaRefSearch" class="form-control form-control-sm" placeholder="e.g. GM/SS-001">
		</div>
		<div class="col-md-3 col-12">
			<label class="form-label mb-1">Search Callsign</label>
			<input type="text" id="sotaCallSearch" class="form-control form-control-sm" placeholder="Callsign">
		</div>
	</div>

	<div id="sotaTableButtons" class="mt-2" style="text-align: right;"></div>
	<style>
		#sotaTableButtons .dt-buttons {
			float: none !important;
			display: inline-flex;
			justify-content: flex-end;
			gap: 0.5rem;
		}
	</style>

	<div id="sotaTable" class="mt-3" hx-get="<?php echo site_url('awards/sota_table'); ?>" hx-trigger="load, refresh from:#sotaFilters" hx-include="#sotaFilters"></div>

	<div id="sotaMap" class="mt-4" hx-get="<?php echo site_url('awards/sota_map'); ?>" hx-trigger="load, refresh from:#sotaFilters" hx-include="#sotaFilters"></div>

	<script>
	(function(){
		function initSotaTable() {
			var $tbl = $('#sotatable');
			if (!$tbl.length || !$.fn.DataTable) return;
			if ($.fn.dataTable.isDataTable('#sotatable')) {
				$tbl.DataTable().destroy();
			}
			$.fn.dataTable.ext.buttons.clear = {
				className: 'buttons-clear',
				action: function(e, dt) {
					dt.search('');
					dt.columns([0,5]).search('');
					var refInput = document.getElementById('sotaRefSearch');
					var callInput = document.getElementById('sotaCallSearch');
					if (refInput) refInput.value = '';
					if (callInput) callInput.value = '';
					dt.draw();
				}
			};
			var api = $tbl.DataTable({
				pageLength: 25,
				ordering: true,
				scrollY: '500px',
				scrollCollapse: true,
				paging: false,
				scrollX: true,
				language: { url: getDataTablesLanguageUrl() },
				order: [[0, 'asc']],
				dom: 'Bfrtip',
				buttons: [
					{ extend: 'csv' },
					{ extend: 'clear', text: 'Clear' }
				]
			});

			if ($('#sotaTableButtons').length) {
				var btnContainer = api.buttons().container();
				$(btnContainer).css('float','none').css('display','inline-flex');
				$(btnContainer).appendTo('#sotaTableButtons');
			}

			var refInput = document.getElementById('sotaRefSearch');
			var callInput = document.getElementById('sotaCallSearch');
			if (refInput) {
				refInput.addEventListener('keyup', function(){ api.column(0).search(this.value).draw(); });
				refInput.addEventListener('change', function(){ api.column(0).search(this.value).draw(); });
			}
			if (callInput) {
				callInput.addEventListener('keyup', function(){ api.column(5).search(this.value).draw(); });
				callInput.addEventListener('change', function(){ api.column(5).search(this.value).draw(); });
			}

			if (typeof isDarkModeTheme === 'function' && isDarkModeTheme()) {
				$('[class*="buttons"]').css('color', 'white');
			}
		}

		document.addEventListener('htmx:afterSwap', function(e){
			if (e.target && e.target.id === 'sotaTable') {
				initSotaTable();
			}
		});

		document.addEventListener('DOMContentLoaded', initSotaTable);
	})();
	</script>
</div>
