<?php

/**
 * Description of UpdateDao
 *
 * @author molinspa
 */
class UpdateDao extends BaseDao implements DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null)
    {
        parent::__construct();
        
        if($bGetElementList && isset($aWhere['iLocationId']))
        {
            $aDataList = array();
            
            $this->sWhereClause = ' WHERE `update_status` = :status';
            $aDataList[':status'] = Number::getInt(Config::get('db.query.status.online'));
            
            $this->sWhereClause .= ' AND location_id = :location_id';
            $aDataList[':location_id'] = Number::getInt($aWhere['iLocationId']);
            
            if(isset($aWhere['bGetLastUpdate']))
            {
                $this->sOrderClause = ' ORDER BY update_creation_date DESC';
                $this->sLimitClause = ' LIMIT 0, 1';
            }
            else
            {
                $this->sLimitClause = ' LIMIT :limit_offset, :limit_length';
                $aDataList[':limit_offset'] = (is_null($iLimitOffset) ? 0 : Number::getInt($iLimitOffset));
                $aDataList[':limit_length'] = (is_null($iLimitLength) ? Number::getInt(Config::get('db.query.limit.length.max')) : Number::getInt($iLimitLength));
            }
            
            $this->sSqlQuery = 'SELECT '
                    . '`update_id`, '
                    . '`update_type`, '
                    . '`update_valid_start_date`, '
                    . '`update_valid_end_date`, '
                    . '`update_creation_date`, '
                    . '`update_url` '
                    . 'FROM `tb_update`'
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
                $oLocation = UpdateFactory::fromDatabase(
                        $oRow->update_id, 
                        $oRow->update_type, 
                        $oRow->update_valid_start_date, 
                        $oRow->update_valid_end_date, 
                        $oRow->update_creation_date,
                        $oRow->update_url);
                
                $this->aElementList[] = $oLocation;
            }
            unset($oDatabase);
        }
    }
    
    public function create($iLocationId, Update $oUpdate = null)
    {
        $aDataList = array(
                ':location_id' => $iLocationId,
                ':type' => $oUpdate->getType(),
                ':valid_start_date' => $oUpdate->getValidStartDate(),
                ':valid_end_date' => $oUpdate->getValidEndDate(),
                ':creation_date' => $oUpdate->getCreationDate(),
                ':storing_date' => Date::getUTCFullDate(),
                ':url' => $oUpdate->getUrl(),
                ':status' => Number::getInt(Config::get('db.query.status.online')),
        );
        
        $this->sSqlQuery = 'INSERT INTO `tb_update` '
                . '(`location_id`, '
                . '`update_type`, '
                . '`update_valid_start_date`, '
                . '`update_valid_end_date`, '
                . '`update_creation_date`, '
                . '`update_storing_date`, '
                . '`update_url`, '
                . '`update_status`)'
                . ' VALUES '
                . '(' . implode(',', array_keys($aDataList)) . ');';
        
        $oDatabase = Database::getConnection();
        $bSuccess = $oDatabase->doQuery($this->sSqlQuery, $aDataList);
        if($bSuccess)
        {
            $oUpdate->setId($oDatabase->doLastInsertId());
        }
        unset($oDatabase);
        
        return $bSuccess;
    }
    
    public function deleteExpiredUpdate($iLocationId, Update $oLastUpdate)
    {
        $bSuccess = false;
        
        if(!empty($oLastUpdate))
        {
            $this->sSqlQuery = 'DELETE FROM tb_update'
                    . ' WHERE location_id = :location_id'
//                    . ' AND update_id <> :update_id'
                    . ' AND update_valid_end_date < :expiration_date';
//            $aDataList[':update_id'] = $oLastUpdate->getId();
            $aDataList[':location_id'] = $iLocationId;
            $aDataList[':expiration_date'] = Date::addDay(Date::getUTCFullDate(), -15);
//            Util::var_dump($this->sSqlQuery); die();

            $oDatabase = Database::getConnection();
            $bSuccess = $oDatabase->doQuery($this->sSqlQuery, $aDataList);
            unset($oDatabase);
        }
        
        return $bSuccess;
    }
}