<?php
/*
	Run: php application/tests/Logged_county_test.php
*/

define('BASEPATH', true);
require dirname(__DIR__) . '/libraries/Logged_county.php';

ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

assert(Logged_county::differs('', 'VA,Fairfax') === false, 'Blank county is not a different county');
assert(Logged_county::differs('VA, Fairfax County', 'VA,FAIRFAX') === false, 'Case, space and County suffix match');
assert(Logged_county::differs('Fairfax', 'VA,Fairfax') === false, 'County name without state matches STATE,County');
assert(Logged_county::differs('VA,Fairfax', 'VA,Arlington') === true, 'County-line partners differ');
assert(Logged_county::differs('VA,York', 'ME,York') === true, 'Same county name in different states differs');
assert(Logged_county::differs('VA,Fairfax', 'MD,Montgomery') === true, 'Different state and county differs');

$fairfax = array('COL_CNTY' => 'VA,Fairfax', 'COL_MY_CNTY' => 'MD,Montgomery');
$arlington = array('COL_CNTY' => 'VA,Arlington', 'COL_MY_CNTY' => 'MD,Montgomery');

assert(Logged_county::is_duplicate(array(), 'VA,Fairfax', '') === false, 'Nothing logged is not a duplicate');
assert(Logged_county::is_duplicate(array($fairfax), 'VA,FAIRFAX COUNTY', 'MD,Montgomery') === true, 'Same contact is a duplicate');
assert(Logged_county::is_duplicate(array($fairfax), 'VA,Arlington', 'MD,Montgomery') === false, 'Different logged county is not a duplicate');
assert(Logged_county::is_duplicate(array($fairfax), 'VA,Fairfax', 'MD,Prince Georges') === false, 'Different operator county is not a duplicate');
assert(Logged_county::is_duplicate(array($fairfax), 'VA,Fairfax', '') === true, 'Blank incoming operator county still matches');
assert(Logged_county::is_duplicate(array($fairfax, $arlington), 'VA,Arlington', '') === true, 'Matches the county-line partner already logged');
assert(Logged_county::is_duplicate(array($fairfax, $arlington), 'VA,Loudoun', '') === false, 'A third county is not a duplicate of either');

$qso_fairfax = array('COL_PRIMARY_KEY' => 1, 'COL_CNTY' => 'VA,Fairfax');
$qso_arlington = array('COL_PRIMARY_KEY' => 2, 'COL_CNTY' => 'VA,Arlington');
$qso_blank = array('COL_PRIMARY_KEY' => 3, 'COL_CNTY' => '');
$pair = array($qso_fairfax, $qso_arlington);

assert(Logged_county::pick_match($pair, 'VA,Arlington') === 2, 'Confirmation matches the logged county');
assert(Logged_county::pick_match(array($qso_blank, $qso_fairfax), 'VA,Arlington') === 3, 'Confirmation fills a QSO that has no county');
assert(Logged_county::pick_match($pair, 'VA,Loudoun') === null, 'Does not pick a county-line QSO with a different county');
assert(Logged_county::pick_match(array($qso_fairfax), 'VA,Arlington') === 1, 'Single QSO still matches when the county text differs');
assert(Logged_county::pick_match($pair, 'VA,Fairfax', array(1)) === 2, 'Only remaining QSO is used; caller must not replace its county');
assert(Logged_county::pick_match(array($qso_blank, $qso_fairfax), 'VA,Fairfax', array(3)) === 1, 'Second confirmation takes the remaining matching county');
assert(Logged_county::pick_match($pair, null) === 1, 'Callers that ignore county keep the first QSO');

echo "Logged_county_test: OK\n";
