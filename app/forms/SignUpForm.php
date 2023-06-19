<?php
declare(strict_types=1);

namespace PSA\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Digit as DigitValidator;

class SignUpForm extends Form
{
    /**
     * @param null $entity
     * @param array $options
     */
    public function initialize($entity = null, array $options = [])
    {
        $name = new Text('name', [
            'placeholder' => 'Full name',
        ]);
        $name->setLabel('Name');
        $name->addValidators([
            new PresenceOf([
                'message' => 'The name is required',
            ]),
        ]);

        $this->add($name);

        $email = new Text('email', [
            'placeholder' => 'E-Mail',
        ]);
        $email->setLabel('E-Mail');
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
            'placeholder' => 'Phone Number',
        ]);
        $phone->setLabel('Phone Number');
        $phone->addValidators([
            new PresenceOf([
                'message' => 'The Phone Number is required',
            ]),
            new StringLength([
                'max' => 9,
                'messageMaximum' => 'Phone Number is too Long. It should be 9 numbers',
            ]),
            new StringLength([
                'min' => 9,
                'messageMinimum' => 'Phone Number is too short. It should be 9 numbers',
            ]),
            new DigitValidator([
                "message" => "The Phone Number Must Contain Numbers Only",
            ])
        ]);

        $this->add($phone);


        $address = new Text('address', [
            'placeholder' => 'Address',
        ]);
        $address->setLabel('Address');
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

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical([
            'value' => $this->security->getRequestToken(),
            'message' => 'CSRF validation failed',
        ]));
        $csrf->clear();

        $this->add($csrf);

        $this->add(new Submit('signUp', [
            'class' => 'btn btn-sm btn-primary btn-block',
            'value' => 'Sign Up'
        ]));
    }

}
