<?php

declare(strict_types=1);

namespace PSA\Models;

use Phalcon\Mvc\Model;
use Phalcon\Security;

class TaskComments extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var integer
     */
    public $userID;

    /**
     * @var integer
     */
    public $taskID;

    public function initialize(){
        $this->belongsTo('userID', Users::class, 'id', [
            'alias' => 'users',
            'reusable' => true,
        ]);

        $this->belongsTo('taskID', Tasks::class, 'id' , [
            'alias' => 'task',
            'reusable' => true,
            ]);
    }



}