// paramètres initiaux
//var currNbJours=6;
var currLang = "fr";
var citiesFr = [];
var citiesEn = [];
var cities = [];
var regions = [];

function updateLocationLanguage(sLanguage)
{
    cities = (sLanguage === "fr" ? citiesFr : citiesEn);
    regions = (sLanguage === "fr" ? regionsFr : regionsEn);
}
updateLocationLanguage(currLang);

// conversion d'affichage des degrés
// attention: on arrondit et on peut perdre des décimales après plusieurs conversions...
function c2f(c) {
    return Math.round((c * 9 / 5 + 32)*10)/10;
}

function f2c(f) {
    return Math.round(((f - 32) * 5 / 9)*10)/10;
}

function updateTooltipCelsius()
{
    var newContent = $("<div>").html($(this).attr("data-title"))
            .find(".celsius").each(function() {
                $(this).text(c2f(parseFloat($(this).text().replace(',', '.'))));
                $(this).toggleClass("celsius farenheit");
            }).parent().html();
            
    if(newContent !== undefined)
    {
        $(this).attr("data-title", newContent);
        $(this).tooltip()
                .attr('data-original-title', newContent)
                .tooltip('fixTitle');
    }
}

function fromCelsius() {
    $(".celsius").text(function (i, e) {
        return c2f(parseFloat(e.replace(',', '.')));
    });
    $(".celsius").toggleClass("celsius farenheit");
    $(".max-temperature").each(updateTooltipCelsius);
    $(".avg-temperature").each(updateTooltipCelsius);
    $(".zero-temperature").each(updateTooltipCelsius);
    $(".min-temperature").each(updateTooltipCelsius);
    $(".temperature").each(updateTooltipCelsius);
    $(".accumCond").each(updateTooltipCelsius);
    $(".windCond").each(updateTooltipCelsius);
    $("svg text tspan.svgcelsius").each(function() {
        $(this).text(c2f(parseFloat($(this).text().replace(',', '.'))) + "°F");
        $(this).attr("class", "svgfarenheit");
    });
}

function updateTooltipFarenheit()
{
    var newContent = $("<div>").html($(this).attr("data-title"))
            .find(".farenheit").each(function() {
                $(this).text(f2c(parseFloat($(this).text().replace('.', ','))));
                $(this).toggleClass("farenheit celsius");
            }).parent().html();
            
    if(newContent !== undefined)
    {
        $(this).attr("data-title", newContent);
        $(this).tooltip()
                .attr('data-original-title', newContent)
                .tooltip('fixTitle');
    }
}

function fromFarenheit() {
    $(".farenheit").text(function (i, e) {
        return f2c(parseFloat(e));
    });
    $(".farenheit").toggleClass("celsius farenheit");
    $(".max-temperature").each(updateTooltipFarenheit);
    $(".avg-temperature").each(updateTooltipFarenheit);
    $(".zero-temperature").each(updateTooltipFarenheit);
    $(".min-temperature").each(updateTooltipFarenheit);
    $(".temperature").each(updateTooltipFarenheit);
    $(".accumCond").each(updateTooltipFarenheit);
    $(".windCond").each(updateTooltipFarenheit);
    $("svg text tspan.svgfarenheit").each(function() {
        $(this).text(f2c(parseFloat($(this).text().replace('.', ','))) + "°C");
        $(this).attr("class", "svgcelsius");
    });
}

function setLanguage(l1, $button) {
    var l2 = (l1 === 'fr' ? 'en' : 'fr');
    $('.' + l2).hide();
    $('.' + l1).show();
    if ($button != null)
    {
        $button.text(l2);
    }
    currLang = l1;
    
    // cacher les options du dessin qui ne sont pas sélectionnés
    // Paul Out : 2015.03.19
//    if (!$("#temperature").is(":checked"))
//    {
//        $(".temperature." + l1).hide();
//    }
    // patcher le placeholder que je n'arrive pas à faire avec le code de langue habituel
//    $("#inVille").attr("placeholder", l1 == "fr" ? "Changer de ville" : "Change city");
    
    //Load JSrealB, is defined in jsrealbLoader.php
    loadJSrealB(l1)

    translatePage(l1);
    updateLocationLanguage(l1);
}

/*
 * Mise a jour du graphique
 */
function updateSvg()
{
    var iSvgWidth = parseInt($("#meteo-graphic").attr("width"));
    
    var aCircleList = $(".temperature[xlink\\:href=#graph_air_temperature]")
            .filter(function() {
                return parseInt($(this).attr('x'), 10) < iSvgWidth;
            });
    
    var aTemperatureData = calculateTemperatureData(aCircleList);
    var fMinimumTemperature = aTemperatureData[0];
    var fMaximumTemperature = aTemperatureData[1];
    var fAvgTemperature = aTemperatureData[2];
    
    updateSvgMinimumTemperatureLabel(fMinimumTemperature);
    updateSvgMaximumTemperatureLabel(fMaximumTemperature);
    updateSvgAvgTemperatureLabel(fAvgTemperature);
    
    var yMin = parseFloat($("#line-minimum-temperature").attr("y1"));
    var yMax = parseFloat($("#line-maximum-temperature").attr("y1"));
    var yAvg = parseFloat($("#line-avg-temperature").attr("y1"));
    var yZero = parseFloat($("#line-zero-temperature").attr("y1"));

    // update min max zero lines
    var iDegreToPxCoeff = 5;
    
    // Min
    var newYMin = parseInt(yAvg + (Math.abs(fMinimumTemperature - fAvgTemperature) * iDegreToPxCoeff));
    newYMin = ((Math.abs(newYMin - yAvg) < 3*iDegreToPxCoeff) ? newYMin+3*iDegreToPxCoeff : newYMin); // minimum space
    $("#line-minimum-temperature").attr("y1", newYMin);
    $("#line-minimum-temperature").attr("y2", newYMin);
    $("#label-minimum-temperature").parent().attr("y", function(i, value) {
        return (parseFloat(value) - parseFloat(parseFloat(yMin) - newYMin));
    });
    
    // Max
    var newYMax = parseInt(yAvg - (Math.abs(fMaximumTemperature - fAvgTemperature) * iDegreToPxCoeff));
    newYMax = ((Math.abs(newYMax - yAvg) < 3*iDegreToPxCoeff) ? newYMax-3*iDegreToPxCoeff : newYMax); // minimum space
    $("#line-maximum-temperature").attr("y1", newYMax);
    $("#line-maximum-temperature").attr("y2", newYMax);
    $("#label-maximum-temperature").parent().attr("y", function(i, value) {
        return (parseFloat(value) - parseFloat(parseFloat(yMax) - newYMax));
    });
    
    // Diffrence between max and min
    var fDiffTemperature = Math.abs(fMaximumTemperature - fMinimumTemperature);
    var yDiff = Math.abs(newYMax - newYMin);
    
    // Zero
    var newYZero = (newYMax + yDiff * (Math.abs(fMaximumTemperature - 0) / fDiffTemperature));
    $("#line-zero-temperature").attr("y1", newYZero);
    $("#line-zero-temperature").attr("y2", newYZero);
    $("#label-zero-temperature").parent().attr("y", function(i, value) {
        return (parseFloat(value) - parseFloat(parseFloat(yZero) - newYZero));
    });
    
    // Temperature
    $.each(aCircleList, function() {
        var oCircle = $(this);
        var temperature = parseFloat(oCircle.data("temperature"));
        var coeff = Math.abs(fMaximumTemperature - temperature) / fDiffTemperature;
        var y = (newYMax + yDiff * coeff);
        
        oCircle.attr("y", y);
    });
    
    var minSpaceBetweenLines = 3*iDegreToPxCoeff;
    if(Math.abs(newYZero - newYMax) < minSpaceBetweenLines 
            || Math.abs(newYZero - yAvg) < minSpaceBetweenLines
            || Math.abs(newYZero - newYMin) < minSpaceBetweenLines
            || fMinimumTemperature > 5
            || fMaximumTemperature < -5)
    {
        $("#label-zero-temperature").hide();
    }
    else
    {
        $("#label-zero-temperature").show();
    }
    
    // update avg temperature
//    var coeffAvg = Math.abs(fMaximumTemperature - fAvgTemperature) / fDiffTemperature;
//    yAvg = (yMax + yDiff * coeffAvg);
//    $("#line-avg-temperature").attr("y1", yAvg);
//    $("#line-avg-temperature").attr("y2", yAvg);
    
//    console.log("fMinimumTemperature : " + fMinimumTemperature);
//    console.log("fMaximumTemperature : " + fMaximumTemperature);
//    console.log("fAvgTemperature : " + fAvgTemperature);
//    console.log("Length : " + aCircleList.length);
}

function updateSvgMinimumTemperatureLabel(fMinimumTemperature)
{
    var oMinimumLabel = $("#label-minimum-temperature");
    var sNewMinimum = fMinimumTemperature + "°C";
    if(oMinimumLabel.attr("class") === "svgfarenheit")
    {
        sNewMinimum = c2f(fMinimumTemperature) + "°F";
    }
    oMinimumLabel.text(sNewMinimum);
}

function updateSvgMaximumTemperatureLabel(fMaximumTemperature)
{
    var oMaximumLabel = $("#label-maximum-temperature");
    var sNewMaximum = fMaximumTemperature + "°C";
    if(oMaximumLabel.attr("class") === "svgfarenheit")
    {
        sNewMaximum = c2f(fMaximumTemperature) + "°F";
    }
    oMaximumLabel.text(sNewMaximum);
}

function updateSvgAvgTemperatureLabel(fAvgTemperature)
{
    var oAvgLabel = $("#label-avg-temperature");
    var sNewAvg = fAvgTemperature + "°C";
    if(oAvgLabel.attr("class") === "svgfarenheit")
    {
        sNewAvg = c2f(fAvgTemperature) + "°F";
    }
    oAvgLabel.text(sNewAvg);
}

function calculateTemperatureData(aCircleList)
{
    if(aCircleList.length > 0)
    {
        var fFirstElementTemperature = parseFloat(aCircleList.first().data("temperature"));
        var fMinimumTemperature = fFirstElementTemperature;
        var fMaximumTemperature = fFirstElementTemperature;
        var fAvgTemperature = fFirstElementTemperature;

        var i = 0;
        var total = 0;
        $.each(aCircleList, function() {
            var oCircle = $(this);
            var temperature = parseFloat(oCircle.data("temperature"));

            if(temperature < fMinimumTemperature)
            {
                fMinimumTemperature = temperature;
            }
            
            if(temperature > fMaximumTemperature)
            {
                fMaximumTemperature = temperature;
            }
            
            total += temperature;

            i++;
        });

        fAvgTemperature = Math.round((total / i)*10)/10;
    
        return [fMinimumTemperature, fMaximumTemperature, fAvgTemperature];
    }
    
    return [null, null, null];
}

function setCondition(condName, buttonId) {
    buttonId = (typeof buttonId === 'undefined') ? condName : buttonId;
    if ($("#" + buttonId).is(":checked"))
        $("." + condName).show();
    else
        $("." + condName).hide();
}

// initialisation et mise en place des listeners
$(document).ready(function () {
    // installation du tableau de suggestion des noms de villes    
//    var villes = [];
//    var regions = (currLang == "fr" ? regionsFr : regionsEn);
	
	//test
	if(localStorage.getItem('lastCitySelect')!='undefined'){
		$('#lastSelect').text = localStorage.getItem('lastCitySelect') 
	}
	//
	
    for (var k in regionsFr)
    {
        citiesFr.push(k);
    } // ne garder que les clés
    for (var k in regionsEn)
    {
        citiesEn.push(k);
    }
    
    // adapté de http://twitter.github.io/typeahead.js/examples/
    $('#inVille').typeahead({
            hint: true, 
            highlight: true, 
            minLength: 1
        },
        {
            name: 'villes',
            displayKey: "value",
            source: function (q, cb) 
            {
                var matches, substringRE;
                matches = [];
                if (q.substr(0, 2) === "st" || q.substr(0, 2) === "St")
                {
                    substringRE = new RegExp('^s(ain)?(\.| |-)?' + q.substr(1), 'i');
                } 
                else
                {    
                    substringRE = new RegExp('^' + q, 'i');
                }

                $.each(cities, function (i, str)
                {
                    if (substringRE.test(str)) 
                    {
                        matches.push({value: str});
                    }
                });
                var matchesSorted = matches.sort(compare);
                cb(matchesSorted.slice(0, 10));
                //alert(matches[0].value);
            }
        }
    );
    
    function compare(a, b)
    {
        if (a.value < b.value)
            return -1;
        if (a.value > b.value)
            return 1;
        return 0;
    }

    // adapté de http://stackoverflow.com/questions/9425024/submit-selection-on-bootstrap-typeahead-autocomplete
    $('#inVille').on('typeahead:selected', function (e) {
        var villeSelectionnee = $('#inVille').val();
        //test
        localStorage.setItem('lastCitySelect',villeSelectionnee)
        alert("You've selected: "+villeSelectionnee)
        //
        var prov = regions[villeSelectionnee][0];
        var ville = regions[villeSelectionnee][1];
        var id02 = regions[villeSelectionnee][2];
        var id37 = regions[villeSelectionnee][3];
        var code = regions[villeSelectionnee][4];
        var province = regions[villeSelectionnee][5];
        $('#prov').val(prov);
        $('#ville').val(ville);
        $('#inVille');
        $('#id02').val(id02);
        $('#id37').val(id37);
        $('#code').val(code);
        $('#province').val(province);

        e.target.form.submit();
    });

    //  peut-être intéressant si on sort avec tab... mais génère souvent trop de submits
    // .blur(function(e) { 
    //           window.setTimeout(function(){e.target.form.submit()}, 50)
    //       });
    // installation des tooltips sur les éléments de temperature 
    $('.temperature, .max-temperature, .avg-temperature, .min-temperature, .accumCond, .percentPrecipCond, .windCond, .cloudCond')
    .tooltip({
        trigger: 'hover click',
        html: true,
        container: 'body'
    });

    //// traitement des boutons à bascule
    // l'unité des degrés de température
    $('#degres').click(function () {
        var d = $(this).text();
        if (d === "°F") {
            fromCelsius();
            $(this).text("°C");
        } else {
            fromFarenheit();
            $(this).text("°F");
        }
    });
    
    
    // affichage selon la langue
    $('#lang').click(function () {
        setLanguage($(this).text(), $(this));

    });   

    // changement du nombre de jours
    $('select#nbdays').change(function (e) { 
        currNbJours = $(this).val();
        afficher_nbr_jour(parseInt(currNbJours));
    });
    function afficher_nbr_jour(NbJour) {
        // Elargir le graphique
        $("#meteo-graphic").attr("width", (NbJour * 150) + "px");

        var $trs = $('#forecast tr');
        $trs.each(function (i, e)
        {
            if (i <= NbJour) // +1 for title
            {
                $(this).show();
            }
            else
            {
                $(this).hide();
            }
        });
        
        updateSvg();
    }
    afficher_nbr_jour(parseInt(currNbJours));

    /// traitement des checkbox
    $('#temperature').change(function() 
    {
        setCondition('max-temperature', 'temperature');
        setCondition('avg-temperature', 'temperature');
        setCondition('zero-temperature', 'temperature');
        setCondition('min-temperature', 'temperature');
        setCondition('temperature');
    });
    setCondition('max-temperature', 'temperature');
    setCondition('avg-temperature', 'temperature');
    setCondition('zero-temperature', 'temperature');
    setCondition('min-temperature', 'temperature');
    setCondition('temperature');
    
    //accumulation
    $('#accumCond').change(function() 
    {
        setCondition('accumCond');
    });
    setCondition('accumCond');
    
    //vent
    $("#windCond").change(function() 
    {
        setCondition('windCond');
    });
    setCondition('windCond');
    
    //precipitation
    $("#precipCond").change(function() 
    {
        setCondition('precipCond');
    });
    setCondition('precipCond');
    
    //percent precipitation
    $("#percentPrecipCond").change(function() 
    {
        setCondition('percentPrecipCond');
    });
    setCondition('percentPrecipCond');
    
    //cloud cover
    $("#cloudCond").change(function() 
    {
        setCondition('cloudCond');
    });
    setCondition('cloudCond');
});
