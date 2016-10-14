<?php

/**
 * Description of AccumulationDao
 *
 * @author molinspa
 */
class AccumulationDao extends BaseDao implements DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null)
    {
        parent::__construct();
        
        if($bGetElementList && isset($aWhere['iLocationId']))
        {
            $aDataList = array();
            
            $this->sWhereClause = ' WHERE `accumulation_status` = :status';
            $aDataList[':status'] = Number::getInt(Config::get('db.query.status.online'));

//            $this->sWhereClause .= ' AND update_id = :update_id';
//            $aDataList[':update_id'] = Number::getInt($aWhere['iUpdateId']);
            $this->sWhereClause .= ' AND location_id = :location_id';
            $aDataList[':location_id'] = Number::getInt($aWhere['iLocationId']);
            
            if(isset($aWhere['sStartDate']))
            {
                $this->sWhereClause .= ' AND accumulation_start_date >= :start_date';
                $aDataList[':start_date'] = $aWhere['sStartDate'];
            }
            
            if(isset($aWhere['sEndDate']))
            {
                $this->sWhereClause .= ' AND accumulation_start_date < :end_date';
                $aDataList[':end_date'] = $aWhere['sEndDate'];
            } 
            
            $this->sOrderClause = ' ORDER BY accumulation_start_date ASC';
            
            $this->sLimitClause = ' LIMIT :limit_offset, :limit_length';
            $aDataList[':limit_offset'] = (is_null($iLimitOffset) ? 0 : Number::getInt($iLimitOffset));
            $aDataList[':limit_length'] = (is_null($iLimitLength) ? Number::getInt(Config::get('db.query.limit.length.max')) : Number::getInt($iLimitLength));
            
            $this->sSqlQuery = 'SELECT '
                    . '`accumulation_start_date`, '
                    . '`accumulation_end_date`, '
                    . '`accumulation_minimum_amount`, '
                    . '`accumulation_maximum_amount`, '
                    . '`accumulation_minimum_total`, '
                    . '`accumulation_maximum_total`, '
                    . '`accumulation_type`, '
                    . '`accumulation_unit` '
                    . 'FROM `tb_accumulation`'
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
                $oAccumulation = AccumulationFactory::fromDatabase(
                        $oRow->accumulation_start_date, 
                        $oRow->accumulation_end_date, 
                        $oRow->accumulation_minimum_amount, 
                        $oRow->accumulation_maximum_amount, 
                        $oRow->accumulation_minimum_total,
                        $oRow->accumulation_maximum_total,
                        $oRow->accumulation_type,
                        $oRow->accumulation_unit);
                
                $this->aElementList[] = $oAccumulation;
            }
            unset($oDatabase);
        }
    }
    
    public function createMulti($iLocationId, $iUpdateId, array $aAccumulationList)
    {
        $bSuccess = true;
        
        if(!empty($aAccumulationList))
        {
            $aDataList = array();

            $iNbRow = 0;
            foreach($aAccumulationList as $oAccumulation)
            {
                $aDataList[] = $iLocationId;
                $aDataList[] = $iUpdateId;
                $aDataList[] = $oAccumulation->getStartDate();
                $aDataList[] = $oAccumulation->getEndDate();
                $aDataList[] = $oAccumulation->getMinimumAmount();
                $aDataList[] = $oAccumulation->getMaximumAmount();
                $aDataList[] = $oAccumulation->getMinimumTotal();
                $aDataList[] = $oAccumulation->getMaximumTotal();
                $aDataList[] = $oAccumulation->getType();
                $aDataList[] = $oAccumulation->getUnit();
                $aDataList[] = Date::getUTCFullDate();
                $aDataList[] = Config::get('db.query.status.online');

                $iNbRow++;
            }

            $iNbColumn = count($aDataList) / $iNbRow;
            $sValueQueryPart = $this->getValueQueryFromData($iNbColumn, $iNbRow);

            $this->sSqlQuery = 'INSERT INTO `tb_accumulation` '
                    . '(`location_id`, '
                    . '`update_id`, '
                    . '`accumulation_start_date`, '
                    . '`accumulation_end_date`, '
                    . '`accumulation_minimum_amount`, '
                    . '`accumulation_maximum_amount`, '
                    . '`accumulation_minimum_total`, '
                    . '`accumulation_maximum_total`, '
                    . '`accumulation_type`, '
                    . '`accumulation_unit`, '
                    . '`accumulation_storing_date`, '
                    . '`accumulation_status`)'
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
//            
//            $sDeleteWhereClause .= ') AND accumulation_status = ' . Number::getInt(Config::get('db.query.status.online'));
//
//            $this->sSqlQuery = 'DELETE FROM tb_accumulation'
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
    
    public function deleteExpiredData($iLocationId, Update $oLastUpdate, array $aNewAccumulationList)
    {
        $bSuccess = false;
        
        if(empty($aNewAccumulationList))
        {
            return true;
        }
        
        if(!empty($oLastUpdate))
        {
            $sDataUpdatedStartDate = Arr::first($aNewAccumulationList)->getStartDate();
            $sValidStartDate = ($sDataUpdatedStartDate < $oLastUpdate->getValidStartDate()) 
                    ? $sDataUpdatedStartDate : $oLastUpdate->getValidStartDate();
            
            $sDataUpdatedEndDate = Arr::last($aNewAccumulationList)->getEndDate();
            $sValidEndDate = ($sDataUpdatedEndDate > $oLastUpdate->getValidEndDate()) 
                    ? $sDataUpdatedEndDate : $oLastUpdate->getValidEndDate();
            
            $this->sSqlQuery = 'DELETE FROM tb_accumulation'
                    . ' WHERE ((accumulation_start_date >= :valid_start_date AND accumulation_start_date <= :valid_end_date)'
                        . ' OR (accumulation_end_date >= :valid_start_date AND accumulation_end_date <= :valid_end_date))'
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