<?php
declare(strict_types=1);

namespace PSA\Models;

use Phalcon\Mvc\Model;
use Phalcon\Security;
use Phalcon\Validation;

class Transferred extends Model{

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $operator;

    /**
     * @var string
     */
    public $reason;

    /**
     * @var integer
     */
    public $user_id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var numeric
     */
    public $phone;

    public function initialize(){
        $this->belongsTo('user_id', Users::class, 'id',[
            'alias' => 'transferUser',
            'reusable' => true,
        ]);
    }
}