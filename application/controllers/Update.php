<?php
class Update extends CI_Controller {

	/*
		Controls Updating Elements of Cloudlog
		Functions:
			dxcc - imports the latest clublog cty.xml data
			lotw_users - imports lotw users
	*/

	public function index()
	{
	    $data['page_title'] = "Updates";
	    $this->load->view('interface_assets/header', $data);
	    $this->load->view('update/index');
	    $this->load->view('interface_assets/footer');

	}

    /*
     * Create a path to a file in the updates folder, respecting the datadir
     * configuration option.
     */
    private function make_update_path($path) {
        $path = "updates/" . $path;
        $datadir = $this->config->item('datadir');
        if(!$datadir) {
            return $path;
        }
        return $datadir . "/" . $path;
    }

    /*
     * Load the dxcc entities
     */
	public function dxcc_entities() {
		// Load Database connectors
		$this->load->model('dxcc_entities');

		// Load the cty file
		$xml_data = simplexml_load_file($this->make_update_path("cty.xml"));
		
		//$xml_data->entities->entity->count();

        $count = 0;
		foreach ($xml_data->entities->entity as $entity) {
			$startinfo = strtotime($entity->start);
            $endinfo = strtotime($entity->end);
            
            $start_date = ($startinfo) ? date('Y-m-d H:i:s',$startinfo) : null;
            $end_date = ($endinfo) ? date('Y-m-d H:i:s',$endinfo) : null;
        
            if(!$entity->cqz) {
                $data = array(
                    'prefix' => (string) $entity->call,
                    'name' =>  (string) $entity->entity,
                );
            } else {
                $data = array(
                    'adif' => (int) $entity->adif,
                    'name' =>  (string) $entity->name,
                    'prefix' => (string)  $entity->prefix,
                    'ituz' => (float) $entity->ituz,
                    'cqz' => (int) $entity->cqz,
                    'cont' => (string) $entity->cont,
                    'long' => (float) $entity->long,
                    'lat' => (float) $entity->lat,
                	'start' => $start_date,
                    'end' => $end_date,
                );	
            }
        
            $this->db->insert('dxcc_entities', $data); 
            $count += 1;
            if ($count % 10  == 0)
                $this->update_status();
		}

        $this->update_status();
	    return $count;	
	}

    /*
     * Load the dxcc exceptions
     */
	public function dxcc_exceptions() {
		// Load Database connectors
		$this->load->model('dxcc_exceptions');
		// Load the cty file
		$xml_data = simplexml_load_file($this->make_update_path("cty.xml"));
		
        $count = 0;
		foreach ($xml_data->exceptions->exception as $record) {
			$startinfo = strtotime($record->start);
            $endinfo = strtotime($record->end);
            
            $start_date = ($startinfo) ? date('Y-m-d H:i:s',$startinfo) : null;
            $end_date = ($endinfo) ? date('Y-m-d H:i:s',$endinfo) : null;

            $data = array(
            	'record' => (int) $record->attributes()->record,
            	'call' => (string) $record->call,
            	'entity' =>  (string) $record->entity,
                'adif' => (int) $record->adif,
                'cqz' => (int) $record->cqz,
                'cont' => (string) $record->cont,
                'long' => (float) $record->long,
                'lat' => (float) $record->lat,
                'start' => $start_date,
                'end' => $end_date,
            );
       
            $this->db->insert('dxcc_exceptions', $data); 
            $count += 1;
            if ($count % 10  == 0)
                $this->update_status();
		}

        $this->update_status();
	    return $count;	
	}

    /*
     * Load the dxcc prefixes
     */
	public function dxcc_prefixes() {
		// Load Database connectors
		$this->load->model('dxcc_prefixes');
		// Load the cty file
		$xml_data = simplexml_load_file($this->make_update_path("cty.xml"));
		
        $count = 0;
		foreach ($xml_data->prefixes->prefix as $record) {
			$startinfo = strtotime($record->start);
            $endinfo = strtotime($record->end);
            
            $start_date = ($startinfo) ? date('Y-m-d H:i:s',$startinfo) : null;
            $end_date = ($endinfo) ? date('Y-m-d H:i:s',$endinfo) : null;
            
            $data = array(
            	'record' => (int) $record->attributes()->record,
            	'call' => (string) $record->call,
            	'entity' =>  (string) $record->entity,
                'adif' => (int) $record->adif,
                'cqz' => (int) $record->cqz,
                'cont' => (string) $record->cont,
                'long' => (float) $record->long,
                'lat' => (float) $record->lat,
                'start' => $start_date,
                'end' => $end_date,
            );
       
            $this->db->insert('dxcc_prefixes', $data); 
            $count += 1;
            if ($count % 10  == 0)
                $this->update_status();
		}

		//print("$count prefixes processed");
        $this->update_status();
	    return $count;	
	}

	// Updates the DXCC & Exceptions from the Club Log Cty.xml file.
	public function dxcc() {
	    $this->update_status("Downloading file");

	    // give it 10 minutes...
	    set_time_limit(600);
	
		// Load Migration data if any.
		$this->load->library('migration');
		$this->fix_migrations();
		$this->migration->latest();

		// Download latest file.
		$url = "https://cdn.clublog.org/cty.php?api=a11c3235cd74b88212ce726857056939d52372bd";
		
		$gz = gzopen($url, 'r');
		if ($gz === FALSE) {
            // If the download from clublog.org fails, try cloudlog.org CDN.
            $url = "https://cdn.cloudlog.org/clublogxml.gz";
            $gz = gzopen($url, 'r');

            // Log failure to log file
            log_message('info', 'Failed to download cty.xml from clublog.org, trying cloudlog.org CDN');

            if ($gz === FALSE) {
                $this->update_status("FAILED: Could not download from clublog.org or cloudlog.org");
                log_message('error', 'FAILED: Could not download exceptions from clublog.org or cloudlog.org');
                return;
            }
		}

		$data = "";
		while (!gzeof($gz)) {
		  $data .= gzgetc($gz);
		}
		gzclose($gz);

		if (file_put_contents($this->make_update_path("cty.xml"), $data) === FALSE) {
			$this->update_status("FAILED: Could not write to cty.xml file");
			return;
		}
	
	    // Clear the tables, ready for new data
		$this->db->empty_table("dxcc_entities");
		$this->db->empty_table("dxcc_exceptions");
		$this->db->empty_table("dxcc_prefixes");
		$this->update_status();

	    // Parse the three sections of the file and update the tables
	    $this->db->trans_start();
		$this->dxcc_entities();
		$this->dxcc_exceptions();
		$this->dxcc_prefixes();
		$this->db->trans_complete();

		$this->update_status("DONE");
	}

	public function update_status($done=""){

        if ($done != "Downloading file"){
            // Check that everything is done?
            if ($done == ""){
                $done = "Updating...";
            }
            $html = $done."<br/>";
            $html .= "Dxcc Entities: ".$this->db->count_all('dxcc_entities')."<br/>";
            $html .= "Dxcc Exceptions: ".$this->db->count_all('dxcc_exceptions')."<br/>";
            $html .= "Dxcc Prefixes: ".$this->db->count_all('dxcc_prefixes')."<br/>";
        }else{
            $html = $done."....<br/>";
        }

        file_put_contents($this->make_update_path("status.html"), $html);
	}


	private function fix_migrations(){
        $res = $this->db->query("select version from migrations");
        if ($res->num_rows() >0){
            $row = $res->row();
            $version = $row->version;

            if ($version < 7){
                $this->db->query("update migrations set version=7");
            }
        }
	}
	
	public function check_missing_dxcc($all = false){
	    $this->load->model('logbook_model');
        $this->logbook_model->check_missing_dxcc_id($all);

	}
	
	public function check_missing_continent() {
		$this->load->model('logbook_model');
		$this->logbook_model->check_missing_continent();
	}

	public function update_distances() {
		$this->load->model('logbook_model');
		$this->logbook_model->update_distances();
	}

	public function check_missing_grid($all = false){
	    $this->load->model('logbook_model');
        $this->logbook_model->check_missing_grid_id($all);
	}

    public function update_clublog_scp() {
        $strFile = $this->make_update_path("clublog_scp.txt");
        $url = "https://cdn.clublog.org/clublog.scp.gz";
        set_time_limit(300);
        echo "Downloading Club Log SCP file...<br>";
        $gz = gzopen($url, 'r');
        if ($gz)
        {
            $data = "";
            while (!gzeof($gz)) {
                $data .= gzgetc($gz);
            }
            gzclose($gz);
            if (file_put_contents($strFile, $data) !== FALSE)
            {
                $nCount = count(file($strFile));
                if ($nCount > 0)
                {
                    echo "DONE: " . number_format($nCount) . " callsigns loaded";
                } else {
                    echo "FAILED: Empty file";
                }
            } else {
                echo "FAILED: Could not write to Club Log SCP file";
            }
        } else {
            echo "FAILED: Could not connect to Club Log";
        }
    }

    public function download_lotw_users() {
        $contents = file_get_contents('https://lotw.arrl.org/lotw-user-activity.csv', true);

        if($contents === FALSE) { 
            echo "Something went wrong with fetching the LoTW users file.";
        } else {
            $file = './updates/lotw_users.csv';

            if (file_put_contents($file, $contents) !== FALSE) {     // Save our content to the file.
                echo "LoTW User Data Saved.";
            } else {
                echo "FAILED: Could not write to LoTW users file";
            }
        }

    }

    public function lotw_users() {
        $mtime = microtime(); 
        $mtime = explode(" ",$mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $starttime = $mtime; 

        $file = 'https://lotw.arrl.org/lotw-user-activity.csv';

        $handle = fopen($file, "r");
        if ($handle === FALSE) {
            echo "Something went wrong with fetching the LoTW uses file";
            return;
        }
        $this->db->empty_table("lotw_users"); 
        $i = 0;
        $data = fgetcsv($handle,1000,",");
        do {
            if ($data[0]) {
                $lotwdata[$i]['callsign'] = $data[0];
                $lotwdata[$i]['lastupload'] = $data[1] . ' ' . $data[2];
                if (($i % 2000) == 0) {
                    $this->db->insert_batch('lotw_users', $lotwdata); 
                    unset($lotwdata);
                    // echo 'Record ' . $i . '<br />';
                }
                $i++;
            }
        } while ($data = fgetcsv($handle,1000,","));
        fclose($handle);

        $this->db->insert_batch('lotw_users', $lotwdata); 

        $mtime = microtime(); 
        $mtime = explode(" ",$mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $endtime = $mtime; 
        $totaltime = ($endtime - $starttime); 
        echo "This page was created in ".$totaltime." seconds <br />"; 
        echo "Records inserted: " . $i . " <br/>";
    }

    public function lotw_check() {
        $f = fopen('./updates/lotw_users.csv', "r");
        $result = false;
        while ($row = fgetcsv($f)) {
            if ($row[0] == '2M0SQL/MM') {
                $result = $row[0];
                echo "found";
                break;
            }
        }
        fclose($f);
    }

    /*
     * Used for autoupdating the DOK file which is used in the QSO entry dialog for autocompletion.
     */
    public function update_dok() {
        $contents = file_get_contents('https://www.df2et.de/cqrlog/dok_and_sdok.txt', true);

        if($contents === FALSE) {
            echo "Something went wrong with fetching the DOK file.";
        } else {
            $file = './assets/json/dok.txt';

            if (file_put_contents($file, $contents) !== FALSE) {     // Save our content to the file.
                $nCount = count(file($file));
                if ($nCount > 0)
                {
                    echo "DONE: " . number_format($nCount) . " DOKs and SDOKs saved";
                } else {
                    echo"FAILED: Empty file";
                }
            } else {
                echo"FAILED: Could not write to dok.txt file";
            }
        }
    }

    /*
     * Used for autoupdating the SOTA file which is used in the QSO entry dialog for autocompletion.
     */
    public function update_sota() {
        $csvfile = 'https://www.sotadata.org.uk/summitslist.csv';

        $sotafile = './assets/json/sota.txt';

        $csvhandle = fopen($csvfile,"r");
        if ($csvhandle === FALSE) {
            echo "Something went wrong with fetching the SOTA file";
            return;
        }

        $data = fgetcsv($csvhandle,1000,","); // Skip line we are not interested in
        $data = fgetcsv($csvhandle,1000,","); // Skip line we are not interested in
        $data = fgetcsv($csvhandle,1000,",");
        $sotafilehandle = fopen($sotafile, 'w');

        if ($sotafilehandle === FALSE) {
            echo"FAILED: Could not write to sota.txt file";
            return;
        }

        $nCount = 0;
        do {
            if ($data[0]) {
                fwrite($sotafilehandle, $data[0].PHP_EOL);
                $nCount++;
            }
        } while ($data = fgetcsv($csvhandle,1000,","));

        fclose($csvhandle);
        fclose($sotafilehandle);

        if ($nCount > 0)
        {
            echo "DONE: " . number_format($nCount) . " SOTA's saved";
        } else {
            echo"FAILED: Empty file";
        }
    }

    /*
     * Pulls the WWFF directory for autocompletion in QSO dialogs
     */
    public function update_wwff() {
        $csvfile = 'https://wwff.co/wwff-data/wwff_directory.csv';

        $wwfffile = './assets/json/wwff.txt';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $csvfile);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Cloudlog Updater');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $csv = curl_exec($ch);
        curl_close($ch);
        if ($csv === FALSE) {
            echo "Something went wrong with fetching the WWFF file";
            return;
        }

        $wwfffilehandle = fopen($wwfffile, 'w');
        if ($wwfffilehandle === FALSE) {
            echo"FAILED: Could not write to wwff.txt file";
            return;
        }

        $data = str_getcsv($csv,"\n");
        $nCount = 0;
        foreach ($data as $idx => $row) {
           if ($idx == 0) continue; // Skip line we are not interested in
           $row = str_getcsv($row, ',');
           if ($row[0]) {
              fwrite($wwfffilehandle, $row[0].PHP_EOL);
              $nCount++;
           }
        }

        fclose($wwfffilehandle);

        if ($nCount > 0)
        {
            echo "DONE: " . number_format($nCount) . " WWFF's saved";
        } else {
            echo"FAILED: Empty file";
        }
    }

    public function update_pota() {
        $csvfile = 'https://pota.app/all_parks.csv';

        $potafile = './assets/json/pota.txt';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $csvfile);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Cloudlog Updater');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $csv = curl_exec($ch);
        curl_close($ch);
        if ($csv === FALSE) {
            echo "Something went wrong with fetching the POTA file";
            return;
        }

        $potafilehandle = fopen($potafile, 'w');
        if ($potafilehandle === FALSE) {
            echo"FAILED: Could not write to pota.txt file";
            return;
        }
        $data = str_getcsv($csv,"\n");
        $nCount = 0;
        foreach ($data as $idx => $row) {
           if ($idx == 0) continue; // Skip line we are not interested in
           $row = str_getcsv($row, ',');
           if ($row[0]) {
              fwrite($potafilehandle, $row[0].PHP_EOL);
              $nCount++;
           }
        }

        fclose($potafilehandle);

        if ($nCount > 0)
        {
            echo "DONE: " . number_format($nCount) . " POTA's saved";
        } else {
            echo"FAILED: Empty file";
        }
    }

}
?>
