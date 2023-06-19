<?php
declare(strict_types=1);

namespace PSA\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;


class CommentsForm extends Form
{

    public function initialize(){
        $comment_type = new Text('comment_type');

        $comment_type->addValidators([
            new PresenceOf([
                'message' => 'Comment Type Is Required!!',
            ]),
        ]);

        $this->add($comment_type);

        $comment = new Text('comment', [
            'placeholder' => 'fill this up',
        ]);

        $this->add($comment);


    }
}