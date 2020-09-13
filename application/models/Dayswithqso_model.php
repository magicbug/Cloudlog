<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dayswithqso_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getDaysWithQso()
    {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $sql = "select year(COL_TIME_ON) Year, COUNT(DISTINCT TO_DAYS(COL_TIME_ON)) as Days from "
            .$this->config->item('table_name'). " thcv
            where station_id = " . $station_id . " and COL_TIME_ON is not null group by year";

        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * Function returns the 10 longest streaks of QSOs based on all QSO dates in the log on active station profile
     */
    function getLongestStreak() {
        $dates = $this->getDates();
        $streak = 1;        // A day with a qso will always be a streak
        $dateprev = date_create('1900-01-01'); // init variable with an old date
        $i = 0;

        foreach($dates as $date) {      // Loop through the result set
            $datecurr = date_create($date->date);
            if ($dateprev == date_create('1900-01-01')) { // If first run
                $dateprev = $datecurr;
            }
            else {
                $diff = $dateprev->diff($datecurr)->format("%a"); // Getting date difference between current date and previous date in array
                if ($diff == 1) {   // If diff = 1, means that we are on a streak
                    $streak++;
                    $endstreak = $datecurr; // As long as the streak continues, we update the end date
                } else {
                    if ($streak > 1) {
                        $streaks[$i]['highstreak'] = $streak;
                        $streaks[$i]['endstreak'] = $endstreak->format('Y-m-d');
                        $streaks[$i]['beginstreak'] = $endstreak->sub(new DateInterval('P'.($streak-1).'D'))->format('Y-m-d');
                        $i++;
                    }
                    $streak = 1;
                }
                $dateprev = date_create($date->date);
            }
        }

        if (isset($streaks) && is_array($streaks)) {
            usort($streaks, array($this,'compareStreak'));      // Sort array, highest streak first
            $streaks_trimmed = array_slice($streaks, 0,10);  // We only want top 10, so we trim the array
            return $streaks_trimmed;
        } else {
            return null;
        }
    }

    /*
     * Used for sorting the array highest streak first
     */
    function compareStreak($a, $b) {
        return strnatcmp($b['highstreak'], $a['highstreak']);
    }

    /*
     * Returns all distinct dates from db on active profile
     */
    function getDates() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $sql = "select distinct cast(col_time_on as date) as date from "
            .$this->config->item('table_name'). " thcv
            where station_id = " . $station_id . " order by date asc";

        $query = $this->db->query($sql);

        return $query->result();
    }

}