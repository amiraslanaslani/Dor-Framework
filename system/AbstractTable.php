<?php
/**
 * User: Amir Aslan Aslani
 * Date: 6/4/18
 * Time: 6:57 PM
 */

namespace Dor\Util;


use Illuminate\Database\Capsule\Manager;

abstract class AbstractTable
{
    public $schema;

    public function __construct(Manager &$manager)
    {
        $this->schema = $manager::schema();
    }

    public function nothing(){ /*  :D  */ }

    abstract public function up();
    abstract public function down();
}