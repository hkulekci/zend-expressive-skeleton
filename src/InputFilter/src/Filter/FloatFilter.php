<?php
/**
 * @author      Haydar KULEKCI <haydarkulekci@gmail.com>
 * @copyright   Copyright (c) Motivolog
 *
 * @link        https://odayonetim.com
 */

namespace InputFilter\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

class FloatFilter extends AbstractFilter
{
    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     *
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        $value = preg_replace('/[^0-9\.\,]/', '', (string)$value);
        $value = str_replace(',', '.', $value);

        return (float)$value;
    }
}
