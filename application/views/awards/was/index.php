
<div class="container">
    <h1><?php echo $page_title; ?></h1>

    <!-- Sub Nav for Awards -->

    <?php $this->load->view("awards/nav_bar")?>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <td style="width:225px">WAS (<?php echo count($was)?>)</td>
            <?php
            foreach ($worked_bands as $slot) {
                echo "          <td>$slot</td>\n";
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($was as $state=>$val){
            print("<tr><td>$state</td>");
            foreach($val as $band=>$count){
                if (in_array($band, $worked_bands)) {
                    if ($count == 0){
                        print("<td>&nbsp;</td>");
                    }else{
                        printf("<td><a href='was_details?State=\"%s\"&Band=\"%s\"'>%d</a></td>", str_replace("&", "%26", $state), $band, $count);
                    }
                }
            }
            print("</tr>");
        }

        ?>
        </tbody>

    </table>
</div>
