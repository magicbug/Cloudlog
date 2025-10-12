<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_adif_modes_performance_indexes extends CI_Migration {

    public function up()
    {
        // ========================================================================================
        // ADIF_MODES TABLE PERFORMANCE INDEXES
        // ========================================================================================
        
        // CRITICAL: Index for active field - used for filtering active modes
        // Used in: Modes->active() -> WHERE active = 1
        // Impact: Fast filtering of active vs inactive modes for UI dropdowns
        $this->db->query("CREATE INDEX idx_active ON adif_modes (active);");
        
        // CRITICAL: Index for mode field - used for mode-based lookups and grouping
        // Used in: Various mode management functions and ORDER BY operations
        // Impact: Fast mode-based filtering and efficient sorting
        $this->db->query("CREATE INDEX idx_mode ON adif_modes (mode);");
        
        // CRITICAL: Index for submode field - used for submode-to-mainmode lookup
        // Used in: get_main_mode_if_submode() -> WHERE submode = ?
        // Impact: Fast submode validation and main mode resolution
        $this->db->query("CREATE INDEX idx_submode ON adif_modes (submode);");
        
        // PERFORMANCE: Composite index for active modes ordered by mode/submode
        // Used in: Modes->active() -> WHERE active = 1 ORDER BY mode, submode
        // Impact: Single index scan for active mode listings (covers WHERE + ORDER BY)
        $this->db->query("CREATE INDEX idx_active_mode_submode ON adif_modes (active, mode, submode);");
        
        // PERFORMANCE: Index for qrgmode field - used for radio mode grouping
        // Used for: QRG (frequency) mode categorization (CW, SSB, DATA)
        // Impact: Fast filtering by radio operating mode category
        $this->db->query("CREATE INDEX idx_qrgmode ON adif_modes (qrgmode);");
        
        // PERFORMANCE: Composite index for mode/submode combination lookups
        // Used for: Unique mode/submode pair validation and lookups
        // Impact: Fast exact mode+submode matching
        $this->db->query("CREATE INDEX idx_mode_submode ON adif_modes (mode, submode);");
    }

    public function down()
    {
        $this->db->query("DROP INDEX idx_active ON adif_modes;");
        $this->db->query("DROP INDEX idx_mode ON adif_modes;");
        $this->db->query("DROP INDEX idx_submode ON adif_modes;");
        $this->db->query("DROP INDEX idx_active_mode_submode ON adif_modes;");
        $this->db->query("DROP INDEX idx_qrgmode ON adif_modes;");
        $this->db->query("DROP INDEX idx_mode_submode ON adif_modes;");
    }
}