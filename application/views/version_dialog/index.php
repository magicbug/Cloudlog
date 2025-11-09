<div class="modal fade" id="versionDialogModal" tabindex="-1" aria-labelledby="versionDialogLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="versionDialogLabel"><?php echo $this->optionslib->get_option('version_dialog_header'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <style>
                    /* Global heading styles for version modal */
                    #versionDialogModal h4 {
                        margin-top: 1rem;
                        margin-bottom: 0.75rem;
                        font-weight: 600;
                        line-height: 1.3;
                    }
                    
                    .release-notes h2, .release-notes h3, .release-notes h4, .release-notes h5 {
                        margin-top: 1.25rem;
                        margin-bottom: 0.5rem;
                        font-weight: 600;
                        line-height: 1.3;
                    }
                    .release-notes h2 { font-size: 1.5rem; margin-top: 1.5rem; }
                    .release-notes h3 { font-size: 1.3rem; }
                    .release-notes h4 { font-size: 1.15rem; }
                    .release-notes h5 { font-size: 1rem; }
                    .release-notes ul, .release-notes ol {
                        margin: 0.75rem 0;
                        padding-left: 2rem;
                        line-height: 1.6;
                    }
                    .release-notes li {
                        margin: 0.3rem 0;
                    }
                    .release-notes p {
                        margin: 0.5rem 0;
                        line-height: 1.6;
                    }
                    .release-notes code {
                        background-color: #f4f4f4;
                        padding: 0.2rem 0.4rem;
                        border-radius: 3px;
                        font-family: 'Courier New', monospace;
                        font-size: 0.9em;
                        color: #d63384;
                    }
                    .release-notes pre {
                        background-color: #f4f4f4;
                        padding: 1rem;
                        border-radius: 5px;
                        overflow-x: auto;
                        margin: 1rem 0;
                    }
                    .release-notes pre code {
                        background-color: transparent;
                        padding: 0;
                        color: inherit;
                    }
                    .release-notes a {
                        color: #0d6efd;
                        text-decoration: none;
                    }
                    .release-notes a:hover {
                        text-decoration: underline;
                    }
                    .release-notes strong {
                        font-weight: 600;
                    }
                    .release-notes em {
                        font-style: italic;
                    }
                    .release-notes img {
                        max-width: 100%;
                        height: auto;
                        margin: 1rem 0;
                        border-radius: 5px;
                        display: block;
                    }
                </style>

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
                                    
                                    // Convert code blocks (```) - do this first to protect code content
                                    $htmlContent = preg_replace('/```([a-z]*)\r?\n(.*?)\r?\n```/s', '<pre><code>$2</code></pre>', $htmlContent);
                                    
                                    // Convert inline code (`) - protect inline code from other conversions
                                    $htmlContent = preg_replace('/`([^`]+)`/', '<code>$1</code>', $htmlContent);
                                    
                                    // Convert bullet points to list items (must be done before bold/italic to avoid interference)
                                    $htmlContent = preg_replace_callback(
                                        '/((?:^[ \t]*[\*\-\+] .+$(?:\r?\n)?)+)/m',
                                        function($matches) {
                                            $listContent = $matches[1];
                                            // Convert each bullet point to <li>
                                            $listContent = preg_replace('/^[ \t]*[\*\-\+] (.+)$/m', '<li>$1</li>', $listContent);
                                            // Wrap in <ul> tags
                                            return "\n<ul>\n" . $listContent . "</ul>\n";
                                        },
                                        $htmlContent
                                    );
                                    
                                    // Convert numbered lists
                                    $htmlContent = preg_replace_callback(
                                        '/((?:^[ \t]*\d+\. .+$(?:\r?\n)?)+)/m',
                                        function($matches) {
                                            $listContent = $matches[1];
                                            // Convert each numbered item to <li>
                                            $listContent = preg_replace('/^[ \t]*\d+\. (.+)$/m', '<li>$1</li>', $listContent);
                                            // Wrap in <ol> tags
                                            return "\n<ol>\n" . $listContent . "</ol>\n";
                                        },
                                        $htmlContent
                                    );
                                    
                                    // Convert headers (process smaller headers first, then larger)
                                    $htmlContent = preg_replace('/^#### (.+)$/m', '<h5>$1</h5>', $htmlContent);
                                    $htmlContent = preg_replace('/^### (.+)$/m', '<h4>$1</h4>', $htmlContent);
                                    $htmlContent = preg_replace('/^## (.+)$/m', '<h3>$1</h3>', $htmlContent);
                                    $htmlContent = preg_replace('/^# (.+)$/m', '<h2>$1</h2>', $htmlContent);
                                    
                                    // Convert bold text (**text** or __text__) - must be before italic
                                    $htmlContent = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $htmlContent);
                                    $htmlContent = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $htmlContent);
                                    
                                    // Convert italic text (*text* or _text_)
                                    $htmlContent = preg_replace('/(?<![*_])\*([^*\n]+)\*(?![*_])/', '<em>$1</em>', $htmlContent);
                                    $htmlContent = preg_replace('/(?<![*_])_([^_\n]+)_(?![*_])/', '<em>$1</em>', $htmlContent);
                                    
                                    // Convert markdown images ![alt](url) - must be before regular links
                                    $htmlContent = preg_replace('/!\[([^\]]*)\]\(([^)]+)\)/', '<img src="$2" alt="$1" class="img-fluid" style="max-width: 100%; height: auto;" />', $htmlContent);
                                    
                                    // Convert markdown links [text](url)
                                    $htmlContent = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank">$1</a>', $htmlContent);
                                    
                                    // Convert GitHub usernames (@username) to profile links
                                    $htmlContent = preg_replace('/(?<![\w\/])@([a-zA-Z0-9_-]+)(?![\w])/', '<a href="https://github.com/$1" target="_blank">@$1</a>', $htmlContent);
                                    
                                    // Convert plain URLs to links (but avoid already linked URLs)
                                    $htmlContent = preg_replace('/(?<!href=["|\']|">|>\s)(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank">$1</a>', $htmlContent);
                                    
                                    // Replace newlines after block-level elements with a marker to prevent nl2br from converting them
                                    $htmlContent = preg_replace('/(<\/(?:h[1-6]|ul|ol|li|pre)>)\r?\n/', '$1<!--BLOCK-->', $htmlContent);
                                    $htmlContent = preg_replace('/(<(?:ul|ol|pre)>)\r?\n/', '$1<!--BLOCK-->', $htmlContent);
                                    
                                    // Convert line breaks to <br> tags
                                    $htmlContent = nl2br($htmlContent);
                                    
                                    // Remove the markers (and any br tags that might have been added near them)
                                    $htmlContent = preg_replace('/<!--BLOCK--><br\s*\/?>/', '', $htmlContent);
                                    $htmlContent = preg_replace('/<br\s*\/?><!--BLOCK-->/', '', $htmlContent);
                                    $htmlContent = preg_replace('/<!--BLOCK-->/', '', $htmlContent);
                                    
                                    // Additional cleanup: remove br tags immediately before and after block elements
                                    $htmlContent = preg_replace('/<br\s*\/?>\s*(<(?:h[1-6]|ul|ol|li|pre|code)[^>]*>)/i', '$1', $htmlContent);
                                    $htmlContent = preg_replace('/(<\/(?:h[1-6]|ul|ol|li|pre)>)\s*<br\s*\/?>/i', '$1', $htmlContent);
                                    
                                    // Remove multiple consecutive br tags (more than 2)
                                    $htmlContent = preg_replace('/(<br\s*\/?>){3,}/', '<br><br>', $htmlContent);
                                    
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