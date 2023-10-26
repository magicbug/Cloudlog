<p>Before start logging a qso to notice to basic rules:</p>
<p>- Every new QSO get its own new line</p>
<p>- At every new line only write data, which changed to the last qso</p>
<p>We start with the first data, you already filled the form on the left with the date, wwff/sota info, stationcall and operator call. The main data contains the <em>band, mode and time</em>. After time you provide the first qso (basically the callsign)</p>
<pre>
    20m ssb
    2134 2m0sql
</pre>
<p>So this QSO started at 21:34 (UTC!) with 2M0SQL on 20m SSB.<p>
<p>If you do not provide any RST Information the Syntax will use 59 (599 for data). Out next QSO wasn't 59 on both sides so we provide the information with the sent RST first. And it was 2 Minutes later then the first QSO.</p>
<pre>
    20m ssb
    2134 2m0sql
    6 la8aja 47 46
</pre>
<p>The first QSO was at 21:34, the second one 2 Minutes later at 21:36 so we write down 6 because this is the only data which changed here. The information about band and mode didn't change. So this data is omitted.</p>
<p>For our next QSO at 21:40 we changed band to 40m, but still on SSB. If now RST information is given the syntax will use 59 for every new QSO.</p>
<pre>
    20m ssb
    2134 2m0sql
    6 la8aja 47 46
    40m 
    40 dj7nt
</pre>
<p>For further information about the syntax please check the Website of DF3CB <a href='https://df3cb.com/fle/documentation/' target='_blank'>here.</a></p>

