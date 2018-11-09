<?php
/**
 * Base input filter class for all derived input filters.
 *
 * @since     May 2016
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace InputFilter;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

/**
 * Base filter class for service level input filters.
 */
class CustomInputFilter extends InputFilter
{
    public function getMessages()
    {
        $messages = [];
        foreach ($this->getInvalidInput() as $name => $input) {
            if ($input instanceof Input) {
                $name = $input->getName();
            }
            $messages[$name] = $input->getMessages();
        }

        return $messages;
    }
}
