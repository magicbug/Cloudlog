<?php

if (!function_exists('folderSize')) {
    function folderSize($dir){
        if (is_dir($dir)) {
            $count_size = 0;
            $count = 0;
            $dir_array = scandir($dir);
            foreach($dir_array as $key=>$filename){
                if($filename!=".." && $filename!="."){
                    if(is_dir($dir."/".$filename)){
                        $new_foldersize = folderSize($dir."/".$filename);
                        $count_size = $count_size+ $new_foldersize;
                    }else if(is_file($dir."/".$filename)){
                        $count_size = $count_size + filesize($dir."/".$filename);
                        $count++;
                    }
                }
            }
            return $count_size;
        } else {
            return 0;
        }
    } 
}

if (!function_exists('sizeFormat')) {
    function sizeFormat($bytes){
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if (($bytes >= 0) && ($bytes < $kb)) {
            return $bytes . ' B';
        } elseif (($bytes >= $kb) && ($bytes < $mb)) {
            return ceil($bytes / $kb) . ' KB';
        } elseif (($bytes >= $mb) && ($bytes < $gb)) {
            return ceil($bytes / $mb) . ' MB';
        } elseif (($bytes >= $gb) && ($bytes < $tb)) {
            return ceil($bytes / $gb) . ' GB';
        } elseif ($bytes >= $tb) {
            return ceil($bytes / $tb) . ' TB';
        } else {
            return $bytes . ' B';
        }
    }
}
