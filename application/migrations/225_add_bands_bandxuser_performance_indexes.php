<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_bands_bandxuser_performance_indexes extends CI_Migration {

    public function up()
    {
        // ========================================================================================
        // BANDS TABLE PERFORMANCE INDEXES
        // ========================================================================================
        
        // CRITICAL: Index for band name lookups - used throughout the application
        // Used in: WHERE bands.band = 'X' queries and band resolution
        // Impact: Fast band name-based lookups and filtering
        $this->db->query("CREATE INDEX idx_band ON bands (band);");
        
        // PERFORMANCE: Index for bandgroup filtering - used for band categorization
        // Used in: WHERE bands.bandgroup != 'sat' and band grouping operations
        // Impact: Fast filtering by band groups (hf, vhf, uhf, shf, sat)
        $this->db->query("CREATE INDEX idx_bandgroup ON bands (bandgroup);");
        
        // ========================================================================================
        // BANDXUSER TABLE PERFORMANCE INDEXES (Many-to-Many Relationship)
        // ========================================================================================
        
        // CRITICAL: Index for user-based queries - MOST IMPORTANT INDEX
        // Used in: WHERE bandxuser.userid = X (every user-specific band query)
        // Impact: Instant user band filtering - eliminates full table scans
        $this->db->query("CREATE INDEX idx_userid ON bandxuser (userid);");
        
        // CRITICAL: Index for band relationship - used in JOINs
        // Used in: JOIN bandxuser ON bandxuser.bandid = bands.id
        // Impact: Fast JOIN performance for band-user relationships
        $this->db->query("CREATE INDEX idx_bandid ON bandxuser (bandid);");
        
        // CRITICAL: Index for active band filtering
        // Used in: WHERE bandxuser.active = 1 (active bands only)
        // Impact: Fast active/inactive band filtering
        $this->db->query("CREATE INDEX idx_active ON bandxuser (active);");
        
        // PERFORMANCE: Composite index for user + active bands (most common query)
        // Used in: WHERE userid = X AND active = 1 (get user's active bands)
        // Impact: Single index scan for most frequent query pattern
        $this->db->query("CREATE INDEX idx_userid_active ON bandxuser (userid, active);");
        
        // PERFORMANCE: Composite index for user + band relationship
        // Used for: User-specific band lookups and validation
        // Impact: Fast user+band combination queries
        $this->db->query("CREATE INDEX idx_userid_bandid ON bandxuser (userid, bandid);");
        
        // PERFORMANCE: Award-specific indexes for common awards
        // These fields are frequently filtered for award tracking
        
        // DXCC Award filtering
        $this->db->query("CREATE INDEX idx_dxcc ON bandxuser (dxcc);");
        
        // IOTA Award filtering  
        $this->db->query("CREATE INDEX idx_iota ON bandxuser (iota);");
        
        // WAS (Worked All States) Award filtering
        $this->db->query("CREATE INDEX idx_was ON bandxuser (was);");
        
        // CQ Magazine Awards filtering
        $this->db->query("CREATE INDEX idx_cq ON bandxuser (cq);");
        
        // PERFORMANCE: Composite index for user + award filtering
        // Used in: WHERE userid = X AND [award] = 1
        // Most common award lookup pattern
        $this->db->query("CREATE INDEX idx_userid_dxcc ON bandxuser (userid, dxcc);");
        $this->db->query("CREATE INDEX idx_userid_was ON bandxuser (userid, was);");
        $this->db->query("CREATE INDEX idx_userid_iota ON bandxuser (userid, iota);");
    }

    public function down()
    {
        // Drop bands table indexes
        $this->db->query("DROP INDEX idx_band ON bands;");
        $this->db->query("DROP INDEX idx_bandgroup ON bands;");
        
        // Drop bandxuser table indexes
        $this->db->query("DROP INDEX idx_userid ON bandxuser;");
        $this->db->query("DROP INDEX idx_bandid ON bandxuser;");
        $this->db->query("DROP INDEX idx_active ON bandxuser;");
        $this->db->query("DROP INDEX idx_userid_active ON bandxuser;");
        $this->db->query("DROP INDEX idx_userid_bandid ON bandxuser;");
        $this->db->query("DROP INDEX idx_dxcc ON bandxuser;");
        $this->db->query("DROP INDEX idx_iota ON bandxuser;");
        $this->db->query("DROP INDEX idx_was ON bandxuser;");
        $this->db->query("DROP INDEX idx_cq ON bandxuser;");
        $this->db->query("DROP INDEX idx_userid_dxcc ON bandxuser;");
        $this->db->query("DROP INDEX idx_userid_was ON bandxuser;");
        $this->db->query("DROP INDEX idx_userid_iota ON bandxuser;");
    }
}