<?php

/**
 * Description of AirTemperatureDao
 *
 * @author molinspa
 */
class AirTemperatureDao extends BaseDao implements DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null)
    {
        parent::__construct();
        
        if($bGetElementList && isset($aWhere['iLocationId']))
        {
            $aDataList = array();
            
            $this->sWhereClause = ' WHERE `air_temperature_status` = :status';
            $aDataList[':status'] = Number::getInt(Config::get('db.query.status.online'));

            $this->sWhereClause .= ' AND air_temperature_minimum_value IS NOT NULL';
            $this->sWhereClause .= ' AND air_temperature_maximum_value IS NOT NULL';
            
            $this->sWhereClause .= ' AND location_id = :location_id';
            $aDataList[':location_id'] = Number::getInt($aWhere['iLocationId']);
            
            if(isset($aWhere['sStartDate']))
            {
                $this->sWhereClause .= ' AND air_temperature_start_date >= :start_date';
                $aDataList[':start_date'] = $aWhere['sStartDate'];
            }
            
            if(isset($aWhere['sEndDate']))
            {
                $this->sWhereClause .= ' AND air_temperature_start_date < :end_date';
                $aDataList[':end_date'] = $aWhere['sEndDate'];
            }  
            
            $this->sOrderClause = ' ORDER BY air_temperature_start_date ASC';
            
            $this->sLimitClause = ' LIMIT :limit_offset, :limit_length';
            $aDataList[':limit_offset'] = (is_null($iLimitOffset) ? 0 : Number::getInt($iLimitOffset));
            $aDataList[':limit_length'] = (is_null($iLimitLength) ? Number::getInt(Config::get('db.query.limit.length.max')) : Number::getInt($iLimitLength));
            
            $this->sSqlQuery = 'SELECT '
                    . '`air_temperature_start_date`, '
                    . '`air_temperature_end_date`, '
                    . '`air_temperature_minimum_value`, '
                    . '`air_temperature_maximum_value`, '
                    . '`air_temperature_unit` '
                    . 'FROM `tb_air_temperature`'
                    . $this->sWhereClause
                    . $this->sGroupClause
                    . $this->sOrderClause
                    . $this->sLimitClause
                    . ';';
//            Util::var_dump($this->sSqlQuery); die();
//            Util::var_dump($aDataList); die();
            
            $oDatabase = Database::getConnection();
            $oDatabase->doQuery($this->sSqlQuery, $aDataList);
            while($oRow = $oDatabase->doFetchObject())
            {
                $oAirTemperature = AirTemperatureFactory::fromDatabase(
                        $oRow->air_temperature_start_date,
                        $oRow->air_temperature_end_date,
                        $oRow->air_temperature_minimum_value,
                        $oRow->air_temperature_maximum_value,
                        $oRow->air_temperature_unit);
                
                $this->aElementList[] = $oAirTemperature;
            }
            unset($oDatabase);
        }
    }
    
    public function createMulti($iLocationId, $iUpdateId, array $aAirTemperatureList)
    {
        $bSuccess = true;
        
        if(!empty($aAirTemperatureList))
        {
            $aDataList = array();

            $iNbRow = 0;
            foreach($aAirTemperatureList as $oAirTemperature)
            {
                $aDataList[] = $iLocationId;
                $aDataList[] = $iUpdateId;
                $aDataList[] = $oAirTemperature->getStartDate();
                $aDataList[] = $oAirTemperature->getEndDate();
                $aDataList[] = $oAirTemperature->getMinValue();
                $aDataList[] = $oAirTemperature->getMaxValue();
                $aDataList[] = $oAirTemperature->getUnit();
                $aDataList[] = Date::getUTCFullDate();
                $aDataList[] = Config::get('db.query.status.online');
                
                $iNbRow++;
            }

            $iNbColumn = count($aDataList) / $iNbRow;
            $sValueQueryPart = $this->getValueQueryFromData($iNbColumn, $iNbRow);

            $this->sSqlQuery = 'INSERT INTO `tb_air_temperature` '
                    . '(`location_id`, '
                    . '`update_id`, '
                    . '`air_temperature_start_date`, '
                    . '`air_temperature_end_date`, '
                    . '`air_temperature_minimum_value`, '
                    . '`air_temperature_maximum_value`, '
                    . '`air_temperature_unit`, '
                    . '`air_temperature_storing_date`, '
                    . '`air_temperature_status`)'
                    . ' VALUES '
                    . $sValueQueryPart . ';';
//            Util::var_dump($this->sSqlQuery); die();
            $oDatabase = Database::getConnection();
            $bSuccess = $oDatabase->doMultiQuery($this->sSqlQuery, $aDataList);
            unset($oDatabase);
        }
        
        return $bSuccess;
    }
    
//    public function deleteExpiredData(array $aUpdateIdList)
//    {
//        $bSuccess = false;
//        
//        if(!empty($aUpdateIdList))
//        {
//            $iNbUpdate = count($aUpdateIdList);
//            $sDeleteWhereClause = '(update_id = ?';
//            for($i = 0; $i < $iNbUpdate-1; $i++)
//            {
//                $sDeleteWhereClause .= ' OR update_id = ?';
//            }
//            $sDeleteWhereClause .= ') AND air_temperature_status = ' . Number::getInt(Config::get('db.query.status.online'));
//
//            $this->sSqlQuery = 'DELETE FROM tb_air_temperature'
//                    . ' WHERE ' . $sDeleteWhereClause;
//            
////            Util::var_dump($this->sSqlQuery); die();
//
//            $oDatabase = Database::getConnection();
//            $bSuccess = $oDatabase->doQuery($this->sSqlQuery, $aUpdateIdList);
//            unset($oDatabase);
//        }
//        
//        return $bSuccess;
//    }
    
    public function deleteExpiredData($iLocationId, Update $oLastUpdate, array $aNewAirTemperatureList)
    {
        $bSuccess = false;
        
        if(empty($aNewAirTemperatureList))
        {
            return true;
        }
        
        if(!empty($oLastUpdate))
        {
            $sDataUpdatedStartDate = Arr::first($aNewAirTemperatureList)->getStartDate();
            $sValidStartDate = ($sDataUpdatedStartDate < $oLastUpdate->getValidStartDate()) 
                    ? $sDataUpdatedStartDate : $oLastUpdate->getValidStartDate();
            
            $sDataUpdatedEndDate = Arr::last($aNewAirTemperatureList)->getEndDate();
            $sValidEndDate = ($sDataUpdatedEndDate > $oLastUpdate->getValidEndDate()) 
                    ? $sDataUpdatedEndDate : $oLastUpdate->getValidEndDate();
            
            $this->sSqlQuery = 'DELETE FROM tb_air_temperature'
                    . ' WHERE ((air_temperature_start_date >= :valid_start_date AND air_temperature_start_date <= :valid_end_date)'
                        . ' OR (air_temperature_end_date >= :valid_start_date AND air_temperature_end_date <= :valid_end_date))'
                    . ' AND location_id = :location_id'
                    . ' AND update_id <> :last_update_id;';
            $aDataList[':location_id'] = $iLocationId;
            $aDataList[':valid_start_date'] = $sValidStartDate;
            $aDataList[':valid_end_date'] = $sValidEndDate;
            $aDataList[':last_update_id'] = $oLastUpdate->getId();
            
//            Util::var_dump($this->sSqlQuery); die;

            $oDatabase = Database::getConnection();
            $bSuccess = $oDatabase->doQuery($this->sSqlQuery, $aDataList);
            unset($oDatabase);
        }
        
        return $bSuccess;
    }
}