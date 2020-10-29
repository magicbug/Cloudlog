<div class="container">

    <br>

    <h2><?php echo $page_title; ?></h2>

    <div id="distances_div">
        <form class="form-inline">
            <label class="my-1 mr-2" for="gridsquare_bands">Band Selection</label>
            <select class="custom-select my-1 mr-sm-2"  id="distplot_bands">
                <option value="sat">SAT</option>
            </select>
            <button id="plot" type="button" name="plot" class="btn btn-primary" onclick="distPlot(this.form)">Plot</button>
        </form>
    </div>

</div>