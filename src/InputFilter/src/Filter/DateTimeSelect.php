<?php

namespace InputFilter\Filter;

use Zend\Filter\AbstractFilter;

class DateTimeSelect extends AbstractFilter
{
    /**
     * Returns the result of filtering $value
     *
     * @param  string           $value
     * @return \DateTime|null
     */
    public function filter($value)
    {
        if ($value) {
            $date = strtotime($value);

            return $date ? new \DateTime('@'.$date) : null;
        }

        return null;
    }
}
