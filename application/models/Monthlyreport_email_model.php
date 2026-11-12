<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Opt-in monthly activity report email: preference, logbook access, send log, and sending.
*/
class Monthlyreport_email_model extends CI_Model {

	const OPTION_TYPE = 'monthly_report';
	const OPTION_NAME = 'email';
	const LIST_CAP = 25;

	public function email_configured() {
		return $this->optionslib->get_option('emailProtocol') != '';
	}

	/**
	 * @return array enabled (bool), logbook_id (int|null)
	 */
	public function get_preference($user_id) {
		$this->load->model('user_options_model');
		$pref = array('enabled' => false, 'logbook_id' => null);
		$rows = $this->user_options_model->get_options(self::OPTION_TYPE, array('option_name' => self::OPTION_NAME), $user_id)->result();
		foreach ($rows as $row) {
			if ($row->option_key == 'enabled') {
				$pref['enabled'] = ($row->option_value === '1');
			} elseif ($row->option_key == 'logbook_id' && $row->option_value !== '') {
				$pref['logbook_id'] = (int) $row->option_value;
			}
		}
		return $pref;
	}

	public function save_preference($user_id, $enabled, $logbook_id) {
		$this->load->model('user_options_model');
		$this->user_options_model->set_option(self::OPTION_TYPE, self::OPTION_NAME, array(
			'enabled' => $enabled ? '1' : '0',
			'logbook_id' => (string) (int) $logbook_id,
		), $user_id);
	}

	/**
	 * Owner or shared with the user, independent of the session.
	 */
	public function user_can_access_logbook($user_id, $logbook_id) {
		$this->db->from('station_logbooks');
		$this->db->join('station_logbooks_permissions slp',
			'slp.logbook_id = station_logbooks.logbook_id AND slp.user_id = '.$this->db->escape((int) $user_id),
			'left');
		$this->db->where('station_logbooks.logbook_id', (int) $logbook_id);
		$this->db->group_start();
			$this->db->where('station_logbooks.user_id', (int) $user_id);
			$this->db->or_where('slp.user_id', (int) $user_id);
		$this->db->group_end();
		return $this->db->count_all_results() > 0;
	}

	/**
	 * Logbooks_model::find_name() only resolves logbooks the session user owns.
	 */
	public function logbook_name($logbook_id) {
		$row = $this->db->select('logbook_name')->where('logbook_id', (int) $logbook_id)->get('station_logbooks')->row();
		return $row ? $row->logbook_name : '';
	}

	/**
	 * Opted-in users with no log row for $period, then users whose last attempt failed.
	 */
	public function get_pending_users($period, $limit) {
		$sql = "SELECT u.user_id, u.user_email, u.user_firstname, u.user_callsign, lb.option_value AS logbook_id
			FROM ".$this->config->item('auth_table')." u
			JOIN user_options en ON en.user_id = u.user_id AND en.option_type = ? AND en.option_name = ? AND en.option_key = 'enabled' AND en.option_value = '1'
			LEFT JOIN user_options lb ON lb.user_id = u.user_id AND lb.option_type = ? AND lb.option_name = ? AND lb.option_key = 'logbook_id'
			LEFT JOIN monthly_report_email_log l ON l.user_id = u.user_id AND l.period = ?
			WHERE u.user_email IS NOT NULL AND u.user_email != ''
			AND (l.id IS NULL OR l.status = 'error')
			ORDER BY (l.id IS NULL) DESC, u.user_id ASC
			LIMIT ".(int) $limit;
		return $this->db->query($sql, array(
			self::OPTION_TYPE, self::OPTION_NAME,
			self::OPTION_TYPE, self::OPTION_NAME,
			$period,
		))->result();
	}

	public function log_result($user_id, $period, $logbook_id, $status, $error = null) {
		$sql = "INSERT INTO monthly_report_email_log (user_id, period, logbook_id, status, sent_at, error)
			VALUES (?, ?, ?, ?, ?, ?)
			ON DUPLICATE KEY UPDATE logbook_id = VALUES(logbook_id), status = VALUES(status), sent_at = VALUES(sent_at), error = VALUES(error)";
		$this->db->query($sql, array(
			(int) $user_id,
			$period,
			$logbook_id === null ? null : (int) $logbook_id,
			$status,
			gmdate('Y-m-d H:i:s'),
			$error,
		));
	}

	/**
	 * Build and send the report for one user and logbook.
	 *
	 * @return array status (sent, skipped_empty, error), error (string|null), total_qsos (int)
	 */
	public function send_report($user, $logbook_id, $year, $month) {
		$this->load->model('monthlyreport_model');
		$this->load->model('logbooks_model');

		$locations = $this->logbooks_model->list_logbook_relationships($logbook_id);
		if (empty($locations)) {
			return array('status' => 'skipped_empty', 'error' => null, 'total_qsos' => 0);
		}

		$report = $this->monthlyreport_model->generate_report($locations, $year, $month);
		if ((int) $report['total_qsos'] === 0) {
			return array('status' => 'skipped_empty', 'error' => null, 'total_qsos' => 0);
		}

		$month_name = date('F', mktime(0, 0, 0, $month, 1, $year));
		$new_dxcc_count = count($report['new_dxcc']);

		$subject = 'Cloudlog: '.$month_name.' '.$year.' - '.$report['total_qsos'].' QSO'.($report['total_qsos'] == 1 ? '' : 's');
		if ($new_dxcc_count > 0) {
			$subject .= ', '.$new_dxcc_count.' new DXCC';
		}

		$message = $this->load->view('email/monthly_report.php', array(
			'user_firstname' => $user->user_firstname,
			'user_callsign' => $user->user_callsign,
			'logbook_name' => $this->logbook_name($logbook_id),
			'month_name' => $month_name,
			'year' => $year,
			'report' => $report,
			'list_cap' => self::LIST_CAP,
			'report_url' => site_url('monthlyreport'),
		), TRUE);

		$this->load->library('mailer');
		$result = $this->mailer->send($user->user_email, $subject, $message);

		if (!$result['sent']) {
			$error = strip_tags($result['error']);
			log_message('error', 'Monthly report email to user '.$user->user_id.' failed: '.$error);
			return array('status' => 'error', 'error' => $error, 'total_qsos' => (int) $report['total_qsos']);
		}

		return array('status' => 'sent', 'error' => null, 'total_qsos' => (int) $report['total_qsos']);
	}
}
