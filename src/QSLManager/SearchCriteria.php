<?php
declare(strict_types=1);

namespace Cloudlog\QSLManager;

use DateTime;
use DateTimeZone;

class SearchCriteria
{
	private int $userId;
	private ?DateTime $dateFrom = null;
	private ?DateTime $dateTo = null;
	private string $de;
	private string $dx;
	private string $mode;
	private string $band;
	private string $qslSent;
	private string $qslReceived;

	/**
	 * @param int $userId
	 * @param string $dateFrom
	 * @param string $dateTo
	 * @param string $de
	 * @param string $dx
	 * @param string $mode
	 * @param string $band
	 * @param string $qslSent
	 * @param string $qslReceived
	 */
	public function __construct(
		int    $userId,
		string $dateFrom,
		string $dateTo,
		string $de,
		string $dx,
		string $mode,
		string $band,
		string $qslSent,
		string $qslReceived
	)
	{
		$this->userId = $userId;
		$dateFrom = trim($dateFrom);
		if ($dateFrom !== "") {
			$dateFromParts = explode("-", $dateFrom);
			if (checkdate((int)$dateFromParts[1], (int)$dateFromParts[2], (int)$dateFromParts[0])) {
				$this->dateFrom = DateTime::createFromFormat("Y-m-d", $dateFrom, new DateTimeZone('UTC'));
			}
		}

		$dateTo = trim($dateTo);
		if ($dateTo !== "") {
			$dateToParts = explode("-", $dateTo);
			if (checkdate((int)$dateToParts[1], (int)$dateToParts[2], (int)$dateToParts[0])) {
				$this->dateTo = DateTime::createFromFormat("Y-m-d", $dateTo, new DateTimeZone('UTC'));
			}
		}

		if ($this->dateFrom !== null && $this->dateTo !== null && $this->dateTo->getTimestamp() < $this->dateFrom->getTimestamp()) {
			$temp = $this->dateFrom;
			$this->dateFrom = $this->dateTo;
			$this->dateTo = $temp;
		}

		$this->de = trim($de);
		$this->dx = trim($dx);
		$this->mode = trim($mode);
		$this->band = trim($band);
		$this->qslSent = $qslSent;
		$this->qslReceived = $qslReceived;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->userId;
	}

	/**
	 * @return DateTime|false|null
	 */
	public function getDateFrom()
	{
		return $this->dateFrom;
	}

	/**
	 * @return DateTime|false|null
	 */
	public function getDateTo()
	{
		return $this->dateTo;
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
	public function getBand(): string
	{
		return $this->band;
	}

	/**
	 * @return string
	 */
	public function getQslSent(): string
	{
		return $this->qslSent;
	}

	/**
	 * @return string
	 */
	public function getQslReceived(): string
	{
		return $this->qslReceived;
	}

}
