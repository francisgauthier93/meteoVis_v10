<?php

/**
 * Description of Update
 *
 * @author molinspa
 */
class Update
{
    private $iId;
    private $sType;
    private $sValidStartDate;
    private $sValidEndDate;
    private $sCreationDate;
    private $sUrl;
    
    public function __construct($iId, $sType,
            $sValidStartDate, $sValidEndDate, $sCreationDate,
            $sUrl)
    {
        $this->iId = $iId;
        $this->sType = $sType;
        $this->sValidStartDate = $sValidStartDate;
        $this->sValidEndDate = $sValidEndDate;
        $this->sCreationDate = $sCreationDate;
        $this->sUrl = $sUrl;
    }

    public function getId()
    {
        return $this->iId;
    }
    
    public function setId($iId)
    {
        $this->iId = Number::getInt($iId);
    }

    public function getType()
    {
        return $this->sType;
    }

    public function getValidStartDate()
    {
        return $this->sValidStartDate;
    }

    public function getValidEndDate()
    {
        return $this->sValidEndDate;
    }

    public function getCreationDate()
    {
        return $this->sCreationDate;
    }
    
    public function getUrl()
    {
        return $this->sUrl;
    }
}
