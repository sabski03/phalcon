<?php

declare(strict_types=1);

namespace PSA\Models;

use Phalcon\Mvc\Model;
use Phalcon\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Tasks extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $departments_task;

    /**
     * @var integer
     */
    public $departmentID;

    /**
     * @var integer
     */
    public $userID;

    public function initialize(){
        $this->belongsTo('departmentID', Departments::class, 'id', [
            'alias' => 'name',
            'reusable' => true,
        ]);


        $this->belongsTo('userID', Users::class, 'id', [
            'alias' => 'users',
            'reusable' => true,
        ]);

    }


}

?>