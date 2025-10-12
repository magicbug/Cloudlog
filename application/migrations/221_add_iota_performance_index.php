<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_iota_performance_index extends CI_Migration {

    public function up()
    {
        // Add critical index for IOTA Tag field - this is the primary lookup field
        // for all IOTA queries and joins with col_iota = iota.tag
        $this->db->query("CREATE INDEX idx_iota_tag ON iota (Tag);");
        
        // Add index for Prefix field - used for callsign prefix lookups
        $this->db->query("CREATE INDEX idx_iota_prefix ON iota (Prefix);");
        
        // Add composite index for Status filtering (exclude deleted records)
        // Used in queries: "coalesce(iota.status, '') <> 'D'"
        $this->db->query("CREATE INDEX idx_iota_status ON iota (Status);");
    }

    public function down()
    {
        $this->db->query("DROP INDEX idx_iota_tag ON iota;");
        $this->db->query("DROP INDEX idx_iota_prefix ON iota;");
        $this->db->query("DROP INDEX idx_iota_status ON iota;");
    }
}