<?php

namespace Dor\Table;

use Dor\Util\AbstractTable;

class {{name}} extends AbstractTable
{
    public function up()
    {
        $this->schema->create(
            '{{name | lower}}',
            function ($table){
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
            }
        );
    }

    public function down()
    {
        $this->schema->drop('{{name | lower}}');
    }
}