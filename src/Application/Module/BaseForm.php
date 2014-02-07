<?php

namespace Application\Module;

use Symfony\Component\Validator\Validation;

abstract class BaseForm
{
    /**
     * Form elemanlarının değerlerinin doğrulanması sonucu oluşan hata mesajlarını tutar. Mesajlar Symfony Validator
     * paketinin verdiği propertyPath değerine göre diziye aktarılır.
     *
     * @var array
     */
    public $messages = array();

    /**
     * Parametreler yapıcı method ile aktarılabilir. Bazı durumlarda bu gerekli oluyor.
     *
     * @param array $params
     */
    public function __construct(array $params = array())
    {
        $this->setParams($params);
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        foreach ($params as $key => $value) {
            $paramName = $this->toCamelCaseParam($key);
            $this->{$paramName} = $value;
        }
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return count($this->messages) > 0;
    }

    /**
     * @param $messageName
     * @param $value
     */
    public function setMessage($messageName, $value)
    {
        $this->messages[$messageName] = $value;
    }

    /**
     * @param $messageName
     * @return null
     */
    public function getMessage($messageName)
    {
        return isset($this->messages[$messageName]) ? $this->messages[$messageName] : null;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param $paramName
     * @return mixed|string
     */
    public function toCamelCaseParam($paramName)
    {
        $paramName = str_replace('_', ' ', $paramName);
        $paramName = str_replace('-', ' ', $paramName);
        $paramName = ucwords($paramName);
        $paramName = str_replace(' ', '', $paramName);
        $paramName = lcfirst($paramName);
        return $paramName;
    }

    /**
     * Form doğrulama işlemini yapıp hata durumunu geri döner.
     *
     * @return bool
     */
    public function isValid()
    {
        $this->validate();
        return $this->hasError() == false;
    }

    /**
     * Form doğrulama işlemini Symfony Validator ile yapar ve hata mesajlarını varsa tanımlar.
     *
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
            $this->setMessage($violation->getPropertyPath(), $violation->getMessage());
        }
    }
}
