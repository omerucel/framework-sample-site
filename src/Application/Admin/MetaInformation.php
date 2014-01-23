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
        $settingMapper = $this->getServiceContainer()->getMapperContainer()->getSettingMapper();
        $request = $this->getServiceContainer()->getRequest();

        $settingMapper->update('site_name', $request->get('site_name'));
        $settingMapper->update('google_analytics', $request->get('google_analytics'));
        $settingMapper->update('meta_title', $request->get('meta_title'));
        $settingMapper->update('meta_description', $request->get('meta_description'));
        $settingMapper->update('meta_keywords', $request->get('meta_keywords'));

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
        $settingMapper = $this->getServiceContainer()->getMapperContainer()->getSettingMapper();

        $settings = $settingMapper->fetchAllByNames(array(
            'google_analytics', 'site_name', 'meta_title', 'meta_description', 'meta_keywords'
        ));

        $templateParams['settings'] = $settings;

        return $this->render('admin/meta_information.twig', $templateParams);
    }
}
