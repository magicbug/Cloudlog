<?php
if ($result) {
    array_to_table($result);
}

function array_to_table($table) 
{   
   echo '<table class="table-sm table table-bordered table-hover table-striped table-condensed text-center">';

   // Table header
        foreach ($table[0] as $key=>$value) {
            echo "<th>".$key."</th>";
        }

    // Table body
       foreach ($table as $value) {
           echo "<tr>";
           foreach ($value as $val) {
                 echo "<td>".$val."</td>";
           } 
          echo "</tr>";
       } 
   echo "</table>";
}

?>