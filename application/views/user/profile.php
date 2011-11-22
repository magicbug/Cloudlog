	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

	<script type="text/javascript">

		$(document).ready(function() {
			$(".qsobox").fancybox({
				'width'				: '75%',
				'height'			: '50%',
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});


		});

	</script>

<div id="container">

<div class="wrap_content profile">

<h2><?php echo $this->session->userdata('user_name')."'s profile"; ?></h2>
<table class="profile">
	<tr>
		<td width="100px">Username</td>
		<td><?php if(isset($user_name)) { echo $user_name; } ?></td>
	</tr>
	
	<tr>
		<td>Level</td>
		<td><?php $l = $this->config->item('auth_level'); echo $l[$user_type]; ?></td>
	</tr>

	<tr>
		<td>E-mail</td>
		<td><?php if(isset($user_email)) { echo $user_email; } ?></td>
	</tr>
	
	<tr>
		<td>Callsign</td>
		<td><?php if(isset($user_callsign)) { echo $user_callsign; } ?></td>
	</tr>
	
	<tr>
		<td>Locator</td>
		<td><?php if(isset($user_locator)) { echo $user_locator; } ?></td>
	</tr>
	
	<tr>
		<td>First name</td>
		<td><?php if(isset($user_firstname)) { echo $user_firstname; } ?></td>
	</tr>
	
	<tr>
		<td>Last name</td>
		<td><?php if(isset($user_lastname)) { echo $user_lastname; } ?></td>
	</tr>
	
</table>	
<div><a class="btn primary" href="<?php echo site_url('user/edit')."/".$this->session->userdata('user_id'); ?>">Edit profile</a></div>

</form>

</div>
