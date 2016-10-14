<?php

/*
 * Copyright(c) Paul Molins. All Rights Reserved.
 * This software is the property of Paul Molins.
 */

/**
 * 
 * 
 * @author Paul Molins <pro@paul-molins.fr>
 * @since 2014.05.30
 * @lastUpdate 2014.05.30
 * 
 * History:
 * 2014.05.30 Paul Molins : Class creation
 * 
 */
class BaseDao
{
    protected $aElementList = array();
//    protected $aElementListById = array();
    protected $iNumberRows = 0;
    protected $sSqlQuery = null;
    protected $sWhereClause = null;
    protected $sGroupClause = null;
    protected $sOrderClause = null;
    protected $sLimitClause = null;
    
    public function __construct()
    {
        
    }
    
    /**
     * Tools
     */
    protected function getValueQueryFromData($iNbColumn, $iNbRow)
    {
        $aRowList = array();
        for($j = 0; $j < $iNbRow; $j++)
        {
            $sValueQueryPart = '(';
            for($k = 0; $k < $iNbColumn; $k++)
            {
                if($k === $iNbColumn - 1)
                {
                    $sValueQueryPart .= '?';
                }
                else
                {
                    $sValueQueryPart .= '?,';
                }
            }
            $sValueQueryPart .= ')';
            
            $aRowList[] = $sValueQueryPart;
        }
        
        return implode(',', $aRowList);
    }
    
    /**
     * Get number of elements in current list
     * @return int number of elements
     */
    public function count()
    {
        return count($this->aElementList);
    }
    
    public function getFirstElement()
    {
        return Arr::first($this->aElementList);
    }

    public function getElementList()
    {
        return $this->aElementList;
    }

//    public function getElementListById()
//    {
//        return $this->aElementListById;
//    }
//    
//    public function getElementById($iElementId)
//    {
//        return $this->aElementListById[$iElementId];
//    }
    
    // Debug
    public function getSqlQuery()
    {
        return $this->sSqlQuery;
    }
}