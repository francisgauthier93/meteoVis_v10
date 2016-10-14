<?php

/**
 * Interface pour les Data Access Object.
 */
interface DaoInterface
{
    public function __construct($bGetElementList = false, $aWhere = array(), 
            $iLimitOffset = null, $iLimitLength = null);

//    public function create();
    
//    public function update();
    
//    public function delete();
    
    /**
     * Get number of elements in current list
     */
    public function count();
    
    /**
     * Get total number of elements in database
     */
//    public function getTotalNbElement();

    public function getFirstElement();
    
    public function getElementList();

//    public function getElementListById();
//    
//    public function getElementById($iElementId);
    
//    public function getElementListToArray();
//    
//    public function getElementListByIdToArray();
}