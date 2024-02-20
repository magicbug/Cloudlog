<div class="modal fade" id="versionDialogModal" tabindex="-1" aria-labelledby="versionDialogLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="versionDialogLabel"><?php echo $this->optionslib->get_option('version_dialog_header'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <script src="<?php echo base_url('assets/js/showdown.min.js'); ?>"></script>

                <script>
                    function convertMarkdownToHTML() {
                        // Get the Markdown content from the div
                        var markdownContent = document.getElementById('markdownDiv').innerText;

                        // Create a new Showdown Converter with simplifiedAutoLink option enabled
                        var converter = new showdown.Converter({
                            simplifiedAutoLink: true
                        });

                        // Convert Markdown to HTML
                        var html = converter.makeHtml(markdownContent);

                        // Set the HTML content of the div
                        document.getElementById('formattedHTMLDiv').innerHTML = html;
                    }

                    convertMarkdownToHTML();
                </script>

                <?php
                $versionDialogMode = isset($this->optionslib) ? $this->optionslib->get_option('version_dialog') : 'release_notes';
                if ($versionDialogMode == 'custom_text' || $versionDialogMode == 'both') {
                ?>
                    <div class="border-bottom border-top p-4 m-4">
                        <?php
                        $versionDialogText = isset($this->optionslib) ? $this->optionslib->get_option('version_dialog_text') : null;
                        if ($versionDialogText !== null) {
                            $versionDialogTextWithLinks = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank">$1</a>', $versionDialogText);
                            echo nl2br($versionDialogTextWithLinks);
                        } else {
                            echo 'No Version Dialog text set. Go to the Admin Menu and set one.';
                        }
                        ?>
                    </div>
                <?php
                }
                if ($versionDialogMode == 'release_notes' || $versionDialogMode == 'both' || $versionDialogMode == 'disabled') {
                ?>
                    <div>
                        <?php
                        $url = 'https://api.github.com/repos/magicbug/Cloudlog/releases';
                        $options = [
                            'http' => [
                                'header' => 'User-Agent: Cloudlog - Amateur Radio Logbook'
                            ]
                        ];
                        $context = stream_context_create($options);
                        $response = file_get_contents($url, false, $context);

                        if ($response !== false) {
                            $data = json_decode($response, true);

                            $current_version = $this->optionslib->get_option('version');
                            if ($data !== null && !empty($data)) {
                                foreach ($data as $singledata) {
                                    if ($singledata['tag_name'] == $current_version) {
                                        $firstRelease = $singledata;
                                        continue;
                                    }
                                }

                                $releaseBody = isset($firstRelease['body']) ? $firstRelease['body'] : 'No release information available';
                                $htmlReleaseBody = htmlspecialchars($releaseBody);
                                $htmlReleaseBodyWithLinks = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank">$1</a>', $htmlReleaseBody);

                                $releaseName = isset($firstRelease['name']) ? $firstRelease['name'] : 'No version name information available';
                                echo "<h4>v" . $releaseName . "</h4>";
                                echo "<div id='markdownDiv' style='display: none;'>" . $releaseBody . "</div>";
                                echo "<div id='formattedHTMLDiv'></div>";
                            } else {
                                echo 'Error decoding JSON data or received empty response.';
                            }
                        } else {
                            echo 'Error retrieving data from the GitHub API.';
                        }
                        ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <?php
                if ($versionDialogMode !== 'disabled') {
                ?>
                    <button class="btn btn-secondary" onclick="dismissVersionDialog()" data-bs-dismiss="modal"><?php echo lang('options_version_dialog_dismiss'); ?></button>
                <?php
                }
                ?>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo lang('options_version_dialog_close'); ?></button>
            </div>
        </div>
    </div>
</div>