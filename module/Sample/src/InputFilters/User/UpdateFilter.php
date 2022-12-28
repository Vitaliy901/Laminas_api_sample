<?php

namespace Sample\InputFilters\User;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\Callback;
use Laminas\Validator\InArray;
use Laminas\Validator\Regex;
use Laminas\Validator\StringLength;

class UpdateFilter implements InputFilterAwareInterface
{
    protected $adapter;
    public function __construct(AdapterInterface $adapter) {
        $this->adapter = $adapter;
    }
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'first_name',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'last_name',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'email',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 128,
                    ],
                ],
                [
                    'name' => Regex::class,
                    'options' => [
                        'pattern' => '#^\w+@[a-zA-Z]+\.[a-zA-Z]+$#',
                        'messages' => [
                            Regex::NOT_MATCH => 'The email is not in the correct format. Should be example@mail.com',
                        ],
                    ]
                ],
                // to do.
/*                [
                    'name' => Callback::class,
                    'options' => [
                        'callback' => function ($value, $context) {
                            $db = new Sql($this->adapter);
                            $select = $db->select('users');
                            $select->where(['email' => $value]);
                            $result = $db->prepareStatementForSqlObject($select)->execute();
                            return !(bool) $result->getAffectedRows();
                        },
                        'messages' => [
                            Callback::INVALID_VALUE => 'The email is exists.',
                        ],
                    ],
                ]*/
            ],
        ]);
        $inputFilter->add([
            'name' => 'password',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'role',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                [
                    'name' => InArray::class,
                    'options' => [
                        'haystack' => ['user', 'admin']
                    ],

                ]
            ],
        ]);
        return $inputFilter;
    }
}