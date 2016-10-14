<style>
@media only screen and (max-width: 800px) {
    
    /* Force table to not be like tables anymore */
	#forecast table, 
	#forecast thead, 
	#forecast tbody, 
	#forecast th, 
	#forecast td, 
	#forecast tr { 
		display: block; 
	}
 
	/* Hide table headers (but not display: none;, for accessibility) */
	#forecast thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
 
	#forecast tr { border: 1px solid #ccc; }
 
	#forecast td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
		white-space: normal;
		text-align:left;
	}
 
	#forecast td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		text-align:left;
		font-weight: bold;
	}
 
	/*
	Label the data
	*/
	#forecast td:before { content: attr(data-title); }
}
</style>
<div id="forecast">
    <table class="col-md-12 table-striped table-condensed cf">
        <thead class="cf">
            <tr>
                <th data-original-translation="">Day</th>
                <th data-original-translation="">Minimum temperature</th>
                <th data-original-translation="">Maximum temperature</th>
                <th data-original-translation="">Additional information</th>
            </tr>
        </thead>
        <tbody>
            <?php
                echo $sForecast;
            ?>
        </tbody>
    </table>
</div>