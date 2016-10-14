<?php

/**
 * Description of XmlParser
 *
 * @author molinspa
 */
class MeteoCodeXmlParser
{
    private $sUrl;
    
    public function __construct($sUrl)
    {
        $this->sUrl = $sUrl;
    }
    
    public function parse()
    {
        $aMeteoCodeList = array();
        
        $sXmlContent = Http::getFile($this->sUrl);
        
        $oSimpleXmlElement = simplexml_load_string($sXmlContent);
        
        $oMeteoCodeXmlInfo = $oSimpleXmlElement->head->product;
        
        $sRegionAbbreviation = $this->extractRegionAbbreviationFromUrl($this->sUrl);

        $aMeteoCodeXmlList = $oSimpleXmlElement->data->forecast->{'meteocode-forecast'};
        if(!empty($aMeteoCodeXmlList))
        {
            foreach($aMeteoCodeXmlList as $oMeteoCodeXml)
            {
                $oLocation = LocationFactory::fromMeteoCodeXml($oMeteoCodeXmlInfo,
                        $sRegionAbbreviation, $oMeteoCodeXml->location);
                
                $oMeteoCodeXmlData = $oMeteoCodeXml->parameters;

                $aCloudCoverList = self::createCloudCoverListFromXml($oMeteoCodeXmlData->{'cloud-list'});
                
                $aWindList = self::createWindListFromXml($oMeteoCodeXmlData->{'wind-list'});
                
                $aAccumulationList = self::createAccumulationListFromXml($oMeteoCodeXmlData->{'accum-list'});
                
                foreach($oMeteoCodeXmlData->{'temperature-list'} as $oMeteoCodeXmlTemperature)
                {
                    if((string)$oMeteoCodeXmlTemperature->attributes()->type === 'air')
                    {
                        $aAirTemperatureList = self::createAirTemperatureListFromXml($oMeteoCodeXmlTemperature);
                    }
                }
                
                $aPrecipitationList = self::createPrecipitationListFromXml(
                        $oMeteoCodeXmlData->{'precipitation-list'},
                        $oMeteoCodeXmlData->{'probability-of-precipitation-list'});
                
                $aWarningList = self::createWarningListFromXml($oMeteoCodeXmlData->{'warning-list'});        
                
                $oUpdate = UpdateFactory::fromMeteoCodeXml($this->sUrl, $oMeteoCodeXmlInfo);

                $oMeteoCode = MeteoCodeFactory::fromObject($oLocation, $oUpdate, $aAirTemperatureList, 
                        $aWindList, $aAccumulationList, $aCloudCoverList, 
                        $aPrecipitationList, $aWarningList);
                
                $aMeteoCodeList[] = $oMeteoCode;
            }
        }
        
        return $aMeteoCodeList;
    }
    
    private function extractRegionAbbreviationFromUrl($sUrl)
    {
        $sRegionAbbreviation = null;
        
        $aUrlFolderList = explode('/', $sUrl);
        $i = 0;
        for($i = 0; $i < count($aUrlFolderList); $i++)
        {
            if($aUrlFolderList[$i] === 'meteocode')
            {
                $sRegionAbbreviation = strtoupper((string)$aUrlFolderList[$i+1]);
            }
        }
        
        return $sRegionAbbreviation;
    }
    
    private function createCloudCoverListFromXml($oMeteoCodeXmlCloudList)
    {
        $sUnit = (string)$oMeteoCodeXmlCloudList->attributes()->units;
        
        $aCloudCoverList = array();
        foreach($oMeteoCodeXmlCloudList->{'cloud-cover'} as $oMeteoCodeXmlCloud)
        {
            $oCloudCover = CloudCoverFactory::fromMeteoCodeXml($sUnit, $oMeteoCodeXmlCloud);
            
            $aCloudCoverList[] = $oCloudCover;
        }
        
        return $aCloudCoverList;
    }
    
    private function createWindListFromXml($oMeteoCodeXmlWindList)
    {
        $sUnit = (string)$oMeteoCodeXmlWindList->attributes()->units;
        
        $aWindList = array();
        foreach($oMeteoCodeXmlWindList->{'wind'} as $oMeteoCodeXmlWind)
        {
            $oWind = WindFactory::fromMeteoCodeXml($sUnit, $oMeteoCodeXmlWind);
            
            $aWindList[] = $oWind;
        }
        
        return $aWindList;
    }
    
    private function createAccumulationListFromXml($oMeteoCodeXmlAccumulationList)
    {
        $sUnit = (string)$oMeteoCodeXmlAccumulationList->attributes()->units;

        $aAccumulationList = array();
        foreach($oMeteoCodeXmlAccumulationList->{'accum-amount'} as $oMeteoCodeXmlAccumulation)
        {
            $oAccumulation = AccumulationFactory::fromMeteoCodeXml($sUnit, $oMeteoCodeXmlAccumulation);
            
            if(!is_null($oAccumulation))
            {
                $aAccumulationList[] = $oAccumulation;
            }
        }
        
        return $aAccumulationList;
    }
    
    private function createAirTemperatureListFromXml($oMeteoCodeXmlAirTemperatureList)
    {
        $sUnit = (string)$oMeteoCodeXmlAirTemperatureList->attributes()->units;

        $aAirTemperatureList = array();
        foreach($oMeteoCodeXmlAirTemperatureList->{'temperature-value'} as $oMeteoCodeXmlAirTemperature)
        {
            $oAirTemperature = AirTemperatureFactory::fromMeteoCodeXml($sUnit, $oMeteoCodeXmlAirTemperature);
            
            if(!is_null($oAirTemperature))
            {
                $aAirTemperatureList[] = $oAirTemperature;
            }
        }
        
        return $aAirTemperatureList;
    }
    
    private function createPrecipitationListFromXml($oMeteoCodeXmlPrecipitationList, 
            $oMeteoCodeXmlPrecipitationProbabilityList)
    {
        $sProbabilityUnit = (string)$oMeteoCodeXmlPrecipitationProbabilityList->attributes()->units;

        $aProbabilityList = array();
        foreach($oMeteoCodeXmlPrecipitationProbabilityList->{'probability-of-precipitation'} as $oMeteoCodeXmlProbability)
        {
            $aProbabilityList[] = array(
                'fProbability' => Number::getFloat((string)$oMeteoCodeXmlProbability),
                'sStartDate' => Date::getFullDateFromIso8601((string)$oMeteoCodeXmlProbability->attributes()->start),
                'sEndDate' => Date::getFullDateFromIso8601((string)$oMeteoCodeXmlProbability->attributes()->end)
                );
        }
        
        $aPrecipitationList = array();
        foreach($oMeteoCodeXmlPrecipitationList->{'precipitation-event'} as $oMeteoCodeXmlPrecipitation)
        {
            $oPrecipitation = PrecipitationFactory::fromMeteoCodeXml($aProbabilityList, $sProbabilityUnit, $oMeteoCodeXmlPrecipitation);
            
            if(!is_null($oPrecipitation))
            {
                $aPrecipitationList[] = $oPrecipitation;
            }
        }
        
        return $aPrecipitationList;
    }
    
    private function createWarningListFromXml($oMeteoCodeXmlWarningList)
    {
        $aWarningList = array();
        foreach($oMeteoCodeXmlWarningList->{'warning-event'} as $oMeteoCodeXmlWarning)
        {
            $oWarning = WarningFactory::fromMeteoCodeXml($oMeteoCodeXmlWarning);
            
            $aWarningList[] = $oWarning;
        }
        
        return $aWarningList;
    }
}
