<div class="container">

    <!-- Award Info Box -->
    <br>
    <div id="awardInfoButton">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <!-- End of Award Info Box -->

    <div class="card">
        <div class="card-header">
            What is the GMDX Summer Challenge?
        </div>
        <div class="card-body">
            <p class="card-text">The GMDX Summer VHF Challenge 2026 takes place from 00:00 on Monday 11th May until 23:59 on Sunday 5th July.</p>
            <p class="card-text">The challenge is a single event using CW, Voice, and Digital modes on both 4m and 6m. Acceptable voice modes are SSB, AM, and FM. Entrants are expected not to set up FT8 or other digital modes in automated robot mode, and to operate manually.</p>
            <p class="card-text">Score is based on 4-digit Maidenhead locator squares (for example IO72), and DXCC countries are not counted. The same locator can be counted once per mode category (CW, Voice, Digital) on each band, so the same locator can count up to 3 times on 4m and again up to 3 times on 6m.</p>
            <p class="card-text">All GMDX members worldwide are welcome to enter.</p>
            <a href="https://forms.gle/UUVLG4oASBxeWGFf7" class="btn btn-primary" target="_blank">Submit your entry</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Fortnightly Breakdown
        </div>
        <div class="card-body">
        <table class="table table-striped table-hover">
            <tr>
                <td>Week</td>
                <td>6m SSB</td>
                <td>6m CW</td>
                <td>6m Digital</td>
                <td>6m Combined</td>
                <td>4m SSB</td>
                <td>4m CW</td>
                <td>4m Digital</td>
                <td>4m Combined</td>
            </tr>

            <tr>
                <td>18:00 on Sunday 24th May</td>
                <td><?php echo $week1_6m_ssb; ?></td>
                <td><?php echo $week1_6m_cw; ?></td>
                <td><?php echo $week1_6m_digital; ?></td>
                <td><?php echo $week1_6m_combined; ?></td>
                <td><?php echo $week1_4m_ssb; ?></td>
                <td><?php echo $week1_4m_cw; ?></td>
                <td><?php echo $week1_4m_digital; ?></td>
                <td><?php echo $week1_4m_combined; ?></td>
            </tr>

            <tr>
                <td>18:00 on Sunday 7th June</td>
                <td><?php echo $week2_6m_ssb; ?></td>
                <td><?php echo $week2_6m_cw; ?></td>
                <td><?php echo $week2_6m_digital; ?></td>
                <td><?php echo $week2_6m_combined; ?></td>
                <td><?php echo $week2_4m_ssb; ?></td>
                <td><?php echo $week2_4m_cw; ?></td>
                <td><?php echo $week2_4m_digital; ?></td>
                <td><?php echo $week2_4m_combined; ?></td>
            </tr>

            <tr>
                <td>18:00 on Sunday 21st June</td>
                <td><?php echo $week3_6m_ssb; ?></td>
                <td><?php echo $week3_6m_cw; ?></td>
                <td><?php echo $week3_6m_digital; ?></td>
                <td><?php echo $week3_6m_combined; ?></td>
                <td><?php echo $week3_4m_ssb; ?></td>
                <td><?php echo $week3_4m_cw; ?></td>
                <td><?php echo $week3_4m_digital; ?></td>
                <td><?php echo $week3_4m_combined; ?></td>
            </tr>

            <tr>
                <td>18:00 on Monday 6th July (QSOs counted to 23:59 Sunday 5th July)</td>
                <td><?php echo $week4_6m_ssb; ?></td>
                <td><?php echo $week4_6m_cw; ?></td>
                <td><?php echo $week4_6m_digital; ?></td>
                <td><?php echo $week4_6m_combined; ?></td>
                <td><?php echo $week4_4m_ssb; ?></td>
                <td><?php echo $week4_4m_cw; ?></td>
                <td><?php echo $week4_4m_digital; ?></td>
                <td><?php echo $week4_4m_combined; ?></td>
            </tr>
        </table>
        </div>
    </div>
</div>