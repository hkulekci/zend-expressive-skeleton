<?php
/**
 * @author      Haydar KULEKCI <haydarkulekci@gmail.com>
 * @copyright   Copyright (c) Motivolog
 *
 * @link        https://odayonetim.com
 */

namespace InputFilter;

use InputFilter\Filter\DateSelect;
use InputFilter\Filter\DateTimeSelect;
use InputFilter\Filter\FloatFilter;
use Zend\Filter\Boolean;
use Zend\Filter\Callback;
use Zend\Filter\Digits;
use Zend\Filter\File\RenameUpload;
use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\Filter\ToNull;
use Zend\Validator\Callback as CallbackValidator;
use Zend\Validator\CreditCard;
use Zend\Validator\Date;
use Zend\Validator\EmailAddress;
use Zend\Validator\File\Extension;
use Zend\Validator\GreaterThan;
use Zend\Validator\NotEmpty;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

trait InputFilterHelperTrait
{
    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @param string $format
     * @return array
     */
    protected function datetime(string $name, bool $required = true, bool $allowEmpty = false, $format = 'Y-m-d H:i:s'): array
    {
        return $this->date($name, $required, $allowEmpty, $format);
    }

    /**
     * @param string $name
     * @param bool $required
     * @param string $target
     * @param array $extensions
     * @return array
     */
    protected function file(string $name, bool $required = true, string $target = '', array $extensions = []): array
    {
        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => !$required,
            'filters'     => [
                [
                    'name' => RenameUpload::class,
                    'options' => [
                        'target'               => $target,
                        'use_upload_name'      => true,
                        'use_upload_extension' => true,
                        'overwrite'            => true,
                        'randomize'            => false,
                    ]
                ],
                ['name' => ToNull::class],
            ],
            'validators'  => [
                ['name' => Extension::class, 'options' => ['extension' => implode(',', $extensions)]],
            ],
        ];
    }

    protected function dateSelect(string $name, bool $required = true, bool $allowEmpty = false, $format = 'Y-m-d'): array
    {
        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => DateSelect::class],
                ['name' => ToNull::class],
            ],
            'validators'  => [
                ['name' => Date::class, 'options' => ['format' => $format]]
            ],
        ];
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @param string $format
     * @return array
     */
    protected function date(string $name, $required = true, $allowEmpty = false, $format = 'Y-m-d'): array
    {
        $validators = [
            [
                'name'    => Date::class,
                'options' => [
                    'format'   => $format,
                    'messages' => array(
                        Date::INVALID      => 'Invalid type given. String, integer, array or DateTime expected',
                        Date::INVALID_DATE => 'The input does not appear to be a valid date',
                        Date::FALSEFORMAT  => 'The input does not fit the date format \'%format%\'',
                    ),
                ]
            ]
        ];
        $this->addNotEmptyValidator($validators, $required);

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => DateTimeSelect::class],
                ['name' => ToNull::class],
            ],
            'validators'  => $validators,
        ];
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @return array
     */
    protected function money(string $name, $required = true, $allowEmpty = false): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => array(
                ['name' => FloatFilter::class],
                ['name' => ToNull::class],
            ),
            'validators'  => $validators,
        ];
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @return array
     */
    protected function float($name, $required = true, $allowEmpty = false): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => array(
                ['name' => FloatFilter::class],
                ['name' => ToNull::class],
            ),
            'validators'  => $validators,
        ];
    }

    /**
     * @param string   $name
     * @param bool     $required
     * @param bool     $allowEmpty
     * @param null|int $greaterThan
     * @return array
     */
    protected function integer($name, $required = true, $allowEmpty = false, $greaterThan = null): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);

        if ($greaterThan !== null) {
            $validators[] = [
                'name'    => GreaterThan::class,
                'options' => [
                    'min'       => $greaterThan,
                    'inclusive' => true,
                    'messages'  => [
                        GreaterThan::NOT_GREATER           => "The input is not greater than '%min%'",
                        GreaterThan::NOT_GREATER_INCLUSIVE => "The input is not greater than or equal to '%min%'",
                    ],
                ]
            ];
        }

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => ToInt::class],
                ['name' => ToNull::class],
            ],
            'validators' => $validators,
        ];
    }

    /**
     * @param array $inputFilter
     * @param callable $callback
     * @return array
     */
    protected function integerWithCallbackValidator(array $inputFilter, callable $callback): array
    {
        $this->addCallbackValidator($inputFilter['validators'], $callback);

        return $inputFilter;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @return array
     */
    protected function integerArray($name, $required = true, $allowEmpty = false): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                [
                    'name'    => Callback::class,
                    'options' => [
                        'callback' => function ($value) {
                            if (\is_array($value)) {
                                $toIntFilter = new ToInt();
                                foreach ($value as $k => $v) {
                                    $v = $toIntFilter->filter($v);
                                    if (!$v) {
                                        unset($value[$k]);
                                    }
                                    $value[$k] = $v;
                                }

                                return array_unique($value);
                            }

                            return null;
                        },
                    ]
                ],
                ['name' => ToNull::class],
            ],
            'validators'  => $validators,
        ];
    }

    /**
     * @param string $name
     * @param bool   $required
     * @return array
     */
    protected function booleanWithNullValue($name, $required = false): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => !$required,
            'filters'     => array(
                [
                    'name'    => Boolean::class,
                    'options' => [
                        'type' => [
                            Boolean::TYPE_BOOLEAN,
                            Boolean::TYPE_INTEGER,
                            Boolean::TYPE_ZERO_STRING,
                            Boolean::TYPE_FALSE_STRING,
                        ],
                        'casting' => false,
                    ],
                ],
                [
                    'name'    => ToNull::class,
                    'options' => [
                        'type' => ToNull::TYPE_ALL - ToNull::TYPE_BOOLEAN,
                    ]
                ]
            ),
            'validators'  => $validators,
        ];
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @return array
     */
    protected function boolean($name, $required = false, $allowEmpty = true): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => array(
                [
                    'name'    => Boolean::class,
                    'options' => [
                        'type' => [
                            Boolean::TYPE_BOOLEAN,
                            Boolean::TYPE_INTEGER,
                            Boolean::TYPE_ZERO_STRING,
                            Boolean::TYPE_FALSE_STRING,
                        ],
                    ],
                ]
            ),
            'validators'  => $validators,
        ];
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @param array  $stringLength
     * @return array
     */
    protected function text($name, $required = true, $allowEmpty = false, array $stringLength = []): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);
        $this->addStringLengthValidator($validators, $stringLength);

        $filter = [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => StringTrim::class],
                ['name' => ToNull::class],
            ],
            'validators' => $validators,
        ];

        return $filter;
    }

    /**
     * @param $filter
     * @param $regex
     * @return array
     */
    protected function addRegexValidatorToString($filter, $regex) : array
    {
        if (!isset($filter['validators'])) {
            $filter['validators'] = [];
        }
        $filter['validators'][] = [
            'name'    => Regex::class,
            'options' => [
                'pattern' => $regex,
            ]
        ];

        return $filter;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @param array  $stringLength => Example: `$stringLength = ['min' => 1, 'max' => 3];`
     * @return array
     */
    protected function string($name, $required = true, $allowEmpty = false, array $stringLength = []): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);
        $this->addStringLengthValidator($validators, $stringLength);

        $filter = [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
                ['name' => StripNewlines::class],
                ['name' => ToNull::class],
            ],
            'validators' => $validators,
        ];

        return $filter;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @param array  $stringLength => Example: `$stringLength = ['min' => 1, 'max' => 3];`
     * @return array
     */
    protected function stringWithNl2br($name, $required = true, $allowEmpty = false, array $stringLength = []): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);
        $this->addStringLengthValidator($validators, $stringLength);

        $filter = [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => StripTags::class],
                ['name' => Callback::class, 'options' => ['callback' => function ($v) { return nl2br($v); }]],
                ['name' => StringTrim::class],
                ['name' => ToNull::class],
            ],
            'validators' => $validators,
        ];

        return $filter;
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @return array
     */
    protected function creditCard($name, $required = true, $allowEmpty = false): array
    {
        $validators = [
            [
                'name'    => CreditCard::class,
                'options' => [
                    'messages' => [
                        CreditCard::CHECKSUM       => "The input seems to contain an invalid checksum",
                        CreditCard::CONTENT        => "The input must contain only digits",
                        CreditCard::INVALID        => "Invalid type given. String expected",
                        CreditCard::LENGTH         => "The input contains an invalid amount of digits",
                        CreditCard::PREFIX         => "The input is not from an allowed institute",
                        CreditCard::SERVICE        => "The input seems to be an invalid credit card number",
                        CreditCard::SERVICEFAILURE => "An exception has been raised while validating the input",
                    ]
                ]
            ]
        ];
        $this->addNotEmptyValidator($validators, $required);

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => Digits::class],
                ['name' => ToNull::class],
            ],
            'validators' => $validators,
        ];
    }

    /**
     * @param string $name
     * @param bool $required
     * @param bool $allowEmpty
     * @param array $stringLength
     * @return array
     */
    protected function digits($name, $required = true, $allowEmpty = false, array $stringLength = []): array
    {
        $validators = [];
        $this->addNotEmptyValidator($validators, $required);
        $this->addStringLengthValidator($validators, $stringLength);

        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => Digits::class],
                ['name' => ToNull::class],
            ],
            'validators'  => $validators
        ];
    }

    /**
     * @param string $name
     * @param bool   $required
     * @param bool   $allowEmpty
     * @return array
     */
    protected function email($name, $required, $allowEmpty): array
    {
        return [
            'name'        => $name,
            'required'    => $required,
            'allow_empty' => $allowEmpty,
            'filters'     => [
                ['name' => StripTags::class],
                ['name' => StripNewlines::class],
                ['name' => StringTrim::class],
                ['name' => ToNull::class],
            ],
            'validators' => [
                [
                    'name'    => EmailAddress::class,
                    'options' => [
                        'messages' => [
                            EmailAddress::INVALID            => "Invalid type given. String expected",
                            EmailAddress::INVALID_FORMAT     => "The input is not a valid email address. Use the basic format local-part@hostname",
                            EmailAddress::INVALID_HOSTNAME   => "'%hostname%' is not a valid hostname for the email address",
                            EmailAddress::INVALID_MX_RECORD  => "'%hostname%' does not appear to have any valid MX or A records for the email address",
                            EmailAddress::INVALID_SEGMENT    => "'%hostname%' is not in a routable network segment. The email address should not be resolved from public network",
                            EmailAddress::DOT_ATOM           => "'%localPart%' can not be matched against dot-atom format",
                            EmailAddress::QUOTED_STRING      => "'%localPart%' can not be matched against quoted-string format",
                            EmailAddress::INVALID_LOCAL_PART => "'%localPart%' is not a valid local part for the email address",
                            EmailAddress::LENGTH_EXCEEDED    => "The input exceeds the allowed length",
                        ]
                    ]
                ],
            ],
        ];
    }

    private function addNotEmptyValidator(&$validators, $required): void
    {
        if ($required) {
            $validators[] = [
                'name'    => NotEmpty::class,
                'options' => [
                    'messages' => [
                        NotEmpty::IS_EMPTY => 'Value is required and can\'t be empty',
                    ],
                ],
            ];
        }
    }

    private function addStringLengthValidator(&$validators, $stringLength): void
    {
        if ($stringLength) {
            $options = array_merge(
                $stringLength,
                [
                    'messages' => [
                        StringLength::INVALID   => 'Invalid type given. String expected',
                        StringLength::TOO_SHORT => 'The input is less than %min% characters long',
                        StringLength::TOO_LONG  => 'The input is more than %max% characters long',
                    ]
                ]
            );
            $validators[] = [
                'name'    => StringLength::class,
                'options' => $options
            ];
        }
    }

    private function addDefaultValueIntegerFilter(array $inputfilter, int $defaultValue): array
    {
        if (isset($inputfilter['filters']) && \is_array($inputfilter['filters'])) {
            $inputfilter['filters'][] = [
                'name'    => Callback::class,
                'options' => [
                    'callback' => function ($value) use ($defaultValue) {
                        if ($value === null) {
                            return $defaultValue;
                        }

                        return $value;
                    },
                ],
            ];
        }

        return $inputfilter;
    }

    private function addCallbackValidator(&$validators, $callback): void
    {
        if (\is_callable($callback)) {
            $validators[] = [
                'name'    => CallbackValidator::class,
                'options' => [
                    'callback' => $callback
                ]
            ];
        }
    }
}
