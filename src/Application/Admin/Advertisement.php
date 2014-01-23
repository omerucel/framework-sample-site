<?php

namespace Application\Admin;

class Advertisement extends BaseAdminController
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

        $settingMapper->update('homepage_728_90', $request->get('homepage_728_90'));

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
            'homepage_728_90'
        ));

        $templateParams['settings'] = $settings;

        return $this->render('admin/advertisement.twig', $templateParams);
    }
}
