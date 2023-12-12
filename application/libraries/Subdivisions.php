<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
	Lookup functions for subdivisions
*/


class Subdivisions {

	public function get_primary_subdivision_name($dxcc) {
		// ref. http://adif.org.uk/314/ADIF_314_annotated.htm#Primary_Administrative_Subdivision
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
			case '281':
			case '284':
			case '318':
			case '375':
			case '386':
				return 'Province';
			case '27':
			case '15':
			case '54':
			case '61':
			case '126':
			case '151':
			case '288':
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
			case '275':
			case '497':
				return 'County';
			case '272':
			case '503':
			case '504':
				return 'District';
			case '287':
				return 'Canton';
			case '291':
				return 'US State';
			case '318':
			case '339':
				return 'Prefecture';
		}
		return 'State';
	}
	
	public function get_secondary_subdivision_name($dxcc) {
		// ref. http://adif.org.uk/314/ADIF_314_annotated.htm#Secondary_Administrative_Subdivision
		switch($dxcc) {
			case '6':
			case '110':
			case '291':
				return 'US County';
			case '15':
			case '54':
			case '61':
			case '126':
			case '151':
			case '288':
				return 'District';
			case '21':
			case '29':
			case '32':
			case '281':
				return 'DME';
			case '339':
				return 'City / Ku / Gun';
		}
		return 'County';
	}
}
