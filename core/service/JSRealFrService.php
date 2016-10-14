<?php

/**
 * Description of JSRealFrService
 *
 * @author molinspa
 */
class JSRealFrService extends JSRealService
{
    public function __construct($sLanguage)
    {
        parent::__construct($sLanguage);
    }
    
    public function export()
    {
        $aLexicon = $this->getLexicon();
        $this->applyLexiconPatch($aLexicon);
        $this->extendLexicon($aLexicon);
        
        $aVerbList = $this->getVerbList();
        $aConjugation = $this->getConjugation();        
        $this->updateVerbId($aVerbList, $aConjugation);
        
        $this->addTableIdToVerb($aVerbList, $aLexicon);
        $this->removeConjugationInLexicon($aLexicon);
        
        $aRuleTable = array();
        $aRuleTable['conjugation'] = $aConjugation;
        
        $aExportedFile = array();
        $aExportedFile = array_merge($aExportedFile, $this->exportLexicon($aLexicon));
        $aExportedFile = array_merge($aExportedFile, $this->exportRuleTable($aRuleTable));
        
        return $aExportedFile;
    }
    
    private function getVerbList()
    {
        $sXmlFileRealPath = Config::get('path.real.root') .
                Config::get('path.relative.root_to_api') .
                Config::get('path.relative.api_to_api_data') .
                Config::get('jsreal.verb.dictionary.xml.' . $this->sLanguage);
        $sXslFileRealPath = Config::get('path.real.root') .
                Config::get('path.relative.root_to_api') .
                Config::get('path.relative.api_to_xsl') .
                Config::get('jsreal.verb.dictionary.xsl.' . $this->sLanguage);
        $sJsonVerbList = Xslt::applyXsltTemplateToXml($sXmlFileRealPath, $sXslFileRealPath);

        return Conversion::getArrayFromJson($sJsonVerbList);
    }
    
    private function getConjugation()
    {
        $sXmlFileRealPath = Config::get('path.real.root') .
                Config::get('path.relative.root_to_api') .
                Config::get('path.relative.api_to_api_data') .
                Config::get('jsreal.verb.conjugation.xml.' . $this->sLanguage);
        $sXslFileRealPath = Config::get('path.real.root') .
                Config::get('path.relative.root_to_api') .
                Config::get('path.relative.api_to_xsl') .
                Config::get('jsreal.verb.conjugation.xsl.' . $this->sLanguage);
        
        $sJsonConjugation = Xslt::applyXsltTemplateToXml($sXmlFileRealPath, $sXslFileRealPath);

        return Conversion::getArrayFromJson($sJsonConjugation);
    }
    
    private function updateVerbId(array &$aVerbList, array &$aConjugation)
    {
        $aOldToNewIdMap = array();
        
        $i = 0;
        $aTmpConjugation = array();
        foreach($aConjugation as $sTableId => $aConjugationTable)
        {
            if(!isset($aOldToNewIdMap[$sTableId]))
            {
                $aOldToNewIdMap[$sTableId] = 'v' . $i++;
            }
            
            $aTmpConjugation[$aOldToNewIdMap[$sTableId]] = $aConjugationTable;
        }
        $aConjugation = $aTmpConjugation;
        
        foreach($aVerbList as $sVerb => $sTableId)
        {
            $aVerbList[$sVerb] = $aOldToNewIdMap[$sTableId];
        }
    }
    
    private function addTableIdToVerb(array &$aVerbList, array &$aLexicon)
    {
        foreach($aVerbList as $sVerb => $sRuleTableId)
        {
            if(isset($aLexicon[$sVerb]))
            {
                if(isset($aLexicon[$sVerb]['V']))
                {
                    $aLexicon[$sVerb]['V']['ct'] = $sRuleTableId;
                }
            }
        }
    }
    
    private function removeConjugationInLexicon(array &$aLexicon)
    {
        $aForbiddenKeyList = array('p1', 'p2', 'p3', 'p4', 'p5', 'p6',
            'ps1', 'ps2', 'ps3', 'ps4', 'ps5', 'ps6',
            's1', 's2', 's3', 's4', 's5', 's6',
            'ip2', 'ip4', 'ip5',
            'pr', 'pp',
            'fr', 'ir');
        
        $aNewLexicon = array();
        foreach($aLexicon as $sUnit => $aValueList)
        {
            foreach($aValueList as $i => $aCategory)
            {
                foreach($aCategory as $sKey => $uValue)
                {
                    if(!in_array($sKey, $aForbiddenKeyList))
                    {
                        $aNewLexicon[$sUnit][$i][$sKey] = $uValue;
                    }
                }
            }
        }
        $aLexicon = $aNewLexicon;
    }
}