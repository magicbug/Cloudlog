<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dayswithqso_model extends CI_Model
{
    function getDaysWithQso()
    {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = "select year(COL_TIME_ON) Year, COUNT(DISTINCT TO_DAYS(COL_TIME_ON)) as Days from "
            .$this->config->item('table_name'). " thcv
            where station_id in (" . $location_list . ") and COL_TIME_ON is not null group by year";

        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * Function returns current streak
     */
    function getCurrentStreak() {
        $dates = $this->getDates();

        if ($dates) {
            $dates = array_reverse($dates);
            $streak = 1;
            $firstrun = true;
    
            $dateprev = date_create(date('Y-m-d'));
    
            foreach($dates as $date) {      // Loop through the result set
                $datecurr = date_create($date->date);
                $diff = $dateprev->diff($datecurr)->format("%a"); // Getting date difference between current date and previous date in array
    
                if ($diff == 0) {
                    $streaks['highstreak'] = $streak;
                    $streaks['endstreak'] = $datecurr->format('Y-m-d');
                    $streaks['beginstreak'] = $datecurr->format('Y-m-d');
                    $firstrun = false;
                }
                else if ($diff == 1 and !$firstrun) {   // If diff = 1, means that we are on a streak
                    $streaks['highstreak'] = ++$streak;
                    $streaks['beginstreak'] = date_create($streaks['endstreak'])->sub(new DateInterval('P'.($streak-1).'D'))->format('Y-m-d');
                } else {
                    break;
                }
                $dateprev = date_create($date->date);
            }
    
            if (isset($streaks) && is_array($streaks)) {
                return $streaks;
            } else {
                return null;
            }
        }
    }

    /*
     * Function returns streak that ended yesterday, but can be continued if a qso is made today
     */
    function getAlmostCurrentStreak() {
        $dates = $this->getDates();

        if  ($dates) {
            $dates = array_reverse($dates);
            $streak = 1;
            $firstrun = true;
    
            $dateprev = date_create(date('Y-m-d'));
    
            foreach($dates as $date) {      // Loop through the result set
                $datecurr = date_create($date->date);
                $diff = $dateprev->diff($datecurr)->format("%a"); // Getting date difference between current date and previous date in array
    
                if ($diff == 1 && $firstrun == true) {
                    $streaks['highstreak'] = $streak++;
                    $streaks['endstreak'] = $datecurr->format('Y-m-d');
                    $streaks['beginstreak'] = $datecurr->format('Y-m-d');
                    $firstrun = false;
                }
                else if ($diff == 1 && $firstrun == false) {
                        $streaks['highstreak'] = $streak++;
                        $streaks['beginstreak'] = date_create($streaks['endstreak'])->sub(new DateInterval('P'.($streak-2).'D'))->format('Y-m-d');
                } else {
                    break;
                }
                $dateprev = date_create($date->date);
            }
    
            if (isset($streaks) && is_array($streaks)) {
                return $streaks;
            } else {
                return null;
            }
        }

    }

    /*
     * Function returns the 10 longest streaks of QSOs based on all QSO dates in the log on active station profile
     */
    function getLongestStreak() {
        $dates = $this->getDates();
        $streak = 1;        // A day with a qso will always be a streak
        $dateprev = date_create('1900-01-01'); // init variable with an old date
        $i = 0;

        if ($dates) {
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
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = "select distinct cast(col_time_on as date) as date from "
            .$this->config->item('table_name'). " thcv
            where station_id in (" . $location_list . ") order by date asc";

        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * Returns the total number of QSOs made for each day of the week (Monday to Sunday)
     */
    function getDaysOfWeek()
    {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = "SELECT DAYNAME(col_time_off) AS weekday, COUNT(*) AS qsos FROM " . $this->config->item('table_name')
              . " WHERE WEEKDAY(col_time_off) BETWEEN 0 AND 6 AND station_id in (" . $location_list . ")"
              . " GROUP BY weekday ORDER BY FIELD(weekday, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * Returns the number of QSOs made for each day in the log
     */
    function getHistoryDays()
    {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            return null;
        }

        $min_date_query = $this->db->query('SELECT MIN(DATE(col_time_off)) AS min_date FROM ' . $this->config->item('table_name'));
        $min_date = $min_date_query->row()->min_date;
        $max_date_query = $this->db->query('SELECT MAX(DATE(col_time_off)) AS max_date FROM ' . $this->config->item('table_name'));
        $max_date = $max_date_query->row()->max_date;

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = "WITH RECURSIVE all_dates AS (
                    SELECT ? AS date
                    UNION ALL
                    SELECT DATE_ADD(date, INTERVAL 1 DAY)
                    FROM all_dates
                    WHERE DATE_ADD(date, INTERVAL 1 DAY) <= ?
                )
                SELECT all_dates.date AS day, COUNT(" . $this->config->item('table_name') . ".col_time_off) AS qsos
                FROM all_dates
                LEFT JOIN " . $this->config->item('table_name') . " ON DATE(" . $this->config->item('table_name') . ".col_time_off) = all_dates.date
                AND station_id in (" . $location_list . ")
                GROUP BY all_dates.date
                ORDER BY all_dates.date";

        $query = $this->db->query($sql, [$min_date, $max_date]);
        $days = $query->result_array();

        return $days;
    }
}
