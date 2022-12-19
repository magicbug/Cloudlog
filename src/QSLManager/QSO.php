<?php
declare(strict_types=1);

namespace Cloudlog\QSLManager;

use DateTime;
use DateTimeZone;
use DomainException;

class QSO
{
	private string $qsoID;
	private DateTime $qsoDateTime;
	private string $de;
	private string $dx;
	private string $mode;
	private string $submode;
	private string $band;
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
	/** Awards */
	private string $cqzone;
	private string $state;
	private string $dxcc;
	private string $iota;
	/** @var string[] */
	private array $deVUCCGridsquares;
	private string $dxGridsquare;
	private string $dxIOTA;
	private string $dxSig;
	private string $dxSigInfo;
	private string $dxDARCDOK;
	private string $dxSOTAReference;
	/** @var string[] */
	private array $dxVUCCGridsquares;
	private string $QSLMsg;
	private ?DateTime $QSLReceivedDate;
	private string $QSLReceived;
	private string $QSLReceivedVia;
	private ?DateTime $QSLSentDate;
	private string $QSLSent;
	private string $QSLSentVia;
	private string $QSLVia;

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
			'COL_SUBMODE',
			'COL_VUCC_GRIDS',
			'COL_CQZ',
			'COL_STATE',
			'COL_COUNTRY',
			'COL_IOTA',
		];


		foreach ($requiredKeys as $requiredKey) {
			if (!array_key_exists($requiredKey, $data)) {
				throw new DomainException("Required key $requiredKey does not exist");
			}
		}

		$this->qsoID = $data['COL_PRIMARY_KEY'];

		$this->qsoDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $data['COL_TIME_ON'], new DateTimeZone("UTC"));

		$this->de = $data['COL_STATION_CALLSIGN'];
		$this->dx = $data['COL_CALL'];

		$this->mode = $data['COL_MODE'] ?? '';
		$this->submode = $data['COL_SUBMODE'] ?? '';
		$this->band = $data['COL_BAND'];
		$this->bandRX = $data['COL_BAND_RX'] ?? '';
		$this->rstR = $data['COL_RST_RCVD'];
		$this->rstS = $data['COL_RST_SENT'];
		$this->propagationMode = $data['COL_PROP_MODE'] ?? '';
		$this->satelliteMode = $data['COL_SAT_MODE'] ?? '';
		$this->satelliteName = $data['COL_SAT_NAME'] ?? '';

		$this->name = $data['COL_NAME'] ?? '';
		$this->email = $data['COL_EMAIL'] ?? '';
		$this->address = $data['COL_ADDRESS'] ?? '';

		$this->deGridsquare = $data['COL_MY_GRIDSQUARE'] ?? '';
		$this->deIOTA = $data['COL_MY_IOTA'] ?? '';
		$this->deSig = $data['COL_MY_SIG'] ?? '';
		$this->deSigInfo = $data['COL_MY_SIG_INFO'] ?? '';
		$this->deIOTAIslandID = $data['COL_MY_IOTA_ISLAND_ID'] ?? '';
		$this->deSOTAReference = $data['COL_MY_SOTA_REF'] ?? '';

		$this->deVUCCGridsquares = ($data['COL_MY_VUCC_GRIDS'] === null) ? [] : explode(",", $data['COL_MY_VUCC_GRIDS'] ?? '');

		$this->dxGridsquare = $data['COL_GRIDSQUARE'] ?? '';
		$this->dxIOTA = $data['COL_IOTA'] ?? '';
		$this->dxSig = $data['COL_SIG'] ?? '';
		$this->dxSigInfo = $data['COL_SIG_INFO'] ?? '';
		$this->dxDARCDOK = $data['COL_DARC_DOK'] ?? '';

		$this->dxSOTAReference = $data['COL_SOTA_REF'] ?? '';

		$this->dxVUCCGridsquares = ($data['COL_VUCC_GRIDS'] === null) ? [] : explode(",", $data['COL_VUCC_GRIDS'] ?? '');

		$this->QSLMsg = $data['COL_QSLMSG'] ?? '';

		$this->QSLReceivedDate = ($data['COL_QSLRDATE'] === null) ? null : DateTime::createFromFormat("Y-m-d H:i:s", $data['COL_QSLRDATE'], new DateTimeZone('UTC'));
		$this->QSLReceived = ($data['COL_QSL_RCVD'] === null) ? '' : $data['COL_QSL_RCVD'];
		$this->QSLReceivedVia = ($data['COL_QSL_RCVD_VIA'] === null) ? '' : $data['COL_QSL_RCVD_VIA'];
		$this->QSLSentDate = ($data['COL_QSLSDATE'] === null) ? null : DateTime::createFromFormat("Y-m-d H:i:s", $data['COL_QSLSDATE'], new DateTimeZone('UTC'));
		$this->QSLSent = ($data['COL_QSL_SENT'] === null) ? '' : $data['COL_QSL_SENT'];
		$this->QSLSentVia = ($data['COL_QSL_SENT_VIA'] === null) ? '' : $data['COL_QSL_SENT_VIA'];
		$this->QSLVia = ($data['COL_QSL_VIA'] === null) ? '' : $data['COL_QSL_VIA'];

		$this->cqzone = ($data['COL_CQZ'] === null) ? '' : $data['COL_CQZ'];
		$this->state = ($data['COL_STATE'] === null) ? '' :$data['COL_STATE'];
		$this->dxcc = ($data['COL_COUNTRY'] === null) ? '' :$data['COL_COUNTRY'];
		$this->iota = ($data['COL_IOTA'] === null) ? '' :$data['COL_IOTA'];
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
	public function getQsoDateTime(): DateTime
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
	public function getDeVUCCGridsquares(): array
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
	 * @return string[]
	 */
	public function getDxVUCCGridsquares(): array
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
	public function getQSLVia(): string
	{
		return $this->QSLVia;
	}

	public function getDXCC(): string
	{
		return $this->dxcc;
	}

	public function getCqzone(): string
	{
		return $this->cqzone;
	}

	public function getState(): string
	{
		return $this->state;
	}

	public function getIOTA(): string
	{
		return $this->iota;
	}

	public function toArray(): array
	{
		return [
			'qsoID' => $this->qsoID,
			'qsoDateTime' => $this->qsoDateTime->format("Y-m-d H:i"),
			'de' => $this->de,
			'dx' => $this->dx,
			'mode' => $this->getFormattedMode(),
			'rstS' => $this->rstS,
			'rstR' => $this->rstR,
			'band' => $this->getFormattedBand(),
			'deRefs' => $this->getFormattedDeRefs(),
			'dxRefs' => $this->getFormattedDxRefs(),
			'qslVia' => $this->QSLVia,
			'qslSent' => $this->getFormattedQSLSent(),
			'qslReceived' => $this->getFormattedQSLReceived(),
			'qslMessage' => $this->getQSLMsg(),
			'name' => $this->getName(),
			'dxcc' => $this->getDXCC(),
			'state' => $this->getState(),
			'cqzone' => $this->getCqzone(),
			'iota' => $this->getIOTA(),
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
		$refs = [];
		if ($this->deVUCCGridsquares !== []) {
			$refs[] = implode(",", $this->deVUCCGridsquares);
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
		if ($this->deSig !== '') {
			$refs[] = $this->deSig . ":" . $this->deSigInfo;
		}
		return trim(implode(" ", $refs));
	}

	private function getFormattedDxRefs(): string
	{
		$refs = [];
		if ($this->dxVUCCGridsquares !== []) {
			$refs[] = implode(",", $this->dxVUCCGridsquares);
		} else if ($this->dxGridsquare !== '') {
			$refs[] = $this->dxGridsquare;
		}
		if ($this->dxSOTAReference !== '') {
			$refs[] = "SOTA:" . $this->dxSOTAReference;
		}
		if ($this->dxSig !== '') {
			$refs[] = $this->dxSig . ":" . $this->dxSigInfo;
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
}
