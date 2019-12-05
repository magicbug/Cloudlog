<div class="container">
	<div class="py-5 text-center">
		<img class="d-block mx-auto mb-4" src="/docs/4.4/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">

		<h2>Create a Cloudlog Account</h2>
 
 		<p class="lead">Before you use Cloudlog, you must create an account and provide us with some information about your station location before you can add some QSOs. This is completely free.</p>
	</div>

	<div class="row">
		<div class="col-md-8 order-md-1">
			<h4 class="mb-3">Personal Information</h4>
			<div class="row">
				<div class="col-md-6 mb-3">
					<label for="firstName">First name</label>
					<input type="text" class="form-control" id="firstName" placeholder="" value="">
				</div>

				<div class="col-md-6 mb-3">
			    	<label for="lastName">Last name</label>
			    	<input type="text" class="form-control" id="lastName" placeholder="" value="" required="">
				</div>
			</div>

			<div class="mb-3">
			    <label for="email">Email</label>
			    <input type="email" class="form-control" id="email" placeholder="you@example.com" required>
			</div>

			<div class="mb-3">
			<label for="username">Username</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">@</span>
					</div>
					<input type="text" class="form-control" id="username" placeholder="Username" required="">
				</div>
			</div>

			<div class="mb-3">
			    <label for="password">Password</label>
			    <input type="password" class="form-control" id="password" required>
			</div>

			<h4 class="mb-3">Station Information</h4>

			<div class="row">
				<div class="col-md-6 mb-3">
					<label for="callsign">Callsign</label>
					<input type="text" class="form-control" id="Callsign" placeholder="" value="">
				</div>

				<div class="col-md-6 mb-3">
			    	<label for="gridsquare">Gridsquare</label>
			    	<input type="text" class="form-control" id="gridsquare" placeholder="" value="" required="">
				</div>
			</div>

			<hr class="mb-4">

			<button class="btn btn-primary btn-lg btn-block" type="submit">Create an account</button>
		</div>
	</div>
</div>