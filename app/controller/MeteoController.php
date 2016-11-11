<?php

/**
 * Description of MeteoController
 *
 * @author molinspa & gauthif
 */

include 'meteoTextRealisations.php';

include 'jsrealbLoaderFr.php';

class MeteoController extends BaseAppController
{
    private $oMeteoCode;
    private $sLocationCode;
    
    public function setUp()
    {
        if(Superglobal::isPostKey('code'))
        {
            $this->sLocationCode = Superglobal::getPostValueByKey('code');
        }
        else
        {
            $this->sLocationCode = Config::get('location.default.city.code');
        }
    }
    
    public function weather()
    {
        if(Superglobal::isPostKey('prov')
                && Superglobal::isPostKey('ville'))
        {
            $province = Superglobal::getPostValueByKey('prov');
            $sCityId = Superglobal::getPostValueByKey('ville');
        }
        else
        {
            $province = Config::get('location.default.city.province.abbr.fr');
            $sCityId = Config::get('location.default.city.id');
        }
        
        $oWeatherView = new WeatherView();
        $sHead = $oWeatherView->getCurrentConditionHeader($province, $sCityId, 'entete.xsl');
        $sBody = $oWeatherView->getCurrentConditionBody($province, $sCityId, 'cond_cour.xsl');
        
        $oMeteoCodeService = new MeteoCodeService();
        $this->oMeteoCode = $oMeteoCodeService->GetMeteoCodeFromLocationCode($this->sLocationCode);
        unset($oMeteoCodeService);
        
        if(is_null($this->oMeteoCode))
        {
            throw new LocationNotExistsException('Location code : ' . $this->sLocationCode);
        }
        
        $sNextHours = $oWeatherView->getNextHoursTemperature(3, $this->oMeteoCode);
        
        $this->registry->template->sHead = $sHead;
        $this->registry->template->sBody = $sBody;
        $this->registry->template->sNextHours = $sNextHours;
        $this->registry->template->show('weather');
    }
    
    public function graph()
    {
        $oGraphView = new GraphView($this->oMeteoCode);
        $this->registry->template->sDayTitle = $oGraphView->getDay();
        $sTemperatureSvg = $oGraphView->getTemperature();
        $sTemperatureSvg .= $oGraphView->getMinimumLine();
        $sTemperatureSvg .= $oGraphView->getAverageLine();
        $sTemperatureSvg .= $oGraphView->getZeroLine();
        $sTemperatureSvg .= $oGraphView->getMaximumLine();
        $this->registry->template->sTemperatureSvg = $sTemperatureSvg;
        $this->registry->template->sPrecipitationSvg = $oGraphView->getPrecipitation();
        $this->registry->template->sCloudCoverSvg = $oGraphView->getCloudCover();
        $this->registry->template->sWindSvg = $oGraphView->getWind();
        $this->registry->template->sAccumulationSvg = $oGraphView->getAccumulation();
        
//        Util::var_dump($sTemperatureSvg); die;
        
        $this->registry->template->iGraphWidth = Config::get('meteo.graph.measure.iWidth');
        $this->registry->template->iGraphHeight = Config::get('meteo.graph.measure.iHeight');
        $this->registry->template->iDayWidth = Config::get('meteo.graph.measure.iDayWidth');
        
        $this->registry->template->sSnowImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.snow');
        $this->registry->template->sRainImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.rain');
        $this->registry->template->sArrowImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.arrow');
        
        $this->registry->template->sSkySunnyImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.sky_sunny');
        $this->registry->template->sSkyFairImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.sky_fair');
        $this->registry->template->sSkyMostlySunnyImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.sky_mostly_sunny');
        $this->registry->template->sSkyPartlyCloudyImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.sky_partly_cloudy');
        $this->registry->template->sSkyMostlyCloudyImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.sky_mostly_cloudy');
        $this->registry->template->sSkyBrokenImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.sky_broken');
        $this->registry->template->sSkyCloudyImagePath = Config::get('path.relative.root_to_image')
                . Config::get('path.folder.img_graph') . Config::get('meteo.graph.image.sky_cloudy');
        
        $this->registry->template->show('graph');
    }
    
    public function forecast()
    {
    	$meteoReal = new MeteoRealisation($this->oMeteoCode);
    	
        $iNbDays = Config::get('meteo.limit.day.max');
        $sForecast = '';
        for($i = 0; $i < $iNbDays; $i++)
        {
            $sForecast .= '<tr>'
                    . '<td data-title="Day" data-original-translation>' . Date::getDisplayableDate(Date::getDateFromNow($i))/*Date::getDisplayableDate(Date::getDateFromNow($i))*/ . '</td>'
                    . '<td align="center" data-title="Minimum">'.$meteoReal->getMinMaxTempOneDay('min', $i).'</td>'
                    . '<td data-title="Maximum">'.$meteoReal->getMinMaxTempOneDay('max', $i).'</td>'
                    . '<td data-title="Information"></td>'
                    . '</tr>';
        }
        
        $this->registry->template->sForecast = $sForecast;
        $this->registry->template->show('forecast');
        
        $meteoReal->updateMeteo();
    }
}
