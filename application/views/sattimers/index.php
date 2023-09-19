<div class="container">
<div class="table-responsive">

    <h2>Satellite Timers</h2>
    <p>This data is from <a target="_blank" href="https://www.df2et.de/tevel/">https://www.df2et.de/tevel/</a></p>
    <script type="text/javascript">
        let dateArray = [];
        dateArray.push(0);
        <?php $i = 1;
           foreach ($activations as $activation) :
           if ($activation['timestamp'] != null) {
              echo "var tevel".$i."Date = ".$activation['timestamp']." * 1000;\n";
              echo "dateArray.push(tevel".$i."Date);\n";
           } else {
              echo "dateArray.push(0);\n";
           }
           $i++;
           endforeach; ?>
        //var tevel11Date = new Date(new Date("2023-09-19T22:00:00.000Z").getTime());
        //dateArray.push(tevel11Date);

    </script>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Satellite</th>
                <th colspan="2">Status</th>
                <th>Time-Out</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($activations as $activation) : ?>
                <tr>
                <td><span><?php echo $activation['sat']; ?></span></td>
                <td><span class="emoji" id="emoji<?php echo $i; ?>">n/a</span></td>
                <td><span id="tevel<?php echo $i; ?>Timer"></span></td>
                <td><span class="timeout" id="tevel<?php echo $i; ?>Timeout">...</span></td>
                </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
