<?php

/**
 * Description of JSRealLexiconService
 *
 * @author molinspa
 */
class JSRealService extends BaseService
{
    protected $sLanguage;
    
    public function __construct($sLanguage)
    {
        $this->sLanguage = $sLanguage;
    }
    
    protected function getLexicon()
    {
        $sXmlFileRealPath = Config::get('path.real.root') .
                Config::get('path.relative.root_to_api') .
                Config::get('path.relative.api_to_api_data') .
                Config::get('jsreal.lexicon.simple_nlg.xml.' . $this->sLanguage);
        $sXslFileRealPath = Config::get('path.real.root') .
                Config::get('path.relative.root_to_api') .
                Config::get('path.relative.api_to_xsl') .
                Config::get('jsreal.lexicon.simple_nlg.xsl');
        $sJsonLexicon = Xslt::applyXsltTemplateToXml($sXmlFileRealPath, $sXslFileRealPath);

        return Conversion::getArrayFromJson($sJsonLexicon);
    }
    
    protected function applyLexiconPatch(array &$aLexicon)
    {
        $sPatchFile = Config::get('path.real.root') . Config::get('path.relative.root_to_api')
                . Config::get('path.relative.api_to_api_data') . 
                Config::get('jsreal.lexicon.simple_nlg.patch.' . $this->sLanguage);
        $sJsonPatch = Filesystem::get($sPatchFile);
        $aPatch = Conversion::getArrayFromJson($sJsonPatch);
        
        $aLexicon = array_replace_recursive($aLexicon, $aPatch);
    }
    
    protected function extendLexicon(array &$aLexicon)
    {
        $sPatchFile = Config::get('path.real.root') . Config::get('path.relative.root_to_api')
                . Config::get('path.relative.api_to_api_data') . 
                Config::get('jsreal.lexicon.simple_nlg.extension.' . $this->sLanguage);
        $sJsonExtension = Filesystem::get($sPatchFile);
        $aExtension = Conversion::getArrayFromJson($sJsonExtension);
        
        $aLexicon = array_merge_recursive($aLexicon, $aExtension);
    }
    
    ///
    // EXPORT
    ///
    protected function exportLexicon(array $aLexicon)
    {
        $aExportedFile = array();
        
        $sUncompressedJson = Config::get('path.real.root') . 
                Config::get('path.relative.root_to_public_data') .
                Config::get('jsreal.lexicon.public.uncompressed.' . $this->sLanguage);
        $aExportedFile['sLexiconFile'] = $this->exportToJson($sUncompressedJson, $aLexicon, false);
        
        $sCompressedJson = Config::get('path.real.root') . 
                Config::get('path.relative.root_to_public_data') .
                Config::get('jsreal.lexicon.public.compressed.' . $this->sLanguage);
        $aExportedFile['sLexiconMinFile'] = $this->exportToJson($sCompressedJson, $aLexicon, true);
        
        return $aExportedFile;
    }
    
    protected function exportRuleTable(array $aRuleTable)
    {
        $aExportedFile = array();
        
        $sUncompressedJson = Config::get('path.real.root') . 
                Config::get('path.relative.root_to_public_data') .
                Config::get('jsreal.rule.public.uncompressed.' . $this->sLanguage);
        $aExportedFile['sRuleFile'] = $this->exportToJson($sUncompressedJson, $aRuleTable, false);
        
        $sCompressedJson = Config::get('path.real.root') . 
                Config::get('path.relative.root_to_public_data') .
                Config::get('jsreal.rule.public.compressed.' . $this->sLanguage);
        $aExportedFile['sRuleMinFile'] = $this->exportToJson($sCompressedJson, $aRuleTable, true);
        
        return $aExportedFile;
    }
    
    protected function exportToJson($sJsonFilePath, array $aData, $bCompress = false)
    {
        $sNewJsonContent = Conversion::getJsonFromArray($aData, $bCompress);
        
        if(Filesystem::put($sJsonFilePath, $sNewJsonContent) !== false)
        {
            return Filesystem::name($sJsonFilePath) . '.' . Filesystem::extension($sJsonFilePath);
        }
        else
        {
            throw new FileNotCreatedException('File : ' . $sJsonFilePath);
        }
    }
}