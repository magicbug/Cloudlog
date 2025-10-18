<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| OAuth / OpenID Connect settings
|--------------------------------------------------------------------------
| Controlled by 'oauth_enabled'. When false, UI and routes are dormant.
| Uses standard OIDC code flow with PKCE where supported.
|
| SECURITY NOTES
| - Keep secrets out of VCS; load from env when possible.
| - Use HTTPS for redirect URIs in production.
| - Set strict cookie settings (SameSite=Lax or Strict) at app level.
*/

$config['oauth_enabled'] = false; // set true to enable SSO button and routes

// Default provider profile; can support multiple in future
$config['oauth_provider'] = [
    'issuer' => getenv('OAUTH_ISSUER') ?: '',            // e.g. https://accounts.google.com, https://login.microsoftonline.com/<tenant>/v2.0
    'client_id' => getenv('OAUTH_CLIENT_ID') ?: '',
    'client_secret' => getenv('OAUTH_CLIENT_SECRET') ?: '',
    'redirect_path' => 'oauth/callback',                 // relative to site_url()
    'scopes' => ['openid', 'profile', 'email'],
    'verify_peer' => true,                               // set false only for internal/dev IdPs with self-signed certs
    'leeway' => 60,                                      // clock skew allowance in seconds
];

// Mapping strategy: pick existing user by email; optional auto-provision
$config['oauth_login'] = [
    'match_on' => 'email',          // 'email' or 'sub' (preferred unique subject)
    'auto_provision' => false,      // create local user if not found
    'default_role' => 3,            // 3 = Operator by this app's roles
];

/* End of file oauth.php */
