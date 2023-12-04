<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
	Lookup functions for subdivisions
*/


class Subdivisions {

	public function get_primary_subdivision_name($dxcc) {
		switch($dxcc) {
			case '1':
			case '29':
			case '32':
			case '100':
			case '137':
			case '163':
			case '206':
			case '209':
			case '212':
			case '225':
			case '248':
			case '263':
			case '269':
				return 'Province';
			case '27':
			case '15':
			case '54':
			case '61':
			case '126':
			case '151':
				return 'Oblast';
			case '112':
				return 'Region';
			case '132':
			case '144':
			case '227':
				return 'Department';
			case '170':
				return 'Region';
			case '224':
				return 'Municipality';
			case '230':
				return 'Federal State';
			case '239':
			case '245':
				return 'County';
			case '291':
				return 'US State';
			case '318':
			case '339':
				return 'Prefecture';
		}
		return 'State';
	}
	
}
