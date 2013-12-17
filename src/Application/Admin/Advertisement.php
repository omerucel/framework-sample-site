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
        $utilityMapper = $this->getServiceContainer()->getMapperContainer()->getUtilityMapper();
        $request = $this->getServiceContainer()->getRequest();

        $utilityMapper->updateSetting('homepage_728_90', $request->get('homepage_728_90'));
        $utilityMapper->updateSetting('homepage_300_250', $request->get('homepage_300_250'));
        $utilityMapper->updateSetting('news_detail_300_250', $request->get('news_detail_300_250'));
        $utilityMapper->updateSetting('iframe_728_90', $request->get('iframe_728_90'));

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

        $templateParams['homepage_728_90'] = $utilityMapper->fetchOneSettingByName('homepage_728_90');
        $templateParams['homepage_300_250'] = $utilityMapper->fetchOneSettingByName('homepage_300_250');
        $templateParams['news_detail_300_250'] = $utilityMapper->fetchOneSettingByName('news_detail_300_250');
        $templateParams['iframe_728_90'] = $utilityMapper->fetchOneSettingByName('iframe_728_90');

        return $this->render('admin/advertisement.twig', $templateParams);
    }
}
