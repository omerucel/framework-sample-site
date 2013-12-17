<?php

namespace Application\Admin;

class MetaInformation extends BaseAdminController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function get()
    {
        return $this->renderPage();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function post()
    {
        $utilityMapper = $this->getServiceContainer()->getMapperContainer()->getUtilityMapper();
        $request = $this->getServiceContainer()->getRequest();

        $utilityMapper->updateSetting('site_name', $request->get('site_name'));
        $utilityMapper->updateSetting('google_analytics', $request->get('google_analytics'));
        $utilityMapper->updateSetting('meta_title', $request->get('meta_title'));
        $utilityMapper->updateSetting('meta_description', $request->get('meta_description'));
        $utilityMapper->updateSetting('meta_keywords', $request->get('meta_keywords'));

        $templateParams = array(
            'message' => 'Değişiklikler kaydedildi.',
            'message_type' => 'success'
        );

        return $this->renderPage($templateParams);
    }

    /**
     * @param array $templateParams
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderPage(array $templateParams = array())
    {
        $utilityMapper = $this->getServiceContainer()->getMapperContainer()->getUtilityMapper();

        $templateParams['site_name'] = $utilityMapper->fetchOneSettingByName('site_name');
        $templateParams['google_analytics'] = $utilityMapper->fetchOneSettingByName('google_analytics');
        $templateParams['meta_title'] = $utilityMapper->fetchOneSettingByName('meta_title');
        $templateParams['meta_description'] = $utilityMapper->fetchOneSettingByName('meta_description');
        $templateParams['meta_keywords'] = $utilityMapper->fetchOneSettingByName('meta_keywords');

        return $this->render('admin/meta_information.twig', $templateParams);
    }
}
