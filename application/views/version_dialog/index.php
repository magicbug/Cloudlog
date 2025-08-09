<div class="modal fade" id="versionDialogModal" tabindex="-1" aria-labelledby="versionDialogLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="versionDialogLabel"><?php echo $this->optionslib->get_option('version_dialog_header'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <script src="<?php echo base_url('assets/js/showdown.min.js'); ?>"></script>

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
                                $firstRelease = null;
                                foreach ($data as $singledata) {
                                    if ($singledata['tag_name'] == $current_version) {
                                        $firstRelease = $singledata;
                                        break;
                                    }
                                }

                                if ($firstRelease !== null) {
                                    $releaseBody = isset($firstRelease['body']) ? $firstRelease['body'] : 'No release information available';

                                    $releaseName = isset($firstRelease['name']) ? $firstRelease['name'] : 'No version name information available';
                                    echo "<h4>v" . $releaseName . "</h4>";
                                    
                                    // Convert markdown to HTML using PHP
                                    $htmlContent = $releaseBody;
                                    
                                    // Escape HTML first to prevent issues
                                    $htmlContent = htmlspecialchars($htmlContent);
                                    
                                    // Convert headers
                                    $htmlContent = preg_replace('/^## (.+)$/m', '<h3>$1</h3>', $htmlContent);
                                    $htmlContent = preg_replace('/^# (.+)$/m', '<h2>$1</h2>', $htmlContent);
                                    
                                    // Convert bullet points to list items
                                    // First, find all bullet point sections and convert them to proper lists
                                    $htmlContent = preg_replace_callback(
                                        '/(?:^[ ]*\* .+(?:\r?\n|$))+/m',
                                        function($matches) {
                                            $listContent = $matches[0];
                                            // Convert each bullet point to <li>, removing any trailing newlines
                                            $listContent = preg_replace('/^[ ]*\* (.+?)(?:\r?\n|$)/m', '<li>$1</li>', $listContent);
                                            // Wrap in <ul> tags
                                            return '<ul>' . trim($listContent) . '</ul>';
                                        },
                                        $htmlContent
                                    );
                                    
                                    // Convert links (markdown style)
                                    $htmlContent = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank">$1</a>', $htmlContent);
                                    
                                    // Convert plain URLs to links
                                    $htmlContent = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank">$1</a>', $htmlContent);
                                    
                                    // Convert GitHub usernames (@username) to profile links
                                    $htmlContent = preg_replace('/@([a-zA-Z0-9_-]+)/', '<a href="https://github.com/$1" target="_blank">@$1</a>', $htmlContent);
                                    
                                    // Convert bold text
                                    $htmlContent = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $htmlContent);
                                    
                                    // Convert line breaks to <br> tags
                                    $htmlContent = nl2br($htmlContent);
                                    
                                    // Clean up: remove <br> tags that appear right before or after list tags
                                    $htmlContent = preg_replace('/<br\s*\/?>\s*(<\/?ul>)/', '$1', $htmlContent);
                                    $htmlContent = preg_replace('/(<\/?ul>)\s*<br\s*\/?>/', '$1', $htmlContent);
                                    $htmlContent = preg_replace('/<br\s*\/?>\s*(<\/?li>)/', '$1', $htmlContent);
                                    $htmlContent = preg_replace('/(<\/?li>)\s*<br\s*\/?>/', '$1', $htmlContent);
                                    
                                    echo "<div class='release-notes mt-3'>" . $htmlContent . "</div>";
                                } else {
                                    echo '<h4>v' . $current_version . '</h4>';
                                    echo '<p>No release information found for this version on GitHub.</p>';
                                }
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