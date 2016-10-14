<?php

/**
 * Description of Location
 *
 * @author molinspa
 */
class Location
{
    private $iId;
    private $sCode;
    private $sNameFr;
    private $sNameEn;
    private $fLatitude;
    private $fLongitude;
    private $sRegionAbbreviation;
    private $sTimeZoneFr;
    private $sTimeZoneEn;
    
    function __construct($iId, $sCode, $sNameFr, $sNameEn, $fLatitude, $fLongitude,
            $sRegionAbbreviation, $sTimeZoneFr, $sTimeZoneEn)
    {
        $this->iId = $iId;
        $this->sCode = $sCode;
        $this->sNameFr = $sNameFr;
        $this->sNameEn = $sNameEn;
        $this->fLatitude = $fLatitude;
        $this->fLongitude = $fLongitude;
        $this->sRegionAbbreviation = $sRegionAbbreviation;
        $this->sTimeZoneFr = $sTimeZoneFr;
        $this->sTimeZoneEn = $sTimeZoneEn;
    }
    
    public function setId($iId)
    {
        $this->iId = Number::GetInt($iId);
    }
    
    public function getId()
    {
        return $this->iId;
    }

    public function getCode()
    {
        return $this->sCode;
    }

    public function getNameFr()
    {
        return $this->sNameFr;
    }

    public function getNameEn()
    {
        return $this->sNameEn;
    }

    public function getLatitude()
    {
        return $this->fLatitude;
    }

    public function getLongitude()
    {
        return $this->fLongitude;
    }

    public function getRegionAbbreviation()
    {
        return $this->sRegionAbbreviation;
    }

    public function getTimeZoneFr()
    {
        return $this->sTimeZoneFr;
    }

    public function getTimeZoneEn()
    {
        return $this->sTimeZoneEn;
    }
}
