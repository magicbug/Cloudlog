<div class="container">
         
        <br>
        <div id="simpleFleInfo">
            <script>
            var lang_simplefle_info_button = "<?php echo lang('simplefle_info_button'); ?>";
            var lang_simplefle_info_ln1 = "<?php echo lang('simplefle_info_ln1'); ?>";
            var lang_simplefle_info_ln2 = "<?php echo lang('simplefle_info_ln2'); ?>";
            var lang_simplefle_info_ln3 = "<?php echo lang('simplefle_info_ln3'); ?>";
            var lang_simplefle_info_ln4 = "<?php echo lang('simplefle_info_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="simpleFleInfo"><?php echo lang('simplefle_info'); ?></button>
        </div> 

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>
</div>
<div class="container-fluid">
		<header
			class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
			<div class="col-md-3 mb-2 mb-md-0">
				
			</div>

			<div class="col-md-3 justify-content-end d-flex">
				
             
			
            </div>
		</header>

		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="qso" role="tabpanel" aria-labelledby="qso-tab">
				<div class="row mt-4">
					<div class="col-xs-12 col-md-4">
						<div class="row">
							<div class="col-xs-12 col-lg-12 col-xl-6">
								<div class="form-group">
									<label for="qsodate">QSO date</label>
									<input type="date" class="form-control" id="qsodate">
								</div>
							</div>
							<div class="col-xs-12 col-lg-12 col-xl-6">
								<div class="form-group">
									<label for="wwff">WWFF/SOTA <span class="text-muted input-example">e.g.
											OKFF-2068</span></label>
									<input type="text" class="form-control text-uppercase" id="my-sota-wwff" autofocus>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-lg-6">
								<div class="form-group">
									<label for="my-call">My call <span class="text-muted input-example">e.g.
											OK2CQR/P</span></label>
									<input type="text" class="form-control text-uppercase" id="my-call">
								</div>
							</div>
							<div class="col-xs-12 col-lg-6">
								<div class="form-group">
									<label for="operator">Operator <span class="text-muted input-example">e.g.
											OK2CQR</span></label>
									<input type="text" class="form-control text-uppercase" id="operator">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<p>Enter the data</p>
								<textarea name="qso" class="form-control qso-area" cols="auto" rows="11"></textarea>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-8">
						QSO list
						<div class="qsoList">
							<table class="table table-condensed table-striped table-sm" id="qsoTable">
								<thead>
									<tr>
										<th>Date</th>
										<th>Time</th>
										<th>Callsign</th>
										<th>Band</th>
										<th>Mode</th>
										<th>RS</th>
										<th>RR</th>
										<th>Op.</th>
										<th>SOTA/WFF</th>
									</tr>
								</thead>
								<tbody id="qsoTableBody">

								</tbody>
							</table>
						</div>
						<span class="js-qso-count"></span>
						<div class="row mt-2">
							<div class="col-3 col-sm-3">
								<button class="btn btn-primary js-reload-qso">Reload QSO list</button>
							</div>
							<div class="col-3 col-sm-3">
								<button class="btn btn-warning js-download-adif">Download ADIF</button>
							</div>
							<div class="col-3 col-sm-3">
								<button class="btn btn-danger js-empty-qso">Clear logging session</button>
							</div>
							<div class="col-3 col-sm-3">
								<button class="btn btn-secondary js-load-sample-log">Load sample log</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col">
						Status:
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p class="js-status"></p>
					</div>
				</div>
			</div>
			<div class="tab-pane fade show" id="settings" role="tabpanel" aria-labelledby="settings-tab">
				<div class="row mt-4">
					<div class="col">
						General
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-lg-6">
						<div class="row">
							<div class="col-3 mt-4">
								<strong>QSO</strong>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="my-power">Power (W)</label>
									<input type="text" class="form-control text-uppercase" id="my-power" value="">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="my-grid">My grid</label>
									<input type="text" class="form-control text-uppercase" id="my-grid" value="">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col">
						Bands
					</div>
				</div>
				<div class="row">
					<div class="col col-lg-6">
						<div class="js-band-settings mt-4 mb-5">
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">
				<div class="row mt-4">
					<div class="col">
						<p>
							Simple fast log entry written in HTML/Javascript by <a href="https://www.ok2cqr.com">Petr,
								OK2CQR</a>.
							Heavily inspired by <a href="https://df3cb.com/fle/">FLE</a> from <a
								href="https://df3cb.com/">Bernd, DF3CB</a>.
							Unfortunately, the FLE works only on Windows and Linux using Wine, but I needed something
							working on macOS and/or
							Android tablet. I&nbsp;didn't need all the features, just wanted to log QSO from my
							WFF/SOTA/GMA activation.
						</p>

						<p>
							The SFLE (Simple fast log entry) is a tool created by GDD (Google Driven Development) using
							Bootstrap 4 and jQuery (I'm a backend developer). Data is stored only in your browser.
							The website does not collect any data about you.
						</p>

						<p>
							If you find any bug or have a suggestion on how to improve the website, please let me know
							at <a href="mailto:petr@ok2cqr.com">petr@ok2cqr.com</a>.
							I&nbsp;get many emails every day, if you don't get a reply in a few days, don't hesitate to
							send your email again.
						</p>
						<p>
							Source code is available on <a href="https://github.com/ok2cqr/sfle">GitHub</a>.
						</p>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col">
						<strong>Changelog:</strong>
					</div>
				</div>
				<div class="row">
					<div class="row">
						<div class="col">
							<p>
								<strong>2023-09-24</strong>
							<ul class="mt-1">
								<li><span class="text-danger text-monospace">fix:</span> 70CM band was not recognized</li>
							</ul>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<p>
								<strong>2023-08-05</strong>
								<ul class="mt-1">
									<li><span class="text-primary text-monospace">new:</span> when you click the 'Load sample log' button, a question dialog will appear, asking if you want to replace the existing data</li>
									<li><span class="text-primary text-monospace">new:</span> after attempting to download the ADIF file, if no band and/or mode is defined, a dialog window will appear displaying an error message</li>
									<li><span class="text-danger text-monospace">fix:</span> getting freq from band and mode</li>
								</ul>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<p>
								<strong>2023-04-25</strong>
								<ul class="mt-1">
									<li><span class="text-primary text-monospace">new:</span> showing frequency after hovering the band column table</li>
									<li><span class="text-primary text-monospace">new:</span> the website's look and feel have been significantly improved, thanks to the pull request submitted by <a href="http://kounovsky.eu">Aleš Kounovský</a>, who did an outstanding job</li>
								</ul>
							</p>
						</div>
					</div>
					<div class="col">
						<p>
							<strong>2022-07-23</strong>
						<ul class="mt-1">
							<li><span class="text-primary text-monospace">new:</span> Power and My grid fields added to
								Settings values will be in the exported ADIF file if filled in.</li>
						</ul>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p>
							<strong>2022-02-16</strong>
						<ul class="mt-1">
							<li><span class="text-primary text-monospace">new:</span> Custom report support - e.g. 5 7
								means 559 as RST sent and 579 as RST received on CW or 55/57 on SSB, click to <span
									class="text-secondary">Load sample log</span> button to see real examples</li>
						</ul>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p>
							<strong>2022-02-03</strong>
						<ul class="mt-1">
							<li><span class="text-primary text-monospace">new:</span> Showing QSO count below the table
							</li>
						</ul>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p>
							<strong>2022-01-29</strong>
						<ul class="mt-1">
							<li><span class="text-primary text-monospace">new:</span> dark mode (thanks to Andreas,
								LA8AJA)</li>
							<li><span class="text-primary text-monospace">new:</span> saving data to local storage
								(thanks to Andreas, LA8AJA)</li>
							<li><span class="text-primary text-monospace">new:</span> grid scrolls to last line after
								adding a new qso (thanks to Andreas, LA8AJA)</li>
							<li><span class="text-primary text-monospace">new:</span> website should be responsive</li>
							<li><span class="text-danger text-monospace">fix:</span> some design fixes (thanks to
								Andreas, LA8AJA)</li>
							<li><span class="text-danger text-monospace">fix:</span> qso time loading</li>
						</ul>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p>
							<strong>2021-12-04</strong>
						<ul class="mt-1">
							<li><span class="text-primary text-monospace">new:</span> area with QSO data has monospace
								font</li>
							<li><span class="text-danger text-monospace">fix:</span> callsign with slash was not
								recognized</li>
							<li><span class="text-danger text-monospace">fix:</span> loading qso date from date field
							</li>
						</ul>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>