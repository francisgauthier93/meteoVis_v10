<?php

/**
 * Description of WindDao
 *
 * @author molinspa
 */
class WindDao extends BaseDao implements DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null)
    {
        parent::__construct();
        
        if($bGetElementList && isset($aWhere['iLocationId']))
        {
            $aDataList = array();
            
            $this->sWhereClause = ' WHERE `wind_status` = :status';
            $aDataList[':status'] = Number::getInt(Config::get('db.query.status.online'));

//            $this->sWhereClause .= ' AND update_id = :update_id';
//            $aDataList[':update_id'] = Number::getInt($aWhere['iUpdateId']);
            $this->sWhereClause .= ' AND location_id = :location_id';
            $aDataList[':location_id'] = Number::getInt($aWhere['iLocationId']);
            
            if(isset($aWhere['sStartDate']))
            {
                $this->sWhereClause .= ' AND wind_start_date >= :start_date';
                $aDataList[':start_date'] = $aWhere['sStartDate'];
            }
            
            if(isset($aWhere['sEndDate']))
            {
                $this->sWhereClause .= ' AND wind_start_date < :end_date';
                $aDataList[':end_date'] = $aWhere['sEndDate'];
            } 
            
            $this->sOrderClause = ' ORDER BY wind_start_date ASC';
            
            $this->sLimitClause = ' LIMIT :limit_offset, :limit_length';
            $aDataList[':limit_offset'] = (is_null($iLimitOffset) ? 0 : Number::getInt($iLimitOffset));
            $aDataList[':limit_length'] = (is_null($iLimitLength) ? Number::getInt(Config::get('db.query.limit.length.max')) : Number::getInt($iLimitLength));
            
            $this->sSqlQuery = 'SELECT '
                    . '`wind_start_date`, '
                    . '`wind_end_date`, '
                    . '`wind_minimum_speed`, '
                    . '`wind_maximum_speed`, '
                    . '`wind_unit`, '
                    . '`wind_direction` '
                    . 'FROM `tb_wind`'
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
                $oWind = WindFactory::fromDatabase(
                        $oRow->wind_start_date,
                        $oRow->wind_end_date,
                        $oRow->wind_minimum_speed,
                        $oRow->wind_maximum_speed,
                        $oRow->wind_unit,
                        $oRow->wind_direction);
                
                $this->aElementList[] = $oWind;
            }
            unset($oDatabase);
        }
    }
    
    public function createMulti($iLocationId, $iUpdateId, array $aWindList)
    {
        $bSuccess = true;
        
        if(!empty($aWindList))
        {
            $aDataList = array();

            $iNbRow = 0;
            foreach($aWindList as $oWind)
            {
                $aDataList[] = $iLocationId;
                $aDataList[] = $iUpdateId;
                $aDataList[] = $oWind->getStartDate();
                $aDataList[] = $oWind->getEndDate();
                $aDataList[] = $oWind->getMinSpeed();
                $aDataList[] = $oWind->getMaxSpeed();
                $aDataList[] = $oWind->getUnit();
                $aDataList[] = $oWind->getDirection();
                $aDataList[] = Date::getUTCFullDate();
                $aDataList[] = Config::get('db.query.status.online');

                $iNbRow++;
            }

            $iNbColumn = count($aDataList) / $iNbRow;
            $sValueQueryPart = $this->getValueQueryFromData($iNbColumn, $iNbRow);

            $this->sSqlQuery = 'INSERT INTO `tb_wind` '
                    . '(`location_id`, '
                    . '`update_id`, '
                    . '`wind_start_date`, '
                    . '`wind_end_date`, '
                    . '`wind_minimum_speed`, '
                    . '`wind_maximum_speed`, '
                    . '`wind_unit`, '
                    . '`wind_direction`, '
                    . '`wind_storing_date`, '
                    . '`wind_status`)'
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
//            $sDeleteWhereClause .= ') AND wind_status = ' . Number::getInt(Config::get('db.query.status.online'));
//
//            $this->sSqlQuery = 'DELETE FROM tb_wind'
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
    
    public function deleteExpiredData($iLocationId, Update $oLastUpdate, array $aNewWindList)
    {
        $bSuccess = false;
        
        if(empty($aNewWindList))
        {
            return true;
        }
        
        if(!empty($oLastUpdate))
        {
            $sDataUpdatedStartDate = Arr::first($aNewWindList)->getStartDate();
            $sValidStartDate = ($sDataUpdatedStartDate < $oLastUpdate->getValidStartDate()) 
                    ? $sDataUpdatedStartDate : $oLastUpdate->getValidStartDate();
            
            $sDataUpdatedEndDate = Arr::last($aNewWindList)->getEndDate();
            $sValidEndDate = ($sDataUpdatedEndDate > $oLastUpdate->getValidEndDate()) 
                    ? $sDataUpdatedEndDate : $oLastUpdate->getValidEndDate();
            
            $this->sSqlQuery = 'DELETE FROM tb_wind'
                    . ' WHERE ((wind_start_date >= :valid_start_date AND wind_start_date <= :valid_end_date)'
                        . ' OR (wind_end_date >= :valid_start_date AND wind_end_date <= :valid_end_date))'
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