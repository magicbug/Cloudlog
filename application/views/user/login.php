<!-- JS -->

	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ;?>/js/jquery.jclock.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ;?>/js/radiohelpers.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

	<script type="text/javascript">

		$(document).ready(function() {
			$(".qsobox").fancybox({
				'autoDimensions'	: false,
				'width'         	: 700,
				'height'        	: 300,
				'transitionIn'		: 'fade',
				'transitionOut'		: 'fade',
				'type'				: 'iframe'
			});

			$(function($) {
		      var options = {
		        utc: true
		      }
		      $('.input_time').jclock(options);
		    });
		});

	</script>

<div id="container">


<?php if($this->session->flashdata('notice') != '') { ?>
<div class="alert-message info">
        <?php echo $this->session->flashdata('notice'); ?>
</div>
<?php } ?>

<?php if($this->session->flashdata('error') != '') { ?>
<div class="alert-message error">
        <?php echo $this->session->flashdata('error'); ?>
</div>
<?php } ?>

<?php if(validation_errors()) { ?>
<div class="alert-message error">
        <?php echo validation_errors(); ?>
</div>
<?php } ?>

<h2>Log in</h2>


<form method="post" action="<?php echo site_url('user/login'); ?>" name="users">
<table>
	<tr>
		<td>Username</td>
		<td><input type="text" name="user_name" value="<?php echo $this->input->post('user_name'); ?>"></td>
	</tr>
	
	<tr>
		<td>Password</td>
		<td><input type="password" name="user_password" /></td>
	</tr>
	
</table>	
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div><input type="submit" value="Log in" /></div>

</form>

</div>
