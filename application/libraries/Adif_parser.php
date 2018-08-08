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
	var $headers = array();
	
	public function initialize() //this function locates the <EOH>
	{
		$pos = stripos($this->data, "<eoh>");
		if($pos == false) //did we find the end of headers?
		{
			echo "Error: Adif_Parser Already Initialized or No <EOH> in ADIF File";
			return 0;
		};
		
		
		//get headers
		
		$this->i = 0;
		$in_tag = false;
		$tag = "";
		$value_length = "";
		$value = "";
				
		while($this->i < $pos)
		{
			//skip comments
			if($this->data[$this->i] == "#")
			{
				while($this->i < $pos)
				{
					if($this->data[$this->i] == "\n")
					{
						break;
					}
				
					$this->i++;
				}
			}else{
				//find the beginning of a tag
				if($this->data[$this->i] == "<")
				{
					$this->i++;
					//record the key
					while($this->data[$this->i] < $pos && $this->data[$this->i] != ':')
					{
						$tag = $tag.$this->data[$this->i];
						$this->i++;
					}
					
					$this->i++; //iterate past the :
					
					//find out how long the value is
					
					while($this->data[$this->i] < $pos && $this->data[$this->i] != '>')
					{
						$value_length = $value_length.$this->data[$this->i];
						$this->i++;
					}
					
					$this->i++; //iterate past the >
					
					$len = (int)$value_length;
					//copy the value into the buffer
					while($len > 0 && $this->i < $pos)
					{
						$value = $value.$this->data[$this->i];
						$len--;
						$this->i++;
					};

					$this->headers[strtolower(trim($tag))] = $value; //convert it to lowercase and trim it in case of \r
					//clear all of our variables
					$tag = "";
					$value_length = "";
					$value = "";
					
				}
			}
			
			$this->i++;
			
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
				$len = (int)$len_str;
				while($len > 0)
				{
					$a++;
					$value = $value.$record[$a];
					$len--;
				};
				$return[strtolower($tag_name)] = $value;
			};
			//skip comments
			if($record[$a] == "#")
			{
				while($a < strlen($record))
				{
					if($record[$a] == "\n")
					{
						break;
					}
					$a++;
				}
			}
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
	
	public function get_header($key)
	{
		if(array_key_exists(strtolower($key), $this->headers))
		{
			return $this->headers[strtolower($key)];
		}else{
			return NULL;
		}
	}
	
}
?>