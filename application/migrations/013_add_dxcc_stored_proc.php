<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_dxcc_stored_proc extends CI_Migration {

  public function up(){
    $res = $this->db->query("drop procedure if exists `find_country`");
    if (!$res){
      print ("Error dropping stored proc");
      exit(0);
    }

    $sql = <<<EOF
CREATE PROCEDURE `find_country`(
  IN callsign varchar(10), 
  IN qso_date date, 
  OUT country varchar(255),
  OUT dxcc_id int)
begin
  declare calllen int default 0;
  set calllen = char_length(callsign);
  L1: while calllen >0 do
    select `entity`, `adif` into country, dxcc_id
      from dxcc_prefixes 
      where `call`= substring(callsign, 1, calllen)
      and (`start` <= qso_date or `start`='0000-00-00')
      and (`end` >= qso_date or `end`='0000-00-00');
    if (FOUND_ROWS() >0) THEN
      LEAVE L1;
    else
      set calllen = calllen - 1;
    end IF;
  end WHILE;
end
EOF;

    $res = $this->db->query($sql);
    if (!$res){
      print ("Error setting stored proc");
      exit(0);
    }
  }

  public function down(){
    $this->db->query("drop procedure if exists `find_country`");
  }
}
