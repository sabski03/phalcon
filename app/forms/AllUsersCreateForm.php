<?php
declare(strict_types=1);

namespace PSA\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\StringLength;



class AllUsersCreateForm extends Form
{

    public function initialize()
    {
        $name = new Text('name', [
            'placeholder' => 'Name',
        ]);

        $name->addValidators([
            new PresenceOf([
                'message' => 'The name is required',
            ]),
        ]);

        $this->add($name);

        $email = new Text('email', [
            'placeholder' => 'Email',
        ]);

        $email->addValidators([
            new PresenceOf([
                'message' => 'The e-mail is required',
            ]),
            new Email([
                'message' => 'The e-mail is not valid',
            ]),
        ]);

        $this->add($email);


        $phone = new Text('phone', [
            'placeholder' => 'Phone',
        ]);

        $phone->addValidators([
            new PresenceOf([
                'message' => 'The Phone Number is required',
            ]),
        ]);

        $this->add($phone);

        $address = new Text('address', [
            'placeholder' => 'Address',
        ]);

        $address->addValidators([
            new PresenceOf([
                'message' => 'The Address is required',
            ]),
        ]);

        $this->add($address);

        $password = new Password('password', [
            'placeholder' => 'Password',
        ]);
        $password->setLabel('Password');
        $password->addValidators([
            new PresenceOf([
                'message' => 'The password is required',
            ]),
            new StringLength([
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters',
            ]),
            new Confirmation([
                'message' => "Password doesn't match confirmation",
                'with' => 'confirmPassword',
            ]),
        ]);

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword', [
            'placeholder' => 'Confirm Password',
        ]);
        $confirmPassword->setLabel('Confirm Password');
        $confirmPassword->addValidators([
            new PresenceOf([
                'message' => 'The confirmation password is required',
            ]),
        ]);

        $this->add($confirmPassword);


    }
}

