<?php
/*
   Copyright 2011 Jason Harris KJ4IWX

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

class ADIF_Parser
{

	var $data; //the adif data
	var $i; //the iterator
	var $current_line; //stores information about the current qso
	
	public function initialize() //this function locates the <EOH>
	{
		$pos = stripos($this->data, "<eoh>");
		if($pos == false) //did we find the end of headers?
		{
			echo "Error: Adif_Parser Already Initialized or No <EOH> in ADIF File";
			return 0;
		};
		$this->i = $pos+5; //iterate past the <eoh>
		if($this->i >= strlen($this->data)) //is this the end of the file?
		{
			echo "Error: ADIF File Does Not Contain Any QSOs";
			return 0;
		};
		return 1;
	}
	
	public function feed($input_data) //allows the parser to be fed a string
	{
		$this->data = $input_data;
	}
	
	public function load_from_file($fname) //allows the user to accept a filename as input
	{
		$this->data = file_get_contents($fname);
	}
	
	//the following function does the processing of the array into its key and value pairs
	public function record_to_array($record)
	{
		$return = array();
		for($a = 0; $a < strlen($record); $a++)
		{
			if($record[$a] == '<') //find the start of the tag
			{
				$tag_name = "";
				$value = "";
				$len_str = "";
				$len = 0;
				$a++; //go past the <
				while($record[$a] != ':') //get the tag
				{
					$tag_name = $tag_name.$record[$a]; //append this char to the tag name
					$a++;
				};
				$a++; //iterate past the colon
				while($record[$a] != '>' && $record[$a] != ':')
				{
					$len_str = $len_str.$record[$a];
					$a++;
				};
				if($record[$a] == ':')
				{
					while($record[$a] != '>')
					{
						$a++;
					};
				};
				$a++; //iterate over the >
				$len = (int)$len_str;
				while($len > 0)
				{
					$value = $value.$record[$a];
					$len--;
					$a++;
				};
				$return[strtolower($tag_name)] = $value;
			};
		};
		return $return;
	}
	
	
	//finds the next record in the file
	public function get_record()
	{
		if($this->i >= strlen($this->data))
		{
			return array(); //return nothing
		};
		$end = stripos($this->data, "<eor>", $this->i);
		if($end == false) //is this the end?
		{
			return array(); //return nothing
		};
		$record = substr($this->data, $this->i, $end-$this->i);
		$this->i = $end+5;
		return $this->record_to_array($record); //process and return output
 	}
}
?>