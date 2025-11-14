# Cloudlog AI Coding Agent Instructions

## Project Overview
Cloudlog is a self-hosted **PHP web application** for amateur radio contact logging. Built on **CodeIgniter 3** MVC framework with Bootstrap 5 frontend, HTMX for AJAX interactions, and jQuery for enhanced functionality.

**Stack**: PHP 7.4+ (8.2 compatible), MySQL 5.7+, Apache/Nginx, CodeIgniter 3

UI: Default Bootstrap styling (no custom theme by default).

## Architecture

### MVC Structure (CodeIgniter 3)
- **Controllers** (`application/controllers/`): Extend `CI_Controller`, handle authentication via `user_model->validate_session()`
- **Models** (`application/models/`): Extend `CI_Model`, handle database operations (e.g., `Logbook_model`, `Stations`)
- **Views** (`application/views/`): PHP templates with Bootstrap 5 classes, loaded via `$this->load->view()`
- **Libraries** (`application/libraries/`): Custom classes like `Qra` (gridsquare calculations), `optionslib` (settings)

### Key Patterns
```php
// Controllers always check authentication first
$this->load->model('user_model');
if ($this->user_model->validate_session() == 0) {
    redirect('user/login');
}

// Authorization levels: authorize(2) for users, authorize(99) for admins
if (!$this->user_model->authorize(2)) {
    $this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
    redirect('dashboard');
}
```

### Configuration
- **Config files**: `application/config/config.php` (app settings), `database.php` (DB credentials)
- Sample files: `config.sample.php`, `database.sample.php` - copy and customize for local setup
- Environment: Set in `index.php` - `define('ENVIRONMENT', 'development')` shows profiler
- Version: Defined in `config.php` as `$config['app_version'] = "2.4.5"`

## Frontend Integration

### HTMX Usage (Primary AJAX Method)
HTMX is the **preferred** method for dynamic content loading. Views use `hx-get`, `hx-post`, `hx-target` attributes:
```php
<!-- Auto-refreshing component -->
<div id="qso-last-table" hx-get="<?php echo site_url('/qso/component_past_contacts'); ?>" 
     hx-trigger="load, every 5s">
</div>

<!-- Form submission -->
<form hx-post="<?php echo site_url('logbooks/save_publicslug/'); ?>" 
      hx-target="#publicSlugForm">
```

### HTMX In Practice
- Previous QSOs widget:
    - View snippet (auto-refresh):
        ```php
        <div id="qso-last-table" hx-get="<?php echo site_url('/qso/component_past_contacts'); ?>" hx-trigger="load, every 5s"></div>
        ```
    - Controller endpoint: `application/controllers/Qso.php::component_past_contacts()` loads `application/views/qso/components/previous_contacts.php` with `$this->logbook_model->last_custom('5')`.

- Save Public Slug form:
    - View snippet:
        ```php
        <form hx-post="<?php echo site_url('logbooks/save_publicslug/'); ?>" hx-target="#publicSlugForm">
            <input type="hidden" name="logbook_id" value="<?php echo $logbook_id; ?>">
            <input type="text" name="public_slug" required>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        ```
    - Controller endpoint: `application/controllers/Logbooks.php::save_publicslug()` validates `public_slug` (`required|alpha_numeric`) and persists via `logbooks_model`.

### Assets
- **Most assets**: Live under `assets/`; core includes are wired via `application/views/interface_assets/header.php` and `application/views/interface_assets/footer.php`.
- **CSS**: `assets/css/` - Bootstrap themes, custom overrides in `themes/*/overrides.css`
- **JS**: `assets/js/` - jQuery, HTMX (`htmx.min.js`), Leaflet maps, custom logic
- **Icons**: Font Awesome (via `assets/fontawesome/`)

## Database

### Migrations
Database schema managed via **CodeIgniter migrations** (`application/migrations/`):
- Sequential numbered files: `001_add_lotw_credentials.php` → `232_tag_2_7_7.php`
- Each extends `CI_Migration` with `up()` method
- Run via: `php index.php migrate` or through admin interface

### Key Tables
- `TABLE_HRD_CONTACTS_V01`: QSO log (configured in `config.php`)
- `station_profile`: Station locations and settings
- `station_logbooks`: Logbook definitions
- `station_logbooks_entity`: Logbook-location relationships

## Development Workflows

### Docker Development Setup
```bash
# Start environment (web + db services)
docker-compose up

# Access: http://localhost/
# DB host in Docker: 'db' (service name, not localhost)
```

**Configuration**: Copy `.env.sample` to `.env` and adjust DB settings before starting.

### Testing
**Cypress** end-to-end tests (`cypress/e2e/`):
```bash
# Install & run tests
npm install cypress
npx cypress run

# Tests require Docker containers running
docker-compose up -d
```

Tests validate: login flows, station creation, logbook operations, version checks.

### Common Tasks
- **Enable profiler**: Set `ENVIRONMENT = 'development'` in `index.php`
- **Routing**: CI3 maps `/controller/method` to `Controller::method` by default; `application/config/routes.php` is typically left at its default (`default_controller = 'dashboard'`). Only edit routes for custom remaps.
- **Base URL helpers**: Use `site_url()` and `base_url()` in views/controllers

## Where To Start (New Features)
- **Controller**: Add `application/controllers/MyFeature.php` extending `CI_Controller`. In `__construct()` or method start, load `user_model` and enforce `validate_session()`/`authorize()` as needed. Return views via `$this->load->view()`.
- **Model**: Add `application/models/Myfeature_model.php` for DB access. Use CI Query Builder. Inject via `$this->load->model('myfeature_model')`.
- **View**: Create `application/views/myfeature/*.php`. Include header/footer (`interface_assets/header` and `interface_assets/footer`). Prefer HTMX (`hx-get`/`hx-post`) for async UI.
- **Migrations**: For schema changes, add `application/migrations/NNN_description.php` and run `php index.php migrate` (or use the admin UI). Keep IDs sequential.
- **Routing note**: New endpoints are reachable as `/index.php/myfeature/method` (and usually `/myfeature/method` with proper web server config); no routes entry required unless you need a custom URI.

## Domain-Specific Context

### Amateur Radio Concepts
- **Gridsquare/Locator**: Maidenhead grid system for location (e.g., `IO87JP`)
- **QSO**: Radio contact/log entry
- **DXCC**: Country entities for award tracking
- **ADIF**: Amateur Data Interchange Format for import/export
- **LoTW/eQSL**: Electronic QSL card confirmation systems

### Libraries & Helpers
- **Qra library** (`application/libraries/Qra.php`): Calculate bearings, distances, gridsquare conversions
- **qra2latlong()**: Global function (defined in `Qra.php`) converts Maidenhead to coordinates

## Code Conventions

### Controllers
- Load models in `__construct()` or method start
- Use flashdata for user messages: `$this->session->set_flashdata('notice', 'Message')`
- Redirect after POST: `redirect('controller/method')`

### Views
- Header/footer: `$this->load->view('interface_assets/header', $data)` + `footer.php`
- JavaScript globals defined in `footer.php`: `base_url`, `site_url`, `my_call`
- Language strings: `<?php echo lang('key'); ?>` (files in `application/language/`)

### Security
- Input sanitization: `$this->security->xss_clean($input)`
- Prevent direct access: `if (!defined('BASEPATH')) exit('No direct script access allowed');`
- SQL: Use Query Builder or prepared statements (handled by CI3 models)

## Pull Request Guidelines
- **Target branch**: `dev` (PRs to `main` will be rejected)
- **One feature per PR**: No multi-feature or bundled bug fixes
- **Comment code**: Explain non-obvious logic
- **Test coverage**: Run Cypress tests before submitting
- **Description**: Clearly state what the PR does and why it's needed

## Common Pitfalls
- **Don't use `&&` in PowerShell commands** - use `;` to chain commands
- **Docker DB host**: Use service name `db`, not `localhost` or `127.0.0.1`
- **Config files**: Never commit `config.php` or `database.php` (use `.sample` versions as templates)
- **Base URL**: Must be set correctly in `config.php` for site to work properly
