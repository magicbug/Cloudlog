<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Oauth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('encryption');
        $this->load->helper('security');
    }

    public function login()
    {
        if (!$this->config->item('oauth_enabled')) {
            $this->session->set_flashdata('warning', 'Single Sign-On is disabled.');
            redirect('user/login');
            return;
        }

        $errorMessage = '';
        $client = $this->buildClient('oauth/callback', $errorMessage);
        if ($client === null) {
            $this->session->set_flashdata('danger', $errorMessage);
            redirect('user/login');
            return;
        }

        try {
            // Triggers redirect to IdP
            $client->authenticate();
        } catch (Exception $e) {
            log_message('error', 'OIDC authenticate failed (login): ' . $e->getMessage());
            $this->session->set_flashdata('danger', 'Unable to start SSO. Please try again later.');
            redirect('user/login');
        }
    }

    public function callback()
    {
        if (!$this->config->item('oauth_enabled')) {
            show_404();
            return;
        }

        $errorMessage = '';
        $client = $this->buildClient('oauth/callback', $errorMessage);
        if ($client === null) {
            $this->session->set_flashdata('danger', $errorMessage);
            redirect('user/login');
            return;
        }

        try {
            $client->authenticate();
        } catch (Exception $e) {
            log_message('error', 'OIDC authenticate failed (callback): ' . $e->getMessage());
            $this->session->set_flashdata('danger', 'SSO authentication failed.');
            redirect('user/login');
            return;
        }

        $this->finalizeLogin($client);
    }

    private function finalizeLogin($client)
    {
        $loginConfig = $this->config->item('oauth_login');
        $defaultRole = isset($loginConfig['default_role']) ? intval($loginConfig['default_role']) : 3;

        // Standard OIDC claims
        $email = $this->safeUserInfo($client, 'email');
        $subject = $this->safeUserInfo($client, 'sub');
        $preferredUsername = $this->safeUserInfo($client, 'preferred_username');
        $givenName = $this->safeUserInfo($client, 'given_name');
        $familyName = $this->safeUserInfo($client, 'family_name');
        $fullName = $this->safeUserInfo($client, 'name');

        if (!$email && $preferredUsername && filter_var($preferredUsername, FILTER_VALIDATE_EMAIL)) {
            $email = $preferredUsername;
        }

        if (!$email) {
            $this->session->set_flashdata('danger', 'SSO did not return an email address.');
            redirect('user/login');
            return;
        }

        // Try existing user by email
        $existing = $this->user_model->get_by_email($email);
        if ($existing && $existing->num_rows() > 0) {
            $userId = $existing->row()->user_id;
            $this->user_model->update_session($userId);
            $this->user_model->set_last_login($userId);
            redirect('dashboard');
            return;
        }

        // Optionally auto-provision
        $autoProvision = isset($loginConfig['auto_provision']) ? (bool)$loginConfig['auto_provision'] : false;
        if (!$autoProvision) {
            $this->session->set_flashdata('danger', 'No local account found for SSO user.');
            redirect('user/login');
            return;
        }

        // Create a local user with conservative defaults
        $usernameCandidate = $this->generateUsernameFromEmail($email);
        $randomPassword = bin2hex(random_bytes(16));

        $firstname = $givenName ?: ($fullName ?: '');
        $lastname = $familyName ?: '';

        $this->user_model->add(
            $usernameCandidate,                    // user_name
            $randomPassword,                       // user_password (hashed in model)
            $email,                                // user_email
            $defaultRole,                          // user_type
            $firstname,                            // user_firstname
            $lastname,                             // user_lastname
            '',                                    // user_callsign
            '',                                    // user_locator
            'UTC',                                 // user_timezone
            0,                                     // measurement base (0 default)
            'D d/m/Y',                             // user_date_format
            'classic',                             // user_stylesheet
            0, 0, 0, 0,                            // qth/sota/wwff/pota lookups
            1,                                     // user_show_notes
            'Mode', 'RSTS', 'RSTR', 'Band', 'Country', // columns
            0,                                     // show profile image
            '',                                    // previous qsl type
            0,                                     // amsat upload
            '',                                    // mastodon url
            '',                                    // default band
            '',                                    // default confirmation
            1,                                     // qso end times
            1,                                     // quicklog
            1,                                     // quicklog enter
            'english',                             // language
            '',                                    // hamsat key
            0,                                     // hamsat workable only
            '', '', ''                             // callbook type/username/password
        );

        // Login the newly created user
        $created = $this->user_model->get_by_email($email);
        if ($created && $created->num_rows() > 0) {
            $userId = $created->row()->user_id;
            $this->user_model->update_session($userId);
            $this->user_model->set_last_login($userId);
            redirect('dashboard');
            return;
        }

        $this->session->set_flashdata('danger', 'SSO provisioning failed.');
        redirect('user/login');
    }

    private function buildClient($redirectPath, &$errorMessage)
    {
        $errorMessage = '';

        $provider = $this->config->item('oauth_provider');
        $issuer = isset($provider['issuer']) ? trim($provider['issuer']) : '';
        $clientId = isset($provider['client_id']) ? $provider['client_id'] : '';
        $clientSecret = isset($provider['client_secret']) ? $provider['client_secret'] : '';
        $verifyPeer = isset($provider['verify_peer']) ? (bool)$provider['verify_peer'] : true;
        $scopes = isset($provider['scopes']) ? (array)$provider['scopes'] : array('openid', 'profile', 'email');
        $leeway = isset($provider['leeway']) ? intval($provider['leeway']) : 60;

        if ($issuer === '' || $clientId === '') {
            $errorMessage = 'SSO is misconfigured: missing issuer or client_id.';
            return null;
        }

        $autoload = $this->locateComposerAutoload();
        if ($autoload === null) {
            $errorMessage = 'SSO dependencies not installed. Please run composer require jumbojett/openid-connect-php and ensure vendor/autoload.php is present.';
            return null;
        }
        require_once $autoload;

        if (!class_exists('Jumbojett\\OpenIDConnectClient')) {
            $errorMessage = 'OpenID Connect client not available.';
            return null;
        }

        try {
            $client = new Jumbojett\OpenIDConnectClient($issuer, $clientId, $clientSecret);
            $client->setVerifyPeer($verifyPeer);
            $client->setCertPath(null); // use system CA bundle
            if (method_exists($client, 'setLeeway')) {
                $client->setLeeway($leeway);
            }
            $client->setRedirectURL(site_url($redirectPath));

            foreach ($scopes as $scope) {
                $client->addScope($scope);
            }

            // Prefer PKCE when supported (method exists in newer versions)
            if (method_exists($client, 'setCodeChallengeMethod')) {
                $client->setCodeChallengeMethod('S256');
            }

            return $client;
        } catch (Exception $e) {
            log_message('error', 'Failed to initialize OIDC client: ' . $e->getMessage());
            $errorMessage = 'Failed to initialize SSO client.';
            return null;
        }
    }

    private function safeUserInfo($client, $field)
    {
        try {
            return $client->requestUserInfo($field);
        } catch (Exception $e) {
            return '';
        }
    }

    private function locateComposerAutoload()
    {
        $candidates = array(
            APPPATH . 'vendor/autoload.php',
            FCPATH . 'vendor/autoload.php',
            dirname(FCPATH) . '/vendor/autoload.php',
            APPPATH . '../vendor/autoload.php',
        );
        foreach ($candidates as $path) {
            if (@is_readable($path)) {
                return $path;
            }
        }
        return null;
    }

    private function generateUsernameFromEmail($email)
    {
        $parts = explode('@', $email, 2);
        $local = $parts[0];
        $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '', strtolower($local));
        if ($slug === '') {
            $slug = 'user' . substr(md5($email), 0, 8);
        }
        // Ensure uniqueness by appending random suffix if taken
        $candidate = $slug;
        $suffix = 1;
        while ($this->user_model->exists($candidate)) {
            $candidate = $slug . $suffix;
            $suffix++;
            if ($suffix > 50) {
                $candidate = $slug . substr(md5(uniqid('', true)), 0, 4);
                break;
            }
        }
        return $candidate;
    }
}
