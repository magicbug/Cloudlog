<h2>Add QSO</h2>

<div class="wrap_content">
<table>
	<tr>
		<td>Date</td>
		<td><?php echo date('d/m/Y'); ?></td>
	</tr>
	
	<tr>
		<td>Start Time</td>
		<td></td>
	</tr>
	
	<tr>
		<td>Callsign</td>
		<td><input type="text" name="callsign" value="" /></td>
	</tr>
	
	<tr>
		<td>Band</td>
		<td><select name="band">
			<option value="160m">160m</option>
			<option value="80m">80m</option>
			<option value="40m">40m</option>
			<option value="30m">30m</option>
			<option value="20m">20m</option>
			<option value="17m">17m</option>
			<option value="15m">15m</option>
			<option value="12m">12m</option>
			<option value="10m">10m</option>
			<option value="6m">6m</option>
			<option value="4m">4m</option>
			<option value="2m">2m</option>
			<option value="70cm">70cm</option>
		</select></td>
	</tr>
	
	<tr>
		<td>RST Sent</td>
		<td><select name="rst_sent">
			<option value="51">51</option>
			<option value="52">52</option>
			<option value="53">53</option>
			<option value="54">54</option>
			<option value="55">55</option>
			<option value="56">56</option>
			<option value="57">57</option>
			<option value="58">58</option>
			<option value="59" selected="selected">59</option>
			<option value="59+10dB">59+10dB</option>
			<option value="59+20dB">59+20dB</option>
			<option value="59+30dB">59+30dB</option>
		</select></td>
	</tr>
	
	<tr>
		<td>RST Recv</td>
		<td><select name="rst_recv">
			<option value="51">51</option>
			<option value="52">52</option>
			<option value="53">53</option>
			<option value="54">54</option>
			<option value="55">55</option>
			<option value="56">56</option>
			<option value="57">57</option>
			<option value="58">58</option>
			<option value="59" selected="selected">59</option>
			<option value="59+10dB">59+10dB</option>
			<option value="59+20dB">59+20dB</option>
			<option value="59+30dB">59+30dB</option>
		</select></td>
	</tr>
	
	<tr>
		<td>Comment</td>
		<td><input type="text" name="comment" value="" /></td>
	</tr>
</table>
</div>