<?php

/**
 * Description of CloudCoverDao
 *
 * @author molinspa
 */
class CloudCoverDao extends BaseDao implements DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null)
    {
        parent::__construct();
        
        if($bGetElementList && isset($aWhere['iLocationId']))
        {
            $aDataList = array();
            
            $this->sWhereClause = ' WHERE `cloud_cover_status` = :status';
            $aDataList[':status'] = Number::getInt(Config::get('db.query.status.online'));

//            $this->sWhereClause .= ' AND update_id = :update_id';
//            $aDataList[':update_id'] = Number::getInt($aWhere['iUpdateId']);
            $this->sWhereClause .= ' AND location_id = :location_id';
            $aDataList[':location_id'] = Number::getInt($aWhere['iLocationId']);
//            
            if(isset($aWhere['sStartDate']))
            {
                $this->sWhereClause .= ' AND cloud_cover_start_date >= :start_date';
                $aDataList[':start_date'] = $aWhere['sStartDate'];
            }
            
            if(isset($aWhere['sEndDate']))
            {
                $this->sWhereClause .= ' AND cloud_cover_start_date < :end_date';
                $aDataList[':end_date'] = $aWhere['sEndDate'];
            } 
            
            $this->sOrderClause = ' ORDER BY cloud_cover_start_date ASC';
            
            $this->sLimitClause = ' LIMIT :limit_offset, :limit_length';
            $aDataList[':limit_offset'] = (is_null($iLimitOffset) ? 0 : Number::getInt($iLimitOffset));
            $aDataList[':limit_length'] = (is_null($iLimitLength) ? Number::getInt(Config::get('db.query.limit.length.max')) : Number::getInt($iLimitLength));
            
            $this->sSqlQuery = 'SELECT '
                    . '`cloud_cover_start_date`, '
                    . '`cloud_cover_end_date`, '
                    . '`cloud_cover_value`, '
                    . '`cloud_cover_unit` '
                    . 'FROM `tb_cloud_cover`'
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
                $oCloudCover = CloudCoverFactory::fromDatabase(
                        $oRow->cloud_cover_start_date,
                        $oRow->cloud_cover_end_date,
                        $oRow->cloud_cover_value,
                        $oRow->cloud_cover_unit);
                
                $this->aElementList[] = $oCloudCover;
            }
            unset($oDatabase);
        }
    }
    
    public function createMulti($iLocationId, $iUpdateId, array $aCloudCoverList)
    {
        $bSuccess = true;
        
        if(!empty($aCloudCoverList))
        {
            $aDataList = array();

            $iNbRow = 0;
            foreach($aCloudCoverList as $oCloudCover)
            {
                $aDataList[] = $iLocationId;
                $aDataList[] = $iUpdateId;
                $aDataList[] = $oCloudCover->getStartDate();
                $aDataList[] = $oCloudCover->getEndDate();
                $aDataList[] = $oCloudCover->getValue();
                $aDataList[] = $oCloudCover->getUnit();
                $aDataList[] = Date::getUTCFullDate();
                $aDataList[] = Config::get('db.query.status.online');

                $iNbRow++;
            }

            $iNbColumn = count($aDataList) / $iNbRow;
            $sValueQueryPart = $this->getValueQueryFromData($iNbColumn, $iNbRow);

            $this->sSqlQuery = 'INSERT INTO `tb_cloud_cover` '
                    . '(`location_id`, '
                    . '`update_id`, '
                    . '`cloud_cover_start_date`, '
                    . '`cloud_cover_end_date`, '
                    . '`cloud_cover_value`, '
                    . '`cloud_cover_unit`, '
                    . '`cloud_cover_storing_date`, '
                    . '`cloud_cover_status`)'
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
//            $sDeleteWhereClause .= ') AND cloud_cover_status = ' . Number::getInt(Config::get('db.query.status.online'));
//
//            $this->sSqlQuery = 'DELETE FROM tb_cloud_cover'
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
    
    public function deleteExpiredData($iLocationId, Update $oLastUpdate, array $aNewCloudCoverList)
    {
        $bSuccess = false;
        
        if(empty($aNewCloudCoverList))
        {
            return true;
        }
        
        if(!empty($oLastUpdate))
        {
            $sDataUpdatedStartDate = Arr::first($aNewCloudCoverList)->getStartDate();
            $sValidStartDate = ($sDataUpdatedStartDate < $oLastUpdate->getValidStartDate()) 
                    ? $sDataUpdatedStartDate : $oLastUpdate->getValidStartDate();
            
            $sDataUpdatedEndDate = Arr::last($aNewCloudCoverList)->getEndDate();
            $sValidEndDate = ($sDataUpdatedEndDate > $oLastUpdate->getValidEndDate()) 
                    ? $sDataUpdatedEndDate : $oLastUpdate->getValidEndDate();
            
            $this->sSqlQuery = 'DELETE FROM tb_cloud_cover'
                    . ' WHERE ((cloud_cover_start_date >= :valid_start_date AND cloud_cover_start_date <= :valid_end_date)'
                        . ' OR (cloud_cover_end_date >= :valid_start_date AND cloud_cover_end_date <= :valid_end_date))'
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