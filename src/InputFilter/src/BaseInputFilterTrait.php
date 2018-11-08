<?php
/**
 * Abstract Resource
 *
 * @since     Sep 2016
 * @author    Haydar KULEKCI  <haydarkulekci@gmail.com>
 */
namespace InputFilter;

use Zend\InputFilter\InputFilter;

trait BaseInputFilterTrait
{
    protected function inputFilterSpecsForPostParameters() : array
    {
        return [];
    }

    protected function inputFilterSpecsForGetParameters() : array
    {
        return [];
    }

    /**
     * @param array $filters
     * @param array $params
     *
     * @return InputFilter
     * @throws \Exception
     */
    protected function buildInputFilter(array $filters, array $params = []): InputFilter
    {
        if (\is_string($filters)) {
            if (!class_exists($filters)) {
                throw new \RuntimeException('Give correct input filter', 500);
            }
            $filters = new $filters($params);
            if (!\is_callable($filters)) {
                throw new \RuntimeException('Give callable input filter', 500);
            }
            $filters = $filters();
        }

        $inputFilter = new CustomInputFilter();

        foreach ($filters as $key => $param) {
            $name = null;
            if (!isset($param['name']) && !isset($param['type'])) {
                $input = $this->buildInputFilter($param);
                $name  = $key;
            } else {
                $input = $inputFilter->getFactory()->createInput($param);
                // in this case, name getting from directly input name
            }
            $inputFilter->add($input, $name);
        }

        return $inputFilter;
    }
}
