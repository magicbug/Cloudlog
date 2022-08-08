<div class="container-fluid qso_manager pt-3 pl-4 pr-4">
	<?php if ($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<h2>QSL Manager</h2>

	<form id="searchForm" name="searchForm" action="<?php echo base_url()."qslmanager/search";?>" method="post">
		<div class="form-row">
			<div class="form-group col-1">
				<label for="dateFrom">From</label>
				<div class="input-group date" id="dateFrom" data-target-input="nearest">
					<input name="dateFrom" type="text" placeholder="<?php echo $datePlaceholder;?>" class="form-control" data-target="#dateFrom"/>
					<div class="input-group-append"  data-target="#dateFrom" data-toggle="datetimepicker">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
			</div>
			<div class="form-group col-1">
				<label for="dateTo">To</label>
				<div class="input-group date" id="dateTo" data-target-input="nearest">
					<input name="dateTo" type="text" placeholder="<?php echo $datePlaceholder;?>" class="form-control" data-target="#dateTo"/>
					<div class="input-group-append"  data-target="#dateTo" data-toggle="datetimepicker">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
			</div>
			<div class="form-group col-1">
				<label for="de">De</label>
				<select id="de" name="de" class="form-control">
					<option value="">All</option>
					<?php
					foreach($deOptions as $deOption){
						?><option value="<?php echo htmlentities($deOption);?>"><?php echo htmlspecialchars($deOption);?></option><?php
					}
					?>
				</select>
			</div>
			<div class="form-group col-1">
				<label for="dx">Dx</label>
				<input type="text" name="dx" id="dx" class="form-control" value="">
			</div>
			<div class="form-group col-1">
				<label for="mode">Mode</label>
				<select id="mode" name="mode" class="form-control">
					<option value="">All</option>
					<?php
					foreach($modes as $modeId => $mode){
						?><option value="<?php echo htmlentities($modeId);?>"><?php echo htmlspecialchars($mode);?></option><?php
					}
					?>
				</select>
			</div>
			<div class="form-group col-1">
				<label for="band">Band</label>
				<select id="band" name="band" class="form-control">
					<option value="">All</option>
					<?php
					foreach($bands as $band){
						?><option value="<?php echo htmlentities($band);?>"><?php echo htmlspecialchars($band);?></option><?php
					}
					?>
				</select>
			</div>
			<div class="form-group col-1">
				<label for="qslSent">QSL Sent</label>
				<select id="qslSent" name="qslSent" class="form-control">
					<option value="">All</option>
					<option value="Y">Yes</option>
					<option value="N">No</option>
					<option value="R">Requested</option>
					<option value="Q">Queued</option>
					<option value="I">Ignore/Invalid</option>
				</select>
			</div>
			<div class="form-group col-1">
				<label for="qslReceived">QSL Received</label>
				<select id="qslReceived" name="qslReceived" class="form-control">
					<option value="">All</option>
					<option value="Y">Yes</option>
					<option value="N">No</option>
					<option value="R">Requested</option>
					<option value="I">Ignore/Invalid</option>
					<option value="V">Verified</option>
				</select>
			</div>
			<div class="form-group col-2">
				<label>&nbsp;</label><br>
				<button type="submit" class="btn btn-primary" id="searchButton">Search</button>
				<button type="reset" class="btn btn-danger" id="resetButton">Reset</button>
			</div>
		</div>
	</form>
	<hr class="mt-0 mb-3">
	<div class="mb-2">
		<span class="h6">With selected :</span>
		<button type="button" class="btn btn-sm btn-primary" id="btnUpdateFromCallbook">Update from Callbook</button>
		<button type="button" class="btn btn-sm btn-primary">Copy refs to QSL Msg</button>
		<button type="button" class="btn btn-sm btn-primary">Queue Bureau</button>
		<button type="button" class="btn btn-sm btn-primary">Queue Direct</button>
		<button type="button" class="btn btn-sm btn-success">Sent Bureau</button>
		<button type="button" class="btn btn-sm btn-success">Sent Direct</button>
		<button type="button" class="btn btn-sm btn-warning">Don't Send</button>
		<button type="button" class="btn btn-sm btn-info">Create ADIF</button>
		<span id="infoBox"></span>
	</div>
	<table class="table" id="qsoList">
		<thead>
			<tr>
				<th><div class="form-check" style="margin-top: -1.5em"><input class="form-check-input" type="checkbox" id="checkBoxAll" /></div></th>
				<th>Date/Time</th>
				<th>De</th>
				<th>Dx</th>
				<th>Mode</th>
				<th>RST (S)</th>
				<th>RST (R)</th>
				<th>Band</th>
				<th>My Refs</th>
				<th>Refs</th>
				<th>Name</th>
				<th>QSL Via</th>
				<th>QSL Sent</th>
				<th>QSL Received</th>
				<th>QSL Msg</th>
			</tr>
		</thead>
		<tbody>
			<tr class="model d-none">
				<td>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" />
					</div>
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php /*
			<tr>
				<td>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
						<label class="form-check-label d-none" for="defaultCheck1">Select QSO ID 999</label>
					</div>
				</td>
				<td>2022-01-02 03:04</td>
				<td>CT7AOV/P</td>
				<td>DF2ET/P</td>
				<td>SSB</td>
				<td>59</td>
				<td>59</td>
				<td>20m</td>
				<td>CT/ES-004</td>
				<td>DLFF-1234</td>
				<td>Buro, Direct, LOTW</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
						<label class="form-check-label d-none" for="defaultCheck1">Select QSO ID 999</label>
					</div>
				</td>
				<td>2022-01-02 03:04</td>
				<td>CT7AOV/P</td>
				<td>DF2ET/P</td>
				<td>SSB</td>
				<td>59</td>
				<td>59</td>
				<td>20m</td>
				<td>CT/HCE-123</td>
				<td>DLFF-1234</td>
				<td>Buro, Direct, LOTW</td>
				<td>Queued Direct</td>
				<td></td>
				<td>CT/HCE-123</td>
			</tr>
			<tr>
				<td>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
						<label class="form-check-label d-none" for="defaultCheck1">Select QSO ID 999</label>
					</div>
				</td>
				<td>2022-01-02 03:04</td>
				<td>DL/CT7AOV/P</td>
				<td>DF2ET/P</td>
				<td>SSB</td>
				<td>59</td>
				<td>59</td>
				<td>20m</td>
				<td>DLFF-1234</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
 			*/ ?>
		</tbody>
	</table>


</div>
