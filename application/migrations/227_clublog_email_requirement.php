<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_clublog_email_requirement extends CI_Migration
{
	public function up()
	{
		// This migration updates any existing Clublog usernames that are not valid email addresses
		// and adds a comment to remind users of the email requirement
		
		// Get all users with Clublog credentials
		$query = $this->db->query("SELECT user_id, user_clublog_name FROM users WHERE user_clublog_name IS NOT NULL AND user_clublog_name != ''");
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				// Check if the username is already a valid email
				if (!filter_var($row->user_clublog_name, FILTER_VALIDATE_EMAIL)) {
					// Log the users that need to update their credentials
					log_message('info', 'Clublog migration: User ID ' . $row->user_id . ' has non-email username: ' . $row->user_clublog_name . ' - user needs to update credentials');
					
					// Clear the clublog credentials so they're forced to re-enter them as email
					$this->db->query("UPDATE users SET user_clublog_name = NULL, user_clublog_password = NULL WHERE user_id = ?", array($row->user_id));
				}
			}
		}
		
		log_message('info', 'Clublog migration completed: All non-email usernames have been cleared and users will need to re-enter their credentials with valid email addresses');
	}

	public function down()
	{
		// This migration cannot be reversed as we don't store the original callsign usernames
		log_message('info', 'Clublog email requirement migration cannot be reversed');
	}
}