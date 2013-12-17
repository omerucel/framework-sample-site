<?php

namespace Application\Site;

use Application\BaseController;
use Application\Site;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseSiteController extends BaseController
{
    /**
     * @var Site
     */
    protected $module;

    /**
     * @param Site $module
     */
    public function __construct(Site $module)
    {
        $this->module = $module;
    }

    /**
     * @return Site
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param $templateFile
     * @param array $templateVariables
     * @param int $status
     * @return Response
     */
    public function render($templateFile, $templateVariables = array(), $status = 200)
    {
        $utilityMapper = $this->getServiceContainer()->getMapperContainer()->getUtilityMapper();

        $templateVariables['site_name'] = $utilityMapper->fetchOneSettingByName('site_name');
        $templateVariables['default_meta_title'] = $utilityMapper->fetchOneSettingByName('meta_title');
        $templateVariables['default_meta_description'] = $utilityMapper->fetchOneSettingByName('meta_description');
        $templateVariables['default_meta_keywords'] = $utilityMapper->fetchOneSettingByName('meta_keywords');
        $templateVariables['google_analytics'] = $utilityMapper->fetchOneSettingByName('google_analytics');

        return parent::render($templateFile, $templateVariables, $status);
    }
}
