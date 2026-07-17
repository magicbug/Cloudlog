<?php
function write_activators($activators_array, $vucc_grids, $custom_date_format, $band, $leogeo) {
    if ($band == '') {
       $band = 'All';
    }
    if ($leogeo == '') {
       $leogeo = 'both';
    }
    $i = 1;
    echo '<div class="table-responsive"><table style="width:100%" class="table table-sm activatorstable table-bordered table-hover table-striped align-middle text-center">
              <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">' . lang('gen_hamradio_callsign') . '</th>
                        <th scope="col">' . lang('general_word_count') . '</th>
                        <th scope="col">' . lang('gridsquares_gridsquares') . '</th>
                        <th scope="col">' . lang('gridsquares_show_qsos') . '</th>
                        <th scope="col">' . lang('gridsquares_show_map') . '</th>
                    </tr>
                </thead>
                <tbody>';

   $activators = array();
    foreach ($activators_array as $line) {
        $call = $line->call;
        $grids = $line->grids;
        $count = $line->count;
        if (array_key_exists($line->call, $vucc_grids)) {
           foreach(explode(',', $vucc_grids[$line->call]) as $vgrid) {
              if(!strpos($grids, $vgrid)) {
                 $grids .= ','.$vgrid;
              }
           }
           $grids = str_replace(' ', '', $grids);
           $grid_array = explode(',', $grids);
           sort($grid_array);
           $count = count($grid_array);
           $grids = implode(', ', $grid_array);
        }
         $activators[] = (object) array(
            'count' => $count,
            'call' => $call,
            'grids' => $grids,
         );
    }

      usort($activators, function ($left, $right) {
         if ($left->count === $right->count) {
            return strcmp($left->call, $right->call);
         }

         return $right->count <=> $left->count;
      });

    foreach ($activators as $line) {
        echo '<tr>
                <td>' . $i++ . '</td>
               <td>'.$line->call.'</td>
               <td>'.$line->count.'</td>
               <td style="text-align: left; font-family: monospace;">'.$line->grids.'</td>
               <td><button type="button" class="btn btn-sm btn-outline-primary" onclick="displayActivatorsContacts(\'' . $line->call . '\',\'' . $band . '\',\'' . $leogeo . '\')"><i class="fas fa-list"></i></button></td>
               <td><button type="button" class="btn btn-sm btn-outline-primary" onclick="spawnActivatorsMap(\'' . $line->call . '\',\'' . $line->count . '\',\'' . str_replace(' ', '', $line->grids) . '\')"><i class="fas fa-globe"></i></button></td>
               </tr>';
    }
    echo '</tbody></table></div>';
}

write_activators($activators_array, $vucc_grids, $custom_date_format, $band, $leogeo);
