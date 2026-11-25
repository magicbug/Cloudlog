<div class="container">
    <style>
        .counties-hero {
            margin-top: 1rem;
            margin-bottom: 1rem;
            padding: 1rem 1.25rem;
            border: 1px solid #d6e7ff;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #f8fbff 0%, #edf5ff 100%);
        }

        .counties-stats {
            margin-bottom: 1rem;
        }

        .counties-stat-card {
            border: 1px solid #e5e8ec;
            border-radius: 0.75rem;
            background-color: #fff;
            padding: 0.9rem 1rem;
            height: 100%;
        }

        .counties-stat-label {
            font-size: 0.75rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 0.2rem;
        }

        .counties-stat-value {
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1.2;
            color: #1f2937;
        }

        .counties-stat-sub {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .counties-table-wrap {
            border: 1px solid #dee2e6;
            border-radius: 0.75rem;
            padding: 0.5rem;
            background-color: #fff;
            margin-bottom: 1rem;
        }

        .counties-progress {
            min-width: 120px;
            margin-bottom: 0;
            height: 0.8rem;
            border-radius: 999px;
            background-color: #e9ecef;
        }

        .counties-progress .progress-bar {
            font-size: 0.65rem;
            font-weight: 600;
        }

        .counties-label-chip {
            display: inline-block;
            padding: 0.15rem 0.45rem;
            border-radius: 0.4rem;
            font-size: 0.75rem;
            color: #0f172a;
            background-color: #e2e8f0;
        }

        .counties-tiers {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .counties-tier {
            flex: 1 1 110px;
            border: 1px solid #e5e8ec;
            border-radius: 0.6rem;
            padding: 0.5rem 0.75rem;
            text-align: center;
            background-color: #fff;
            color: #1f2937;
        }

        .counties-tier.reached {
            border-color: #198754;
            background-color: #e8f5ee;
        }

        .counties-tier-name {
            font-weight: 700;
        }

        .counties-tier-sub {
            font-size: 0.75rem;
            color: #6b7280;
        }

        #countiesmap {
            height: 70vh;
            min-height: 400px;
            max-height: 750px;
        }

        #countiesmap .legend {
            padding: 6px 8px;
            font: 14px Arial, Helvetica, sans-serif;
            background: rgba(255, 255, 255, 0.8);
            line-height: 24px;
            color: #555;
            border-radius: 5px;
        }

        #countiesmap .legend h4 {
            text-align: center;
            font-size: 16px;
            margin: 2px 12px 8px;
            color: #555;
        }

        #countiesmap .legend span {
            position: relative;
            bottom: 3px;
            color: #555;
        }

        #countiesmap .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin: 0 8px 0 0;
            opacity: 0.7;
        }

        #countiesmap .counties-map-info {
            padding: 6px 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #222;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        #countiesmap .counties-map-info h4 {
            margin: 0 0 5px;
            font-size: 0.95rem;
        }

        #countiesmap .counties-map-back .btn {
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script>
        var tileUrl = "<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>";
    </script>

    <div class="awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => $page_title,
        'subtitle' => 'MARAC United States of America Counties Award (USA-CA) &middot; Track worked counties toward each 500-county class and the full 3,077',
        'icon' => 'fas fa-map',
        'info_lines' => array(
            lang('awards_counties_description_ln1'),
            lang('awards_counties_description_ln2'),
            lang('awards_counties_description_ln3'),
            lang('awards_counties_description_ln4'),
        ),
    )); ?>
    </div>

    <?php if ($counties_array) {
        $summary = $counties_array;
        $overall_target = (int)$summary['target'];
        $worked_total = (int)$summary['worked'];
        $confirmed_total = (int)$summary['confirmed'];
        $tiers = $summary['tiers'];

        $highest_tier = function ($count) use ($tiers) {
            $reached = null;
            foreach ($tiers as $tier) {
                if ($count >= $tier) {
                    $reached = $tier;
                }
            }
            return $reached;
        };
        $tier_name = function ($tier) {
            return $tier === null ? '-' : 'USA ' . $tier;
        };

        $next_tier = null;
        foreach ($tiers as $tier) {
            if ($worked_total < $tier) {
                $next_tier = $tier;
                break;
            }
        }

        $remaining_total = max(0, $overall_target - $worked_total);
        $worked_progress = $overall_target > 0 ? round(($worked_total / $overall_target) * 100, 1) : 0;
        $confirmed_progress = $overall_target > 0 ? round(($confirmed_total / $overall_target) * 100, 1) : 0;
        ?>

        <div class="row counties-stats g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="counties-stat-card">
                    <div class="counties-stat-label">Worked Counties</div>
                    <div class="counties-stat-value"><?php echo $worked_total; ?></div>
                    <div class="counties-stat-sub">of <?php echo $overall_target; ?> USA-CA counties</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="counties-stat-card">
                    <div class="counties-stat-label">Award Class</div>
                    <div class="counties-stat-value"><?php echo $tier_name($highest_tier($worked_total)); ?></div>
                    <div class="counties-stat-sub"><?php echo $next_tier !== null ? ($next_tier - $worked_total) . ' more for USA ' . $next_tier : 'All classes reached'; ?></div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="counties-stat-card">
                    <div class="counties-stat-label">Confirmed Counties</div>
                    <div class="counties-stat-value"><?php echo $confirmed_total; ?></div>
                    <div class="counties-stat-sub">QSL, eQSL or LoTW (not required by MARAC)</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="counties-stat-card">
                    <div class="counties-stat-label">Remaining Counties</div>
                    <div class="counties-stat-value"><?php echo $remaining_total; ?></div>
                    <div class="counties-stat-sub"><?php echo $worked_progress; ?>% worked, <?php echo $confirmed_progress; ?>% confirmed</div>
                </div>
            </div>
        </div>

        <div class="counties-tiers">
            <?php foreach ($tiers as $tier) { ?>
                <div class="counties-tier<?php echo $worked_total >= $tier ? ' reached' : ''; ?>">
                    <div class="counties-tier-name">USA <?php echo $tier; ?></div>
                    <div class="counties-tier-sub"><?php echo $worked_total >= $tier ? 'Reached' : ($tier - $worked_total) . ' to go'; ?></div>
                </div>
            <?php } ?>
        </div>

        <?php if (!empty($summary['city_credits'])) {
            $open_cities = 0;
            foreach ($summary['city_credits'] as $city) {
                if (!empty($city['open_neighbors'])) {
                    $open_cities++;
                }
            }
        ?>
            <div class="alert alert-info" role="alert">
                <?php echo count($summary['city_credits']); ?> independent city contact(s) are not counted yet because the city borders more than one county<?php echo $open_cities > 0 ? ' (' . $open_cities . ' could still fill an unworked county)' : ''; ?>.
                MARAC lets you claim one adjoining county for each.
                <a href='counties_details?State="All"&Type="worked"#independent-cities'>Review independent city credits</a>.
            </div>
        <?php } ?>

        <?php if (!empty($summary['unknown'])) { ?>
            <div class="alert alert-warning" role="alert">
                <?php echo count($summary['unknown']); ?> logged county name(s) do not match a USA-CA county and are not counted:
                <?php echo htmlspecialchars(implode('; ', array_slice($summary['unknown'], 0, 25))); ?><?php echo count($summary['unknown']) > 25 ? '; ...' : ''; ?>
                <div class="mt-2">
                    <a class="btn btn-sm btn-warning" href="<?php echo site_url('awards/counties_fix'); ?>">Review and fix invalid counties</a>
                </div>
            </div>
        <?php } ?>

        <ul class="nav nav-tabs" id="countiesTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="counties-table-tab" data-bs-toggle="tab" href="#countiestabletab" role="tab" aria-controls="countiestabletab" aria-selected="true">Table</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="counties-map-tab" data-bs-toggle="tab" href="#countiesmaptab" role="tab" aria-controls="countiesmaptab" aria-selected="false">Map</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="countiestabletab" role="tabpanel" aria-labelledby="counties-table-tab">
                <div class="counties-table-wrap">
                    <table style="width:100%" class="countiestable table table-sm table-bordered table-hover table-striped table-condensed text-center align-middle">
                        <thead>
                            <tr>
                                <th scope="col">State</th>
                                <th scope="col">Worked</th>
                                <th scope="col">Confirmed</th>
                                <th scope="col">Target</th>
                                <th scope="col">Remaining</th>
                                <th scope="col">Worked Progress</th>
                                <th scope="col">Confirmed Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($summary['states'] as $state => $counts) {
                                $worked = (int)$counts['worked'];
                                $confirmed = (int)$counts['confirmed'];
                                $target = (int)$counts['target'];
                                $remaining = max(0, $target - $worked);
                                $worked_pct = $target > 0 ? round(($worked / $target) * 100, 1) : 0;
                                $confirmed_pct = $target > 0 ? round(($confirmed / $target) * 100, 1) : 0;
                            ?>
                                <tr>
                                    <td><span class="counties-label-chip"><?php echo $state; ?></span></td>
                                    <td><a href='counties_details?State="<?php echo $state; ?>"&Type="worked"'><?php echo $worked; ?></a></td>
                                    <td><a href='counties_details?State="<?php echo $state; ?>"&Type="confirmed"'><?php echo $confirmed; ?></a></td>
                                    <td><?php echo $target; ?></td>
                                    <td><?php echo $remaining; ?></td>
                                    <td>
                                        <div class="progress counties-progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $worked_pct; ?>%" aria-valuenow="<?php echo $worked_pct; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $worked_pct; ?>%</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress counties-progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $confirmed_pct; ?>%" aria-valuenow="<?php echo $confirmed_pct; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $confirmed_pct; ?>%</div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row">Total</th>
                                <th><a href='counties_details?State="All"&Type="worked"'><?php echo $worked_total; ?></a></th>
                                <th><a href='counties_details?State="All"&Type="confirmed"'><?php echo $confirmed_total; ?></a></th>
                                <th><?php echo $overall_target; ?></th>
                                <th><?php echo $remaining_total; ?></th>
                                <th><?php echo $worked_progress; ?>%</th>
                                <th><?php echo $confirmed_progress; ?>%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="countiesmaptab" role="tabpanel" aria-labelledby="counties-map-tab">
                <div id="countiesmap" class="map-leaflet"></div>
                <p class="text-muted small mt-2">States are shaded by county progress. Click a state for its counties; click a worked or confirmed county to open the QSO list.</p>
            </div>
        </div>

        <h4 class="mt-4">Single band and single mode endorsements</h4>
        <p class="text-muted small">MARAC endorses an award class for one band or one mode when every county counted was worked on that band or mode. SSB, CW and Digital are counted separately; AM, FM and digital voice only count toward the basic award.</p>
        <div class="counties-table-wrap">
            <table style="width:100%" class="table table-sm table-bordered table-hover table-striped text-center align-middle">
                <thead>
                    <tr>
                        <th scope="col">Band / Mode</th>
                        <th scope="col">Worked</th>
                        <th scope="col">Confirmed</th>
                        <th scope="col">Class Reached</th>
                        <th scope="col">Next Class</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $endorsements = array();
                    foreach ($summary['modes'] as $mode => $counts) {
                        $endorsements[$mode] = $counts;
                    }
                    foreach ($summary['bands'] as $band => $counts) {
                        $endorsements[$band] = $counts;
                    }
                    foreach ($endorsements as $label => $counts) {
                        $next = null;
                        foreach ($tiers as $tier) {
                            if ($counts['worked'] < $tier) {
                                $next = $tier;
                                break;
                            }
                        }
                    ?>
                        <tr>
                            <td><span class="counties-label-chip"><?php echo htmlspecialchars($label); ?></span></td>
                            <td><?php echo (int)$counts['worked']; ?></td>
                            <td><?php echo (int)$counts['confirmed']; ?></td>
                            <td><?php echo $tier_name($highest_tier((int)$counts['worked'])); ?></td>
                            <td><?php echo $next !== null ? 'USA ' . $next . ' (' . ($next - (int)$counts['worked']) . ' to go)' : '-'; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else {
        echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
    } ?>
</div>
