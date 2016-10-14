<?php

/**
 * Description of WarningDao
 *
 * @author molinspa
 */
class WarningDao extends BaseDao implements DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null)
    {
        parent::__construct();
        
        if($bGetElementList && isset($aWhere['iLocationId']))
        {
            $aDataList = array();
            
            $this->sWhereClause = ' WHERE `warning_status` = :status';
            $aDataList[':status'] = Number::getInt(Config::get('db.query.status.online'));

//            $this->sWhereClause .= ' AND update_id = :update_id';
//            $aDataList[':update_id'] = Number::getInt($aWhere['iUpdateId']);
            $this->sWhereClause .= ' AND location_id = :location_id';
            $aDataList[':location_id'] = Number::getInt($aWhere['iLocationId']);
            
            if(isset($aWhere['sStartDate']))
            {
                $this->sWhereClause .= ' AND warning_start_date >= :start_date';
                $aDataList[':start_date'] = $aWhere['sStartDate'];
            }
            
            if(isset($aWhere['sEndDate']))
            {
                $this->sWhereClause .= ' AND warning_start_date < :end_date';
                $aDataList[':end_date'] = $aWhere['sEndDate'];
            } 
            
            $this->sOrderClause = ' ORDER BY warning_start_date ASC';
            
            $this->sLimitClause = ' LIMIT :limit_offset, :limit_length';
            $aDataList[':limit_offset'] = (is_null($iLimitOffset) ? 0 : Number::getInt($iLimitOffset));
            $aDataList[':limit_length'] = (is_null($iLimitLength) ? Number::getInt(Config::get('db.query.limit.length.max')) : Number::getInt($iLimitLength));
            
            $this->sSqlQuery = 'SELECT '
                    . '`warning_start_date`, '
                    . '`warning_end_date`, '
                    . '`warning_code`, '
                    . '`warning_type`, '
                    . '`warning_description`, '
                    . '`warning_state` '
                    . 'FROM `tb_warning`'
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
                $oWarning = WarningFactory::fromDatabase(
                        $oRow->warning_start_date,
                        $oRow->warning_end_date,
                        $oRow->warning_type,
                        $oRow->warning_code,
                        $oRow->warning_description,
                        $oRow->warning_state);
                
                $this->aElementList[] = $oWarning;
            }
            unset($oDatabase);
        }
    }
    
    public function createMulti($iLocationId, $iUpdateId, array $aWarningList)
    {
        $bSuccess = true;
        
        if(!empty($aWarningList))
        {
            $aDataList = array();

            $iNbRow = 0;
            foreach($aWarningList as $oWarning)
            {
                $aDataList[] = $iLocationId;
                $aDataList[] = $iUpdateId;
                $aDataList[] = $oWarning->getStartDate();
                $aDataList[] = $oWarning->getEndDate();
                $aDataList[] = $oWarning->getType();
                $aDataList[] = $oWarning->getCode();
                $aDataList[] = $oWarning->getDescription();
                $aDataList[] = $oWarning->getState();
                $aDataList[] = Date::getUTCFullDate();
                $aDataList[] = Config::get('db.query.status.online');

                $iNbRow++;
            }

            $iNbColumn = count($aDataList) / $iNbRow;
            $sValueQueryPart = $this->getValueQueryFromData($iNbColumn, $iNbRow);

            $this->sSqlQuery = 'INSERT INTO `tb_warning` '
                    . '(`location_id`, '
                    . '`update_id`, '
                    . '`warning_start_date`, '
                    . '`warning_end_date`, '
                    . '`warning_type`, '
                    . '`warning_code`, '
                    . '`warning_description`, '
                    . '`warning_state`, '
                    . '`warning_storing_date`, '
                    . '`warning_status`)'
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
//            $sDeleteWhereClause .= ') AND warning_status = ' . Number::getInt(Config::get('db.query.status.online'));
//
//            $this->sSqlQuery = 'DELETE FROM tb_warning'
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
    
    public function deleteExpiredData($iLocationId, Update $oLastUpdate, array $aNewWarningList)
    {
        $bSuccess = false;
        
        if(empty($aNewWarningList))
        {
            return true;
        }
        
        if(!empty($oLastUpdate))
        {
            $sDataUpdatedStartDate = Arr::first($aNewWarningList)->getStartDate();
            $sValidStartDate = ($sDataUpdatedStartDate < $oLastUpdate->getValidStartDate()) 
                    ? $sDataUpdatedStartDate : $oLastUpdate->getValidStartDate();
            
            $sDataUpdatedEndDate = Arr::last($aNewWarningList)->getEndDate();
            $sValidEndDate = ($sDataUpdatedEndDate > $oLastUpdate->getValidEndDate()) 
                    ? $sDataUpdatedEndDate : $oLastUpdate->getValidEndDate();
            
            $this->sSqlQuery = 'DELETE FROM tb_warning'
                    . ' WHERE ((warning_start_date >= :valid_start_date AND warning_start_date <= :valid_end_date)'
                        . ' OR (warning_end_date >= :valid_start_date AND warning_end_date <= :valid_end_date))'
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