<?php

class Dxatlas_model extends CI_Model
{

	public $bandslots = array("160m" => 0,
		"80m" => 0,
		"60m" => 0,
		"40m" => 0,
		"30m" => 0,
		"20m" => 0,
		"17m" => 0,
		"15m" => 0,
		"12m" => 0,
		"10m" => 0,
		"6m" => 0,
		"4m" => 0,
		"2m" => 0,
		"70cm" => 0,
		"23cm" => 0,
		"13cm" => 0,
		"9cm" => 0,
		"6cm" => 0,
		"3cm" => 0,
		"1.25cm" => 0,
		"SAT" => 0,
	);

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	/*
	 *  Fetches worked and confirmed gridsquare on each band and total
	 */
	function get_gridsquares($data) {
		$gridArray = $this->fetchGrids($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate);

		if (isset($gridArray)) {
			return $gridArray;
		} else {
			return 0;
		}
	}

}
?>
