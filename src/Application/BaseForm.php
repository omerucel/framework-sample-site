<?php

namespace Application;

use Symfony\Component\Validator\Validation;

abstract class BaseForm
{
    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @var array
     */
    protected $messages = array();

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @return ServiceContainer
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @return bool
     */
    abstract public function isValid();

    /**
     * @return mixed
     */
    abstract public function loadParams();

    /**
     * @return bool
     */
    public function hasError()
    {
        return count($this->messages) > 0;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function validate()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $this->messages = array();

        $violations = $validator->validate($this);
        for ($i = 0; $i < $violations->count(); $i++) {
            $violation = $violations->get($i);
            $this->messages[$violation->getPropertyPath()] = $violation->getMessage();
        }
    }
}
