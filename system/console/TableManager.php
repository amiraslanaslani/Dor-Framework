<?php
/**
 * User: Amir Aslan Aslani
 * Date: 6/4/18
 * Time: 7:18 PM
 */

namespace Dor\Console\Command;

use Illuminate\Database\Capsule\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TableManager extends Command
{
    const   UP = 'up',
            DOWN = 'down';

    protected $method = 'nothing';

    protected function configure()
    {
        $this   ->setName("table:{$this->method}")
                ->setDescription("Up the table.")
                ->setHelp("This command create a new table.")
                ->addArgument("name", InputArgument::REQUIRED, "Table name");

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $config = include(__BASE_ROOT__ . 'config.php');
        if($config['database'] !== false) {
            $capsule = new Manager();
            $capsule->addConnection($config['database']);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            require_once __BASE_ROOT__ . 'system/AbstractTable.php';
            $className = $input->getArgument("name") . "Table";
            require_once(__BASE_ROOT__ . "src/tables/{$className}.php");
            $className = '\\Dor\\Table\\' . $className;
            require_once __BASE_ROOT__ . $config['system']['directories']['configs'] . '/illuminate.php';
            $table = new $className($capsule);
            $method = $this->method;
            $table->$method();
            echo 'Table\'s work is done database successfully in database! :)';
        }
        else{
            throw new \Exception("There is a problem in DB connection!");
        }
    }
}