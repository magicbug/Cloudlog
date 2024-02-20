<div class="container">

    <h2><?php echo $page_title; ?></h2>
    <div id="dxcclist_display" hx-get="<?php echo site_url('workabledxcc/dxcclist'); ?>" hx-trigger="load"></div>
</div>
