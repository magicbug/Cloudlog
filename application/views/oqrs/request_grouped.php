<br />
<?php if ($result) { ?>
The following QSO(s) were found. Please fill out the date and time and submit your request.
<table style="width:100%" class="result-table table-sm table table-bordered table-hover table-striped table-condensed text-center">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Time (UTC)</th>
            <th class="center"><span class="larger_font band">Band</th>
            <th class="center">Mode</th>
            <th class="center">Callsign</th>
            <th class="center">Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
            foreach ($result as $qso) {
                echo '<tr stationid="'. $qso->station_id.'">';
                    echo '<td>'. $i++ .'</td>';
                    echo '<td><input class="form-control" type="date" name="date" value="" id="date" placeholder="YYYY-MM-DD"></td>';
                    echo '<td><input class="form-control qsotime" type="text" name="time" value="" id="time" maxlength="5" placeholder="HH:MM"></td>';
                    echo '<td id="band">'. $qso->col_band .'</td>';
                    echo '<td id="mode">'; echo $qso->col_submode == null ? strtoupper($qso->col_mode) : strtoupper($qso->col_submode);  echo '</td>';      
                    echo '<td>'. $qso->station_callsign .'</td>';
                    echo '<td>'. $qso->station_profile_name .'</td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>
<br />

<form>
    <div class="form-check form-check-inline">
        <label class="form-check-label">QSL Route</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="qslroute" id="bureau" value="B" checked/>
        <label class="form-check-label" for="bureau">Bureau</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="qslroute" id="direct" value="D" />
        <label class="form-check-label" for="direct">Direct (write address in message below)</label>
    </div>
    <br /><br />
    <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" class="form-control" id="messageInput" rows="3" aria-describedby="messageHelp"></textarea>
        <small id="messageHelp" class="form-text text-muted">Any extra information we need to know about?</small>
    </div>

    <div class="form-group">
        <label for="emailInput">E-mail</label>
        <input type="text" class="form-control" name="mode" id="emailInput" aria-describedby="emailInputHelp" required>
        <small id="emailInputHelp" class="form-text text-muted">Your e-mail address where we can contact you</small>
    </div>

    <button type="button" id="requestGroupedSubmit" onclick="submitOqrsRequestGrouped(this.form);" class="btn btn-sm btn-primary"><i
            class="fas fa-plus-square"></i> Submit request</button>
</form>
<?php } else {
	echo 'No QSOs found in the log.<br />';
}
	?>