<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_options_table_performance_index extends CI_Migration {

    public function up()
    {
        // ========================================================================================
        // OPTIONS TABLE CRITICAL PERFORMANCE INDEX
        // ========================================================================================
        
        // CRITICAL: Index for autoload field - QUERIED ON EVERY PAGE LOAD!
        // Used in: Options_model->get_autoloads() -> WHERE autoload = "yes"
        // Called from: OptionsLib constructor (auto-loaded library)
        // Impact: This query runs on EVERY page request to load configuration options
        // Without this index: Full table scan on every page load
        // With this index: Instant lookup of autoloaded options
        $this->db->query("CREATE INDEX idx_autoload ON options (autoload);");
        
        // Optional: Composite index for autoload + option_name lookups
        // This can help with queries that filter both fields simultaneously
        // Used for: Combined autoload filtering with specific option lookups
        $this->db->query("CREATE INDEX idx_autoload_name ON options (autoload, option_name);");
    }

    public function down()
    {
        $this->db->query("DROP INDEX idx_autoload ON options;");
        $this->db->query("DROP INDEX idx_autoload_name ON options;");
    }
}