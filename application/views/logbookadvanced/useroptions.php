<table style="width:100%" class="table-sm table table-hover table-striped table-condensed text-left" id="useroptions">
	<thead>
		<tr>
			<th class="text-left">Column</th>
			<th>Show</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Date/Time</td>
			<td><div class="form-check"><input class="form-check-input" name="datetime" type="checkbox" <?php if (($options->datetime->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>De</td>
			<td><div class="form-check"><input class="form-check-input" name="de" type="checkbox" <?php if (($options->de->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>Dx</td>
			<td><div class="form-check"><input class="form-check-input" name="dx" type="checkbox" <?php if (($options->dx->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>Mode</td>
			<td><div class="form-check"><input class="form-check-input" name="mode" type="checkbox" <?php if (($options->mode->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>RST (S)</td>
			<td><div class="form-check"><input class="form-check-input" name="rsts" type="checkbox" <?php if (($options->rsts->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>RST (R)</td>
			<td><div class="form-check"><input class="form-check-input" name="rstr" type="checkbox" <?php if (($options->rstr->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>Band</td>
			<td><div class="form-check"><input class="form-check-input" name="band" type="checkbox" <?php if (($options->band->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>My Refs</td>
			<td><div class="form-check"><input class="form-check-input" name="myrefs" type="checkbox" <?php if (($options->myrefs->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>Refs</td>
			<td><div class="form-check"><input class="form-check-input" name="refs" type="checkbox" <?php if (($options->refs->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>Name</td>
			<td><div class="form-check"><input class="form-check-input" name="name" type="checkbox" <?php if (($options->name->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>QSL Via</td>
			<td><div class="form-check"><input class="form-check-input" name="qslvia" type="checkbox" <?php if (($options->qslvia->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>QSL</td>
			<td><div class="form-check"><input class="form-check-input" name="qsl" type="checkbox" <?php if (($options->qsl->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>LoTW</td>
			<td><div class="form-check"><input class="form-check-input" name="lotw" type="checkbox" <?php if (($options->lotw->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>eQSL</td>
			<td><div class="form-check"><input class="form-check-input" name="eqsl" type="checkbox" <?php if (($options->eqsl->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>QSL Msg</td>
			<td><div class="form-check"><input class="form-check-input" name="qslmsg" type="checkbox" <?php if (($options->qslmsg->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>DXCC</td>
			<td><div class="form-check"><input class="form-check-input" name="dxcc" type="checkbox" <?php if (($options->dxcc->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>State</td>
			<td><div class="form-check"><input class="form-check-input" name="state" type="checkbox" <?php if (($options->state->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>CQ Zone</td>
			<td><div class="form-check"><input class="form-check-input" name="cqzone" type="checkbox" <?php if (($options->cqzone->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
		<tr>
			<td>IOTA</td>
			<td><div class="form-check"><input class="form-check-input" name="iota" type="checkbox" <?php if (($options->iota->show ?? "true") == "true") { echo 'checked'; } ?>></div></td>
		</tr>
	</tbody>
</table>
