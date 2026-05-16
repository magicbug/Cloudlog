<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controls the interaction with the QRZCALL.EU Premium XML API.

	QRZCALL.EU is a QRZ-compatible callsign database. Its XML feed
	(/v1/pub/callsign_xml.php) returns the same field names as QRZ.com so
	this library mirrors the QRZ.com integration almost identically — but
	uses a single long-lived Personal Access Token (PAT) instead of a
	callsign/password → session-key dance.

	Users generate a PAT at https://qrzcall.eu/ → My Profile → Account →
	API Tokens. In Cloudlog they select "QRZCALL.EU" as their Callbook
	Provider in Account Settings and paste the PAT into the Callbook
	Password field (stored encrypted, per user, like the QRZ password);
	the Callbook Username field is left blank. The library sends the PAT
	as "Authorization: Bearer pat_…" on every lookup. Tokens are revocable
	per-logger, so a compromised Cloudlog install can be locked out
	without disturbing other clients.

	Access tier: PAT generation requires a Data or Extra subscription on
	QRZCALL.EU. The library doesn't need to know about subscription state —
	the upstream endpoint enforces it and returns 401/403 if absent.

	Sign-up + token UI: https://qrzcall.eu/
*/

class Qrzcall {

	public $callbookname = 'QRZCALL';

	// QRZCALL.EU's QRZ-compatible premium XML endpoint.
	const XML_URL = 'https://api.qrzcall.eu/v1/pub/callsign_xml.php';

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * Look up a callsign with a Personal Access Token.
	 *
	 * @param string $callsign     Callsign to query.
	 * @param string $token        The user's "pat_…" Personal Access Token.
	 * @param bool   $use_fullname True ⇒ "Firstname Lastname"; false ⇒ "Firstname" only.
	 * @return array Same shape as Qrz::search() — downstream Cloudlog code (QSO entry
	 *               form, etc.) needs no changes.
	 */
	public function search($callsign, $token, $use_fullname = false) {
		$data = null;
		try {
			$url = self::XML_URL . '?callsign=' . urlencode($callsign);

			$ua = 'Cloudlog/' . $this->CI->config->item('app_version');

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_USERAGENT, $ua);
			$body     = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($httpcode == 401) {
				log_message('debug', 'QRZCALL.EU search 401 for ' . $callsign . ' — invalid or revoked token');
				$data['error'] = 'Invalid or revoked QRZCALL.EU API token';
				return $data;
			}

			if ($httpcode == 403) {
				$data['error'] = 'QRZCALL.EU subscription required (Data or Extra tier)';
				return $data;
			}

			if ($httpcode == 404) {
				$data['error'] = 'Callsign not found';
				return $data;
			}

			if ($httpcode != 200) {
				log_message('debug', 'QRZCALL.EU search for ' . $callsign . ' returned HTTP ' . $httpcode);
				$data['error'] = 'Problems with qrzcall.eu communication';
				return $data;
			}

			$xml = simplexml_load_string($body);
			if (!$xml || !isset($xml->Callsign)) {
				$data['error'] = isset($xml->Error) ? (string)$xml->Error : 'Empty response';
				return $data;
			}

			// QRZCALL.EU's XML uses identical field names to QRZ.com — we mirror the
			// Qrz::search() field mapping exactly so downstream code needs no changes.
			$data['callsign']  = (string)$xml->Callsign->call;
			$data['name']      = (string)$xml->Callsign->fname;
			$data['name_last'] = (string)$xml->Callsign->name;

			if ($use_fullname === true) {
				$data['name'] = trim($data['name'] . ' ' . $data['name_last']);
			} else {
				$data['name'] = trim($data['name']);
			}

			// Trim grid to 8 chars (max useful precision) — same rule as QRZ provider
			$grid = (string)$xml->Callsign->grid;
			$data['gridsquare'] = strlen($grid) > 8 ? substr($grid, 0, 8) : $grid;

			$data['city']      = (string)$xml->Callsign->addr2;   // backward-compat alias
			$data['addr1']     = (string)$xml->Callsign->addr1;
			$data['addr2']     = (string)$xml->Callsign->addr2;
			$data['zip']       = (string)$xml->Callsign->zip;
			$data['lat']       = (string)$xml->Callsign->lat;
			$data['long']      = (string)$xml->Callsign->lon;     // backward-compat alias
			$data['lon']       = (string)$xml->Callsign->lon;
			$data['country']   = (string)$xml->Callsign->country;
			$data['dxcc']      = (string)$xml->Callsign->dxcc;
			$data['iota']      = (string)$xml->Callsign->iota;
			$data['qslmgr']    = (string)$xml->Callsign->qslmgr;
			$data['image']     = (string)$xml->Callsign->image;
			$data['email']     = (string)$xml->Callsign->email;
			$data['lotw']      = (string)$xml->Callsign->lotw;
			$data['eqsl']      = (string)$xml->Callsign->eqsl;
			$data['mqsl']      = (string)$xml->Callsign->mqsl;
			$data['cqzone']    = (string)$xml->Callsign->cqzone;
			$data['ituzone']   = (string)$xml->Callsign->ituzone;
			$data['ituz']      = $data['ituzone'];                 // backward-compat alias
			$data['cqz']       = $data['cqzone'];                  // backward-compat alias
			$data['xref']      = (string)$xml->Callsign->xref;
			$data['aliases']   = (string)$xml->Callsign->aliases;
			$data['ccode']     = (string)$xml->Callsign->ccode;
			$data['state']     = (string)$xml->Callsign->state;
			$data['county']    = (string)$xml->Callsign->county;
			$data['fips']      = (string)$xml->Callsign->fips;
			$data['land']      = (string)$xml->Callsign->land;
			$data['efdate']    = (string)$xml->Callsign->efdate;
			$data['expdate']   = (string)$xml->Callsign->expdate;
			$data['p_call']    = (string)$xml->Callsign->p_call;
			$data['class']     = (string)$xml->Callsign->class;
			$data['codes']     = (string)$xml->Callsign->codes;
			$data['url']       = (string)$xml->Callsign->url;
			$data['bio']       = (string)$xml->Callsign->bio;
			$data['biodate']   = (string)$xml->Callsign->biodate;
			$data['imageinfo'] = (string)$xml->Callsign->imageinfo;
			$data['moddate']   = (string)$xml->Callsign->moddate;
			$data['geoloc']    = (string)$xml->Callsign->geoloc;
			$data['born']      = (string)$xml->Callsign->born;
			$data['nickname']  = (string)$xml->Callsign->nickname;

			$data['us_county'] = ($data['country'] === 'United States') ? $data['county'] : null;

		} finally {
			return $data;
		}
	}

	public function sourcename() {
		return $this->callbookname;
	}

}
