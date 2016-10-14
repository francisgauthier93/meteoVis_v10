<?php

/**
 * Description of MeteoCodeDao
 *
 * @author molinspa
 */
class MeteoCodeDao extends BaseDao implements DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
        $iLimitOffset = null, $iLimitLength = null)
    {
        if($bGetElementList && isset($aWhere['sLocationCode']))
        {
            // Location
            $oLocationDao = new LocationDao(true, array('sCode' => $aWhere['sLocationCode']), 0, 1);
            $oLocation = $oLocationDao->getFirstElement();
            unset($oLocationDao);
            
            if(!empty($oLocation))
            {
                $iLocationId = $oLocation->getId();

                // Last Update
                $oUpdateDao = new UpdateDao(true, array(
                    'bGetLastUpdate' => true, 
                    'iLocationId' => $iLocationId), 0, 1);
                $oLastUpdate = $oUpdateDao->getFirstElement();
                unset($oUpdateDao);

                $sStartDate = Date::getUTCFullDate();
                $sEndDate = Date::addSecond(
                                    Date::addDay(
                                        Date::setToEndOfDay(Date::getUTCFullDate()),
                                        Config::get('meteo.limit.day.max', 7)-1
                                    ),
                                    -(Date::getTimeZoneOffset())
                                );

                // Accumulation
                $oAccumulationDao = new AccumulationDao(true, array('iLocationId' => $iLocationId,
                    'sStartDate' => $sStartDate, 'sEndDate' => $sEndDate));
                $aAccumulationList = $oAccumulationDao->getElementList();
                unset($oAccumulationDao);

                // AirTemperature
                $oAirTemperatureDao = new AirTemperatureDao(true, array('iLocationId' => $iLocationId,
                    'sStartDate' => $sStartDate, 'sEndDate' => $sEndDate));
                $aAirTemperatureList = $oAirTemperatureDao->getElementList();
                unset($oAirTemperatureDao);

                // CloudCover
                $oCloudCoverDao = new CloudCoverDao(true, array('iLocationId' => $iLocationId,
                    'sStartDate' => $sStartDate, 'sEndDate' => $sEndDate));
                $aCloudCoverList = $oCloudCoverDao->getElementList();
                unset($oCloudCoverDao);

                // Precipitation
                $oPrecipitationDao = new PrecipitationDao(true, array('iLocationId' => $iLocationId,
                    'sStartDate' => $sStartDate, 'sEndDate' => $sEndDate));
                $aPrecipitationList = $oPrecipitationDao->getElementList();
                unset($oPrecipitationDao);

                // Warning
                $oWarningDao = new WarningDao(true, array('iLocationId' => $iLocationId,
                    'sStartDate' => $sStartDate, 'sEndDate' => $sEndDate));
                $aWarningList = $oWarningDao->getElementList();
                unset($oWarningDao);

                // Wind
                $oWindDao = new WindDao(true, array('iLocationId' => $iLocationId,
                    'sStartDate' => $sStartDate, 'sEndDate' => $sEndDate));
                $aWindList = $oWindDao->getElementList();
                unset($oWindDao);

                $oMeteoCode = MeteoCodeFactory::fromObject($oLocation, $oLastUpdate, $aAirTemperatureList, 
                        $aWindList, $aAccumulationList, $aCloudCoverList, $aPrecipitationList, $aWarningList);

                $this->aElementList[] = $oMeteoCode;
            }
        }
    }
    
    public function create(MeteoCode &$oMeteoCode)
    {
        // Location
        $oLocation = $oMeteoCode->getLocation();
        $oLocationDao = new LocationDao(true, array('sCode' => $oLocation->getCode()), 0, 1);
        $oFirstLocation = $oLocationDao->getFirstElement();
        if($oFirstLocation === null)
        {
            $oLocationDao->create($oLocation);
        }
        else
        {
            $oLocation = $oFirstLocation;
        }
        unset($oLocationDao);
        
        $bLocationSuccess = $bUpdateSuccess = $bAccumulationSuccess
                = $bAirTemperatureSuccess = $bCloudCoverSuccess 
                = $bPrecipitationSuccess = $bWarningSuccess = $bWindSuccess = false;
        if($oLocation instanceof Location)
        {
            $iLocationId = $oLocation->getId();
            $bLocationSuccess = ($iLocationId > 0);
            if($bLocationSuccess)
            {
                $oMeteoCode->setLocation($oLocation);
            }
            
            // Update
            $oUpdate = $oMeteoCode->getLastUpdate();
            $oUpdateDao = new UpdateDao(false);
            $bUpdateSuccess = $oUpdateDao->create($iLocationId, $oUpdate);
            $iUpdateId = $oUpdate->getId();
            unset($oUpdateDao);

            if($bUpdateSuccess)
            {
                // Accumulation
                $oAccumulationDao = new AccumulationDao(false);
                $bAccumulationSuccess = $oAccumulationDao->createMulti($iLocationId, $iUpdateId, $oMeteoCode->getAccumulationList());
                unset($oAccumulationDao);

                // AirTemperature
                $oAirTemperatureDao = new AirTemperatureDao(false);
                $bAirTemperatureSuccess = $oAirTemperatureDao->createMulti($iLocationId, $iUpdateId, $oMeteoCode->getAirTemperatureList());
                unset($oAirTemperatureDao);

                // CloudCover
                $oCloudCoverDao = new CloudCoverDao(false);
                $bCloudCoverSuccess = $oCloudCoverDao->createMulti($iLocationId, $iUpdateId, $oMeteoCode->getCloudCoverList());
                unset($oCloudCoverDao);

                // Precipitation
                $oPrecipitationDao = new PrecipitationDao(false);
                $bPrecipitationSuccess = $oPrecipitationDao->createMulti($iLocationId, $iUpdateId, $oMeteoCode->getPrecipitationList());
                unset($oPrecipitationDao);

                // Warning
                $oWarningDao = new WarningDao(false);
                $bWarningSuccess = $oWarningDao->createMulti($iLocationId, $iUpdateId, $oMeteoCode->getWarningList());
                unset($oWarningDao);

                // Wind
                $oWindDao = new WindDao(false);
                $bWindSuccess = $oWindDao->createMulti($iLocationId, $iUpdateId, $oMeteoCode->getWindList());
                unset($oWindDao);
            }
        }
//        Util::var_dump('SQL Error : ' . Database::getConnection()->getLastError());

        return ($bLocationSuccess && $bUpdateSuccess && $bAccumulationSuccess
                && $bAirTemperatureSuccess && $bCloudCoverSuccess 
                && $bPrecipitationSuccess && $bWarningSuccess && $bWindSuccess);
    }
    
    public function createMulti(array &$aMeteoCodeList)
    {
        $i = 0;
        $bSuccess = true;
        $iMeteoCodeListLength = count($aMeteoCodeList);
        while($i < $iMeteoCodeListLength && $bSuccess)
        {
            $bSuccess = $this->create($aMeteoCodeList[$i]);
            
            $i++;
        }

        return $bSuccess;
    }
    
    public function deleteExpiredData(MeteoCode $oMeteoCode)
    {
        $bUpdateSuccess = $bAccumulationSuccess = $bAirTemperatureSuccess 
                = $bCloudCoverSuccess = $bPrecipitationSuccess = $bWarningSuccess 
                = $bWindSuccess = false;
        
        $iLocationId = $oMeteoCode->getLocation()->getId();
        $oLastUpdate = $oMeteoCode->getLastUpdate();
        

        // Accumulation
        $oAccumulationDao = new AccumulationDao(false);
        $bAccumulationSuccess = $oAccumulationDao->deleteExpiredData
                ($iLocationId, $oLastUpdate, $oMeteoCode->getAccumulationList());
        unset($oAccumulationDao);

        // AirTemperature
        $oAirTemperatureDao = new AirTemperatureDao(false);
        $bAirTemperatureSuccess = $oAirTemperatureDao->deleteExpiredData
                ($iLocationId, $oLastUpdate, $oMeteoCode->getAirTemperatureList());
        unset($oAirTemperatureDao);

        // CloudCover
        $oCloudCoverDao = new CloudCoverDao(false);
        $bCloudCoverSuccess = $oCloudCoverDao->deleteExpiredData
                ($iLocationId, $oLastUpdate, $oMeteoCode->getCloudCoverList());
        unset($oCloudCoverDao);

        // Precipitation
        $oPrecipitationDao = new PrecipitationDao(false);
        $bPrecipitationSuccess = $oPrecipitationDao->deleteExpiredData
                ($iLocationId, $oLastUpdate, $oMeteoCode->getPrecipitationList());
        unset($oPrecipitationDao);

        // Warning
        $oWarningDao = new WarningDao(false);
        $bWarningSuccess = $oWarningDao->deleteExpiredData
                ($iLocationId, $oLastUpdate, $oMeteoCode->getWarningList());
        unset($oWarningDao);

        // Wind
        $oWindDao = new WindDao(false);
        $bWindSuccess = $oWindDao->deleteExpiredData
                ($iLocationId, $oLastUpdate, $oMeteoCode->getWindList());
        unset($oWindDao);
        
//        Util::var_dump($bAirTemperatureSuccess);die();
//        Util::var_dump($aUpdateIdList);die();
        
        $bDeleteDataSuccess = $bAccumulationSuccess
                && $bAirTemperatureSuccess && $bCloudCoverSuccess 
                && $bPrecipitationSuccess && $bWarningSuccess && $bWindSuccess;
        if($bDeleteDataSuccess)
        {
            $oUpdateDao2 = new UpdateDao(false);
            $bUpdateSuccess = $oUpdateDao2->deleteExpiredUpdate($iLocationId, $oLastUpdate);
            unset($oUpdateDao2);
        }
        
        return ($bUpdateSuccess && $bDeleteDataSuccess);
    }
    
    public function deleteExpiredDataMulti(array $aMeteoCodeList)
    {
        $i = 0;
        $bSuccess = true;
        $iMeteoCodeListLength = count($aMeteoCodeList);
        while($i < $iMeteoCodeListLength && $bSuccess)
        {
            $bSuccess = $this->deleteExpiredData($aMeteoCodeList[$i]);
            
            $i++;
        }
        
        return $bSuccess;
    }
}