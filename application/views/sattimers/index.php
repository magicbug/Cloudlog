<script type="text/javascript">
var custom_date_format = "<?php echo $custom_date_format ?>";
</script>
<div class="container">
<div class="table-responsive">
   <br>
    <h2>Satellite Timers</h2>
    <p>This data is from <a target="_blank" href="https://www.df2et.de/tevel/">https://www.df2et.de/tevel/</a> calculated for current station location grid <?php echo strtoupper($gridsquare);?>.</p>
    <script type="text/javascript">
        let dateArray = [];
        dateArray.push(0);
        <?php $i = 1;
           foreach ($activations as $activation) :
           if ($activation['timestamp'] != null) {
              echo "var tevel".$i."Date = ".$activation['timestamp']." * 1000;\n";
              echo "dateArray.push(tevel".$i."Date);\n";
              echo "var tevel".$i."Workable = ".($activation['timestamp'] > $activation['aos_time'] ? 1 : 0)."\n";
              echo "dateArray.push(tevel".$i."Workable);\n";
           } else {
              echo "dateArray.push(0);\n";
              echo "dateArray.push(0);\n";
           }
           $i++;
           endforeach; ?>

    </script>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Satellite</th>
                <th colspan="2">Status</th>
                <th>Time(d)-Out</th>
                <th>AOS</th>
                <th>LOS</th>
                <th style="text-align: center !important">AOS Azimuth</th>
                <th style="text-align: center !important">LOS Azimuth</th>
                <th style="text-align: center !important">Max Elevation</th>
                <th style="text-align: center !important">Duration</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($activations as $activation) : ?>
                <tr id="line">
                <td><span><?php echo $activation['sat']; ?></span></td>
                <td><span class="emoji" id="emoji<?php echo $i; ?>">n/a</span></td>
                <td><span id="tevel<?php echo $i; ?>Timer"></span></td>
                <td><span id="tevel<?php echo $i; ?>Timeout">...</span></td>
                <td><span id="tevel<?php echo $i; ?>AosTime"><?php echo date('H:i:s', $activation['aos_time']); ?></span></td>
                <td><span id="tevel<?php echo $i; ?>LosTime"><?php echo date('H:i:s', $activation['los_time']); ?></span></td>
                <td align="right"><span id="tevel<?php echo $i; ?>Aos"><?php echo $activation['aos']; ?>°</span><span style="margin-left: 10px; display: inline-block; transform: rotate(<?php echo (-45+$activation['aos']); ?>deg);"><i class="fas fa-location-arrow fa-xs"></i></span></td>
                <td align="right"><span id="tevel<?php echo $i; ?>Los"><?php echo $activation['los']; ?>°</span><span style="margin-left: 10px; display: inline-block; transform: rotate(<?php echo (-45+$activation['los']); ?>deg);"><i class="fas fa-location-arrow fa-xs"></i></span></td>
                <td align="right"><span id="tevel<?php echo $i; ?>MaxEl"><?php echo $activation['max_elev']; ?>°</span><span style="margin-left: 10px; display: inline-block; transform: rotate(-<?php echo ($activation['max_elev']); ?>deg);"><i class="fas fa-arrow-right fa-xs"></i></span></td>
                <td align="right"><span id="tevel<?php echo $i; ?>Duration"><?php echo $activation['duration_min']; ?> min</span></td>
                <td>
                <?php
                   if (strpos($activation['sat'], 'TEVEL') !== false) {
                      echo "<a href=\"https://mailman.amsat.org/hyperkitty/search?q=TEVEL&page=1&mlist=amsat-bb%40amsat.org&sort=date-desc\" target=\"_blank\">Info</a>";
                   } else if (strpos($activation['sat'], 'UVSQ') !== false) {
                      echo "<a href=\"https://x.com/uvsqsat?s=20\" target=\"_blank\">Info</a>";
                   } else if (strpos($activation['sat'], 'PO-101') !== false) {
                      echo "<a href=\"https://x.com/Diwata2PH?s=20\" target=\"_blank\">Info</a>";
                   } else if (strpos($activation['sat'], 'CAS-3H') !== false) {
                      echo "<a href=\"https://www.amsat.org/two-way-satellites/lilacsat-2-cas-3h/\" target=\"_blank\">Info</a>";
                   } else if (strpos($activation['sat'], 'LEDSAT') !== false) {
                      echo "<a href=\"https://www.esa.int/Education/CubeSats_-_Fly_Your_Satellite/Connect_and_communicate_with_a_satellite_via_the_LEDSAT_Digipeater_Challenge\" target=\"_blank\">Info</a>";
                   }
                ?>
                </td>
                </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
