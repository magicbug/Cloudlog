<div class="container">

    <h2><?php echo $page_title; ?></h2>
    <div id="dxcclist_display" hx-get="<?php echo site_url('workabledxcc/dxcclist'); ?>" hx-trigger="load">
        <!-- Loading spinner and message shown while HTMX loads content -->
        <div class="d-flex justify-content-center align-items-center py-5">
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="text-muted">Generating Table</h5>
                <p class="text-muted">Processing DXPedition data and checking your logbook...</p>
            </div>
        </div>
    </div>
</div>
