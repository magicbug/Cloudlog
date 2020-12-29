<div class="container qso_panel">
    <button type="button" class="btn btn-sm btn-warning float-right" onclick="reset_contest_session()"><i class="fas fa-sync-alt"></i> Reset Contest Session</button>
    <h2><?php echo $page_title; ?></h2>
    <div class="row">

        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Logging Form</h5></div>
                <div class="card-body">
                    <form id="qso_input" name="qsos">

                            <div class="form-group row">

                                    <label class="col-auto control-label" for="radio">Exchange Type</label>
                                    <div class="col-auto">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="exchangeradio" id="serial" value="serial" checked>
                                            <label class="form-check-label" for="serial">
                                                Serial
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="exchangeradio" id="other" value="other">
                                            <label class="form-check-label" for="other">
                                                Other
                                            </label>
                                        </div>
                                    </div>
                            <label class="col-auto control-label" for="contestname">Contest Name</label>
                            <div class="col-auto">
                            <select class="form-control-sm" id="contestname" name="contestname">
                                <option value="Other" selected="selected">Other</option>
                                <option value="070-160M-SPRINT">PODXS Great Pumpkin Sprint</option>
                                <option value="070-3-DAY">PODXS Three Day Weekend</option>
                                <option value="070-31-FLAVORS">PODXS 31 Flavors</option>
                                <option value="070-40M-SPRINT">PODXS 40m Firecracker Sprint</option>
                                <option value="070-80M-SPRINT">PODXS 80m Jay Hudak Memorial Sprint</option>
                                <option value="070-PSKFEST">PODXS PSKFest</option>
                                <option value="070-ST-PATS-DAY">PODXS St. Patricks Day</option>
                                <option value="070-VALENTINE-SPRINT">PODXS Valentine Sprint</option>
                                <option value="10-RTTY">Ten-Meter RTTY Contest (2011 onwards)</option>
                                <option value="1010-OPEN-SEASON">Open Season Ten Meter QSO Party</option>
                                <option value="7QP">7th-Area QSO Party</option>
                                <option value="AL-QSO-PARTY">Alabama QSO Party</option>
                                <option value="ALL-ASIAN-DX-CW">JARL All Asian DX Contest (CW)</option>
                                <option value="ALL-ASIAN-DX-PHONE">JARL All Asian DX Contest (PHONE)</option>
                                <option value="ANARTS-RTTY">ANARTS WW RTTY</option>
                                <option value="ANATOLIAN-RTTY">Anatolian WW RTTY</option>
                                <option value="AP-SPRINT">Asia - Pacific Sprint</option>
                                <option value="AR-QSO-PARTY">Arkansas QSO Party</option>
                                <option value="ARI-DX">ARI DX Contest</option>
                                <option value="ARRL-10">ARRL 10 Meter Contest</option>
                                <option value="ARRL-10-GHZ">ARRL 10 GHz and Up Contest</option>
                                <option value="ARRL-160">ARRL 160 Meter Contest</option>
                                <option value="ARRL-222">ARRL 222 MHz and Up Distance Contest</option>
                                <option value="ARRL-DX-CW">ARRL International DX Contest (CW)</option>
                                <option value="ARRL-DX-SSB">ARRL International DX Contest (Phone)</option>
                                <option value="ARRL-EME">ARRL EME contest</option>
                                <option value="ARRL-FIELD-DAY">ARRL Field Day</option>
                                <option value="ARRL-RR-CW">ARRL Rookie Roundup (CW)</option>
                                <option value="ARRL-RR-RTTY">ARRL Rookie Roundup (RTTY)</option>
                                <option value="ARRL-RR-SSB">ARRL Rookie Roundup (Phone)</option>
                                <option value="ARRL-RTTY">ARRL RTTY Round-Up</option>
                                <option value="ARRL-SCR">ARRL School Club Roundup</option>
                                <option value="ARRL-SS-CW">ARRL November Sweepstakes (CW)</option>
                                <option value="ARRL-SS-SSB">ARRL November Sweepstakes (Phone)</option>
                                <option value="ARRL-UHF-AUG">ARRL August UHF Contest</option>
                                <option value="ARRL-VHF-JAN">ARRL January VHF Sweepstakes</option>
                                <option value="ARRL-VHF-JUN">ARRL June VHF QSO Party</option>
                                <option value="ARRL-VHF-SEP">ARRL September VHF QSO Party</option>
                                <option value="AZ-QSO-PARTY">Arizona QSO Party</option>
                                <option value="BARTG-RTTY">BARTG Spring RTTY Contest</option>
                                <option value="BARTG-SPRINT">BARTG Sprint Contest</option>
                                <option value="BC-QSO-PARTY">British Columbia QSO Party</option>
                                <option value="CA-QSO-PARTY">California QSO Party</option>
                                <option value="CIS-DX">CIS DX Contest</option>
                                <option value="CO-QSO-PARTY">Colorado QSO Party</option>
                                <option value="CQ-160-CW">CQ WW 160 Meter DX Contest (CW)</option>
                                <option value="CQ-160-SSB">CQ WW 160 Meter DX Contest (SSB)</option>
                                <option value="CQ-M">CQ-M International DX Contest</option>
                                <option value="CQ-VHF">CQ World-Wide VHF Contest</option>
                                <option value="CQ-WPX-CW">CQ WW WPX Contest (CW)</option>
                                <option value="CQ-WPX-RTTY">CQ/RJ WW RTTY WPX Contest</option>
                                <option value="CQ-WPX-SSB">CQ WW WPX Contest (SSB)</option>
                                <option value="CQ-WW-CW">CQ WW DX Contest (CW)</option>
                                <option value="CQ-WW-RTTY">CQ/RJ WW RTTY DX Contest</option>
                                <option value="CQ-WW-SSB">CQ WW DX Contest (SSB)</option>
                                <option value="CT-QSO-PARTY">Connecticut QSO Party</option>
                                <option value="CVA-DX-CW">Concurso Verde e Amarelo DX CW Contest</option>
                                <option value="CVA-DX-SSB">Concurso Verde e Amarelo DX SSB Contest</option>
                                <option value="CWOPS-CW-OPEN">CWops CW Open Competition</option>
                                <option value="CWOPS-CWT">CWops Mini-CWT Test</option>
                                <option value="DARC-WAEDC-CW">WAE DX Contest (CW)</option>
                                <option value="DARC-WAEDC-RTTY">WAE DX Contest (RTTY)</option>
                                <option value="DARC-WAEDC-SSB">WAE DX Contest (SSB)</option>
                                <option value="DARC-WAG">DARC Worked All Germany</option>
                                <option value="DE-QSO-PARTY">Delaware QSO Party</option>
                                <option value="DL-DX-RTTY">DL-DX RTTY Contest</option>
                                <option value="DMC-RTTY">DMC RTTY Contest</option>
                                <option value="EA-CNCW">Concurso Nacional de Telegrafía</option>
                                <option value="EA-DME">Municipios Españoles</option>
                                <option value="EA-PSK63">EA PSK63</option>
                                <option value="EA-RTTY (import-only)">Unión de Radioaficionados Españoles RTTY Contest</option>
                                <option value="EA-SMRE-CW">Su Majestad El Rey de España - CW</option>
                                <option value="EA-SMRE-SSB">Su Majestad El Rey de España - SSB</option>
                                <option value="EA-VHF-ATLANTIC">Atlántico V-UHF</option>
                                <option value="EA-VHF-COM">Combinado de V-UHF</option>
                                <option value="EA-VHF-COSTA-SOL">Costa del Sol V-UHF</option>
                                <option value="EA-VHF-EA">Nacional VHF</option>
                                <option value="EA-VHF-EA1RCS">Segovia EA1RCS V-UHF</option>
                                <option value="EA-VHF-QSL">QSL V-UHF & 50MHz</option>
                                <option value="EA-VHF-SADURNI">Sant Sadurni V-UHF</option>
                                <option value="EA-WW-RTTY">Unión de Radioaficionados Españoles RTTY Contest</option>
                                <option value="EPC-PSK63">PSK63 QSO Party</option>
                                <option value="EU Sprint">EU Sprint</option>
                                <option value="EU-HF">EU HF Championship</option>
                                <option value="EU-PSK-DX">EU PSK DX Contest</option>
                                <option value="EUCW160M">European CW Association 160m CW Party</option>
                                <option value="FALL SPRINT">FISTS Fall Sprint</option>
                                <option value="FL-QSO-PARTY">Florida QSO Party</option>
                                <option value="GA-QSO-PARTY">Georgia QSO Party</option>
                                <option value="HA-DX">Hungarian DX Contest</option>
                                <option value="HELVETIA">Helvetia Contest</option>
                                <option value="HI-QSO-PARTY">Hawaiian QSO Party</option>
                                <option value="HOLYLAND">IARC Holyland Contest</option>
                                <option value="IA-QSO-PARTY">Iowa QSO Party</option>
                                <option value="IARU-FIELD-DAY">DARC IARU Region 1 Field Day</option>
                                <option value="IARU-HF">IARU HF World Championship</option>
                                <option value="ID-QSO-PARTY">Idaho QSO Party</option>
                                <option value="IL QSO Party">Illinois QSO Party</option>
                                <option value="IN-QSO-PARTY">Indiana QSO Party</option>
                                <option value="JARTS-WW-RTTY">JARTS WW RTTY</option>
                                <option value="JIDX-CW">Japan International DX Contest (CW)</option>
                                <option value="JIDX-SSB">Japan International DX Contest (SSB)</option>
                                <option value="JT-DX-RTTY">Mongolian RTTY DX Contest</option>
                                <option value="KS-QSO-PARTY">Kansas QSO Party</option>
                                <option value="KY-QSO-PARTY">Kentucky QSO Party</option>
                                <option value="LA-QSO-PARTY">Louisiana QSO Party</option>
                                <option value="LDC-RTTY">DRCG Long Distance Contest (RTTY)</option>
                                <option value="LZ DX">LZ DX Contest</option>
                                <option value="MAR-QSO-PARTY">Maritimes QSO Party</option>
                                <option value="MD-QSO-PARTY">Maryland QSO Party</option>
                                <option value="ME-QSO-PARTY">Maine QSO Party</option>
                                <option value="MI-QSO-PARTY">Michigan QSO Party</option>
                                <option value="MIDATLANTIC-QSO-PARTY">Mid-Atlantic QSO Party</option>
                                <option value="MN-QSO-PARTY">Minnesota QSO Party</option>
                                <option value="MO-QSO-PARTY">Missouri QSO Party</option>
                                <option value="MS-QSO-PARTY">Mississippi QSO Party</option>
                                <option value="MT-QSO-PARTY">Montana QSO Party</option>
                                <option value="NA-SPRINT-CW">North America Sprint (CW)</option>
                                <option value="NA-SPRINT-RTTY">North America Sprint (RTTY)</option>
                                <option value="NA-SPRINT-SSB">North America Sprint (Phone)</option>
                                <option value="NAQP-CW">North America QSO Party (CW)</option>
                                <option value="NAQP-RTTY">North America QSO Party (RTTY)</option>
                                <option value="NAQP-SSB">North America QSO Party (Phone)</option>
                                <option value="NC-QSO-PARTY">North Carolina QSO Party</option>
                                <option value="ND-QSO-PARTY">North Dakota QSO Party</option>
                                <option value="NE-QSO-PARTY">Nebraska QSO Party</option>
                                <option value="NEQP">New England QSO Party</option>
                                <option value="NH-QSO-PARTY">New Hampshire QSO Party</option>
                                <option value="NJ-QSO-PARTY">New Jersey QSO Party</option>
                                <option value="NM-QSO-PARTY">New Mexico QSO Party</option>
                                <option value="NRAU-BALTIC-CW">NRAU-Baltic Contest (CW)</option>
                                <option value="NRAU-BALTIC-SSB">NRAU-Baltic Contest (SSB)</option>
                                <option value="NV-QSO-PARTY">Nevada QSO Party</option>
                                <option value="NY-QSO-PARTY">New York QSO Party</option>
                                <option value="OCEANIA-DX-CW">Oceania DX Contest (CW)</option>
                                <option value="OCEANIA-DX-SSB">Oceania DX Contest (SSB)</option>
                                <option value="OH-QSO-PARTY">Ohio QSO Party</option>
                                <option value="OK-DX-RTTY">Czech Radio Club OK DX Contest</option>
                                <option value="OK-OM-DX">Czech Radio Club OK-OM DX Contest</option>
                                <option value="OK-QSO-PARTY">Oklahoma QSO Party</option>
                                <option value="OMISS-QSO-PARTY">Old Man International Sideband Society QSO Party</option>
                                <option value="ON-QSO-PARTY">Ontario QSO Party</option>
                                <option value="OR-QSO-PARTY">Oregon QSO Party</option>
                                <option value="PA-QSO-PARTY">Pennsylvania QSO Party</option>
                                <option value="PACC">Dutch PACC Contest</option>
                                <option value="PSK-DEATHMATCH">MDXA PSK DeathMatch (2005-2010)</option>
                                <option value="QC-QSO-PARTY">Quebec QSO Party</option>
                                <option value="RAC (import-only)">Canadian Amateur Radio Society Contest</option>
                                <option value="RAC-CANADA-DAY">RAC Canada Day Contest</option>
                                <option value="RAC-CANADA-WINTER">RAC Canada Winter Contest</option>
                                <option value="RDAC">Russian District Award Contest</option>
                                <option value="RDXC">Russian DX Contest</option>
                                <option value="REF-160M">Reseau des Emetteurs Francais 160m Contest</option>
                                <option value="REF-CW">Reseau des Emetteurs Francais Contest (CW)</option>
                                <option value="REF-SSB">Reseau des Emetteurs Francais Contest (SSB)</option>
                                <option value="REP-PORTUGAL-DAY-HF">Rede dos Emissores Portugueses Portugal Day HF Contest</option>
                                <option value="RI-QSO-PARTY">Rhode Island QSO Party</option>
                                <option value="RSGB-160">1.8MHz Contest</option>
                                <option value="RSGB-21/28-CW">21/28 MHz Contest (CW)</option>
                                <option value="RSGB-21/28-SSB">21/28 MHz Contest (SSB)</option>
                                <option value="RSGB-80M-CC">80m Club Championships</option>
                                <option value="RSGB-AFS-CW">Affiliated Societies Team Contest (CW)</option>
                                <option value="RSGB-AFS-SSB">Affiliated Societies Team Contest (SSB)</option>
                                <option value="RSGB-CLUB-CALLS">Club Calls</option>
                                <option value="RSGB-COMMONWEALTH">Commonwealth Contest</option>
                                <option value="RSGB-IOTA">IOTA Contest</option>
                                <option value="RSGB-LOW-POWER">Low Power Field Day</option>
                                <option value="RSGB-NFD">National Field Day</option>
                                <option value="RSGB-ROPOCO">RoPoCo</option>
                                <option value="RSGB-SSB-FD">SSB Field Day</option>
                                <option value="RUSSIAN-RTTY">Russian Radio RTTY Worldwide Contest</option>
                                <option value="SAC-CW">Scandinavian Activity Contest (CW)</option>
                                <option value="SAC-SSB">Scandinavian Activity Contest (SSB)</option>
                                <option value="SARTG-RTTY">SARTG WW RTTY</option>
                                <option value="SC-QSO-PARTY">South Carolina QSO Party</option>
                                <option value="SCC-RTTY">SCC RTTY Championship</option>
                                <option value="SD-QSO-PARTY">South Dakota QSO Party</option>
                                <option value="SMP-AUG">SSA Portabeltest</option>
                                <option value="SMP-MAY">SSA Portabeltest</option>
                                <option value="SP-DX-RTTY">PRC SPDX Contest (RTTY)</option>
                                <option value="SPAR-WINTER-FD">SPAR Winter Field Day</option>
                                <option value="SPDXContest">SP DX Contest</option>
                                <option value="SPRING SPRINT">FISTS Spring Sprint</option>
                                <option value="SR-MARATHON">Scottish-Russian Marathon</option>
                                <option value="STEW-PERRY">Stew Perry Topband Distance Challenge</option>
                                <option value="SUMMER SPRINT">FISTS Summer Sprint</option>
                                <option value="TARA-GRID-DIP">TARA Grid Dip PSK-RTTY Shindig</option>
                                <option value="TARA-RTTY">TARA RTTY Mêlée</option>
                                <option value="TARA-RUMBLE">TARA Rumble PSK Contest</option>
                                <option value="TARA-SKIRMISH">TARA Skirmish Digital Prefix Contest</option>
                                <option value="TEN-RTTY">Ten-Meter RTTY Contest (before 2011)</option>
                                <option value="TMC-RTTY">The Makrothen Contest</option>
                                <option value="TN-QSO-PARTY">Tennessee QSO Party</option>
                                <option value="TX-QSO-PARTY">Texas QSO Party</option>
                                <option value="UBA-DX-CW">UBA Contest (CW)</option>
                                <option value="UBA-DX-SSB">UBA Contest (SSB)</option>
                                <option value="UK-DX-BPSK63">European PSK Club BPSK63 Contest</option>
                                <option value="UK-DX-RTTY">UK DX RTTY Contest</option>
                                <option value="UKR-CHAMP-RTTY">Open Ukraine RTTY Championship</option>
                                <option value="UKRAINIAN DX">Ukrainian DX</option>
                                <option value="UKSMG-6M-MARATHON">UKSMG 6m Marathon</option>
                                <option value="UKSMG-SUMMER-ES">UKSMG Summer Es Contest</option>
                                <option value="URE-DX  (import-only)">Ukrainian DX Contest</option>
                                <option value="US-COUNTIES-QSO">Mobile Amateur Awards Club</option>
                                <option value="UT-QSO-PARTY">Utah QSO Party</option>
                                <option value="VA-QSO-PARTY">Virginia QSO Party</option>
                                <option value="VENEZ-IND-DAY">RCV Venezuelan Independence Day Contest</option>
                                <option value="VIRGINIA QSO PARTY (import-only)">Virginia QSO Party</option>
                                <option value="VOLTA-RTTY">Alessandro Volta RTTY DX Contest</option>
                                <option value="WA-QSO-PARTY">Washington QSO Party</option>
                                <option value="WI-QSO-PARTY">Wisconsin QSO Party</option>
                                <option value="WIA-HARRY ANGEL">WIA Harry Angel Memorial 80m Sprint</option>
                                <option value="WIA-JMMFD">WIA John Moyle Memorial Field Day</option>
                                <option value="WIA-OCDX">WIA Oceania DX (OCDX) Contest</option>
                                <option value="WIA-REMEMBRANCE">WIA Remembrance Day</option>
                                <option value="WIA-ROSS HULL">WIA Ross Hull Memorial VHF/UHF Contest</option>
                                <option value="WIA-TRANS TASMAN">WIA Trans Tasman Low Bands Challenge</option>
                                <option value="WIA-VHF/UHF FD">WIA VHF UHF Field Days</option>
                                <option value="WIA-VK SHIRES">WIA VK Shires</option>
                                <option value="WINTER SPRINT">FISTS Winter Sprint</option>
                                <option value="WV-QSO-PARTY">West Virginia QSO Party</option>
                                <option value="WW-DIGI">World Wide Digi DX Contest</option>
                                <option value="WY-QSO-PARTY">Wyoming QSO Party</option>
                                <option value="XE-INTL-RTTY">Mexico International Contest (RTTY)</option>
                                <option value="YOHFDX">YODX HF contest</option>
                                <option value="YUDXC">YU DX Contest</option>

                            </select>
                                        </div></div>

                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="start_date">Date</label>
                                        <input type="text" class="form-control form-control-sm input_date" name="start_date" id="start_date" value="<?php if (($this->session->userdata('start_date') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_date'); } else { echo date('d-m-Y');}?>">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="start_time">Time</label>
                                        <input type="text" class="form-control form-control-sm input_time" name="start_time" id="start_time" value="<?php if (($this->session->userdata('start_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_time'); } else {echo date('H:i'); } ?>" size="7">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="mode">Mode</label>
                                        <select id="mode" class="form-control mode form-control-sm" name="mode">
                                            <?php
                                            foreach($modes->result() as $mode){
                                                if ($mode->submode == null) {
                                                    printf("<option value=\"%s\" %s>%s</option>", $mode->mode, $this->session->userdata('mode')==$mode->mode?"selected=\"selected\"":"",$mode->mode);
                                                } else {
                                                    printf("<option value=\"%s\" %s>&rArr; %s</option>", $mode->submode, $this->session->userdata('mode')==$mode->submode?"selected=\"selected\"":"",$mode->submode);
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="band">Band</label>

                                        <select id="band" class="form-control form-control-sm" name="band">
                                            <optgroup label="HF">
                                                <option value="160m" <?php if($this->session->userdata('band') == "160m") { echo "selected=\"selected\""; } ?>>160m</option>
                                                <option value="80m" <?php if($this->session->userdata('band') == "80m") { echo "selected=\"selected\""; } ?>>80m</option>
                                                <option value="60m" <?php if($this->session->userdata('band') == "60m") { echo "selected=\"selected\""; } ?>>60m</option>
                                                <option value="40m" <?php if($this->session->userdata('band') == "40m") { echo "selected=\"selected\""; } ?>>40m</option>
                                                <option value="30m" <?php if($this->session->userdata('band') == "30m") { echo "selected=\"selected\""; } ?>>30m</option>
                                                <option value="20m" <?php if($this->session->userdata('band') == "20m") { echo "selected=\"selected\""; } ?>>20m</option>
                                                <option value="17m" <?php if($this->session->userdata('band') == "17m") { echo "selected=\"selected\""; } ?>>17m</option>
                                                <option value="15m" <?php if($this->session->userdata('band') == "15m") { echo "selected=\"selected\""; } ?>>15m</option>
                                                <option value="12m" <?php if($this->session->userdata('band') == "12m") { echo "selected=\"selected\""; } ?>>12m</option>
                                                <option value="10m" <?php if($this->session->userdata('band') == "10m") { echo "selected=\"selected\""; } ?>>10m</option>
                                            </optgroup>

                                            <optgroup label="VHF">
                                                <option value="6m" <?php if($this->session->userdata('band') == "6m") { echo "selected=\"selected\""; } ?>>6m</option>
                                                <option value="4m" <?php if($this->session->userdata('band') == "4m") { echo "selected=\"selected\""; } ?>>4m</option>
                                                <option value="2m" <?php if($this->session->userdata('band') == "2m") { echo "selected=\"selected\""; } ?>>2m</option>
                                            </optgroup>

                                            <optgroup label="UHF">
                                                <option value="70cm" <?php if($this->session->userdata('band') == "70cm") { echo "selected=\"selected\""; } ?>>70cm</option>
                                                <option value="23cm" <?php if($this->session->userdata('band') == "23cm") { echo "selected=\"selected\""; } ?>>23cm</option>
                                                <option value="13cm" <?php if($this->session->userdata('band') == "13cm") { echo "selected=\"selected\""; } ?>>13cm</option>
                                                <option value="9cm" <?php if($this->session->userdata('band') == "9cm") { echo "selected=\"selected\""; } ?>>9cm</option>
                                            </optgroup>

                                            <optgroup label="Microwave">
                                                <option value="6cm" <?php if($this->session->userdata('band') == "6cm") { echo "selected=\"selected\""; } ?>>6cm</option>
                                                <option value="3cm" <?php if($this->session->userdata('band') == "3cm") { echo "selected=\"selected\""; } ?>>3cm</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="frequency">Frequency</label>
                                        <input type="text" class="form-control form-control-sm" id="frequency" name="freq_display" value="<?php echo $this->session->userdata('freq'); ?>" />
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="inputRadio">Radio</label>
                                        <select class="form-control form-control-sm radios" id="radio" name="radio">
                                            <option value="0" selected="selected">None</option>
                                            <?php foreach ($radios->result() as $row) { ?>
                                                <option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="form-group col-md-3">
                                        <label for="callsign">Callsign</label>
                                        <input type="text" class="form-control form-control-sm" id="callsign" name="callsign" required>
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="rst_sent">RST (S)</label>
                                        <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="59">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="exch_sent">Exch (S)</label>
                                        <input type="text" class="form-control form-control-sm" name="exch_sent" id="exch_sent" value="">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="rst_recv">RST (R)</label>
                                        <input type="text" class="form-control form-control-sm" name="rst_recv" id="rst_recv" value="59">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="exch_recv">Exch (R)</label>
                                        <input type="text" class="form-control form-control-sm" name="exch_recv" id="exch_recv" value="">
                                    </div>

                                </div>
                                <div class="form-row">
                                        <div class="form-group col-md-5">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control form-control-sm" name="name" id="name" value="">
                                        </div>

                                        <div class="form-group col-md-5">
                                            <label for="comment">Comment</label>
                                            <input type="text" class="form-control form-control-sm" name="comment" id="comment" value="">
                                        </div>
                                </div>
                            <button type="reset" class="btn btn-sm btn-light" onclick="reset_log_fields()"><i class="fas fa-sync-alt"></i> Reset QSO</button>
                            <button type="button" class="btn btn-sm btn-primary" onclick="logQso();"><i class="fas fa-save"></i> Save QSO</button>
                            </div>

                        </div>

                        </div>
                    </form>
            </div>

            <br/>
            <div class="card callsign-suggest">
                <div class="card-header"><h5 class="card-title">Callsign Suggestions</h5></div>

                <div class="card-body callsign-suggestions"></div>
            </div>

            <div class="card log">
                <div class="card-header"><h5 class="card-title">Contest Logbook (Only for this session)</h5></div>

                        <table style="width:100%" class="table-sm table qsotable table-bordered table-hover table-striped table-condensed text-center">
                            <thead>
                            <tr class="log_title titles">
                                <th>Date/Time</th>
                                <th>Call</th>
                                <th>Band</th>
                                <th>Mode</th>
                                <th>RST (S)</th>
                                <th>RST (R)</th>
                                <th>Exch S</th>
                                <th>Exch R</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
            </div>
        </div>
    </div>


</div>

</div>
