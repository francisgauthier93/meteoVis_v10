<?php

/**
 * Description of LocationDao
 *
 * @author molinspa
 */
class LocationDao extends BaseDao implements DaoInterface
{    
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null)
    {
        parent::__construct();
        
        if($bGetElementList)
        {
            $aDataList = array();
            
            $this->sWhereClause = ' WHERE `location_status` = :status';
            $aDataList[':status'] = Config::get('db.query.status.online');
            
            if(isset($aWhere['sCode']))
            {
                $this->sWhereClause .= ' AND `location_code` = :code';
                $aDataList[':code'] = $aWhere['sCode'];
            }
            
            $this->sLimitClause = ' LIMIT :limit_offset, :limit_length';
            $aDataList[':limit_offset'] = (is_null($iLimitOffset) ? 0 : Number::getInt($iLimitOffset));
            $aDataList[':limit_length'] = (is_null($iLimitLength) ? Config::get('db.limit.length.max') : Number::getInt($iLimitLength));
            
            $this->sSqlQuery = 'SELECT '
                    . '`location_id`, '
                    . '`location_code`, '
                    . '`location_name_fr`, '
                    . '`location_name_en`, '
                    . '`location_latitude`, '
                    . '`location_longitude`, '
                    . '`location_region_abbr`, '
                    . '`location_time_zone_fr`, '
                    . '`location_time_zone_en` '
                    . 'FROM `tb_location`'
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
                $oLocation = LocationFactory::fromDatabase(
                        $oRow->location_id, 
                        $oRow->location_code, 
                        $oRow->location_name_fr, 
                        $oRow->location_name_en, 
                        $oRow->location_latitude, 
                        $oRow->location_longitude, 
                        $oRow->location_region_abbr, 
                        $oRow->location_time_zone_fr, 
                        $oRow->location_time_zone_en);
                
                $this->aElementList[] = $oLocation;
            }
            unset($oDatabase);
        }
    }
    
    public function create(Location $oLocation = null)
    {
        $aDataList = array(
                ':code' => $oLocation->getCode(),
                ':name_fr' => $oLocation->getNameFr(),
                ':name_en' => $oLocation->getNameEn(),
                ':latitude' => $oLocation->getLatitude(),
                ':longitude' => $oLocation->getLongitude(),
                ':region_abbr' => $oLocation->getRegionAbbreviation(),
                ':time_zone_fr' => $oLocation->getTimeZoneFr(),
                ':time_zone_en' => $oLocation->getTimeZoneEn(),
                ':storing_date' => Date::getUTCFullDate(),
                ':status' => Config::get('db.query.status.online'),
        );
        
        $this->sSqlQuery = 'INSERT INTO `tb_location` '
                . '(location_code, '
                . 'location_name_fr, '
                . 'location_name_en, '
                . 'location_latitude, '
                . 'location_longitude, '
                . 'location_region_abbr, '
                . 'location_time_zone_fr, '
                . 'location_time_zone_en,'
                . 'location_storing_date,'
                . 'location_status)'
                . ' VALUES '
                . '(' . implode(',', array_keys($aDataList)) . ');';
        
        $oDatabase = Database::getConnection();
        $bSuccess = $oDatabase->doQuery($this->sSqlQuery, $aDataList);
        if($bSuccess)
        {
            $oLocation->setId($oDatabase->doLastInsertId());
        }
        unset($oDatabase);
        
        return $bSuccess;
    }
}