<?php
declare(strict_types=1);

namespace PSA\Models;

use Phalcon\Mvc\Model;
use Phalcon\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class News extends Model{

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $new_header;

    /**
     * @var integer
     */
    public $usersID;

    /**
     * @var string
     */
    public $new_post;

    public function validation(){
        $validator = new Validation();

        $validator->add(
            'new_header',
            new Uniqueness(
                [
                    'message' => 'The Title Should Be Unique',
                ]
            )
        );
        return $this->validate($validator);



    }

    public function initialize(){
        $this->belongsTo('usersID', Users::class, 'id', [
            'alias' => 'username',
            'reusable' => true,
        ]);

    }



}