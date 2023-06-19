<?php

declare(strict_types=1);

namespace PSA\Models;

use Phalcon\Mvc\Model;
use Phalcon\Security;
use Phalcon\Validation;

class Departments extends Model
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var integer
     */
    public $departmentid;
    /**
     * @var string
     */
    public $department;
}