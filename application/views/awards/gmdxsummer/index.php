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
            <p class="card-text">The GMDX Summer Challenge will takes place over 6 weeks beginning 00:00 on Monday 13th May until 2359 on Sunday 30th June.</p>
            <p class="card-text">The Summer Challenge is a single event using CW, SSB or Digital Modes on both 4m and 6m. GMDX members are expected not to set up FT8 in automated robot mode. You are trusted to operate your station manually.</p>
            <p class="card-text">The overall winner will have worked the most 4 digit maidenhead locator squares (eg IO75) on 4m and 6m to give the highest combined score. DXCC Countries are not counted, only locator squares.  In order to have the leading score, it is expected operators would have to make contacts on both 4m and 6m using all modes.</p>
            <p class="card-text">All GMDX members worldwide are welcome to enter.</p>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSek4GlQzx7OXJBxYh-KCLdK86_yRbqXGL1rTl1dFXmTlkpdlA/viewform" class="btn btn-primary" target="_blank">Submit your entry</a>
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
                <td>18:00 on Sunday 26th May</td>
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
                <td>18:00 on Sunday 9th June</td>
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
                <td>18:00 on Sunday 23th June</td>
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
                <td>18:00 on Monday 1st July</td>
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