<?php
class MeteoRealisation
{
	public function __construct($meteocode){
		$this->meteocode = $meteocode;
	}
	
	public function getMeteoInfoOneDay($choice,$day){
		switch ($choice) {
			case "minTemp":
			case "maxTemp":
				$aList = $this->meteocode->getAirTemperatureList();
				break;
			case "cloudCover":
				$aList = $this->meteocode->getCloudCoverList();
				break;
			default:
				break;			
		}
		
		$aList0 = $aList[0];
		$startDate = Date::getDisplayableHour($aList0->getStartDate());
		$firstDelay = (integer) substr($startDate, 0, 2);
		
		if(!empty($aList))
		{
			$min = null;
			$max = null;
			$avAm = array();
			$avPm = array();
			
			$i = ($day == 0)? 0 : (24-$firstDelay)+24*($day-1);
			$tStop = (24-$firstDelay)+24*$day;
			if($tStop > count($aList)){$tStop = count($aList);}
			for(; $i < $tStop ; $i++)
			{
				$oUnitVal = $aList[$i];
				$sDate = $oUnitVal->getStartDate();
				if($max == null){
					$max = $oUnitVal->getValue();
					$min = $oUnitVal->getValue();
				}
				else{
					$value = $oUnitVal->getValue();
					if($value > $max){
						$max = $value;
					}
					if($value < $min){
						$min = $value;
					}
				}
			}
			$average = ($max+$min)/2.0;
			// retour minValue,maxValue,average,averageAm,averagePM
			return array($min,$max,$average);
		}
		
		
	}
	
	public function getMinMaxTempOneDay($minOrMax, $day, $unitBool = false){
		//return 'N/A';
		try{
			$aAirTemperatureList = $this->meteocode->getAirTemperatureList();
				
			$oAirTemperature0 = $aAirTemperatureList[0];
			$startDate = Date::getDisplayableHour($oAirTemperature0->getStartDate());
		} catch (Exception $e) {
				return 'N/A';
		}
		
		$firstDelay = (integer) substr($startDate, 0, 2);
			
		if(!empty($aAirTemperatureList))
		{
			$minTemp = null;
			$maxTemp = null;
			$i = ($day == 0)? 0 : (24-$firstDelay)+24*($day-1);
			$tStop = (24-$firstDelay)+24*$day;
			if($tStop > count($aAirTemperatureList)){$tStop = count($aAirTemperatureList);}
			for(; $i < $tStop ; $i++)
			{
				$oAirTemperature = $aAirTemperatureList[$i];
				$sDate = $oAirTemperature->getStartDate();
				if($maxTemp == null){
					$maxTemp = $oAirTemperature->getMaxValue();
					$minTemp = $oAirTemperature->getMinValue();
				}
				else{
					$max= $oAirTemperature->getMaxValue();
					$min= $oAirTemperature->getMinValue();
					if($max > $maxTemp){
						$maxTemp = $max;
					}
					if($min < $minTemp){
						$minTemp = $min;
					}
				}
			}
		}
		if($unitBool == true){
			$tempUnit = $oAirTemperature0->getUnit();
			$tempUnitString = ($tempUnit == "celsius")?"°C":"°F";
			if($minOrMax == 'min'){;
			return $minTemp . " " . $tempUnitString;
			}
			else if($minOrMax == 'max'){
				return $maxTemp . " " . $tempUnitString;
			}
			else{
				return null;
			}
		}else{
			if($minOrMax == 'min'){
				return round($minTemp);
			}else{
				return round($maxTemp);
			}
		}
	
			
		
		
		
		
		
	}
	
	public function getCloudCoverText($coverValue,$dataJSON,$lang){
	
		$ccv = $dataJSON[$lang]["cloudCond"]["alternative"]["cloud-cover-value"];
		for($i=1; $i<count($ccv); $i++){
			if($ccv[$i]["min"] > $coverValue){
				return $ccv[$i-1]["text"];
			}
		}
		return $ccv[count($ccv)-1]["text"];
	}
	
	public function updateMeteo(){
		
		//echo getcwd() . "\n";
		
		$jsonString = file_get_contents('public/data/jsreal-realization-instruction.json');
		$data = json_decode($jsonString, true);
				
		$sevenPhrasesFr = array(7);
		$sevenPhrasesEn = array(7);		

		for ($day=0; $day<7; $day++){
			$cloudCoverValues = $this->getMeteoInfoOneDay("cloudCover", $day);
			//cloudCover integer (1 to 10)
			$cloudCover = intval(round($cloudCoverValues[2]));
			//cloudCover text
			$cloudCoverFr = $this->getCloudCoverText($cloudCover,$data,"fr");
			$cloudCoverEn = $this->getCloudCoverText($cloudCover,$data,"en");
			$minTemp = $this->getMinMaxTempOneDay('min', $day); 
			$maxTemp = $this->getMinMaxTempOneDay('max', $day); 
			
			$sevenPhrasesFr[$day]= "S(NP(D('le'),N('journée')),VP(V('être').t('f'),$cloudCoverFr),C('et'),NP(D('le'),N('température')),VP(V('varier').t('f'),P('entre'),CP(NO($minTemp).tag('span',{'class':'celsius'}),C('et'),NO($maxTemp).tag('span',{'class':'celsius'}))))";
			$sevenPhrasesEn[$day]= "S(NP(D('the'),N('day')),VP(V('be').t('f'),$cloudCoverEn),C('and'),NP(D('the'),N('temperature')),VP(V('vary').t('f'),P('between'),CP(NO($minTemp).tag('span',{'class':'celsius'}),C('and'),NO($maxTemp).tag('span',{'class':'celsius'}))))";
		}
		
		$newJsonStringFr = json_encode($sevenPhrasesFr);
		file_put_contents('public/data/additional-info-phrasesFr.json', $newJsonStringFr);
		$newJsonStringEn = json_encode($sevenPhrasesEn);
		file_put_contents('public/data/additional-info-phrasesEn.json', $newJsonStringEn);
		
		//
		//No need to put bak in file
		//$newJsonString = json_encode($data);
		//file_put_contents('public/data/jsreal-realization-instruction.json', $newJsonString);
	}
	
	
	
	
	
}
?>
