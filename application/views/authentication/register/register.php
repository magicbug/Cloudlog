<div class="container">
	<div class="py-5 text-center">
		<h2>Create a Cloudlog Account</h2>
 
 		<p class="lead">Before you use Cloudlog, you must create an account and provide us with some information about your station location before you can add some QSOs.</p>
	</div>

	<?php if (!empty(validation_errors())): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" title="close">x</a>
        <ul><?php echo (validation_errors('<li>', '</li>')); ?></ul>
    </div>
	<?php endif; ?>

	<?php echo form_open('register'); ?>
	<div class="row">
		<div class="col-md-8 order-md-1">
			<h4 class="mb-3">Personal Information</h4>
			<div class="row">
				<div class="col-md-6 mb-3">
					<label for="firstName">First name</label>
					<input type="text" class="form-control" name="firstname" id="firstName" placeholder="" value="" required>
				</div>

				<div class="col-md-6 mb-3">
			    	<label for="lastName">Last name</label>
			    	<input type="text" class="form-control" name="lastname" id="lastName" placeholder="" value="" required="">
				</div>
			</div>

			<div class="mb-3">
			    <label for="email">Email</label>
			    <input type="email" class="form-control" name="email" id="email" placeholder="you@example.com" required>
			</div>

			<div class="mb-3">
			<label for="username">Username</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">@</span>
					</div>
					<input type="text" class="form-control" name="username" id="username" placeholder="Username" required="">
				</div>
			</div>

			<div class="mb-3">
			    <label for="password">Password</label>
			    <input type="password" class="form-control" name="password" id="password" required>
			</div>

			<h4 class="mb-3">Station Information</h4>

			<div class="row">
				<div class="col-md-6 mb-3">
					<label for="callsign">Callsign</label>
					<input type="text" class="form-control" name="callsign" id="Callsign" placeholder="" value="" required>
				</div>

				<div class="col-md-6 mb-3">
			    	<label for="gridsquare">Gridsquare</label>
			    	<input type="text" class="form-control" name="gridsquare" id="gridsquare" placeholder="" value="" required>
				</div>
			</div>

				<div class="mb-3">
				    <label for="dxcc_name">Country (DXCC)</label>
				    <?php if ($dxcc_list->num_rows() > 0) { ?>
					<select class="form-control" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
					<?php foreach ($dxcc_list->result() as $dxcc) { ?>
					<option value="<?php echo $dxcc->adif; ?>"><?php echo $dxcc->name; ?></option>
					<?php } ?>
					</select>
					<?php } ?>
					<input type="hidden" id="country" name="station_country" value="" required />
				</div>

			<div class="row">
				<div class="col-md-6 mb-3">
					<label for="cq_zone">CQ Zone</label>
					<input type="text" name="cq_zone" class="form-control" id="cq_zone" placeholder="" value="">
				</div>

				<div class="col-md-6 mb-3">
			    	<label for="itu_zone">ITU Zone</label>
			    	<input type="text" class="form-control" name="itu_zone" id="itu_zone" placeholder="" value="" required="">
				</div>
			</div>

			<hr class="mb-4">

			<button class="btn btn-primary btn-lg btn-block" type="submit">Create an account</button>
		</div>

		</form>
	</div>
</div>

<footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; <?php echo date("Y");?> Cloudlog</p>
    <ul class="list-inline">
      <li class="list-inline-item"><a href="<?php echo site_url('pages/privacy'); ?>">Privacy</a></li>
      <li class="list-inline-item"><a href="<?php echo site_url('pages/termsandconditions'); ?>">Terms</a></li>
      <li class="list-inline-item"><a href="<?php echo site_url('pages/support'); ?>">Support</a></li>
    </ul>
</footer>