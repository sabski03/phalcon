<?php
declare(strict_types=1);

namespace PSA\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;



class NewsForm extends Form
{

    public function initialize()
    {
        $new_header = new Text('new_header', [
            'placeholder' => 'new_header',
        ]);

        $new_header->addValidators([
            new PresenceOf([
                'message' => 'The Title Needs A Header!',
            ]),
        ]);

        $this->add($new_header);

        $new_post = new Text('new_post', [
            'placeholder' => 'new_post',
        ]);

        $new_post->addValidators([
            new PresenceOf([
                'message' => 'This Field Cannot Be Empty!',
            ]),
        ]);

        $this->add($new_post);


    }

}