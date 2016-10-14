<?php

/**
 * Description of GraphView
 *
 * @author molinspa
 */
class GraphView
{
    private $oMeteoCode;
    
    private $fMinTemperature;
    private $fAvgTemperature;
    private $fMaxTemperature;
    
    private $iXSpace;
    private $iYSpace;
    private $iDayWidth;
    private $iYCenter;
    private $iDegreToPxCoeff;
    private $iMaxNbDays;
    
    public function __construct(MeteoCode &$oMeteoCode)
    {
        $this->oMeteoCode = $oMeteoCode;
        
        $this->fMinTemperature = AirTemperatureFactory::getMinimumAirTemperature(
                $oMeteoCode->getAirTemperatureList());
        $this->fMinTemperature = is_null($this->fMinTemperature) ? 0 : $this->fMinTemperature;
        
        $this->fAvgTemperature = AirTemperatureFactory::getAverageAirTemperature(
                $oMeteoCode->getAirTemperatureList());
        $this->fAvgTemperature = is_null($this->fAvgTemperature) ? 0 : $this->fAvgTemperature;
        
        $this->fMaxTemperature = AirTemperatureFactory::getMaximumAirTemperature(
                $oMeteoCode->getAirTemperatureList());
        $this->fMaxTemperature = is_null($this->fMaxTemperature) ? 0 : $this->fMaxTemperature;
        
        $this->iGraphHeight = Config::get('meteo.graph.measure.iHeight');
        $this->iXSpace = Config::get('meteo.graph.measure.iXSpace');
        $this->iYSpace = Config::get('meteo.graph.measure.iYSpace');
        $this->iDayWidth = Config::get('meteo.graph.measure.iDayWidth');
        $this->iYCenter = Config::get('meteo.graph.measure.iYCenter');
        $this->iDegreToPxCoeff = Config::get('meteo.graph.coeff.degre_to_px');
        $this->iMaxNbDays = Config::get('meteo.limit.day.max');
        $this->iEndLine = ($this->iMaxNbDays * $this->iDayWidth);
    }
    
    public function getDay()
    {
        $sReturnString = '';
        for ($i = 0; $i < $this->iMaxNbDays; $i++) 
        {
            $sDay = '<tspan data-original-translation>' 
                    . Date::getDisplayableDate(Date::getDateFromNow($i)) . '</tspan>';

            $sReturnString .= Graphic::text($sDay, array(
                'x' => ($i * $this->iDayWidth + 75), 
                'y'=> 25,
                'style'=>'fill:black',
                'text-anchor'=> 'middle'));
        }
        return $sReturnString;
    }
    
    public function getTemperature()
    {
        $sReturnString = '';

        $i = 0;
        $sInitialDate = Date::setToNextHour(Date::getLocalFullDate(Date::getUTCFullDate()));

        $iMargin = Date::getHour($sInitialDate);
        $aAirTemperatureList = $this->oMeteoCode->getAirTemperatureList();
        foreach($aAirTemperatureList as $oAirTemperature)
        {
            $fCurrentTemperatureValue = Number::getRoundedFloat($oAirTemperature->getAvgValue());
            $sCurrentTemperatureDate = $oAirTemperature->getStartDate();
            
            $i = Date::getFullDateHourDiff($sInitialDate, 
                    Date::getLocalFullDate($sCurrentTemperatureDate));
            
            $sReturnString .= Graphic::usesvg(array(
                'xlink:href' => '#graph_air_temperature',
                'x' => (($i + $iMargin) * $this->iXSpace),
                'y' => Number::getInt($this->iYCenter + (abs($fCurrentTemperatureValue - $this->fAvgTemperature) * $this->iDegreToPxCoeff)
                        * ($fCurrentTemperatureValue > $this->fAvgTemperature ? -1 : 1)),
                'class' => 'temperature',
                'data-placement' => 'bottom',
                'data-original-title' => '',
                'data-temperature' => Number::getFloat($fCurrentTemperatureValue),
                'data-jsreal-temperature-date' => Date::getLocalFullDate($sCurrentTemperatureDate),
                'data-jsreal-temperature-value' => Number::getDisplayableFloat($fCurrentTemperatureValue)
            ));
        }
        
        return $sReturnString;
    }
    
    public function getMinimumLine()
    {
        $sReturnString = '';
        
        $arg = array('class' => 'min-temperature',
            'id' => 'line-minimum-temperature',
            'data-original-title' => '',
            'data-jsreal-temperature-min' => Number::getDisplayableFloat($this->fMinTemperature),
            "x1" => 0,
            "y1" => intval($this->iYCenter + (abs($this->fMinTemperature - $this->fAvgTemperature) * $this->iDegreToPxCoeff)),
            "x2" => $this->iEndLine,
            "y2" => intval($this->iYCenter + (abs($this->fMinTemperature - $this->fAvgTemperature) * $this->iDegreToPxCoeff)),
            "stroke" => "blue",
            "stroke-width" => 0.5);
        $sReturnString .= Graphic::line($arg);
    
        $argtxt = array(
            'class' => $arg['class'],
            'data-original-title' => '',
            'data-jsreal-temperature-min' => Number::getDisplayableFloat($this->fMinTemperature),
            'x' => 0,
            'y' => intval($this->iYCenter + (abs($this->fMinTemperature - $this->fAvgTemperature) * $this->iDegreToPxCoeff) - 3),
            'stroke-width' => 0,
            'fill' => "blue");
        $sReturnString .= Graphic::text("<tspan id='label-minimum-temperature' class='svgcelsius'>" 
                . Number::getRoundedFloat($this->fMinTemperature, 1) . "°C</tspan>", $argtxt);
        
        return $sReturnString;
    }
    
    public function getAverageLine()
    {
        $sReturnString = '';
        
        $y = $this->iYCenter;
        $sReturnString .= Graphic::line(array(
                'id' => 'line-avg-temperature', 
                'x1' => 0, 
                'y1' => $y, 
                'x2' => $this->iEndLine, 
                'y2' => $y, 
                'stroke' => 'green',
                'stroke-width' => 0.5, 
                'class' => 'avg-temperature',
                'data-original-title' => '',
                'data-jsreal-temperature-avg' => Number::getDisplayableFloat($this->fAvgTemperature)));
        $arg = array(
            'x' => 0,
            'y' => $y - 3,
            'stroke-width' => 0,
            'fill' => 'green',
            'class' => 'avg-temperature',
            'data-original-title' => '',
            'data-jsreal-temperature-avg' => Number::getDisplayableFloat($this->fAvgTemperature));
        $sReturnString .= Graphic::text("<tspan id='label-avg-temperature' class='svgcelsius'>" 
                . Number::getRoundedFloat($this->fAvgTemperature, 1) . "°C</tspan>", $arg);
        $arg["x"] = ($this->iEndLine - 50);
        
        return $sReturnString;
    }
    
    public function getZeroLine()
    {
        $sReturnString = '';
        
        $arg = array('class' => 'zero-temperature',
            'id' => 'line-zero-temperature',
            "x1" => 0,
            "y1" => intval($this->iYCenter - ((0 - $this->fAvgTemperature) * $this->iDegreToPxCoeff)),
            "x2" => $this->iEndLine,
            "y2" => intval($this->iYCenter - ((0 - $this->fAvgTemperature) * $this->iDegreToPxCoeff)),
            "stroke" => "grey",
            "stroke-width" => 0.5);
        $sReturnString .= Graphic::line($arg);
    
        $argtxt = array(
            'class' => $arg['class'],
            'x' => 0,
            'y' => intval($this->iYCenter - ((0 - $this->fAvgTemperature) * $this->iDegreToPxCoeff) - 3),
            'stroke-width' => 0,
            'fill' => "grey");
        $sReturnString .= Graphic::text("<tspan id='label-zero-temperature' class='svgcelsius'>" 
                . Number::getRoundedFloat(0, 0) . "°C</tspan>", $argtxt);
        
        return $sReturnString;
    }
    
    public function getMaximumLine()
    {
        $sReturnString = '';
        
        $arg = array('class' => 'max-temperature',
            'id' => 'line-maximum-temperature',
            'data-original-title' => '',
            'data-jsreal-temperature-max' => Number::getDisplayableFloat($this->fMaxTemperature),
            'x1' => 0,
            'y1' => intval($this->iYCenter - (abs($this->fMaxTemperature - $this->fAvgTemperature) * $this->iDegreToPxCoeff)),
            'x2' => $this->iEndLine,
            'y2' => intval($this->iYCenter - (abs($this->fMaxTemperature - $this->fAvgTemperature) * $this->iDegreToPxCoeff)),
            'stroke' => 'red',
            'stroke-width' => 0.5);
        
        $sReturnString .= Graphic::line($arg);
        
        $argtxt = array(
            'class' => $arg['class'],
            'data-original-title' => '',
            'data-jsreal-temperature-max' => Number::getDisplayableFloat($this->fMaxTemperature),
            'x' => 0,
            'y' => intval($this->iYCenter - (abs($this->fMaxTemperature - $this->fAvgTemperature) * $this->iDegreToPxCoeff)) - 3,
            'stroke-width' => 0,
            'fill' => "red");
        $sReturnString .= Graphic::text('<tspan id="label-maximum-temperature" class="svgcelsius">' 
                . Number::getRoundedFloat($this->fMaxTemperature, 1) . "°C</tspan>", $argtxt);
        
        return $sReturnString;
    }
    
    public function getPrecipitation()
    {
        $sReturnString = '';
        
        $aPrecipitationList = $this->oMeteoCode->getPrecipitationList();
        $aAccumulationList = $this->oMeteoCode->getAccumulationList();

        if(!empty($aPrecipitationList))
        {
            $i = 0;
            $sInitialDate = Date::setToNextHour(Date::getLocalFullDate(Date::getUTCFullDate()));
            $iMargin = Date::getHour($sInitialDate);
            $iPreviousProbabilityPosX = 0;
            foreach($aPrecipitationList as $oPrecipitation)
            {
                if(!is_null($oPrecipitation->getType())
                        && $oPrecipitation->getProbability() >= Config::get('meteo.require.precipitation.probability'))
                {
                    $i = Date::getFullDateHourDiff($sInitialDate, 
                            Date::getLocalFullDate($oPrecipitation->getStartDate()));

                    $iNewX = (($i + $iMargin) * $this->iXSpace);
                    
                    $iTimeSlot = Date::getFullDateHourDiff(
                            Date::getLocalFullDate($oPrecipitation->getStartDate()), 
                            Date::getLocalFullDate($oPrecipitation->getEndDate()));
                    
                    $sPrecipitationType = $this->getDisplayablePrecipitationType($oPrecipitation->getType());
                    
                    $sIntensity = $oPrecipitation->getIntensity();
                    if(is_null($sIntensity))
                    {
                        switch($oPrecipitation->getFrequency())
                        {
                            case 'brief':
                                $sIntensity = 'very light';
                                break;
                            case 'few':
                                $sIntensity = 'light';
                                break;
                            case 'frequent':
                                $sIntensity = 'moderate';
                                break;
                            case 'occasional':
                                $sIntensity = 'moderate';
                                break;
                            case 'continuous':
                                $sIntensity = 'heavy';
                                break;
                        }
                    }
                    
                    switch($sIntensity)
                    {
                        case 'heavy':
                            $iMinDuration = 2;
                            $iMaxDuration = 8;
                            break;
                        case 'light':
                            $iMinDuration = 15;
                            $iMaxDuration = 22;
                            break;
                        case 'very light':
                            $iMinDuration = 22;
                            $iMaxDuration = 28;
                            break;
                        default:
                            $iMinDuration = 8;
                            $iMaxDuration = 15;
                    }
                    
                    $k = 0;
                    $fTmpAccumulation = 0;
                    foreach($aAccumulationList as $oAccumulation)
                    {
                        if(($oAccumulation->getStartDate() <= $oPrecipitation->getStartDate()
                                && $oAccumulation->getEndDate() >= $oPrecipitation->getEndDate())
                            || ($oAccumulation->getStartDate() >= $oPrecipitation->getStartDate()
                                && $oAccumulation->getEndDate() <= $oPrecipitation->getEndDate()))
                        {
                            $fTmpAccumulation += (($oAccumulation->getMinimumAmount() + $oAccumulation->getMaximumAmount()) / 2);
                            $k++;
                        }
                    }
                    $fAccumulation = ($k > 0) ? Number::getFloat($fTmpAccumulation / $k) : 0;
                    
                    $fPrecipitationFactor = Number::getFloat(
                            ($fAccumulation > 10.0) ? 10.0 : $fAccumulation+1.0);
                    
                    for($j = 0; $j < ($iTimeSlot * $fPrecipitationFactor); $j++)
                    {
                        $iDuration = mt_rand($iMinDuration, $iMaxDuration);
                        
                        $sReturnString .= Graphic::useAnimated(
                            array(
                                'xlink:href' => '#graph_precipitation_' . $sPrecipitationType,
                                'x' => (($j * $this->iXSpace) / $fPrecipitationFactor) + $iNewX,
                                'y' => 50,
                                'class' => 'precipCond',
                                'data-title' => null,
                                'data-temperature' => null
                            ),
                            array(
                                'attributeName' => 'y',
                                'attributeType' => 'XML',
                                'from' => 50,
                                'to' => 272,
                                'begin' => mt_rand(0, ($fPrecipitationFactor * 3)) . 's',
                                'dur' => $iDuration . 's',
                                'repeatCount' => 9999,
                                'class' => 'precipCond'
                            ));
                    }
                    
                    //// Display probability
                    $iProbabilityPosX = $iNewX + (($iTimeSlot * $this->iXSpace) / 1.5);
                    if((($iProbabilityPosX - $iPreviousProbabilityPosX) / $this->iXSpace) >= 4
                        || $iPreviousProbabilityPosX === 0)
                    {
                        $sReturnString .= Graphic::text(
                                Number::getDisplayableInt($oPrecipitation->getProbability()) . '&#37;', 
                                array(
                                    'x' => $iProbabilityPosX, 
                                    'y'=> $this->iGraphHeight-8,
                                    'fill'=>'black',
                                    'font-size' => 12,
                                    'font-weight' => 'normal',
                                    'text-anchor' => 'middle',
                                    'dominant-baseline' => 'middle',
                                    'class' => 'percentPrecipCond',
                                    'data-placement' => 'bottom',
                                    'data-original-title' => '',
                                    'data-jsreal-probability-precipitation-value' => Number::getDisplayableInt($oPrecipitation->getProbability()),
                                )
                            );
                        
                        $iPreviousProbabilityPosX = $iProbabilityPosX;
                    }
                }
            }
        }
        
        return $sReturnString;
    }
    
    private function getDisplayablePrecipitationType($sRawPrecipitationType)
    {
        switch($sRawPrecipitationType)
        {
            case 'freezing rain': case 'rain': case 'shower':
            case 'thunderstorm': case 'waterspout':
            case 'drizzle': case 'freezing drizzle':
                $sNewPrecipitationType = 'rain';
                break;
            default:
                $sNewPrecipitationType = 'snow';
        }
        
        return $sNewPrecipitationType;
    }
    
    public function getCloudCover()
    {
        $sReturnString = '';
        
        $iPreviousX = 0;
        $iNewX = 0;
        $sInitialDate = Date::setToNextHour(Date::getLocalFullDate(Date::getUTCFullDate()));
        $iMargin = Date::getHour($sInitialDate);
        $aCloudCoverList = $this->oMeteoCode->getCloudCoverList();
        foreach($aCloudCoverList as $oCloudCover)
        {
            $i = Date::getFullDateHourDiff($sInitialDate, 
                    Date::getLocalFullDate($oCloudCover->getStartDate()));

            $iNewX = (($i + $iMargin) * $this->iXSpace);
            
            if((($iNewX - $iPreviousX) / $this->iXSpace) >= 4
                    || $iPreviousX === 0)
            {   
                switch(Number::getInt($oCloudCover->getValue()))
                {
                    case 0:
                        $sCloudCoverageType = '#graph_sky_sunny';
                        break;
                    case 1:
                        $sCloudCoverageType = '#graph_sky_fair';
                        break;
                    case 2: case 3:
                        $sCloudCoverageType = '#graph_sky_mostly_sunny';
                        break;
                    case 4: case 5: case 6:
                        $sCloudCoverageType = '#graph_sky_partly_cloudy';
                        break;
                    case 7: case 8:
                        $sCloudCoverageType = '#graph_sky_mostly_cloudy';
                        break;
                    case 9:
                        $sCloudCoverageType = '#graph_sky_broken';
                        break;
                    case 10:
                        $sCloudCoverageType = '#graph_sky_cloudy';
                        break;
                    default:
                        $sCloudCoverageType = '#graph_sky_sunny';
                }

                $sReturnString .= Graphic::usesvg(array(
                    'xlink:href' => $sCloudCoverageType,
                    'x' => $iNewX,
                    'y' => 40,
                    'class' => 'cloudCond',
                    'data-placement' => 'bottom',
                    'data-original-title' => 'Ok',
                    'data-jsreal-cloud-cover-value' => Number::getInt($oCloudCover->getValue())
                ));
                
                $iPreviousX = $iNewX;
            }
        }
        
        return $sReturnString;
    }
    
    public function getWind()
    {
        $sReturnString = '';

        $iPreviousX = 0;
        $iNewX = 0;
        $sInitialDate = Date::setToNextHour(Date::getLocalFullDate(Date::getUTCFullDate()));
        $iMargin = Date::getHour($sInitialDate);
        $aWindList = $this->oMeteoCode->getWindList();
        foreach($aWindList as $oWind)
        {            
            $i = Date::getFullDateHourDiff($sInitialDate, 
                    Date::getLocalFullDate($oWind->getStartDate()));

            $iNewX = (($i + $iMargin) * $this->iXSpace);
            $y = 65;
            
            if((($iNewX - $iPreviousX) / $this->iXSpace) >= 4
                    || $iPreviousX === 0)
            {
                switch($oWind->getDirection())
                {
                    case 'north':
                        $fRotation = 0;
                        break;
                    case 'south':
                        $fRotation = 180;
                        break;
                    case 'northeast':
                        $fRotation = 45;
                        break;
                    case 'northwest':
                        $fRotation = -45;
                        break;
                    case 'west':
                        $fRotation = -90;
                        break;
                    case 'east':
                        $fRotation = 90;
                        break;
                    case 'southwest':
                        $fRotation = -135;
                        break;
                    case 'southeast':
                        $fRotation = 135;
                        break;
                    default:
                        $fRotation = 0;
                }
                
                // If max speed < 10 then wind is hidden
                $iRealScale = Number::getInt($oWind->getMaxSpeed() / 10);
                $iScale = ($iRealScale > 5) ? 5 : $iRealScale;
                
                $sReturnString .= Graphic::useSvg(
                        array(
                            'xlink:href' => '#graph_wind',
                            'x' => $iNewX,
                            'y' => $y,
                            'width' => ($oWind->getMaxSpeed() * 2),
                            'height' => 30,
                            'data-original-title' => '',
                            'class' => 'windCond',
                            'transform' => 'rotate(' . $fRotation . ' ' . ($iNewX+((10*$iScale)/2)) . ' ' . ($y+((10*$iScale)/2)) . ') '
                            . 'translate(' . ((1-$iScale)*$iNewX) . ', ' . ((1-$iScale)*$y) . ') '
                            . 'scale(' . $iScale . ')',
                            'data-jsreal-wind-start-date' => Date::getLocalFullDate($oWind->getStartDate()),
                            'data-jsreal-wind-end-date' => Date::getLocalFullDate($oWind->getEndDate()),
                            'data-jsreal-wind-direction' => $oWind->getDirection(),
                            'data-jsreal-wind-max-speed' => Number::getDisplayableFloat($oWind->getMaxSpeed())
                        ));
                
                $iPreviousX = $iNewX;
            }
        }
        
        return $sReturnString;
    }
    
    public function getAccumulation()
    {
        $sReturnString = '';
        
        $aAccumulationList = $this->oMeteoCode->getAccumulationList();

        if(!empty($aAccumulationList))
        {
            $i = 0;
            $sInitialDate = Date::setToNextHour(Date::getLocalFullDate(Date::getUTCFullDate()));
            $iMargin = Date::getHour($sInitialDate);
            $sPrecipitationColor = 'rgb(0,247,238)';
            foreach($aAccumulationList as $oAccumulation)
            {
                if(!is_null($oAccumulation->getType())
                        && in_array($oAccumulation->getType(), 
                                array_keys(Config::get('meteo.graph.image')))
                        && $oAccumulation->getMaximumAmount() > 0)
                {
                    $i = Date::getFullDateHourDiff($sInitialDate, 
                            Date::getLocalFullDate($oAccumulation->getStartDate()));

                    $iNewX = (($i + $iMargin) * $this->iXSpace);
                    
                    $iTimeSlot = Date::getFullDateHourDiff(
                            Date::getLocalFullDate($oAccumulation->getStartDate()), 
                            Date::getLocalFullDate($oAccumulation->getEndDate()));
                    
                    $sAccumulationType = $this->getDisplayablePrecipitationType($oAccumulation->getType());
                    
                    $sAccumulationColor = ($sAccumulationType == 'snow') 
                            ? 'rgb(233,242,241)' : 'rgb(51,102,238)';
                    
                    $fAccumulationFactor = ($sAccumulationType == 'snow') ? 0.6 : 1;
                    
                    $fPrecipitationFactor = ($sAccumulationType == 'snow') ? 0.6 : 1;
                    
                    $iAccumulationHeight = (($oAccumulation->getMinimumTotal() + $oAccumulation->getMaximumTotal()) / 2) * $fAccumulationFactor;
                    $sReturnString .= Graphic::rectangle(array(
                        'x' => $iNewX,
                        'y' => $this->iGraphHeight - ($iAccumulationHeight + 18),
                        'width' => ($iTimeSlot * $this->iXSpace),
                        'height' => $iAccumulationHeight,
                        'class' => 'accumCond',
                        'data-original-title' => '',
                        'data-jsreal-accumulation-date' => Date::getLocalFullDate($oAccumulation->getStartDate()),
                        'data-jsreal-accumulation-type' => $sAccumulationType,
                        'data-jsreal-accumulation-amount' => (($oAccumulation->getMinimumAmount() + $oAccumulation->getMaximumAmount()) / 2),
                        'data-jsreal-accumulation-total' => (($oAccumulation->getMinimumTotal() + $oAccumulation->getMaximumTotal()) / 2)
                    ),
                    $sAccumulationColor);

                    $iPrecipitationHeight = (($oAccumulation->getMinimumAmount() + $oAccumulation->getMaximumAmount()) / 2) * $fPrecipitationFactor;
                    $sReturnString .= Graphic::rectangle(array(
                        'x' => $iNewX,
                        'y' => $this->iGraphHeight - ($iPrecipitationHeight + 18),
                        'width' => ($iTimeSlot * $this->iXSpace),
                        'height' => $iPrecipitationHeight,
                        'class' => 'accumCond',
                        'data-original-title' => '',
                        'data-jsreal-accumulation-date' => Date::getLocalFullDate($oAccumulation->getStartDate()),
                        'data-jsreal-accumulation-type' => $sAccumulationType,
                        'data-jsreal-accumulation-amount' => (($oAccumulation->getMinimumAmount() + $oAccumulation->getMaximumAmount()) / 2),
                        'data-jsreal-accumulation-total' => (($oAccumulation->getMinimumTotal() + $oAccumulation->getMaximumTotal()) / 2)
                    ),
                    $sPrecipitationColor);
                }
            }
        }
        
        return $sReturnString;
    }
}