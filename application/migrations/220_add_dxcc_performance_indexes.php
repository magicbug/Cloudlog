<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Add additional performance indexes to DXCC tables
 * 
 * This migration adds indexes to improve DXCC table performance based on usage patterns:
 * 1. dxcc_entities.name - for country name lookups
 * 2. dxcc_entities.prefix - for prefix-based searches
 * 3. dxcc_master optimizations for geographic/band/continent queries
 * 4. Date-based indexes for historical DXCC data queries
 */
class Migration_add_dxcc_performance_indexes extends CI_Migration {

    public function up()
    {
        // Add index on dxcc_entities.name for country name lookups
        if ($this->db->table_exists('dxcc_entities')) {
            $this->db->query("ALTER TABLE `dxcc_entities` ADD INDEX `idx_name` (`name`)");
            
            // Add index on prefix for prefix-based searches
            $this->db->query("ALTER TABLE `dxcc_entities` ADD INDEX `idx_prefix` (`prefix`)");
            
            // Add composite index for continent and CQZ filtering
            $this->db->query("ALTER TABLE `dxcc_entities` ADD INDEX `idx_cont_cqz` (`cont`, `cqz`)");
            
            // Add index on end date for active entity filtering
            $this->db->query("ALTER TABLE `dxcc_entities` ADD INDEX `idx_end_date` (`end`)");
        }

        // Add additional indexes to dxcc_master for geographic queries
        if ($this->db->table_exists('dxcc_master')) {
            // Index for continent-based queries (very common in statistics)
            $this->db->query("ALTER TABLE `dxcc_master` ADD INDEX `idx_continent` (`Continent`)");
            
            // Index for CQZone queries
            $this->db->query("ALTER TABLE `dxcc_master` ADD INDEX `idx_cqzone` (`CQZone`)");
            
            // Index for ITU zone queries  
            $this->db->query("ALTER TABLE `dxcc_master` ADD INDEX `idx_ituzone` (`ITUZone`)");
            
            // Composite index for date range filtering (StartDate, EndDate)
            $this->db->query("ALTER TABLE `dxcc_master` ADD INDEX `idx_date_range` (`StartDate`, `EndDate`)");
            
            // Index on DXCCPrefix for direct prefix lookups
            $this->db->query("ALTER TABLE `dxcc_master` ADD INDEX `idx_dxccprefix` (`DXCCPrefix`)");
        }

        // Optimize dxcc_exceptions for ADIF-based lookups
        if ($this->db->table_exists('dxcc_exceptions')) {
            // Index on ADIF for joining with other tables
            $this->db->query("ALTER TABLE `dxcc_exceptions` ADD INDEX `idx_adif` (`adif`)");
            
            // Index on continent for continent-based filtering
            $this->db->query("ALTER TABLE `dxcc_exceptions` ADD INDEX `idx_cont` (`cont`)");
        }

        // Optimize dxcc_prefixes for ADIF-based lookups
        if ($this->db->table_exists('dxcc_prefixes')) {
            // Index on ADIF for joining with other tables
            $this->db->query("ALTER TABLE `dxcc_prefixes` ADD INDEX `idx_adif` (`adif`)");
            
            // Index on continent for continent-based filtering  
            $this->db->query("ALTER TABLE `dxcc_prefixes` ADD INDEX `idx_cont` (`cont`)");
            
            // Composite index for entity searches
            $this->db->query("ALTER TABLE `dxcc_prefixes` ADD INDEX `idx_entity_adif` (`entity`, `adif`)");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('dxcc_entities')) {
            $this->db->query("ALTER TABLE `dxcc_entities` DROP INDEX `idx_name`");
            $this->db->query("ALTER TABLE `dxcc_entities` DROP INDEX `idx_prefix`");
            $this->db->query("ALTER TABLE `dxcc_entities` DROP INDEX `idx_cont_cqz`");
            $this->db->query("ALTER TABLE `dxcc_entities` DROP INDEX `idx_end_date`");
        }

        if ($this->db->table_exists('dxcc_master')) {
            $this->db->query("ALTER TABLE `dxcc_master` DROP INDEX `idx_continent`");
            $this->db->query("ALTER TABLE `dxcc_master` DROP INDEX `idx_cqzone`");
            $this->db->query("ALTER TABLE `dxcc_master` DROP INDEX `idx_ituzone`");
            $this->db->query("ALTER TABLE `dxcc_master` DROP INDEX `idx_date_range`");
            $this->db->query("ALTER TABLE `dxcc_master` DROP INDEX `idx_dxccprefix`");
        }

        if ($this->db->table_exists('dxcc_exceptions')) {
            $this->db->query("ALTER TABLE `dxcc_exceptions` DROP INDEX `idx_adif`");
            $this->db->query("ALTER TABLE `dxcc_exceptions` DROP INDEX `idx_cont`");
        }

        if ($this->db->table_exists('dxcc_prefixes')) {
            $this->db->query("ALTER TABLE `dxcc_prefixes` DROP INDEX `idx_adif`");
            $this->db->query("ALTER TABLE `dxcc_prefixes` DROP INDEX `idx_cont`");
            $this->db->query("ALTER TABLE `dxcc_prefixes` DROP INDEX `idx_entity_adif`");
        }
    }
}