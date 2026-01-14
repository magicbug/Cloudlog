<div class="container">
	<br>
	<div id="awardInfoButton">
		<script>
			var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
			var lang_award_info_ln1 = "<?php echo lang('awards_pota_description_ln1'); ?>";
			var lang_award_info_ln2 = "<?php echo lang('awards_pota_description_ln2'); ?>";
			var lang_award_info_ln3 = "<?php echo lang('awards_pota_description_ln3'); ?>";
			var lang_award_info_ln4 = "<?php echo lang('awards_pota_description_ln4'); ?>";
		</script>
		<h2><?php echo $page_title; ?></h2>
		<button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
	</div>

	<!-- Filters -->
	<div class="card mt-3">
		<div class="card-body">
			<form id="potaFilters" class="row gy-2 gx-3 align-items-end">
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
						<?php if (!empty($worked_bands)) { foreach ($worked_bands as $b) { echo '<option value="'.htmlspecialchars($b).'">'.htmlspecialchars($b).'</option>'; } } ?>
					</select>
				</div>
				<div class="col-auto">
					<label class="form-label"><?php echo lang('gen_hamradio_mode'); ?></label>
					<select name="mode" class="form-select form-select-sm">
						<option value="All">All</option>
						<?php if (!empty($modes)) { foreach ($modes as $m) { echo '<option value="'.htmlspecialchars($m->mode).'">'.htmlspecialchars($m->mode).'</option>'; } } ?>
					</select>
				</div>
				<div class="col-auto form-check mt-4">
					<input class="form-check-input" type="checkbox" id="confirmedOnly" name="confirmed" value="1">
					<label class="form-check-label" for="confirmedOnly">Confirmed only</label>
				</div>
				<div class="col-auto mt-4">
					<button type="button" class="btn btn-sm btn-primary" onclick="htmx.trigger('#potaTable','refresh'); htmx.trigger('#potaStats','refresh'); htmx.trigger('#potaProgress','refresh'); htmx.trigger('#potaMap','refresh');">Apply</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Stats -->
	<div id="potaStats" class="mt-3" hx-get="<?php echo site_url('awards/pota_stats'); ?>" hx-trigger="load, refresh from:#potaFilters" hx-include="#potaFilters"></div>

	<!-- Progress -->
	<div id="potaProgress" class="mt-2" hx-get="<?php echo site_url('awards/pota_progress'); ?>" hx-trigger="load, refresh from:#potaFilters" hx-include="#potaFilters"></div>

	<!-- Table search controls -->
	<div class="row g-2 mt-3" id="potaTableSearch">
		<div class="col-md-3 col-12">
			<label class="form-label mb-1">Search POTA Reference</label>
			<input type="text" id="potaRefSearch" class="form-control form-control-sm" placeholder="Search POTA Reference">
		</div>
		<div class="col-md-3 col-12">
			<label class="form-label mb-1">Search Callsign</label>
			<input type="text" id="potaCallSearch" class="form-control form-control-sm" placeholder="Search Callsign">
		</div>
	</div>

	<div id="potaTableButtons" class="mt-2" style="text-align: right;"></div>
	<style>
		#potaTableButtons .dt-buttons {
			float: none !important;
			display: inline-flex;
			justify-content: flex-end;
			gap: 0.5rem;
		}
	</style>

	<!-- Table -->
	<div id="potaTable" class="mt-3" hx-get="<?php echo site_url('awards/pota_table'); ?>" hx-trigger="load, refresh from:#potaFilters" hx-include="#potaFilters"></div>

	<!-- Map -->
	<div id="potaMap" class="mt-4" hx-get="<?php echo site_url('awards/pota_map'); ?>" hx-trigger="load, refresh from:#potaFilters" hx-include="#potaFilters"></div>
	<script>
	(function(){
		function initPotaTable() {
			var $tbl = $('#potatable');
			if (!$tbl.length || !$.fn.DataTable) return;
			if ($.fn.dataTable.isDataTable('#potatable')) {
				$tbl.DataTable().destroy();
			}
			$.fn.dataTable.ext.buttons.clear = {
				className: 'buttons-clear',
				action: function(e, dt, node, config) {
					// Reset global and column searches plus external inputs
					dt.search('');
					dt.columns([0,3]).search('');
					var refInput = document.getElementById('potaRefSearch');
					var callInput = document.getElementById('potaCallSearch');
					if (refInput) refInput.value = '';
					if (callInput) callInput.value = '';
					dt.draw();
				}
			};
			var api = $tbl.DataTable({
				pageLength: 25,
				responsive: false,
				ordering: true,
				scrollY: '500px',
				scrollCollapse: true,
				paging: false,
				scrollX: true,
				language: { url: getDataTablesLanguageUrl() },
				order: [0, 'asc'],
				dom: 'Bfrtip',
				buttons: [
					{ extend: 'csv' },
					{ extend: 'clear', text: 'Clear' }
				]
			});
			// Move buttons to right-aligned container
			if ($('#potaTableButtons').length) {
				var btnContainer = api.buttons().container();
				$(btnContainer).css('float','none').css('display','inline-flex');
				$(btnContainer).appendTo('#potaTableButtons');
			}
			// Column search for Ref and Callsign via external inputs
			var refInput = document.getElementById('potaRefSearch');
			var callInput = document.getElementById('potaCallSearch');
			if (refInput) {
				refInput.addEventListener('keyup', function(){ api.column(0).search(this.value).draw(); });
				refInput.addEventListener('change', function(){ api.column(0).search(this.value).draw(); });
			}
			if (callInput) {
				callInput.addEventListener('keyup', function(){ api.column(3).search(this.value).draw(); });
				callInput.addEventListener('change', function(){ api.column(3).search(this.value).draw(); });
			}
			if (typeof isDarkModeTheme === 'function' && isDarkModeTheme()) {
				$('[class*="buttons"]').css('color', 'white');
			}
		}

		document.addEventListener('htmx:afterSwap', function(e){
			if (e.target && e.target.id === 'potaTable') {
				initPotaTable();
			}
		});

		document.addEventListener('DOMContentLoaded', initPotaTable);
	})();
	</script>
</div>
