<?php

namespace Application\Admin\Form;

use Application\BaseForm;
use Application\Field as Field;
use Symfony\Component\Validator\Constraints as Assert;

class MetaInformationForm extends BaseForm
{
    /**
     * @Field\Text(name="site_name")
     */
    protected $site_name;

    /**
     * @Field\Text
     */
    protected $meta_title;

    /**
     * @Field\Text
     */
    protected $meta_keywords;

    /**
     * @Field\Textarea
     */
    protected $meta_description;

    /**
     * @Field\Textarea
     */
    protected $google_analytics;

    /**
     * @return mixed
     */
    public function loadParams()
    {
        $request = $this->getServiceContainer()->getRequest();
        $this->site_name = $request->get('site_name');
        $this->meta_title = $request->get('meta_title');
        $this->meta_description = $request->get('meta_description');
        $this->meta_keywords = $request->get('meta_keywords');
        $this->google_analytics = $request->get('google_analytics');
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $this->validate();
        return $this->hasError() == false;
    }

    public function toHtml()
    {
        $reflClass = new \ReflectionClass($this);
        foreach ($reflClass->getProperties() as $property) {
            $reflProperty = new \ReflectionProperty($this, $property->getName());
            $annotations = $this->getServiceContainer()->getAnnotationReader()
                ->getPropertyAnnotations($reflProperty);

            foreach ($annotations as $annotation) {
                if ($annotation instanceof \Application\Field\Text) {
                    echo $annotation->name;
                }

                exit;
            }
        }
    }

    public function getSiteName()
    {
        return $this->site_name;
    }

    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    public function getGoogleAnalytics()
    {
        return $this->google_analytics;
    }
}
