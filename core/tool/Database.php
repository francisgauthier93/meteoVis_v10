<?php

/**
 * Description of Database
 *
 * @author molinspa
 */
class Database extends PDO
{
    private static $oInstance = null;
//    private static $aInstanceList = array();
    
    private $oStatement = null;
    
    private $sLastSqlQuery = null;
    
    private $sLastError = null;
    private $iNbAffectedRow = 0;
 
    final public function __construct()
    {
        
    }
    
    private function setUp()
    {
        try
        {
            parent::__construct('mysql:host=' . Config::get('db.connection.mysql.hostname') 
                . ';dbname=' . Config::get('db.connection.mysql.database') . ';charset=utf8;', 
                Config::get('db.connection.mysql.username'), 
                Config::get('db.connection.mysql.password'), 
                array(
                    PDO::ATTR_EMULATE_PREPARES => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone="+00:00";'
                )
            );
        }
        catch(Exception $e)
        {
            $this->setLastError($e->getMessage());
        }
    }
 
    final public function __clone()
    {
    }
    
    final public function __destruct()
    {   
        self::$oInstance = null;
        unset($this);
    }
 
    final public static function getConnection()
    {
        if(!(self::$oInstance instanceof self))
        {
            self::$oInstance = new self();
            self::$oInstance->setUp();
        }
 
        return self::$oInstance;
        
//        $c = get_called_class();
// 
//        if(!isset(self::$aInstanceList[$c]))
//        {
//            self::$aInstanceList[$c] = new $c;
//        }
// 
//        return self::$aInstanceList[$c];
    }
    
    /**
     * Simple Query
     */
    public function doQuery($sSqlRawQuery, $aDataList = array(), $bNamedParameter = true)
    {
        $bSuccess = true;
        
        try
        {
            $this->sLastSqlQuery = $sSqlRawQuery;
            $this->oStatement = $this->prepare($this->sLastSqlQuery, 
                    ($bNamedParameter ? array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) : array()));
            $this->bindParameterList($aDataList, $this->oStatement);
            $this->iNbAffectedRow = $this->oStatement->execute();
        }
        catch(Exception $e)
        {
            $this->setLastError($e->getMessage());
            $this->oStatement = null;
            $this->iNbAffectedRow = 0;
            $bSuccess = false;
        }
        
        return $bSuccess;
    }
    
    /**
     * Multi Queries
     */
    public function doMultiQuery($sSqlRawQuery, $aDataList = array(), $bNamedParameter = true)
    {
        $bSuccess = true;
        
        try
        {
            $this->beginTransaction();
            
            $this->sLastSqlQuery = $sSqlRawQuery;
            $this->oStatement = $this->prepare($this->sLastSqlQuery, 
                    ($bNamedParameter ? array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) : array()));
            $this->bindParameterList($aDataList, $this->oStatement);
            $this->iNbAffectedRow = $this->oStatement->execute();
            
            $this->commit();
        }
        catch(Exception $e)
        {
            $this->setLastError($e->getMessage());
            $this->oStatement = null;
            $this->iNbAffectedRow = 0;
            $bSuccess = false;
        }
        
        return $bSuccess;
    }

    /**
     * Last Id Inserted
     */
    public function doLastInsertId()
    {
        return $this->lastInsertId();
    }
    
    /**
     * All Fetch
     */
    public function doFetchArray()
    {
        if(!is_null($this->oStatement))
        {
            $this->oStatement->setFetchMode(PDO::FETCH_NUM);
            return $this->oStatement->fetch();
        }
        else
        {
            return null;
        }
    }
    
    public function doFetchAssoc()
    {
        if(!is_null($this->oStatement))
        {
            $this->oStatement->setFetchMode(PDO::FETCH_ASSOC);
            return $this->oStatement->fetch();
        }
        else
        {
            return null;
        }
    }
    
    public function doFetchObject()
    {
        if(!is_null($this->oStatement))
        {
            $this->oStatement->setFetchMode(PDO::FETCH_OBJ);
            return $this->oStatement->fetch();
        }
        else
        {
            return null;
        }
    }
    
    /**
     * Tools
     */
    private function bindParameterList(array $aParameterList, &$oStatement)
    {
        foreach($aParameterList as $uKey => $uValue)
        {
            if(is_int($uValue))
            {
                $iPdoType = PDO::PARAM_INT;
            }
            else if($uValue === null)
            {
                $iPdoType = PDO::PARAM_NULL;
            }
            else if(is_bool($uValue))
            {
                $iPdoType = PDO::PARAM_BOOL;
            }
            else
            {
                $iPdoType = PDO::PARAM_STR;
            }

            $sKey = is_int($uKey) ? $uKey+1 : $uKey;
            
            $oStatement->bindValue($sKey, $uValue, $iPdoType);
        }
    }
    
    /**
     * Getters
     */
    public function hasError()
    {
        return !is_null($this->sLastError);
    }
    
    private function setLastError($sErrorMessage)
    {
        $this->sLastError = $sErrorMessage;
        
        if(Config::isDevEnv())
        {
            var_dump($this->errorInfo());
            var_dump($this->sLastSqlQuery);
            die('SQL Error : ' . $this->sLastError);
        }
    }
    
    public function getLastError()
    {
        return $this->sLastError;
    }
    
    public function getNbAffectedRow()
    {
        $this->iNbAffectedRow;
    }
}