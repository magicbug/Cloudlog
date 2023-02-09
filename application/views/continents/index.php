<style>
	#continentChart{
		margin: 0 auto;
	}
</style>
<div class="container statistics">

	<h2>
		<?php echo $page_title; ?>
	</h2>

	<br>
	<div hidden class="tabs">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="continents-tab" data-toggle="tab" href="#continents" role="tab" aria-controls="continents" aria-selected="true">No of QSOs</a>
			</li>
		</ul>
	</div>

		<div class="tab-content" id="myTabContent">

			<div class="tab-pane fade active show" id="continents" role="tabpanel" aria-labelledby="continents-tab">
				<br/>	
				<div style="display: flex;" id="continentContainer"><div style="flex: 1;"><canvas id="continentChart" width="500" height="500"></canvas></div><div style="flex: 1;" id="continentTable">
				
				<table style="width:100%" class="continentstable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>
					<tr>
					<td>#</td>
					<td>Continent</td>
					<td># of QSO's worked</td>
					</tr>
					</thead>
					<tbody></tbody>
				</table></div></div>
			</div>
		</div>
</div>
