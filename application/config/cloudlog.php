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
| CAT Timeout Warning Inverval
|--------------------------------------------------------------------------
| 
| The external CAT applications can obviously stop working for various reasons 
| this interval is used for displaying a warning on the QSO Panel
|
| Default is: 300 seconds (5 minutes)
|
*/

$config['cat_timeout_interval'] = 300;

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

$config['callsign_tags'] = TRUE;