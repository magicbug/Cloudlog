<?php
declare(strict_types=1);

namespace Tests\Cloudlog\QSLManager;

use Cloudlog\QSLManager\SearchCriteria;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class SearchCriteriaTest extends TestCase
{
	public function testSearchCriteria()
	{
		$testUserId = 1;
		$testDateFrom = '2022-01-02';
		$testDateFromObject = DateTime::createFromFormat('Y-m-d', $testDateFrom, new DateTimeZone('UTC'));
		$testDateTo = '2022-01-03';
		$testDateToObject = DateTime::createFromFormat('Y-m-d', $testDateTo, new DateTimeZone('UTC'));
		$testDe = 'De';
		$testDx = 'Dx';
		$testMode = 'Mode';
		$testBand = 'Band';
		$testQSLSent = 'S';
		$testQSLReceived = 'R';

		$sut = new SearchCriteria(
			$testUserId,
			$testDateFrom,
			$testDateTo,
			$testDe,
			$testDx,
			$testMode,
			$testBand,
			$testQSLSent,
			$testQSLReceived
		);

		$this->assertEquals($testUserId, $sut->getUserId());
		$this->assertEquals($testDateFromObject, $sut->getDateFrom());
		$this->assertEquals($testDateToObject, $sut->getDateTo());
		$this->assertEquals($testDe, $sut->getDe());
		$this->assertEquals($testDx, $sut->getDx());
		$this->assertEquals($testMode, $sut->getMode());
		$this->assertEquals($testBand, $sut->getBand());
		$this->assertEquals($testQSLSent, $sut->getQSLSent());
		$this->assertEquals($testQSLReceived, $sut->getQSLReceived());
	}

	public function testSearchCriteriaCorrectsWrongDateOrder()
	{
		$testUserId = 1;
		$testDateFrom = '2022-01-03';
		$testDateTo = '2022-01-02';
		$testDe = 'De';
		$testDx = 'Dx';
		$testMode = 'Mode';
		$testBand = 'Band';
		$testQSLSent = 'S';
		$testQSLReceived = 'R';

		$sut = new SearchCriteria(
			$testUserId,
			$testDateFrom,
			$testDateTo,
			$testDe,
			$testDx,
			$testMode,
			$testBand,
			$testQSLSent,
			$testQSLReceived
		);

		$this->assertEquals(
			DateTime::createFromFormat(
				'Y-m-d',
				$testDateTo,
				new DateTimeZone('UTC')
			),
			$sut->getDateFrom()
		);

		$this->assertEquals(
			DateTime::createFromFormat(
				'Y-m-d',
				$testDateFrom,
				new DateTimeZone('UTC')
			),
			$sut->getDateTo()
		);

	}
}
