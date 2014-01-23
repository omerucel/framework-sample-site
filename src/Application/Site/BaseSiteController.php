<?php

namespace Application\Site;

use Application\BaseController;
use Application\Meta;
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
        $templateVariables = $this->prepareMeta($templateVariables);
        return $this->renderWithoutMeta($templateFile, $templateVariables, $status);
    }

    /**
     * @param array $templateVariables
     * @return array
     */
    public function prepareMeta(array $templateVariables = array())
    {
        $settingMapper = $this->getServiceContainer()->getMapperContainer()->getSettingMapper();

        if (!isset($templateVariables['meta'])) {
            $settings = $settingMapper->fetchAllByNames(array(
                'google_analytics', 'site_name', 'meta_title', 'meta_description', 'meta_keywords'
            ));

            $meta = new Meta();

            if (isset($settings['site_name'])) {
                $meta->siteName = $settings['site_name']->value;
            }
            if (isset($settings['meta_title'])) {
                $meta->title = $settings['meta_title']->value;
            }
            if (isset($settings['meta_description'])) {
                $meta->description = $settings['meta_description']->value;
            }
            if (isset($settings['meta_keywords'])) {
                $meta->keywords = $settings['meta_keywords']->value;
            }
            $meta->canonical = $this->getServiceContainer()->getRequest()->getUri();

            $templateVariables['meta'] = $meta;
        } else {
            $settings = $settingMapper->fetchAllByNames(array('google_analytics'));
        }

        if (isset($setting['google_analytics'])) {
            $templateVariables['google_analytics'] = $settings['google_analytics']->value;
        }

        return $templateVariables;
    }

    /**
     * @param $templateFile
     * @param array $templateVariables
     * @param int $status
     * @return Response
     */
    public function renderWithoutMeta($templateFile, $templateVariables = array(), $status = 200)
    {
        return parent::render($templateFile, $templateVariables, $status);
    }
}
