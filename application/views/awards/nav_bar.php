
	<ul class="tabs">
	  <li id="tab_dxcc">
	    <a href="<?php echo site_url('awards/dxcc'); ?>">DXCC</a>
	  </li>
	  <li id="tab_wab">
	    <a href="<?php echo site_url('awards/wab'); ?>">WAB</a>
	  </li>
	  <li id="tab_sota">
	    <a href="<?php echo site_url('awards/sota'); ?>">SOTA</a>
	  </li>
	  <li id="tab_wacral">
	    <a href="<?php echo site_url('awards/wacral'); ?>">WACRAL</a>
	  </li>
	  <li id="tab_cq">
	    <a href="<?php echo site_url('awards/cq'); ?>">CQ</a>
	  </li>
	</ul>

    <script>
        var tab = "<?php echo $this->router->fetch_method(); ?>";
        $('#tab_'+tab).addClass('active');
    </script>
