<?php
declare(strict_types=1);

namespace Tests\Cloudlog\QSLManager;

use DomainException;
use PHPUnit\Framework\TestCase;
use Cloudlog\QSLManager\QSO;

class QSOTest extends TestCase
{
	public function testCanCreateObject()
	{
		$data = $this->getTestData();
		$sut = new QSO($data);

		$this->assertEquals($data['COL_PRIMARY_KEY'], $sut->getQsoID());
		$this->assertEquals($data['COL_ADDRESS'], $sut->getAddress());
		$this->assertEquals($data['COL_BAND'], $sut->getBand());
		$this->assertEquals($data['COL_BAND_RX'], $sut->getBandRX());
		$this->assertEquals($data['COL_CALL'], $sut->getDx());
		$this->assertEquals($data['COL_EMAIL'], $sut->getEmail());
		$this->assertEquals($data['COL_GRIDSQUARE'], $sut->getDxGridsquare());
		$this->assertEquals($data['COL_IOTA'], $sut->getDxIOTA());
		$this->assertEquals($data['COL_MODE'], $sut->getMode());
		$this->assertEquals($data['COL_MY_GRIDSQUARE'], $sut->getDeGridsquare());
		$this->assertEquals($data['COL_MY_IOTA'], $sut->getDeIOTA());
		$this->assertEquals($data['COL_MY_SIG'], $sut->getDeSig());
		$this->assertEquals($data['COL_MY_SIG_INFO'], $sut->getDeSigInfo());
		$this->assertEquals($data['COL_NAME'], $sut->getName());
		$this->assertEquals($data['COL_PROP_MODE'], $sut->getPropagationMode());
		$this->assertEquals($data['COL_QSLMSG'], $sut->getQSLMsg());
		$this->assertEquals($data['COL_QSLRDATE'], $sut->getQSLReceivedDate()->format("Y-m-d H:i:s"));
		$this->assertEquals($data['COL_QSLSDATE'], $sut->getQSLSentDate()->format("Y-m-d H:i:s"));
		$this->assertEquals($data['COL_QSL_RCVD'], $sut->getQSLReceived());
		$this->assertEquals($data['COL_QSL_RCVD_VIA'], $sut->getQSLReceivedVia());
		$this->assertEquals($data['COL_QSL_SENT'], $sut->getQSLSent());
		$this->assertEquals($data['COL_QSL_SENT_VIA'], $sut->getQSLSentVia());
		$this->assertEquals($data['COL_QSL_VIA'], $sut->getQSLVia());
		$this->assertEquals($data['COL_RST_RCVD'], $sut->getRstR());
		$this->assertEquals($data['COL_RST_SENT'], $sut->getRstS());
		$this->assertEquals($data['COL_SAT_MODE'], $sut->getSatelliteMode());
		$this->assertEquals($data['COL_SAT_NAME'], $sut->getSatelliteName());
		$this->assertEquals($data['COL_SIG'], $sut->getDxSig());
		$this->assertEquals($data['COL_SIG_INFO'], $sut->getDxSigInfo());
		$this->assertEquals($data['COL_STATION_CALLSIGN'], $sut->getDe());
		$this->assertEquals($data['COL_TIME_ON'], $sut->getQsoDateTime()->format("Y-m-d H:i:s"));
		$this->assertEquals($data['COL_DARC_DOK'], $sut->getDxDARCDOK());
		$this->assertEquals($data['COL_MY_IOTA_ISLAND_ID'], $sut->getDeIOTAIslandID());
		$this->assertEquals($data['COL_MY_SOTA_REF'], $sut->getDeSOTAReference());
		$this->assertEquals($data['COL_MY_VUCC_GRIDS'], implode(",", $sut->getDeVUCCGridsquares()));
		$this->assertEquals($data['COL_SOTA_REF'], $sut->getDxSOTAReference());
		$this->assertEquals($data['COL_SUBMODE'], $sut->getSubmode());
		$this->assertEquals($data['COL_VUCC_GRIDS'], implode(",", $sut->getDxVUCCGridsquares()));
	}

	public function testCreateFailsIfOneKeyIsMissing()
	{
		$data = $this->getTestData();
		$keys = array_keys($data);
		foreach ($keys as $key) {
			$dataWithMissingKey = $data;
			unset($dataWithMissingKey[$key]);
			try {
				new QSO($dataWithMissingKey);
				$this->fail();
			} catch (DomainException $e) {
				$this->assertEquals("Required key $key does not exist", $e->getMessage());
			}
		}
	}

	/**
	 * @param string $testDescription
	 * @param array $testData
	 * @param array $expectedResult
	 * @return void
	 * @dataProvider toArrayTestDataProvider
	 */
	public function testToArrayReturnsValidData(string $testDescription, array $testData, array $expectedResult)
	{
		$testDescription = "Testing for $testDescription";
		$sut = new QSO($testData);
		$result = $sut->toArray();
		foreach ($expectedResult as $expectedKey => $expectedValue) {
			$this->assertArrayHasKey($expectedKey, $result, $testDescription);
			$this->assertEquals($expectedValue, $result[$expectedKey], $testDescription);
		}
	}

	private function getTestData(): array
	{
		return [
			'COL_PRIMARY_KEY' => 'COL_PRIMARY_KEY',
			'COL_ADDRESS' => 'COL_ADDRESS',
			'COL_BAND' => 'COL_BAND',
			'COL_BAND_RX' => 'COL_BAND_RX',
			'COL_CALL' => 'COL_CALL',
			'COL_EMAIL' => 'COL_EMAIL',
			'COL_GRIDSQUARE' => 'COL_GRIDSQUARE',
			'COL_IOTA' => 'COL_IOTA',
			'COL_MODE' => 'COL_MODE',
			'COL_MY_GRIDSQUARE' => 'COL_MY_GRIDSQUARE',
			'COL_MY_IOTA' => 'COL_MY_IOTA',
			'COL_MY_SIG' => 'COL_MY_SIG',
			'COL_MY_SIG_INFO' => 'COL_MY_SIG_INFO',
			'COL_NAME' => 'COL_NAME',
			'COL_PROP_MODE' => 'COL_PROP_MODE',
			'COL_QSLMSG' => 'COL_QSLMSG',
			'COL_QSLRDATE' => '2022-01-02 03:04:06',
			'COL_QSLSDATE' => '2022-01-02 03:04:07',
			'COL_QSL_RCVD' => 'COL_QSL_RCVD',
			'COL_QSL_RCVD_VIA' => 'COL_QSL_RCVD_VIA',
			'COL_QSL_SENT' => 'COL_QSL_SENT',
			'COL_QSL_SENT_VIA' => 'COL_QSL_SENT_VIA',
			'COL_QSL_VIA' => 'COL_QSL_VIA',
			'COL_RST_RCVD' => 'COL_RST_RCVD',
			'COL_RST_SENT' => 'COL_RST_SENT',
			'COL_SAT_MODE' => 'COL_SAT_MODE',
			'COL_SAT_NAME' => 'COL_SAT_NAME',
			'COL_SIG' => 'COL_SIG',
			'COL_SIG_INFO' => 'COL_SIG_INFO',
			'COL_STATION_CALLSIGN' => 'COL_STATION_CALLSIGN',
			'COL_TIME_ON' => '2022-01-02 03:04:05',
			'COL_DARC_DOK' => 'COL_DARC_DOK',
			'COL_MY_IOTA_ISLAND_ID' => 'COL_MY_IOTA_ISLAND_ID',
			'COL_MY_SOTA_REF' => 'COL_MY_SOTA_REF',
			'COL_MY_VUCC_GRIDS' => 'COL_MY_VUCC_GRIDS',
			'COL_SOTA_REF' => 'COL_SOTA_REF',
			'COL_SUBMODE' => 'COL_SUBMODE',
			'COL_VUCC_GRIDS' => 'COL_VUCC_GRIDS',
		];
	}

	/**
	 * @return array[]
	 */
	public function toArrayTestDataProvider() : array
	{
		return [
			[
				'minimal qso',
				$this->getBaseTestQSO(),
				[
					'qsoID' => '1',
					'qsoDateTime' => '2022-01-02 03:04',
					'de' => 'AA0AAA',
					'dx' => 'AA0AAB',
					'mode' => 'SSB',
					'rstS' => '59',
					'rstR' => '95',
					'band' => '20m',
				]
			],
			[
				'QSO with submode',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_SUBMODE' => 'USB'
					]
				),
				[
					'mode' => 'USB'
				]
			],
			[
				'QSO with band rx and tx',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_BAND_RX' => '10m'
					]
				),
				[
					'band' => '20m/10m'
				]
			],
			[
				'QSO with propagatiom mode',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_PROP_MODE' => 'SAT'
					]
				),
				[
					'band' => 'SAT 20m'
				]
			],
			[
				'QSO with propagatiom mode and sat name',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_PROP_MODE' => 'SAT',
						'COL_SAT_NAME' => 'QO-100'
					]
				),
				[
					'band' => 'SAT QO-100 20m'
				]
			],
			[
				'QSO with propagatiom mode and sat name and satmode',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_PROP_MODE' => 'SAT',
						'COL_SAT_NAME' => 'QO-100',
						'COL_SAT_MODE' => 'S/X',
					]
				),
				[
					'band' => 'SAT QO-100 S/X 20m'
				]
			],
			[
				'QSO with my gridsquare',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_MY_GRIDSQUARE' => 'AA00',
					]
				),
				[
					'deRefs' => 'AA00'
				]
			],
			[
				'QSO with my vucc_gridsquares',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_MY_VUCC_GRIDS' => 'AA00,BB00',
					]
				),
				[
					'deRefs' => 'AA00,BB00'
				]
			],
			[
				'QSO with my IOTA',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_MY_IOTA' => 'EU-123',
					]
				),
				[
					'deRefs' => 'IOTA:EU-123'
				]
			],
			[
				'QSO with my IOTA and Island Reference',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_MY_IOTA' => 'EU-123',
						'COL_MY_IOTA_ISLAND_ID' => '321'
					]
				),
				[
					'deRefs' => 'IOTA:EU-123(321)'
				]
			],
			[
				'QSO with my SOTA',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_MY_SOTA_REF' => 'AA/BB-000',
					]
				),
				[
					'deRefs' => 'SOTA:AA/BB-000'
				]
			],
			[
				'QSO with my SIG',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_MY_SIG' => 'ABC',
						'COL_MY_SIG_INFO' => '987',
					]
				),
				[
					'deRefs' => 'ABC:987'
				]
			],
			[
				'QSO with gridsquare',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_GRIDSQUARE' => 'AA00',
					]
				),
				[
					'dxRefs' => 'AA00'
				]
			],
			[
				'QSO with vucc_gridsquares',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_VUCC_GRIDS' => 'AA00,BB00',
					]
				),
				[
					'dxRefs' => 'AA00,BB00'
				]
			],
			[
				'QSO with IOTA',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_IOTA' => 'EU-123',
					]
				),
				[
					'dxRefs' => 'IOTA:EU-123'
				]
			],
			[
				'QSO with SOTA',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_SOTA_REF' => 'AA/BB-000',
					]
				),
				[
					'dxRefs' => 'SOTA:AA/BB-000'
				]
			],
			[
				'QSO with SIG',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_SIG' => 'ABC',
						'COL_SIG_INFO' => '987',
					]
				),
				[
					'dxRefs' => 'ABC:987'
				]
			],
			[
				'QSO with DOK',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_DARC_DOK' => '123',
					]
				),
				[
					'dxRefs' => 'DOK:123'
				]
			],
			[
				'QSO with QSL Sent',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'Y',
					]
				),
				[
					'qslSent' => 'Yes'
				]
			],
			[
				'QSO with QSL Sent with Date',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'Y',
						'COL_QSLSDATE' => '2022-01-02 03:04:05',
					]
				),
				[
					'qslSent' => '2022-01-02'
				]
			],
			[
				'QSO with QSL Sent with Date and method Bureau',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'Y',
						'COL_QSLSDATE' => '2022-01-02 03:04:05',
						'COL_QSL_SENT_VIA' => 'B'
					]
				),
				[
					'qslSent' => '2022-01-02 Bureau'
				]
			],
			[
				'QSO with QSL Sent with Date and method Direct',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'Y',
						'COL_QSLSDATE' => '2022-01-02 03:04:05',
						'COL_QSL_SENT_VIA' => 'D'
					]
				),
				[
					'qslSent' => '2022-01-02 Direct'
				]
			],
			[
				'QSO with QSL Sent with Date and method Electronic',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'Y',
						'COL_QSLSDATE' => '2022-01-02 03:04:05',
						'COL_QSL_SENT_VIA' => 'E'
					]
				),
				[
					'qslSent' => '2022-01-02 Electronic'
				]
			],
			[
				'QSO with QSL Sent with Date and method Manager',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'Y',
						'COL_QSLSDATE' => '2022-01-02 03:04:05',
						'COL_QSL_SENT_VIA' => 'M'
					]
				),
				[
					'qslSent' => '2022-01-02 Manager'
				]
			],
			[
				'QSO with QSL Not Sent',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'N'
					]
				),
				[
					'qslSent' => 'No'
				]
			],
			[
				'QSO with QSL Sent Queued',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'Q'
					]
				),
				[
					'qslSent' => 'Queued'
				]
			],
			[
				'QSO with QSL Sent Queued with Date',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'Q',
						'COL_QSLSDATE' => '2022-01-02 03:04:05',
					]
				),
				[
					'qslSent' => 'Queued 2022-01-02'
				]
			],
			[
				'QSO with QSL Sent Requested',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'R'
					]
				),
				[
					'qslSent' => 'Requested'
				]
			],
			[
				'QSO with QSL Sent Requested with Date',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_SENT' => 'R',
						'COL_QSLSDATE' => '2022-01-02 03:04:05',
					]
				),
				[
					'qslSent' => 'Requested 2022-01-02'
				]
			],
			[
				'QSO with QSL Received',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'Y',
					]
				),
				[
					'qslReceived' => 'Yes'
				]
			],
			[
				'QSO with QSL Received with Date',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'Y',
						'COL_QSLRDATE' => '2022-01-02 03:04:05',
					]
				),
				[
					'qslReceived' => '2022-01-02'
				]
			],
			[
				'QSO with QSL Received with Date and method Bureau',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'Y',
						'COL_QSLRDATE' => '2022-01-02 03:04:05',
						'COL_QSL_RCVD_VIA' => 'B'
					]
				),
				[
					'qslReceived' => '2022-01-02 Bureau'
				]
			],
			[
				'QSO with QSL Received with Date and method Direct',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'Y',
						'COL_QSLRDATE' => '2022-01-02 03:04:05',
						'COL_QSL_RCVD_VIA' => 'D'
					]
				),
				[
					'qslReceived' => '2022-01-02 Direct'
				]
			],
			[
				'QSO with QSL Received with Date and method Electronic',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'Y',
						'COL_QSLRDATE' => '2022-01-02 03:04:05',
						'COL_QSL_RCVD_VIA' => 'E'
					]
				),
				[
					'qslReceived' => '2022-01-02 Electronic'
				]
			],
			[
				'QSO with QSL Received with Date and method Manager',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'Y',
						'COL_QSLRDATE' => '2022-01-02 03:04:05',
						'COL_QSL_RCVD_VIA' => 'M'
					]
				),
				[
					'qslReceived' => '2022-01-02 Manager'
				]
			],
			[
				'QSO with QSL Not Received',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'N'
					]
				),
				[
					'qslReceived' => 'No'
				]
			],
			[
				'QSO with QSL Received Queued',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'Q'
					]
				),
				[
					'qslReceived' => 'Queued'
				]
			],
			[
				'QSO with QSL Received Queued with Date',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'Q',
						'COL_QSLRDATE' => '2022-01-02 03:04:05',
					]
				),
				[
					'qslReceived' => 'Queued 2022-01-02'
				]
			],
			[
				'QSO with QSL Received Requested',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'R'
					]
				),
				[
					'qslReceived' => 'Requested'
				]
			],
			[
				'QSO with QSL Received Requested with Date',
				array_merge(
					$this->getBaseTestQSO(),
					[
						'COL_QSL_RCVD' => 'R',
						'COL_QSLRDATE' => '2022-01-02 03:04:05',
					]
				),
				[
					'qslReceived' => 'Requested 2022-01-02'
				]
			],
		];
	}

	private function getBaseTestQSO(): array
	{
		return [
			'COL_PRIMARY_KEY' => '1',
			'COL_ADDRESS' => '',
			'COL_BAND' => '20m',
			'COL_BAND_RX' => '',
			'COL_CALL' => 'AA0AAB',
			'COL_EMAIL' => '',
			'COL_GRIDSQUARE' => '',
			'COL_IOTA' => '',
			'COL_MODE' => 'SSB',
			'COL_MY_GRIDSQUARE' => '',
			'COL_MY_IOTA' => '',
			'COL_MY_SIG' => '',
			'COL_MY_SIG_INFO' => '',
			'COL_NAME' => '',
			'COL_PROP_MODE' => '',
			'COL_QSLMSG' => '',
			'COL_QSLRDATE' => null,
			'COL_QSLSDATE' => null,
			'COL_QSL_RCVD' => 'I',
			'COL_QSL_RCVD_VIA' => '',
			'COL_QSL_SENT' => 'I',
			'COL_QSL_SENT_VIA' => '',
			'COL_QSL_VIA' => '',
			'COL_RST_RCVD' => '95',
			'COL_RST_SENT' => '59',
			'COL_SAT_MODE' => '',
			'COL_SAT_NAME' => '',
			'COL_SIG' => '',
			'COL_SIG_INFO' => '',
			'COL_STATION_CALLSIGN' => 'AA0AAA',
			'COL_TIME_ON' => '2022-01-02 03:04:05',
			'COL_DARC_DOK' => '',
			'COL_MY_IOTA_ISLAND_ID' => '',
			'COL_MY_SOTA_REF' => '',
			'COL_MY_VUCC_GRIDS' => '',
			'COL_SOTA_REF' => '',
			'COL_SUBMODE' => '',
			'COL_VUCC_GRIDS' => '',
		];
	}
}
