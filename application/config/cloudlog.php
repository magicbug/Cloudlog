<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*
* Config file for storing fixed variables
*
*/

/*
|--------------------------------------------------------------------------
| Show time on dashboard and QSO-details if not logged in
|--------------------------------------------------------------------------
| 
| If you want to show QSO-times (additional to the date) on the dashboard
| and on QSO-detail-view, set this to TRUE, otherwise set it to FALSE
|
*/
$config['show_time'] = FALSE;

/*
|--------------------------------------------------------------------------
| Configure the measurement base distance is measured in
|--------------------------------------------------------------------------
| 
| Here you can configure different measurement bases to be used on
| distance caculations. Valid values are:
| M: miles
| K: kilometers
| N: nautic miles
| 
| Default is: M
|
*/
$config['measurement_base'] = 'M';

/*
|--------------------------------------------------------------------------
| Show Gridsquares on Maps
|--------------------------------------------------------------------------
| 
| You can turn on whether gridsquares are shown on maps automatically else 
| you have to turn them on via the layer control
|
| Default is: FALSE
|
*/
$config['map_gridsquares'] = FALSE;

/*
|--------------------------------------------------------------------------
| Public Search
|--------------------------------------------------------------------------
| 
| Setting this to TRUE makes the search bar at the top of the display 
| visable and usable to all users and visitors
|
| Default is: FALSE
|
*/

$config['public_search'] = FALSE;

/*
|--------------------------------------------------------------------------
| Callsign Tags
|--------------------------------------------------------------------------
| 
| Setting this to TRUE switches on the visibility of the callsign-labels  
| within the log-table
|
| Default is: TRUE
|
*/

$config['callsign_tags'] = TRUE;

/*
|--------------------------------------------------------------------------
| Date Format
|--------------------------------------------------------------------------
|
| QSO Date format allows you to change the frontend display date to something 
| that suits your region or operating style better.
|
| It uses the php date format so see https://www.php.net/manual/en/function.date.php
| for information on the layout.
|
|	Example
|	d/m/y = day/month/year
| 	Y/m/d = 2020/02/21
|
| Default is: d/m/y
|
*/

$config['qso_date_format'] = "d/m/y";

/*
|--------------------------------------------------------------------------
| Show 6-digit Grid Squares on Maps
|--------------------------------------------------------------------------
|
| Setting this to TRUE allows the map functions to also show worked and
| confirmed 6-digit grid squares. As this may consume much memory with large
| logs this is disabled by default. Use at your own risk.
|
| Default is: FALSE
|
*/

$config['map_6digit_grids'] = FALSE;


/*
|--------------------------------------------------------------------------
| Automatically populate the QTH
|--------------------------------------------------------------------------
|
| Setting this to TRUE allows the QTH locator to be pre-filled
| based on the person's location when creating new QSO.
| OSM's Nominatim API is being used for that purpose
|
| Default is: FALSE
|
*/

$config['qso_auto_qth'] = FALSE;
