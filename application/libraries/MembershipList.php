<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MembershipList {

	/*
        Load MembershipList
	*/

    function searchFilesForCallsign($callsign) {

        $callsign = strtoupper($callsign);
        $dir = 'assets/membership_lists/';
        if (!is_dir($dir)) {
            return "Directory does not exist";
        }
    
        $results = array();
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'txt') {
                    $filename = $dir . $file;
                    $file_handle = fopen($filename, "r");
                    $headers = fgetcsv($file_handle, 0, ",");
                    while (($line = fgetcsv($file_handle, 0, ",")) !== FALSE) {
                        if (is_array($line) && in_array($callsign, $line)) {
                            if (count($headers) == count($line)) {
                                $results[pathinfo($file, PATHINFO_FILENAME)][] = array_combine($headers, $line);
                            } else {
                                $results[pathinfo($file, PATHINFO_FILENAME)][] = $line;
                            }
                        }
                    }
                    fclose($file_handle);
                }
            }
            closedir($handle);
        }
    
        return $results;
    }

}
?>