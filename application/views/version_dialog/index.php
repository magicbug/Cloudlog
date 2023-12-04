<div class="modal fade" id="versionDialogModal" tabindex="-1" aria-labelledby="versionDialogLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="versionDialogLabel">Version Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="border-bottom pb-4 mb-4">
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

                        if ($data !== null && !empty($data)) {
                            $firstRelease = $data[0];

                            $releaseBody = isset($firstRelease['body']) ? $firstRelease['body'] : 'No release information available';
                            $htmlReleaseBody = htmlspecialchars($releaseBody);
                            $htmlReleaseBodyWithLinks = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank">$1</a>', $htmlReleaseBody);
                            echo nl2br($htmlReleaseBodyWithLinks);
                        } else {
                            echo 'Fehler beim Decodieren der JSON-Daten oder leere Antwort erhalten.';
                        }
                    } else {
                        echo 'Fehler beim Abrufen der Daten von der GitHub API.';
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="dismissVersionDialog()" data-bs-dismiss="modal"><?php echo lang('options_version_dialog_dismiss'); ?></button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo lang('options_version_dialog_close'); ?></button>
            </div>
        </div>
    </div>
</div>