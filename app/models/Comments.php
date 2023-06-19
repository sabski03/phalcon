<?php
declare(strict_types=1);

namespace PSA\Models;

use Phalcon\Mvc\Model;
use Phalcon\Security;



class Comments extends Model{

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $comment_type;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var integer
     */
    public $name;

}