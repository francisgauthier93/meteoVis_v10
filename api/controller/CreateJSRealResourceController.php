<?php

/**
 * Description of CreateJSRealResourceController
 *
 * @author molinspa
 */
class CreateJSRealResourceController extends BaseApiController
{
    public function execute()
    {
        if(Superglobal::isGetKey('sLanguage'))
        {
            $sLanguage = Superglobal::getGetValueByKey('sLanguage');
            
            if(strlen($sLanguage) <= 2
                    && Arr::in(Config::get('app.support.language'), $sLanguage))
            {
                if($sLanguage === 'fr')
                {
                    $oJSRealService = new JSRealFrService($sLanguage);
                }
                else
                {
                    $oJSRealService = new JSRealEnService($sLanguage);
                }
                $aExportedFile = $oJSRealService->export();

                return new SuccessJsonView($aExportedFile);
            }
            else
            {
                throw new LanguageNotSupportedException('Language not supported : ' . $sLanguage);
            }
        }
        else
        {
            throw new WrongParameterException('Wrong parameter : sLanguage');
        }
    }
}