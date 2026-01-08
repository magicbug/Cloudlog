<?php

defined('BASEPATH') OR exit('Direkter Zugriff auf Skripte ist nicht erlaubt');

$lang['statistics_statistics'] = 'Statistiken';

$lang['statistics_explore_the_logbook'] = 'Logbuch untersuchen.';

// Personal Propagation Advisor
$lang['propagation_title'] = 'Personal Propagation Advisor';
$lang['propagation_description'] = 'See when you most often work a DXCC entity, using your own QSOs only.';
$lang['propagation_select_dxcc'] = 'DXCC entity';
$lang['propagation_select_band'] = 'Band (optional)';
$lang['propagation_select_mode'] = 'Mode (optional)';
$lang['propagation_best_window'] = 'Best UTC window';
$lang['propagation_best_band'] = 'Most successful band';
$lang['propagation_best_mode'] = 'Most successful mode';
$lang['propagation_last_worked'] = 'Last worked';
$lang['propagation_total_qsos'] = 'Total QSOs analyzed';
$lang['propagation_heatmap_title'] = '24-hour heatmap';
$lang['propagation_band_breakdown'] = 'Band breakdown';
$lang['propagation_download_csv'] = 'Download CSV';
$lang['propagation_required_filters'] = 'Select a DXCC to start.';
$lang['propagation_no_data'] = 'No QSOs found for this selection.';
$lang['propagation_relative_intensity'] = 'Relative intensity:';
$lang['propagation_intensity_none'] = 'None';
$lang['propagation_intensity_low'] = 'Low';
$lang['propagation_intensity_medium'] = 'Medium';
$lang['propagation_intensity_high'] = 'High';
$lang['propagation_intensity_very_high'] = 'Very high';
$lang['propagation_strongest_band_label'] = 'Strongest band';
$lang['propagation_activity_last_30'] = 'Activity last 30 days';
$lang['propagation_trend_7d'] = '7d';
$lang['propagation_trend_30d'] = '30d';
$lang['propagation_trend_90d'] = '90d';

$lang['statistics_years'] = 'Jahre';
$lang['statistics_modes'] = 'Modi';
$lang['statistics_bands'] = 'Bänder';
$lang['statistics_qsos'] = 'QSOs';
$lang['statistics_unique_callsigns'] = 'Eindeutige Rufzeichen';

$lang['statistics_total'] = 'Gesamt';

$lang['statistics_year'] = 'Jahr';

$lang['statistics_number_of_qso_worked_each_year'] = "Anzahl der QSOs gearbeitet pro Jahr";
$lang['statistics_number_of_qso_worked'] = "# gearbeitete QSOs";

/*
*
* Distances
*
*/

$lang['statistics_distances_bands_all'] = "Alle";
$lang['statistics_distances_modes_all'] = "Alle";
$lang['statistics_distances_worked'] = "Gearbeitete Entfernungen";
$lang['statistics_distances_part1_contacts_were_plotted_furthest'] = "Kontakte wurden dargestellt.<br /> Der weiteste Kontakt war"; // make sure'<br />' stays there
$lang['statistics_distances_part2_contacts_were_plotted_furthest'] = "im Planquadrat";
$lang['statistics_distances_part3_contacts_were_plotted_furthest'] = "Die Distanz betrug";
$lang['statistics_distances_part4_contacts_were_plotted_furthest'] = "Die durchschnittliche Distanz ist";
$lang['statistics_distances_number_of_qsos'] = "Anzahl der QSOs";
$lang['statistics_distances_callsigns_worked'] = "Gearbeitete(s) Rufzeichen (max 5 werden gezeigt)";
$lang['statistics_distances_qsos_with'] = "QSOs mit Distanz : ";
$lang['statistics_distances_and_band'] = ", Band : ";
$lang['statistics_distances_and_mode'] = ", Mode : ";
$lang['statistics_distances_and_power'] = ", Sendeleistung : ";
$lang['statistics_distances_and_propagation'] = ", Wellenausbreitung : ";
$lang['statistics_distances_no_qsos_to_plot'] = "No QSOs found to plot.";

/*
*
* Timeline
*
*/

$lang['statistics_timeline'] = "Zeitleiste";

/*
*
* Days with QSO
*
*/

$lang['statistics_tab_yearly'] = "Yearly";
$lang['statistics_tab_streaks'] = "Streaks";
$lang['statistics_tab_weekdays'] = "Days of the week";
$lang['statistics_tab_daily'] = "Daily";
$lang['statistics_days_yearly'] = "Number of days with QSOs each year";
$lang['statistics_days_with_qso'] = "Tage mit QSOs";
$lang['statistics_qsos_each_day'] = "Number of QSOs each day";
$lang['statistics_weekdays_with_qso'] = "QSOs breakdown by day of the week";
$lang['statistics_number_of_qsos_this_day'] = "Number of QSOs this day";
$lang['statistics_number_of_qsos_this_weekday'] = "Number of QSOs for this day of the week";
$lang['statistics_dwq_longest_streak_in_log'] = "Längste Serie mit QSOs im Logbuch";
$lang['statistics_dwq_longest_streak_in_log_hint'] = "Es werden maximal die 10 längsten Serien angezeigt!";
$lang['statistics_dwq_streak_continuous_days'] = "Serie (fortlaufende Tage mit QSOs)";
$lang['statistics_dwq_current_streak_in_log'] = "Aktuelle Serie mit QSOs im Logbuch";
$lang['statistics_dwq_current_streak_continuous_days'] = "Aktuelle Serie (fortlaufende Tage mit QSOs)";
$lang['statistics_dwq_make_qso_to_extend_streak'] = "Wenn Sie heute ein QSO machen, können Sie Ihre Serie verlängern... Andernfalls wird Ihre aktuelle Serie unterbrochen!";
$lang['statistics_dwq_no_current_streak'] = "Keine aktuelle Serie gefunden!";
