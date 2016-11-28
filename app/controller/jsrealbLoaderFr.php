<script type="text/javascript" charset="utf-8" src="JSrealB/static/js/JSrealB.js"></script>
<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>
 <script type="text/javascript">
 var URL = {
                lexicon:  {
                    fr: "JSrealB/data/lex-fr.min.json",
                    en: "JSrealB/data/lex-en.min.json"
                },
                rule: {
                    fr: "JSrealB/data/rule-fr.min.json",
                    en: "JSrealB/data/rule-en.min.json"
                },
                feature: "JSrealB/data/feature.min.json"
            };

/*    JSrealLoader({
                language: "fr",
                lexiconUrl: URL.lexicon.fr,
                ruleUrl: URL.rule.fr,
                featureUrl: URL.feature
            }, function() {
                console.log("Langue française chargée");

                //Ajouts aux lexique:
                
		JSrealB.Config.get("lexicon")["partiellement"] = {"Adv": {"tab": ["av"]}};
                JSrealB.Config.get("lexicon")["nuageux"] = {"A": {"tab": ["n54"]}};
                JSrealB.Config.get("lexicon")["alternance"] = {"N": {"g":"f","tab": ["n17"]}};
		
		JSrealB.Config.get("lexicon")["alternance"] = {"N": {"g":"f","tab": ["n17"]}};
		

                for(var i=1; i<8; i++){
                	$("#forecastTable tr:eq("+i+")").append("<td data-title='Information'>"+eval(phrase[i-1])+"</td>");
                }
    			
            });
*/

    var loadJSrealB = function(language){
	JSrealLoader({
                language: language,
                lexiconUrl: (language=="fr")?URL.lexicon.fr:URL.lexicon.en,
                ruleUrl: (language=="fr")?URL.rule.fr:URL.rule.en,
                featureUrl: URL.feature
            }, function() {
                console.log((language=="fr")?"Langue française chargée":"English language loaded");

                //Ajouts aux lexique:
		if(language=="fr"){
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
		    JSrealB.Config.get("lexicon")["sunny"] = {"A": {"tab": ["a2"]}};
		    JSrealB.Config.get("lexicon")["mainly"] = {"Adv": {"tab": ["b1"]}};
		    JSrealB.Config.get("lexicon")["cloudy"] = {"A": {"tab": ["a2"]}};
		    JSrealB.Config.get("lexicon")["cloudy"] = {"A": {"tab": ["a2"]}};
		    JSrealB.Config.get("lexicon")["cloudy"] = {"A": {"tab": ["a2"]}};
		    JSrealB.Config.get("lexicon")["centimeter"] = {"N": {"tab": ["n1"]}};
		    JSrealB.Config.get("lexicon")["km/h"] = {"N": {"tab": ["n4"]}};
		    JSrealB.Config.get("lexicon")["centimeter"] = {"N": {"tab": ["n1"]}};
		    JSrealB.Config.get("lexicon")["south"] = {"N": {"tab": ["n4"]}};
		    JSrealB.Config.get("lexicon")["west"] = JSrealB.Config.get("lexicon")["east"] = {"N": {"tab": ["n4"]}};
            JSrealB.Config.get("lexicon")["northwest"] = JSrealB.Config.get("lexicon")["northeast"] = {"N": {"tab": ["n4"]}};
            JSrealB.Config.get("lexicon")["southwest"] = JSrealB.Config.get("lexicon")["southeast"] = {"N": {"tab": ["n4"]}};
		    
		}

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
                $("#forecastTable").find("tr").eq(i).find("td")[3].textContent = (language=="fr")?eval(phraseFr[i-1]):eval(phraseEn[i-1]);
        	}
    			
     	});
    };

</script>

