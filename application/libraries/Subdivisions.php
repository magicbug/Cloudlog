<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
	Lookup functions for subdivisions
*/


class Subdivisions {

	public function get_primary_subdivision_name($dxcc) {
		switch($dxcc) {
			case '1':
				return 'Province';
			case '291':
				return 'US State';
			case '339':
				return 'Prefecture';
		}
		return 'State';
	}
	
}
