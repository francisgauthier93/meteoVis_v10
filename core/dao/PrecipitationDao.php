<?php

/**
 * Description of PrecipitationDao
 *
 * @author molinspa
 */
class PrecipitationDao extends BaseDao implements DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null)
    {
        parent::__construct();
        
        if($bGetElementList && isset($aWhere['iLocationId']))
        {
            $aDataList = array();
            
            $this->sWhereClause = ' WHERE `precipitation_status` = :status';
            $aDataList[':status'] = Number::getInt(Config::get('db.query.status.online'));

//            $this->sWhereClause .= ' AND update_id = :update_id';
//            $aDataList[':update_id'] = Number::getInt($aWhere['iUpdateId']);
            $this->sWhereClause .= ' AND location_id = :location_id';
            $aDataList[':location_id'] = Number::getInt($aWhere['iLocationId']);
            
            if(isset($aWhere['sStartDate']))
            {
                $this->sWhereClause .= ' AND precipitation_start_date >= :start_date';
                $aDataList[':start_date'] = $aWhere['sStartDate'];
            }
            
            if(isset($aWhere['sEndDate']))
            {
                $this->sWhereClause .= ' AND precipitation_start_date < :end_date';
                $aDataList[':end_date'] = $aWhere['sEndDate'];
            } 
            
            $this->sOrderClause = ' ORDER BY precipitation_start_date ASC';
            
            $this->sLimitClause = ' LIMIT :limit_offset, :limit_length';
            $aDataList[':limit_offset'] = (is_null($iLimitOffset) ? 0 : Number::getInt($iLimitOffset));
            $aDataList[':limit_length'] = (is_null($iLimitLength) ? Number::getInt(Config::get('db.query.limit.length.max')) : Number::getInt($iLimitLength));
            
            $this->sSqlQuery = 'SELECT '
                    . '`precipitation_start_date`, '
                    . '`precipitation_end_date`, '
                    . '`precipitation_type`, '
                    . '`precipitation_frequency`, '
                    . '`precipitation_intensity`, '
                    . '`precipitation_probability`, '
                    . '`precipitation_probability_unit` '
                    . 'FROM `tb_precipitation`'
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
                $oPrecipitation = PrecipitationFactory::fromDatabase(
                        $oRow->precipitation_start_date, 
                        $oRow->precipitation_end_date, 
                        $oRow->precipitation_type, 
                        $oRow->precipitation_frequency,
                        $oRow->precipitation_intensity,
                        $oRow->precipitation_probability,
                        $oRow->precipitation_probability_unit);
                
                $this->aElementList[] = $oPrecipitation;
            }
            unset($oDatabase);
        }
    }
    
    public function createMulti($iLocationId, $iUpdateId, array $aPrecipitationList)
    {
        $bSuccess = true;
        
        if(!empty($aPrecipitationList))
        {
            $aDataList = array();

            $iNbRow = 0;
            foreach($aPrecipitationList as $oPrecipitation)
            {
                $aDataList[] = $iLocationId;
                $aDataList[] = $iUpdateId;
                $aDataList[] = $oPrecipitation->getStartDate();
                $aDataList[] = $oPrecipitation->getEndDate();
                $aDataList[] = $oPrecipitation->getType();
                $aDataList[] = $oPrecipitation->getFrequency();
                $aDataList[] = $oPrecipitation->getIntensity();
                $aDataList[] = $oPrecipitation->getProbability();
                $aDataList[] = $oPrecipitation->getProbabilityUnit();
                $aDataList[] = Date::getUTCFullDate();
                $aDataList[] = Config::get('db.query.status.online');

                $iNbRow++;
            }

            $iNbColumn = count($aDataList) / $iNbRow;
            $sValueQueryPart = $this->getValueQueryFromData($iNbColumn, $iNbRow);

            $this->sSqlQuery = 'INSERT INTO `tb_precipitation` '
                    . '(`location_id`, '
                    . '`update_id`, '
                    . '`precipitation_start_date`, '
                    . '`precipitation_end_date`, '
                    . '`precipitation_type`, '
                    . '`precipitation_frequency`, '
                    . '`precipitation_intensity`, '
                    . '`precipitation_probability`, '
                    . '`precipitation_probability_unit`, '
                    . '`precipitation_storing_date`, '
                    . '`precipitation_status`)'
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
//            $sDeleteWhereClause .= ') AND precipitation_status = ' . Number::getInt(Config::get('db.query.status.online'));
//
//            $this->sSqlQuery = 'DELETE FROM tb_precipitation'
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
    
    public function deleteExpiredData($iLocationId, Update $oLastUpdate, array $aNewPrecipitationList)
    {
        $bSuccess = false;
        
        if(empty($aNewPrecipitationList))
        {
            return true;
        }
        
        if(!empty($oLastUpdate))
        {
            $sDataUpdatedStartDate = Arr::first($aNewPrecipitationList)->getStartDate();
            $sValidStartDate = ($sDataUpdatedStartDate < $oLastUpdate->getValidStartDate()) 
                    ? $sDataUpdatedStartDate : $oLastUpdate->getValidStartDate();
            
            $sDataUpdatedEndDate = Arr::last($aNewPrecipitationList)->getEndDate();
            $sValidEndDate = ($sDataUpdatedEndDate > $oLastUpdate->getValidEndDate()) 
                    ? $sDataUpdatedEndDate : $oLastUpdate->getValidEndDate();
            
            $this->sSqlQuery = 'DELETE FROM tb_precipitation'
                    . ' WHERE ((precipitation_start_date >= :valid_start_date AND precipitation_start_date <= :valid_end_date)'
                        . ' OR (precipitation_end_date >= :valid_start_date AND precipitation_end_date <= :valid_end_date))'
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