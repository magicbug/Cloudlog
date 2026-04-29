<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ClubAwardsPlusPlugin {

    private $CI;

    public function __construct($ci)
    {
        $this->CI = $ci;
    }

    /**
     * Filter hook: can change QSO data before it is saved.
     */
    public function beforeSave($payload, $context = array())
    {
        if (!is_array($payload)) {
            return $payload;
        }

        // Example: normalize SIG and SIG_INFO for club award tracking.
        if (!empty($payload['COL_SIG'])) {
            $payload['COL_SIG'] = strtoupper(trim((string)$payload['COL_SIG']));
        }

        if (!empty($payload['COL_SIG_INFO'])) {
            $payload['COL_SIG_INFO'] = strtoupper(trim((string)$payload['COL_SIG_INFO']));
        }

        // Example: add note marker for this plugin on new QSOs.
        $source = isset($context['source']) ? (string)$context['source'] : '';
        if ($source === 'manual') {
            $existing_notes = isset($payload['COL_NOTES']) ? trim((string)$payload['COL_NOTES']) : '';
            if (strpos($existing_notes, '[ClubAwardsPlus]') === false) {
                $payload['COL_NOTES'] = trim($existing_notes . ' [ClubAwardsPlus]');
            }
        }

        return $payload;
    }

    /**
     * Action hook: runs after a QSO is inserted.
     */
    public function afterSave($payload, $context = array())
    {
        $qso_id = isset($payload['qso_id']) ? (int)$payload['qso_id'] : 0;
        log_message('info', 'ClubAwardsPlus afterSave qso_id=' . $qso_id);
    }

    /**
     * Action hook: runs after a QSO is edited.
     */
    public function afterEdit($payload, $context = array())
    {
        $qso_id = isset($payload['qso_id']) ? (int)$payload['qso_id'] : 0;
        log_message('info', 'ClubAwardsPlus afterEdit qso_id=' . $qso_id);
    }

    /**
     * Award page renderer used by plugin_awards/view/<slug>.
     */
    public function renderAwardPage($context = array())
    {
        $user_id = isset($context['user_id']) ? (int)$context['user_id'] : 0;

        $content = '';
        $content .= '<div class="alert alert-info">';
        $content .= '<strong>Club Awards Plus</strong> is active for user #' . $user_id . '.';
        $content .= '</div>';
        $content .= '<p>This is an example plugin award page. Replace this output with your own award statistics and tables.</p>';

        return array(
            'page_title' => 'Club Awards Plus',
            'content' => $content,
        );
    }
}
