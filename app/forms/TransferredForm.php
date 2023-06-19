<?php
declare(strict_types=1);

namespace PSA\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator;

class TransferredForm extends Form
{
    public function initialize()
    {
        $operator = new Text('operator');

        $operator->addValidators([
            new PresenceOf([
                'message' => 'The name of the operator is required',
            ]),
        ]);

        $this->add($operator);

        $phone = new Text('phone');

        $phone->addValidators([
            new PresenceOf([
                'message' => 'The Phone Number is required',
            ]),
        ]);

        $this->add($phone);

        $address = new Text('address');

        $address->addValidators([
            new PresenceOf([
                'message' => 'The Address is required',
            ]),
        ]);

        $this->add($address);


    }
}