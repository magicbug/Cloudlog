<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_station_tables_performance_indexes extends CI_Migration {

    public function up()
    {
        // ========================================================================================
        // STATION_LOGBOOKS_RELATIONSHIP TABLE INDEXES
        // ========================================================================================
        
        // Critical: Individual lookup indexes for relationship queries
        // Used in: list_logbook_relationships() - WHERE station_logbook_id = X
        $this->db->query("CREATE INDEX idx_station_logbook_id ON station_logbooks_relationship (station_logbook_id);");
        
        // Used for reverse lookups: finding which logbooks contain a station
        $this->db->query("CREATE INDEX idx_station_location_id ON station_logbooks_relationship (station_location_id);");
        
        // ========================================================================================
        // STATION_PROFILE TABLE INDEXES
        // ========================================================================================
        
        // OQRS (Online QSL Request System) Performance
        // Used in: WHERE station_profile.oqrs = "1" 
        $this->db->query("CREATE INDEX idx_oqrs ON station_profile (oqrs);");
        
        // QRZ API Integration Performance  
        // Used in: WHERE coalesce(qrzapikey, '') <> '' 
        // Note: Index helps with NULL checks and non-empty value filtering
        $this->db->query("CREATE INDEX idx_qrzapikey ON station_profile (qrzapikey);");
        
        // WebADIF API Integration Performance
        // Used in: WHERE COALESCE(webadifapikey, '') <> ''
        $this->db->query("CREATE INDEX idx_webadifapikey ON station_profile (webadifapikey);");
        
        // HRDLog Integration Performance - Username field
        // Used in: WHERE coalesce(hrdlog_username, '') <> ''
        $this->db->query("CREATE INDEX idx_hrdlog_username ON station_profile (hrdlog_username);");
        
        // HRDLog Integration Performance - Code field  
        // Used in: WHERE coalesce(hrdlog_code, '') <> ''
        $this->db->query("CREATE INDEX idx_hrdlog_code ON station_profile (hrdlog_code);");
        
        // Station Callsign Lookup Performance
        // Used for station identification and OQRS queries
        $this->db->query("CREATE INDEX idx_station_callsign ON station_profile (station_callsign);");
        
        // Station Active Status Performance
        // Used for filtering active vs inactive stations
        $this->db->query("CREATE INDEX idx_station_active ON station_profile (station_active);");
        
        // Composite index for HRDLog complete integration check
        // Used in: WHERE coalesce(hrdlog_username, '') <> '' AND coalesce(hrdlog_code, '') <> ''
        $this->db->query("CREATE INDEX idx_hrdlog_complete ON station_profile (hrdlog_username, hrdlog_code);");
    }

    public function down()
    {
        // Drop station_logbooks_relationship indexes
        $this->db->query("DROP INDEX idx_station_logbook_id ON station_logbooks_relationship;");
        $this->db->query("DROP INDEX idx_station_location_id ON station_logbooks_relationship;");
        
        // Drop station_profile indexes
        $this->db->query("DROP INDEX idx_oqrs ON station_profile;");
        $this->db->query("DROP INDEX idx_qrzapikey ON station_profile;");
        $this->db->query("DROP INDEX idx_webadifapikey ON station_profile;");
        $this->db->query("DROP INDEX idx_hrdlog_username ON station_profile;");
        $this->db->query("DROP INDEX idx_hrdlog_code ON station_profile;");
        $this->db->query("DROP INDEX idx_station_callsign ON station_profile;");
        $this->db->query("DROP INDEX idx_station_active ON station_profile;");
        $this->db->query("DROP INDEX idx_hrdlog_complete ON station_profile;");
    }
}