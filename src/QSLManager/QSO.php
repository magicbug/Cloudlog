<?php
declare(strict_types=1);

namespace Cloudlog\QSLManager;

use DateTime;
use DateTimeZone;
use DomainException;

class QSO
{
	private string $qsoID;
	private string $qsoDateTime;
	private string $de;
	private string $dx;
	private string $mode;
	private string $submode;
	private ?string $band;
	private string $bandRX;
	private string $rstR;
	private string $rstS;
	private string $propagationMode;
	private string $satelliteMode;
	private string $satelliteName;
	private string $name;
	private string $email;
	private string $address;
	private string $deGridsquare;
	private string $deIOTA;
	private string $deSig;
	private string $deSigInfo;
	private string $deIOTAIslandID;
	private string $deSOTAReference;
	private string $deWWFFReference;
	/** Awards */
	private string $cqzone;
	private string $state;
	private string $dxcc;
	private string $iota;
	/** @var string[] */
	private string $deVUCCGridsquares;
	private string $dxGridsquare;
	private string $dxIOTA;
	private string $dxSig;
	private string $dxSigInfo;
	private string $dxDARCDOK;
	private string $dxSOTAReference;
	private string $dxPOTAReference;
	private string $dxWWFFReference;
	/** @var string[] */
	private string $dxVUCCGridsquares;
	private string $QSLMsg;
	private ?DateTime $QSLReceivedDate;
	private string $QSLReceived;
	private string $QSLReceivedVia;
	private ?DateTime $QSLSentDate;
	private string $QSLSent;
	private string $QSLSentVia;
	private string $QSLVia;
	private ?DateTime $end;
	/** QSL **/
	private string $qsl;
	private string $lotw;
	private string $eqsl;
	/** Lotw callsign info **/
	private string $callsign;
	private string $lastupload;
	private string $lotw_hint;
	private string $operator;

	/**
	 * @param array $data Does no validation, it's assumed to be a row from the database in array format
	 */
	public function __construct(array $data)
	{
		$requiredKeys = [
			'COL_PRIMARY_KEY',
			'COL_ADDRESS',
			'COL_BAND',
			'COL_BAND_RX',
			'COL_CALL',
			'COL_EMAIL',
			'COL_GRIDSQUARE',
			'COL_IOTA',
			'COL_MODE',
			'COL_MY_GRIDSQUARE',
			'COL_MY_IOTA',
			'COL_MY_SIG',
			'COL_MY_SIG_INFO',
			'COL_NAME',
			'COL_PROP_MODE',
			'COL_QSLMSG',
			'COL_QSLRDATE',
			'COL_QSLSDATE',
			'COL_QSL_RCVD',
			'COL_QSL_RCVD_VIA',
			'COL_QSL_SENT',
			'COL_QSL_SENT_VIA',
			'COL_QSL_VIA',
			'COL_RST_RCVD',
			'COL_RST_SENT',
			'COL_SAT_MODE',
			'COL_SAT_NAME',
			'COL_SIG',
			'COL_SIG_INFO',
			'COL_STATION_CALLSIGN',
			'COL_TIME_ON',
			'COL_DARC_DOK',
			'COL_MY_IOTA_ISLAND_ID',
			'COL_MY_SOTA_REF',
			'COL_MY_VUCC_GRIDS',
			'COL_SOTA_REF',
			'COL_POTA_REF',
			'COL_WWFF_REF',
			'COL_SUBMODE',
			'COL_VUCC_GRIDS',
			'COL_CQZ',
			'COL_STATE',
			'COL_COUNTRY',
			'COL_IOTA',
			'COL_OPERATOR',
		];


		foreach ($requiredKeys as $requiredKey) {
			if (!array_key_exists($requiredKey, $data)) {
				throw new DomainException("Required key $requiredKey does not exist");
			}
		}

		$this->qsoID = $data['COL_PRIMARY_KEY'];

		$CI =& get_instance();
		// Get Date format
		if($CI->session->userdata('user_date_format')) {
			// If Logged in and session exists
			$custom_date_format = $CI->session->userdata('user_date_format');
		} else {
			// Get Default date format from /config/cloudlog.php
			$custom_date_format = $CI->config->item('qso_date_format');
		}
		$this->qsoDateTime = date($custom_date_format . " H:i", strtotime($data['COL_TIME_ON']));

		$this->de = $data['station_callsign'];
		$this->dx = $data['COL_CALL'];

		$this->mode = $data['COL_MODE'] ?? '';
		$this->submode = $data['COL_SUBMODE'] ?? '';
		$this->band = $data['COL_BAND'];
		$this->bandRX = $data['COL_BAND_RX'] ?? '';
		$this->rstR = $data['COL_RST_RCVD'];
		$this->rstS = $data['COL_RST_SENT'];
		$this->propagationMode = $data['COL_PROP_MODE'] ?? '';
		$this->satelliteMode = $data['COL_SAT_MODE'] != '' ? (strlen($data['COL_SAT_MODE']) == 2 ? (strtoupper($data['COL_SAT_MODE'][0]).'/'.strtoupper($data['COL_SAT_MODE'][1])) : strtoupper($data['COL_SAT_MODE'])) : '';
		$this->satelliteName = $data['COL_SAT_NAME'] ?? '';

		$this->name = $data['COL_NAME'] ?? '';
		$this->email = $data['COL_EMAIL'] ?? '';
		$this->address = $data['COL_ADDRESS'] ?? '';

		$this->deGridsquare = $data['station_gridsquare'] ?? '';
		$this->deIOTA = $data['station_iota'] ?? '';
		$this->deSig = $data['station_sig'] ?? '';
		$this->deSigInfo = $data['station_sig_info'] ?? '';
		$this->deIOTAIslandID = $data['COL_MY_IOTA_ISLAND_ID'] ?? '';
		$this->deSOTAReference = $data['station_sota'] ?? '';
		$this->deWWFFReference = $data['station_wwff'] ?? '';

		$this->deVUCCGridsquares = $data['COL_MY_VUCC_GRIDS'] ?? '';

		$this->dxGridsquare = $data['COL_GRIDSQUARE'] ?? '';
		$this->dxIOTA = $data['COL_IOTA'] ?? '';
		$this->dxSig = $data['COL_SIG'] ?? '';
		$this->dxSigInfo = $data['COL_SIG_INFO'] ?? '';
		$this->dxDARCDOK = $data['COL_DARC_DOK'] ?? '';

		$this->dxSOTAReference = $data['COL_SOTA_REF'] ?? '';
		$this->dxPOTAReference = $data['COL_POTA_REF'] ?? '';
		$this->dxWWFFReference = $data['COL_WWFF_REF'] ?? '';

		$this->dxVUCCGridsquares = $data['COL_VUCC_GRIDS'] ?? '';

		$this->QSLMsg = $data['COL_QSLMSG'] ?? '';

		$this->QSLReceivedDate = ($data['COL_QSLRDATE'] === null) ? null : DateTime::createFromFormat("Y-m-d H:i:s", $data['COL_QSLRDATE'], new DateTimeZone('UTC'));
		$this->QSLReceived = ($data['COL_QSL_RCVD'] === null) ? '' : $data['COL_QSL_RCVD'];
		$this->QSLReceivedVia = ($data['COL_QSL_RCVD_VIA'] === null) ? '' : $data['COL_QSL_RCVD_VIA'];
		$this->QSLSentDate = ($data['COL_QSLSDATE'] === null) ? null : DateTime::createFromFormat("Y-m-d H:i:s", $data['COL_QSLSDATE'], new DateTimeZone('UTC'));
		$this->QSLSent = ($data['COL_QSL_SENT'] === null) ? '' : $data['COL_QSL_SENT'];
		$this->QSLSentVia = ($data['COL_QSL_SENT_VIA'] === null) ? '' : $data['COL_QSL_SENT_VIA'];
		$this->QSLVia = ($data['COL_QSL_VIA'] === null) ? '' : $data['COL_QSL_VIA'];

		$this->qsl = $this->getQslString($data, $custom_date_format);
		$this->lotw = $this->getLotwString($data, $custom_date_format);
		$this->eqsl = $this->getEqslString($data, $custom_date_format);

		$this->cqzone = ($data['COL_CQZ'] === null) ? '' : $this->geCqLink($data['COL_CQZ']);
		$this->state = ($data['COL_STATE'] === null) ? '' :$data['COL_STATE'];
		$this->dxcc = (($data['name'] ?? null) === null) ? '- NONE -' : '<a href="javascript:spawnLookupModal('.$data['COL_DXCC'].',\'dxcc\');">'.ucwords(strtolower($data['name']), "- (/").'</a>';
		$this->iota = ($data['COL_IOTA'] === null) ? '' : $this->getIotaLink($data['COL_IOTA']);
		if (array_key_exists('end', $data)) {
			$this->end = ($data['end'] === null) ? null : DateTime::createFromFormat("Y-m-d", $data['end'], new DateTimeZone('UTC'));
		} else {
			$this->end = null;
		}
		$this->callsign = (($data['callsign'] ?? null) === null) ? '' : $data['callsign'];
		$this->lastupload = (($data['lastupload'] ?? null) === null) ? '' : date($custom_date_format . " H:i", strtotime($data['lastupload'] ?? null));
		$this->lotw_hint = $this->getLotwHint($data['lastupload'] ?? null);
		$this->operator = ($data['COL_OPERATOR'] === null) ? '' :$data['COL_OPERATOR'];
	}

	/**
	 * @return string
	 */
	function geCqLink($cqz): string
	{
		$cqz_link = '';
		if ($cqz <= '40') {
			return '<a href="javascript:spawnLookupModal('.$cqz.',\'cq\');">'.$cqz.'</a>';
		}
		return $cqz;
	}

	/**
	 * @return string
	 */
	function getLotwHint($lastupload): string
	{
		$lotw_hint = '';
		if ($lastupload !== null) {
			$diff = time();
			$diff = (time() - strtotime($lastupload)) / 86400;
			if ($diff > 365) {
				$lotw_hint = ' lotw_info_red';
			} elseif ($diff > 30) {
				$lotw_hint = ' lotw_info_orange';
			} elseif ($diff > 7) {
				$lotw_hint = ' lotw_info_yellow';
			}
		}
		return $lotw_hint;
	}

	/**
	 * @return string
	 */
	function getQSLString($data, $custom_date_format): string
	{
		$CI =& get_instance();

		$qslstring = '<span ';

		if ($data['COL_QSL_SENT'] != "N") {
			switch ($data['COL_QSL_SENT']) {
			case "Y":
				$qslstring .= "class=\"qsl-green\" data-bs-toggle=\"tooltip\" title=\"".$CI->lang->line('general_word_sent');
				break;
			case "Q":
				$qslstring .= "class=\"qsl-yellow\" data-bs-toggle=\"tooltip\" title=\"".$CI->lang->line('general_word_queued');
				break;
			case "R":
				$qslstring .= "class=\"qsl-yellow\" data-bs-toggle=\"tooltip\" title=\"".$CI->lang->line('general_word_requested');
				break;
			case "I":
				$qslstring .= "class=\"qsl-grey\" data-bs-toggle=\"tooltip\" title=\"".$CI->lang->line('general_word_invalid_ignore');
				break;
			default:
			$qslstring .= "class=\"qsl-red";
				break;
			}
			if ($data['COL_QSLSDATE'] != null) {
				$timestamp = strtotime($data['COL_QSLSDATE']);
				$qslstring .= " "  .($timestamp != '' ? date($custom_date_format, $timestamp) : '');
			}
		} else {
			$qslstring .= "class=\"qsl-red";
		}

		if ($data['COL_QSL_SENT_VIA'] != "") {
			switch ($data['COL_QSL_SENT_VIA']) {
				case "B":
					$qslstring .= " (" . $CI->lang->line('general_word_qslcard_bureau') . ")";
					break;
				case "D":
				$qslstring .= " (".$CI->lang->line('general_word_qslcard_direct').")";
					break;
				case "M":
				$qslstring .= " (".$CI->lang->line('general_word_qslcard_via').": ".($data['COL_QSL_VIA'] !="" ? $data['COL_QSL_VIA']:"n/a").")";
					break;
				case "E":
				$qslstring .= " (".$CI->lang->line('general_word_qslcard_electronic').")";
					break;
			}
		}

			$qslstring .= '">&#9650;</span><span ';

			if ($data['COL_QSL_RCVD'] != "N") {
				switch ($data['COL_QSL_RCVD']) {
					case "Y":
						$qslstring .= "class=\"qsl-green\" data-bs-toggle=\"tooltip\" title=\"".$CI->lang->line('general_word_received');
					break;
					case "Q":
						$qslstring .= "class=\"qsl-yellow\" data-bs-toggle=\"tooltip\" title=\"".$CI->lang->line('general_word_queued');
					break;
					case "R":
						$qslstring .= "class=\"qsl-yellow\" data-bs-toggle=\"tooltip\" title=\"".$CI->lang->line('general_word_requested');
					break;
					case "I":
						$qslstring .= "class=\"qsl-grey\" data-bs-toggle=\"tooltip\" title=\"".$CI->lang->line('general_word_invalid_ignore');
					break;
					default:
					$qslstring .= "class=\"qsl-red";
					break;
				}
				if ($data['COL_QSLRDATE'] != null) {
					$timestamp = strtotime($data['COL_QSLRDATE']);
					$qslstring .= " "  .($timestamp != '' ? date($custom_date_format, $timestamp) : '');
				}
			} else {
			$qslstring .= "class=\"qsl-red"; }
			if ($data['COL_QSL_RCVD_VIA'] != "") {
				switch ($data['COL_QSL_RCVD_VIA']) {
					case "B":
					$qslstring .= " (".$CI->lang->line('general_word_qslcard_bureau').")";
						break;
					case "D":
					$qslstring .= " (".$CI->lang->line('general_word_qslcard_direct').")";
						break;
					case "M":
					$qslstring .= " (Manager)";
						break;
					case "E":
					$qslstring .= " (".$CI->lang->line('general_word_qslcard_electronic').")";
						break;
				}
			}
			$qslstring .= '">&#9660;</span>';
			if ($data['qslcount'] ?? null != null) {
				$qslstring .= ' <a href="javascript:displayQsl('.$data['COL_PRIMARY_KEY'].');"><i class="fa fa-id-card"></i></a>';
			}
		return $qslstring;
	}

	/**
	 * @return string
	 */
	function getLotwString($data, $custom_date_format): string
	{
		$CI =& get_instance();

		$lotwstring = '<span ';

		if ($data['COL_LOTW_QSL_SENT'] == "Y") {
			$lotwstring .= "title=\"" . $CI->lang->line('lotw_short')." ".$CI->lang->line('general_word_sent');
			if ($data['COL_LOTW_QSLSDATE'] != null) {
				$timestamp = strtotime($data['COL_LOTW_QSLSDATE']);
				$lotwstring .= " ". ($timestamp != '' ? date($custom_date_format, $timestamp) : '');
			}
			$lotwstring .= "\" data-bs-toggle=\"tooltip\"";
		}

		$lotwstring .= ' class="lotw-' . (($data['COL_LOTW_QSL_SENT']=='Y') ? 'green' : 'red') . '">&#9650;</span>';
		$lotwstring .= '<span ';

		if ($data['COL_LOTW_QSL_RCVD'] == "Y") {
			$lotwstring .= "title=\"". $CI->lang->line('lotw_short') ." ". $CI->lang->line('general_word_received');

			if ($data['COL_LOTW_QSLRDATE'] != null) {
				$timestamp = strtotime($data['COL_LOTW_QSLRDATE']);
				$lotwstring .=  " ". ($timestamp != '' ? date($custom_date_format, $timestamp) : '');
			}

			$lotwstring .= "\" data-bs-toggle=\"tooltip\"";
		}

		$lotwstring .= ' class="lotw-' . (($data['COL_LOTW_QSL_RCVD']=='Y') ? 'green':'red') . '">&#9660;</span>';

		return $lotwstring;
	}

	/**
	 * @return string
	 */
	function getEqslString($data, $custom_date_format): string
	{
		$CI =& get_instance();

		$eqslstring = '<span ';

		if ($data['COL_EQSL_QSL_SENT'] == "Y") {
			$eqslstring .= "title=\"".$CI->lang->line('eqsl_short')." ".$CI->lang->line('general_word_sent');

			if ($data['COL_EQSL_QSLSDATE'] != null) {
				$timestamp = strtotime($data['COL_EQSL_QSLSDATE']);
				$eqslstring .=  " ".($timestamp!=''?date($custom_date_format, $timestamp):'');
			}

			$eqslstring .= "\" data-bs-toggle=\"tooltip\"";
		}

		$eqslstring .= ' class="eqsl-' . (($data['COL_EQSL_QSL_SENT'] =='Y') ? 'green':'red') . '">&#9650;</span><span ';

		if ($data['COL_EQSL_QSL_RCVD'] == "Y") {
			$eqslstring .= "title=\"".$CI->lang->line('eqsl_short')." ".$CI->lang->line('general_word_received');

			if ($data['COL_EQSL_QSLRDATE'] != null) {
				$timestamp = strtotime($data['COL_EQSL_QSLRDATE']);
				$eqslstring .= " ".($timestamp!=''?date($custom_date_format, $timestamp):'');
			}
			$eqslstring .= "\" data-bs-toggle=\"tooltip\"";
		}

		$eqslstring .= ' class="eqsl-' . (($data['COL_EQSL_QSL_RCVD'] =='Y')?'green':'red') . '">';

		if($data['COL_EQSL_QSL_RCVD'] =='Y') {
			$eqslstring .= '<a class="eqsl-green" href="' . site_url("eqsl/image/".$data['COL_PRIMARY_KEY']) . '" data-fancybox="images" data-width="528" data-height="336">&#9660;</a>';
		} else {
			$eqslstring .= '&#9660;';
		}

		$eqslstring .= '</span>';

		return $eqslstring;
	}

	/**
	 * @return string
	 */
	public function getQsoID(): string
	{
		return $this->qsoID;
	}

	/**
	 * @return DateTime
	 */
	public function getQsoDateTime(): string
	{
		return $this->qsoDateTime;
	}

	/**
	 * @return string
	 */
	public function getDe(): string
	{
		return $this->de;
	}

	/**
	 * @return string
	 */
	public function getDx(): string
	{
		return $this->dx;
	}

	/**
	 * @return string
	 */
	public function getMode(): string
	{
		return $this->mode;
	}

	/**
	 * @return string
	 */
	public function getSubmode(): string
	{
		return $this->submode;
	}

	/**
	 * @return string
	 */
	public function getBand(): string
	{
		return $this->band;
	}

	/**
	 * @return string
	 */
	public function getBandRX(): string
	{
		return $this->bandRX;
	}

	/**
	 * @return string
	 */
	public function getRstR(): string
	{
		return $this->rstR;
	}

	/**
	 * @return string
	 */
	public function getRstS(): string
	{
		return $this->rstS;
	}

	/**
	 * @return string
	 */
	public function getPropagationMode(): string
	{
		return $this->propagationMode;
	}

	/**
	 * @return string
	 */
	public function getSatelliteMode(): string
	{
		return $this->satelliteMode;
	}

	/**
	 * @return string
	 */
	public function getSatelliteName(): string
	{
		return $this->satelliteName;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @return string
	 */
	public function getAddress(): string
	{
		return $this->address;
	}

	/**
	 * @return string
	 */
	public function getDeGridsquare(): string
	{
		return $this->deGridsquare;
	}

	/**
	 * @return string
	 */
	public function getDeIOTA(): string
	{
		return $this->deIOTA;
	}

	/**
	 * @return string
	 */
	public function getDeSig(): string
	{
		return $this->deSig;
	}

	/**
	 * @return string
	 */
	public function getDeSigInfo(): string
	{
		return $this->deSigInfo;
	}

	/**
	 * @return string
	 */
	public function getDeIOTAIslandID(): string
	{
		return $this->deIOTAIslandID;
	}

	/**
	 * @return string
	 */
	public function getDeSOTAReference(): string
	{
		return $this->deSOTAReference;
	}

	/**
	 * @return string[]
	 */
	public function getDeVUCCGridsquares(): string
	{
		return $this->deVUCCGridsquares;
	}

	/**
	 * @return string
	 */
	public function getDxGridsquare(): string
	{
		return $this->dxGridsquare;
	}

	/**
	 * @return string
	 */
	public function getDxIOTA(): string
	{
		return $this->dxIOTA;
	}

	/**
	 * @return string
	 */
	public function getDxSig(): string
	{
		return $this->dxSig;
	}

	/**
	 * @return string
	 */
	public function getDxSigInfo(): string
	{
		return $this->dxSigInfo;
	}

	/**
	 * @return string
	 */
	public function getDxDARCDOK(): string
	{
		return $this->dxDARCDOK;
	}

	/**
	 * @return string
	 */
	public function getDxSOTAReference(): string
	{
		return $this->dxSOTAReference;
	}

	/**
	 * @return string
	 */
	public function getDxPOTAReference(): string
	{
		return $this->dxPOTAReference;
	}

	/**
	 * @return string
	 */
	public function getWWFFReference(): string
	{
		return $this->dxWWFFReference;
	}

	/**
	 * @return string[]
	 */
	public function getDxVUCCGridsquares(): string
	{
		return $this->dxVUCCGridsquares;
	}

	/**
	 * @return string
	 */
	public function getQSLMsg(): string
	{
		return $this->QSLMsg;
	}

	/**
	 * @return ?DateTime
	 */
	public function getQSLReceivedDate(): ?DateTime
	{
		return $this->QSLReceivedDate;
	}

	/**
	 * @return string
	 */
	public function getQSLReceived(): string
	{
		return $this->QSLReceived;
	}

	/**
	 * @return string
	 */
	public function getQSLReceivedVia(): string
	{
		return $this->QSLReceivedVia;
	}

	/**
	 * @return ?DateTime
	 */
	public function getQSLSentDate(): ?DateTime
	{
		return $this->QSLSentDate;
	}

	/**
	 * @return string
	 */
	public function getQSLSent(): string
	{
		return $this->QSLSent;
	}

	/**
	 * @return string
	 */
	public function getQSLSentVia(): string
	{
		return $this->QSLSentVia;
	}

	/**
	 * @return string
	 */
	public function getqsl(): string
	{
		return $this->qsl;
	}

	/**
	 * @return string
	 */
	public function getlotw(): string
	{
		return $this->lotw;
	}

	/**
	 * @return string
	 */
	public function geteqsl(): string
	{
		return $this->eqsl;
	}

	/**
	 * @return string
	 */
	public function getQSLVia(): string
	{
		return $this->QSLVia;
	}

	public function getDXCC(): string
	{
		return '<span id="dxcc">' . $this->dxcc . '</span>';
	}

	public function getCqzone(): string
	{
		return '<span id="cqzone">' . $this->cqzone . '</span>';
	}

	public function getState(): string
	{
		return '<span id="state">' . $this->state . '</span>';
	}

	public function getIOTA(): string
	{
		return '<span id="iota">' . $this->iota . '</span>';
	}

	public function getOperator(): string
	{
		return '<span id="operator">' . $this->operator . '</span>';
	}

	public function toArray(): array
	{
		return [
			'qsoID' => $this->qsoID,
			'qsoDateTime' => $this->qsoDateTime,
			'de' => $this->de,
			'dx' => $this->getDx(),
			'mode' => $this->getFormattedMode(),
			'rstS' => $this->rstS,
			'rstR' => $this->rstR,
			'band' => $this->getFormattedBand(),
			'deRefs' => $this->getFormattedDeRefs(),
			'dxRefs' => $this->getFormattedDxRefs(),
			'qslVia' => $this->QSLVia,
			'qsl' => $this->getqsl(),
			'lotw' => $this->getlotw(),
			'eqsl' => $this->geteqsl(),
			'qslMessage' => $this->getQSLMsg(),
			'name' => $this->getName(),
			'dxcc' => $this->getDXCC(),
			'state' => $this->getState(),
			'pota' => $this->dxPOTAReference,
			'operator' => $this->getOperator(),
			'cqzone' => $this->getCqzone(),
			'iota' => $this->getIOTA(),
			'end' => $this->end === null ? null : $this->end->format("Y-m-d"),
			'callsign' => $this->callsign,
			'lastupload' => $this->lastupload,
			'lotw_hint' => $this->lotw_hint,
		];
	}

	private function getFormattedMode(): string
	{
		if ($this->submode !== '') {
			return $this->submode;
		} else {
			return $this->mode;
		}
	}

	private function getFormattedBand(): string
	{
		$label = "";
		if ($this->propagationMode !== '') {
			$label .= $this->propagationMode;
			if ($this->satelliteName !== '') {
				$label .= " " . $this->satelliteName;
				if ($this->satelliteMode !== '') {
					$label .= " " . $this->satelliteMode;
				}
			}
		}
		$label .= " " . $this->band;
		if ($this->bandRX !== '' && $this->band !== '') {
			$label .= "/" . $this->bandRX;
		}
		return trim($label);
	}

	private function getFormattedDeRefs(): string
	{
		$includedInRefs=[];
		$refs = [];
		if ($this->deVUCCGridsquares !== '') {
			$refs[] = $this->deVUCCGridsquares;
		} else {
			if ($this->deGridsquare !== '') {
				$refs[] = $this->deGridsquare;
			}
		}
		if ($this->deIOTA !== '') {
			if ($this->deIOTAIslandID !== '') {
				$refs[] = "IOTA:" . $this->deIOTA . "(" . $this->deIOTAIslandID . ")";
			} else {
				$refs[] = "IOTA:" . $this->deIOTA;
			}
		}
		if ($this->deSOTAReference !== '') {
			$refs[] = "SOTA:" . $this->deSOTAReference;
		}
		if ($this->deWWFFReference !== '') {
			$includedInRefs[] = "WWFF";
			$refs[] = "WWFF:" . $this->deWWFFReference;
		}
		if ($this->deSig !== '') {
			if (!in_array($this->deSig, $includedInRefs)) {
				$refs[] = $this->deSig . ":" . $this->deSigInfo;
			}
		}
		return trim(implode(" ", $refs));
	}

	private function getFormattedDxRefs(): string
	{
		$includedInRefs=[];
		$refs = [];
		if ($this->dxVUCCGridsquares !== '') {
			$refs[] = '<span id="dxgrid">' . $this->dxVUCCGridsquares . '</span> ' .$this->getQrbLink($this->deGridsquare, $this->dxVUCCGridsquares, $this->dxGridsquare);
		} else if ($this->dxGridsquare !== '') {
			$refs[] = '<span id="dxgrid">' . $this->dxGridsquare . '</span> ' .$this->getQrbLink($this->deGridsquare, $this->dxVUCCGridsquares, $this->dxGridsquare);
		}
		if ($this->dxSOTAReference !== '') {
			$refs[] = "SOTA: " . '<span id="dxsota">' . $this->dxSOTAReference. '</span>';
		}
		if ($this->dxPOTAReference !== '') {
			$refs[] = "POTA: " . '<span id="dxpota">' . $this->dxPOTAReference. '</span>';
		}
		if ($this->dxWWFFReference !== '') {
			$includedInRefs[] = "WWFF";
			$refs[] = "WWFF: " . '<span id="dxwwff">' . $this->dxWWFFReference. '</span>';
		}
		if ($this->dxSig !== '') {
			if (!in_array($this->dxSig, $includedInRefs)) {
				$refs[] = $this->dxSig . ":" . $this->dxSigInfo;
			}
		}
		if ($this->dxDARCDOK !== '') {
			$refs[] = "DOK:" . $this->dxDARCDOK;
		}
		return implode(" ", $refs);
	}

	private function getFormattedQSLSent(): string
	{
		$showVia = false;
		$label = [];
		if ($this->QSLSent === "Y") {
			if ($this->QSLSentDate !== null) {
				$label[] = $this->QSLSentDate->format("Y-m-d");
			} else {
				$label[] = "Yes";
			}
			$showVia = true;
		} else if ($this->QSLSent === "N") {
			$label[] = "No";
		} else if ($this->QSLSent === "Q") {
			$label[] = "Queued";
			if ($this->QSLSentDate !== null) {
				$label[] = $this->QSLSentDate->format("Y-m-d");
			}
			$showVia = true;
		} else if ($this->QSLSent === "R") {
			$label[] = "Requested";
			if ($this->QSLSentDate !== null) {
				$label[] = $this->QSLSentDate->format("Y-m-d");
			}
			$showVia = true;
		}

		if ($showVia && $this->QSLSentVia !== '') {
			switch ($this->QSLSentVia) {
				case 'B':
					$label[] = "Bureau";
					break;
				case 'D':
					$label[] = "Direct";
					break;
				case 'E':
					$label[] = "Electronic";
					break;
				case 'M':
					$label[] = "Manager";
					break;
			}
		}

		return trim(implode(" ", $label));
	}

	private function getFormattedQSLReceived(): string
	{
		$showVia = false;
		$label = [];
		if ($this->QSLReceived === "Y") {
			if ($this->QSLReceivedDate !== null) {
				$label[] = $this->QSLReceivedDate->format("Y-m-d");
			} else {
				$label[] = "Yes";
			}
			$showVia = true;
		} else if ($this->QSLReceived === "N") {
			$label[] = "No";
		} else if ($this->QSLReceived === "Q") {
			$label[] = "Queued";
			if ($this->QSLReceivedDate !== null) {
				$label[] = $this->QSLReceivedDate->format("Y-m-d");
			}
			$showVia = true;
		} else if ($this->QSLReceived === "R") {
			$label[] = "Requested";
			if ($this->QSLReceivedDate !== null) {
				$label[] = $this->QSLReceivedDate->format("Y-m-d");
			}
			$showVia = true;
		}

		if ($showVia && $this->QSLReceivedVia !== '') {
			switch ($this->QSLReceivedVia) {
				case 'B':
					$label[] = "Bureau";
					break;
				case 'D':
					$label[] = "Direct";
					break;
				case 'E':
					$label[] = "Electronic";
					break;
				case 'M':
					$label[] = "Manager";
					break;
			}
		}

		return trim(implode(" ", $label));
	}

	private function getQrbLink($mygrid, $grid, $vucc) : string
	{
		if (!empty($grid)) {
			return '<a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $grid . '\')"><i class="fas fa-globe"></i></a>';
		} else if (!empty($vucc)) {
			return '<a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $vucc . '\')"><i class="fas fa-globe"></i></a>';
		}
		return '';
	}

	private function getIotaLink($iota) : string
	{
		if ($iota !== '') {
			return '<a href="javascript:spawnLookupModal(\''.$iota.'\',\'iota\');">'.$iota.'</a> <a href="https://www.iota-world.org/iotamaps/?grpref=' .$iota . '" target="_blank"><i class="fas fa-globe"></i></a>';
		}
		return '';
	}
}
