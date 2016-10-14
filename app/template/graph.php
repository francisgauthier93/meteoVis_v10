<!-- modification d'affichage -->
<div class="container-fluid">
    <form class="form-inline" autocomplete="off" method="post" action="#" role="form">
        <div class="form-group" id="inputVille">
            <label for="inVille" data-original-translation="" class="sr-only">City</label>
            <input type="text" class="typeahead tt-query" id="inVille"
                   spellcheck="false" placeholder="Change city" name="inVille" 
                   data-original-translation="" /> 
            <input type="hidden" id="prov" name="prov" />
            <input type="hidden" id="province" name="province" />
            <input type="hidden" id="ville"  name="ville"/>
            <input type="hidden" id="villeen"  name="villeen"/>
            <input type="hidden" id="id02" name="id02" />
            <input type="hidden" id="id37" name="id37" />
            <input type="hidden" id="code" name="code" />
        </div>
        <div class="form-group">
            <label for="nbdays" data-original-translation="" class="sr-only">Display</label>
            <select id="nbdays" name="nbdays">
                <option value="1" data-original-translation="">
                    1 day
                </option>
                <option value="2" data-original-translation="">
                    2 days
                </option>
                <option value="3" data-original-translation="">
                    3 days
                </option>
                <option value="4" data-original-translation="">
                    4 days
                </option>
                <option value="5" data-original-translation="">
                    5 days
                </option>
                <option value="6" data-original-translation="">
                    6 days
                </option>
                <option value="7" selected="selected" data-original-translation="">
                    7 days
                </option>
            </select>
        </div>
        <div class="checkbox-inline">
            <input type="checkbox" id="cloudCond" name="cloudCond" checked="checked">
            <label for="cloudCond" data-original-translation="">Cloud covering</label>
        </div>
        <div class="checkbox-inline">
            <input type="checkbox" id="precipCond" name="precipCond" checked="checked" > 
            <label for="precipCond" data-original-translation="">Precipitations</label>
        </div>
        <div class="checkbox-inline">
            <input type="checkbox" id="windCond" name="windCond" checked="checked"> 
            <label for="windCond" data-original-translation="">Wind</label>
        </div>
        <div class="checkbox-inline">
            <input type="checkbox" id="temperature" name="temperature" checked="checked" > 
            <label for="temperature" data-original-translation="">Temperature</label>
        </div>
        <div class="checkbox-inline">
            <input type="checkbox" id="accumCond" checked="checked" name="accumCond">
            <label for="accumCond" data-original-translation="">Accumulation</label>
        </div>
        <div class="checkbox-inline">
            <input type="checkbox" id="percentPrecipCond" name="percentPrecipCond" checked="checked">
            <label for="percentPrecipCond" data-original-translation="">&#37; of precipitation</label>
        </div>
    </form>
</div>
<!-- graphique pour les prochains jours -->
<div class="row scroll">
<svg height="0" width="0">
    <defs>
        <g id="graph_air_temperature">
            <circle cx="0" cy="0" r="3" fill="rgb(200, 208, 70)" 
                stroke-width="0" class="temperature" />
        </g>
        <g id="graph_precipitation_rain">
            <image class="precipCond" x="0" y="0" width="11" height="11" 
                   xlink:href="<?php echo $sRainImagePath; ?>">
            </image>
        </g>
        <g id="graph_precipitation_snow">
            <image class="precipCond" x="0" y="0" width="11" height="11" 
                   xlink:href="<?php echo $sSnowImagePath; ?>" />
        </g>
        <g id="graph_wind">
            <image x="0" y="0" width="10" height="10" 
                   xlink:href="<?php echo $sArrowImagePath; ?>"
                   opacity="0.3" class="windCond"/>
        </g>
        <g id="graph_sky_sunny">
            <image x="0" y="0" width="25" height="30"
                   xlink:href="<?php echo $sSkySunnyImagePath; ?>"
                   class="cloudCond"/>
        </g>
        <g id="graph_sky_fair">
            <image x="0" y="0" width="25" height="30"
                   xlink:href="<?php echo $sSkyFairImagePath; ?>"
                   class="cloudCond"/>
        </g>
        <g id="graph_sky_mostly_sunny">
            <image x="0" y="0" width="25" height="30"
                   xlink:href="<?php echo $sSkyMostlySunnyImagePath; ?>"
                   class="cloudCond"/>
        </g>
        <g id="graph_sky_partly_cloudy">
            <image x="0" y="0" width="25" height="30"
                   xlink:href="<?php echo $sSkyPartlyCloudyImagePath; ?>"
                   class="cloudCond"/>
        </g>
        <g id="graph_sky_mostly_cloudy">
            <image x="0" y="0" width="25" height="30"
                   xlink:href="<?php echo $sSkyMostlyCloudyImagePath; ?>"
                   class="cloudCond"/>
        </g>
        <g id="graph_sky_broken">
            <image x="0" y="0" width="25" height="30"
                   xlink:href="<?php echo $sSkyBrokenImagePath; ?>"
                   class="cloudCond"/>
        </g>
        <g id="graph_sky_cloudy">
            <image x="0" y="0" width="25" height="30"
                   xlink:href="<?php echo $sSkyCloudyImagePath; ?>"
                   class="cloudCond"/>
        </g>
    </defs>
</svg>
    
<svg height="<?php echo $iGraphHeight; ?>px" width="<?php echo $iGraphWidth; ?>px" id="meteo-graphic">
    <!-- Background -->
    <rect x="0" y="0" width="100%" height="100%" />
    <line x1="<?php echo $iDayWidth*1; ?>" y1="0" x2="<?php echo $iDayWidth*1; ?>" y2="100%" />
    <line x1="<?php echo $iDayWidth*2; ?>" y1="0" x2="<?php echo $iDayWidth*2; ?>" y2="100%" />
    <line x1="<?php echo $iDayWidth*3; ?>" y1="0" x2="<?php echo $iDayWidth*3; ?>" y2="100%" />
    <line x1="<?php echo $iDayWidth*4; ?>" y1="0" x2="<?php echo $iDayWidth*4; ?>" y2="100%" />
    <line x1="<?php echo $iDayWidth*5; ?>" y1="0" x2="<?php echo $iDayWidth*5; ?>" y2="100%" />
    <line x1="<?php echo $iDayWidth*6; ?>" y1="0" x2="<?php echo $iDayWidth*6; ?>" y2="100%" />
    
    <!-- Title -->
    <?php
        echo $sDayTitle;
    ?>
    
    <!-- Data -->
    <?php
    
        echo $sTemperatureSvg;
        
        echo $sPrecipitationSvg;
        
        echo $sCloudCoverSvg;
        
        echo $sWindSvg;
        
        echo $sAccumulationSvg;
        
    ?>
</svg>
</div>
<br />