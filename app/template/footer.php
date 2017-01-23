    </div>
</body>
</html>
<!-- bootstrap -->
<?php echo Import::css('public/css/bootstrap-3.3.2.min.css'); ?>
<?php echo Import::css('public/css/bootstrap-theme-3.3.2.min.css'); ?>
<!--<link rel="stylesheet" href="public/css/bootstrap-3.3.2.min.css" type="text/css" charset="utf-8" />-->
<!--<link rel="stylesheet" href="public/css/bootstrap-theme-3.3.2.min.css" type="text/css" charset="utf-8" />-->

<!-- spécifique à MeteoVis -->
<?php echo Import::css('public/css/MeteoVis.css'); ?>
<?php echo Import::css('public/css/MeteoVis-typeahead.css'); ?>
<!--<link rel="stylesheet" href="public/css/MeteoVis.css" type="text/css" charset="utf-8">
<link rel="stylesheet" href="public/css/MeteoVis-typeahead.css" type="text/css" charset="utf-8">-->

<?php echo Import::js('public/js/jquery-1.11.2.min.js'); ?>
<?php echo Import::js('public/js/bootstrap-3.3.2.min.js'); ?>
<?php echo Import::js('public/js/typeahead.bundle.min.js'); ?>
<!--<script type="text/javascript" charset="utf-8" src="public/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/bootstrap-3.3.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/typeahead.bundle.min.js"></script>-->
<script language="javascript" type="text/javascript">
    var currNbJours = <?php echo ($param = 7); ?>
</script>

<?php echo Import::js('public/js/date.js'); ?>
<?php echo Import::js('public/data/regionTable.min.js'); ?>
<?php echo Import::js('public/js/MeteoVis.js'); ?>
<?php echo Import::js('public/js/util.js'); ?>
<?php echo Import::js('public/js/translator.js'); ?>
<!--<script type="text/javascript" charset="utf-8" src="public/js/date.js"></script>
<script type="text/javascript" charset="utf-8" src="public/data/regionTable.min.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/MeteoVis.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/util.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/translator.js"></script>-->

<?php echo Import::js('public/js/jsreal-realization.js'); ?>
<!--<script type="text/javascript" charset="utf-8" src="public/data/lex-fr.min.js"></script>-->
<!--<script type="text/javascript" charset="utf-8" src="public/js/JSreal.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/jsreal-realization.js"></script>-->

<?php echo Import::js('../web_page_gauthif/JSrealB-EnFr.js'); ?>

<script type="text/javascript">

    var loadJSrealB = function(language){
	
		if(language=="fr"){
			loadFr();
		    JSrealB.Config.get("lexicon")["partiellement"] = {"Adv": {"tab": ["av"]}};
            JSrealB.Config.get("lexicon")["nuageux"] = {"A": {"tab": ["n54"]}};
            JSrealB.Config.get("lexicon")["alternance"] = {"N": {"g":"f","tab": ["n17"]}};
		    JSrealB.Config.get("lexicon")["possibilité"] = {"N": {"g":"f","tab": ["n17"]}};
		    JSrealB.Config.get("lexicon")["précipitation"] = {"N": {"g":"f","tab": ["n17"]}};
           	JSrealB.Config.get("lexicon")["total"] = {"N": {"g":"m","tab": ["n5"]}};
            JSrealB.Config.get("lexicon")["quasi-certain"] = {"A": {"tab": ["n28"]}};
            JSrealB.Config.get("lexicon")["km/h"] = {"N": {"tab": ["nI"]}};
            JSrealB.Config.get("lexicon")["ouest"] = JSrealB.Config.get("lexicon")["est"] = {"N": {"tab": ["n35"]}};
            JSrealB.Config.get("lexicon")["nord-ouest"] = JSrealB.Config.get("lexicon")["nord-est"] = {"N": {"tab": ["n35"]}};
            JSrealB.Config.get("lexicon")["sud-ouest"] = JSrealB.Config.get("lexicon")["sud-est"] = {"N": {"tab": ["n35"]}};            
		}
		else{       
			loadEn();         
		    JSrealB.Config.get("lexicon")["sunny"] = {"A": {"tab": ["a2"]}};
		    JSrealB.Config.get("lexicon")["mainly"] = {"Adv": {"tab": ["b1"]}};
		    JSrealB.Config.get("lexicon")["cloudy"] = {"A": {"tab": ["a2"]}};
		    JSrealB.Config.get("lexicon")["cloudy"] = {"A": {"tab": ["a2"]}};
		    JSrealB.Config.get("lexicon")["cloudy"] = {"A": {"tab": ["a2"]}};
		    JSrealB.Config.get("lexicon")["centimeter"] = {"N": {"tab": ["n1"]}};
		    JSrealB.Config.get("lexicon")["precipitation"] = {"N": {"tab": ["n1"]}};
		    JSrealB.Config.get("lexicon")["km/h"] = {"N": {"tab": ["n4"]}};
		    JSrealB.Config.get("lexicon")["centimeter"] = {"N": {"tab": ["n1"]}};
		    JSrealB.Config.get("lexicon")["south"] = {"N": {"tab": ["n4"]}};
		    JSrealB.Config.get("lexicon")["west"] = JSrealB.Config.get("lexicon")["east"] = {"N": {"tab": ["n4"]}};
            JSrealB.Config.get("lexicon")["northwest"] = JSrealB.Config.get("lexicon")["northeast"] = {"N": {"tab": ["n4"]}};
            JSrealB.Config.get("lexicon")["southwest"] = JSrealB.Config.get("lexicon")["southeast"] = {"N": {"tab": ["n4"]}};
		    
		}

	$(document).ready(function () {

		var phraseEn = 
                <?php 
			$jsonString = file_get_contents('public/data/additional-info-phrasesEn.json');
                	$phrases = json_decode($jsonString, true);
                	
					$phrases7 = "[";
					for($i=0;$i<7;$i++){
						if($i==0){
							$phrases7 =  $phrases7 . "\"" . $phrases[$i]. "\"";
						}else{
							$phrases7 =  $phrases7 . ",\"" . $phrases[$i] . "\"";
						}
						
					}
                	echo "$phrases7]";
                ?>
                
                var phraseFr = 
                <?php 
                	$jsonString = file_get_contents('public/data/additional-info-phrasesFr.json');
                	$phrases = json_decode($jsonString, true);
                	
					$phrases7 = "[";
					for($i=0;$i<7;$i++){
						if($i==0){
							$phrases7 =  $phrases7 . "\"" . $phrases[$i]. "\"";
						}else{
							$phrases7 =  $phrases7 . ",\"" . $phrases[$i] . "\"";
						}
						
					}
                	echo "$phrases7]";
                ?>
		

        
        	for(var i=1; i<8; i++){
                $("#forecastTable").find("tr").eq(i).find("td")[3].innerHTML = (language=="fr")?eval(phraseFr[i-1]):eval(phraseEn[i-1]);
        	}
        })
    };
    
	//Initialisation de la page aux paramètres de l'utilisateur
	setLanguage(localStorage.getItem('lastLangSelect'), $('#lang'));
	//var d = $(this).text();

    if ( localStorage.getItem('lastDegSelect')=== "°F") {
        fromCelsius();
        $('#degres').text("°C");
    } else {
        fromFarenheit();
        $('#degres').text("°F");
    }
	//
	//test
	if(localStorage.getItem('lastCitySelect')!='undefined'){
		$('#lastSelect').text = localStorage.getItem('lastCitySelect') 
	}
	console.log('its called')
	console.log(localStorage.getItem('lastCitySelect'))
	console.log(localStorage.getItem('lastLangSelect'))
	console.log(localStorage.getItem('lastDegSelect'))
	//

</script>
