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

	    // give it 5 minutes...
	    set_time_limit(600);
	
		// Load Migration data if any.
		$this->load->library('migration');
		$this->fix_migrations();
		$this->migration->latest();

		// Download latest file.
		$url = "https://cdn.clublog.org/cty.php?api=a11c3235cd74b88212ce726857056939d52372bd";
		
		$gz = gzopen($url, 'r');
		$data = "";
		while (!gzeof($gz)) {
		  $data .= gzgetc($gz);
		}
		gzclose($gz);

		file_put_contents($this->make_update_path("cty.xml"), $data);
	
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
	
	public function check_missing_grid($all = false){
	    $this->load->model('logbook_model');
        $this->logbook_model->check_missing_grid_id($all);
	}

    public function update_clublog_scp() {
        $strFile = $this->make_update_path("clublog_scp.txt");
        $url = "https://cdn.clublog.org/clublog.scp.gz";
        set_time_limit(300);
        $this->update_status("Downloading Club Log SCP file");
        $gz = gzopen($url, 'r');
        if ($gz)
        {
            $data = "";
            while (!gzeof($gz)) {
                $data .= gzgetc($gz);
            }
            gzclose($gz);
            file_put_contents($strFile, $data);
            if (file_exists($strFile))
            {
                $nCount = count(file($strFile));
                if ($nCount > 0)
                {
                    $this->update_status("DONE: " . number_format($nCount) . " callsigns loaded" );
                } else {
                    $this->update_status("FAILED: Empty file");
                }
            } else {
                $this->update_status("FAILED: Could not create Club Log SCP file locally");
            }
        } else {
            $this->update_status("FAILED: Could not connect to Club Log");
        }
    }

    public function download_lotw_users() {



        $contents = file_get_contents('https://lotw.arrl.org/lotw-user-activity.csv', true);

        if($contents === FALSE) { 
            echo "something went wrong";
        } else {
            $file = './updates/lotw_users.csv';

            if(!is_file($file)){        // Some simple example content.
                file_put_contents($file, $contents);     // Save our content to the file.
            }

            echo "LoTW User Data Saved.";
        }

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

            file_put_contents($file, $contents);     // Save our content to the file.

            if (file_exists($file))
            {
                $nCount = count(file($file));
                if ($nCount > 0)
                {
                    echo "DONE: " . number_format($nCount) . " DOKs and SDOKs saved";
                } else {
                    echo"FAILED: Empty file";
                }
            } else {
                echo"FAILED: Could not create dok.txt file locally";
            }
        }
    }

    /*
     * Used for autoupdating the SOTA file which is used in the QSO entry dialog for autocompletion.
     */
    public function update_sota() {
        $csvfile = 'https://www.sotadata.org.uk/summitslist.csv';

        $sotafile = './assets/json/sota.txt';

        if($csvfile === FALSE) {
            echo "Something went wrong with fetching the SOTA file";
        } else {
            $csvhandle = fopen($csvfile,"r");

            $data = fgetcsv($csvhandle,1000,","); // Skip line we are not interested in
            $data = fgetcsv($csvhandle,1000,","); // Skip line we are not interested in
            $data = fgetcsv($csvhandle,1000,",");
            $sotafilehandle = fopen($sotafile, 'w');

            do {
                if ($data[0]) {
                    fwrite($sotafilehandle, $data[0].PHP_EOL);
                }
            } while ($data = fgetcsv($csvhandle,1000,","));

            fclose($csvhandle);
            fclose($sotafilehandle);
            if (file_exists($sotafile))
            {
                $nCount = count(file($sotafile));
                if ($nCount > 0)
                {
                    echo "DONE: " . number_format($nCount) . " SOTA's saved";
                } else {
                    echo"FAILED: Empty file";
                }
            } else {
                echo"FAILED: Could not create sota.txt file locally";
            }
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

        $wwfffilehandle = fopen($wwfffile, 'w');
        $data = str_getcsv($csv,"\n");
        foreach ($data as $idx => $row) {
           if ($idx == 0) continue; // Skip line we are not interested in
           $row = str_getcsv($row, ',');
           if ($row[0]) {
              fwrite($wwfffilehandle, $row[0].PHP_EOL);
           }
        }

        fclose($wwfffilehandle);
        if (file_exists($wwfffile))
        {
            $nCount = count(file($wwfffile));
            if ($nCount > 0)
            {
                echo "DONE: " . number_format($nCount) . " WWFF's saved";
            } else {
                echo"FAILED: Empty file";
            }
        } else {
            echo"FAILED: Could not create wwff.txt file locally";
        }
    }


}
?>
