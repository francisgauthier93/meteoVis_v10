<?php

/**
 * Description of WeatherView
 *
 * @author molinspa
 */
class WeatherView
{
    public function __construct()
    {
        ;
    }
    
    public function getCurrentConditionHeader($province, $city, $sStyleSheet)
    {
        if(empty($province)
                || empty($city)
                || empty($sStyleSheet))
        {
            throw new WrongArgumentExceptionException($province . ' ' . $city . ' ' . $sStyleSheet);
        }
        
        return $this->loadCityPageEnglish($sStyleSheet, $province, $city);
    }

    public function getCurrentConditionBody($province, $city, $sStyleSheet)
    {
        if(empty($province)
                || empty($city)
                || empty($sStyleSheet))
        {
            throw new WrongArgumentExceptionException($province . ' ' . $city . ' ' . $sStyleSheet);
        }
        
        return $this->loadCityPageBilingual($sStyleSheet, $province, $city);
    }

    public function getNextHoursTemperature($iNbHours, MeteoCode $oMeteoCode)
    {
        $sReturnString = '';
        
        $aAirTemperatureList = $oMeteoCode->getAirTemperatureList();

        if(!empty($aAirTemperatureList) && $iNbHours <= count($aAirTemperatureList))
        {
            for($i = 0; $i < $iNbHours; $i++)
            {
                $oAirTemperature = $aAirTemperatureList[$i];
                $sDate = $oAirTemperature->getStartDate();
                $fTemperature = (($oAirTemperature->getMinValue() + $oAirTemperature->getMaxValue()) / 2);
                $sReturnString .= '<tr><th>' . Date::getDisplayableHour($sDate) . '</th>'
                        . '<td>' . Number::getDisplayableFloat($fTemperature) . '</td></tr>';
            }
        }
        
        return $sReturnString;
    }

    private function loadCityPageBilingual($sStyleSheet, $province, $sCityId)
    {
        $xml = new DOMDocument();
        $sLocalSourceFileFr = Config::get('path.real.root') 
                . Config::get('path.relative.root_to_citypage')
                . $sCityId . '_f.' . Config::get('file.extension.xml');
        try
        {
        	////Remove after debug
        	throw new FileNotFoundException('File : ' . $sLocalSourceFileFr);
        	/////
            if(Filesystem::exists($sLocalSourceFileFr))
            {
                $sSourceFileFr = $sLocalSourceFileFr;
                if(!@$xml->load($sSourceFileFr))
                {
                    throw new InvalidXmlException('File : ' . $sSourceFileFr);
                }
            }
            else
            {
                throw new FileNotFoundException('File : ' . $sLocalSourceFileFr);
            }
        }
        catch(Exception $e)
        {
            $sSourceFileFr = 'http://dd.weatheroffice.gc.ca/citypage_weather/xml/' . $province . '/' . $sCityId . '_f.xml';
            $xml->load($sSourceFileFr);
        }

        $oXmlDocument = simplexml_import_dom($xml);
        Date::setLocalTimeZoneFromZoneNameFr(
                        (string)$oXmlDocument->currentConditions[0]->dateTime[1]->attributes()->zone);

        $xsl = new DOMDocument();
        $xsl->load(Config::get('path.real.root') . Config::get('path.relative.root_to_app') . 'xsl/' . $sStyleSheet);
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl);
        
        $sLocalSourceFileEn = Config::get('path.real.root') 
                . Config::get('path.relative.root_to_citypage')
                . $sCityId . '_e.' . Config::get('file.extension.xml');
        if(file_exists($sLocalSourceFileEn))
        {
            $sSourceFileEn = $sLocalSourceFileEn;
        }
        else
        {
            $sSourceFileEn = 'http://dd.weatheroffice.gc.ca/citypage_weather/xml/' . $province . '/' . $sCityId . '_e.xml';
        }
        
        $proc->setParameter(null, 'province', $province);
        $proc->setParameter(null, 'fichieren', $sSourceFileEn);
        $proc->setParameter(null, 'ville', $sCityId);

        return $proc->transformToXML($xml);  
    }

    private function loadCityPageEnglish($sStyleSheet, $province, $sCityId)
    {
        $xml = new DOMDocument();
        $sLocalSourceFileEn = Config::get('path.real.root') 
                . Config::get('path.relative.root_to_citypage')
                . $sCityId . '_e.' . Config::get('file.extension.xml');
        try
        {
        	////Remove after debug
        	//throw new FileNotFoundException('File : ' . $sLocalSourceFileFr);
        	/////
            $sSourceFileEn = $sLocalSourceFileEn;
            if(Filesystem::exists($sLocalSourceFileEn))
            {
                $sSourceFileEn = $sLocalSourceFileEn;
                if(!@$xml->load($sLocalSourceFileEn))
                {
                    throw new InvalidXmlException('File : ' . $sLocalSourceFileEn);
                }
            }
            else
            {
                throw new FileNotFoundException('File : ' . $sLocalSourceFileEn);
            }
        }
        catch(Exception $e2)
        {
            $sSourceFileEn = 'http://dd.weatheroffice.gc.ca/citypage_weather/xml/' . $province . '/' . $sCityId . '_e.xml';
            $xml->load($sSourceFileEn);            
        }

        $oXmlDocument = simplexml_import_dom($xml);
        Date::setLocalTimeZoneFromZoneNameEn(
                (string)$oXmlDocument->currentConditions[0]->dateTime[1]->attributes()->zone);

        $xsl = new DOMDocument();
        $xsl->load(Config::get('path.real.root') . Config::get('path.relative.root_to_app') . 'xsl/' . $sStyleSheet);
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl);
        $proc->setParameter(null, 'province', $province);
        $proc->setParameter(null, 'ville', $sCityId);

        return $proc->transformToXML($xml);
    }
}