<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Sends Cloudlog mail with the protocol chosen on the Email settings page.
	sendmail uses PHP mail(), smtp uses the saved SMTP server, and ses calls the Amazon SES API.
*/

class Mailer {

	public function send($to, $subject, $message, $reply_to = '', $reply_to_name = '') {
		$CI =& get_instance();
		$protocol = (string) $CI->optionslib->get_option('emailProtocol');

		if ($protocol === '') {
			return array('sent' => false, 'error' => 'Email is not configured.');
		}
		if ($protocol === 'ses') {
			$CI->load->library('ses_mail');
			$result = $CI->ses_mail->send(array(
				'region' => $CI->optionslib->get_option('sesRegion'),
				'access_key' => $CI->optionslib->get_option('sesAccessKey'),
				'secret_key' => $CI->optionslib->get_option('sesSecretKey'),
				'from' => $CI->optionslib->get_option('emailAddress'),
				'from_name' => $CI->optionslib->get_option('emailSenderName'),
			), array(
				'to' => $to,
				'subject' => $subject,
				'body' => $message,
				'reply_to' => $reply_to,
				'reply_to_name' => $reply_to_name,
			));
			return array('sent' => $result['sent'], 'error' => $result['error']);
		}

		return $this->send_with_codeigniter($to, $subject, $message, $reply_to, $reply_to_name, $protocol);
	}

	private function send_with_codeigniter($to, $subject, $message, $reply_to, $reply_to_name, $protocol) {
		$CI =& get_instance();
		$CI->load->library('email');

		if ($protocol === 'smtp') {
			$config = array(
				'protocol' => 'smtp',
				'smtp_crypto' => $CI->optionslib->get_option('smtpEncryption'),
				'smtp_host' => $CI->optionslib->get_option('smtpHost'),
				'smtp_port' => $CI->optionslib->get_option('smtpPort'),
				'smtp_user' => $CI->optionslib->get_option('smtpUsername'),
				'smtp_pass' => $CI->optionslib->get_option('smtpPassword'),
				'crlf' => "\r\n",
				'newline' => "\r\n",
			);
		} else {
			// The Email page calls this Sendmail. Existing sends left CodeIgniter on PHP mail().
			$config = array(
				'protocol' => 'mail',
			);
		}

		$CI->email->clear();
		$CI->email->initialize($config);
		$CI->email->from($CI->optionslib->get_option('emailAddress'), $CI->optionslib->get_option('emailSenderName'));
		$CI->email->to($to);
		if ($reply_to !== '') {
			$CI->email->reply_to($reply_to, $reply_to_name);
		}
		$CI->email->subject($subject);
		$CI->email->message($message);

		if (!$CI->email->send(FALSE)) {
			$error = $CI->email->print_debugger(array('headers'));
			$CI->email->clear();
			return array('sent' => false, 'error' => $error);
		}

		$CI->email->clear();
		return array('sent' => true, 'error' => '');
	}
}
