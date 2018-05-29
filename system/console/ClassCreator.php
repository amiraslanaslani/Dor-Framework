<?php
/**
 * User: Amir Aslan Aslani
 * Date: 5/29/18
 * Time: 4:49 AM
 */

namespace Dor\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ClassCreator extends Command
{
    private $directory;
    private $fileName;
    private $classType;
    private $classTypeText;

    protected function setDirectory(string $dir){
        $this->directory = $dir;
        return $this;
    }

    protected function setFileName(string $fn){
        $this->fileName = $fn;
        return $this;
    }

    protected function setClassType(string $ct){
        $this->classType = $ct;
        $this->classTypeText = ucfirst($ct);
        return $this;
    }

    protected function configure()
    {
        $this   ->setName($this->classType . ":add")
                ->setDescription("Add new {$this->classType}.")
                ->setHelp("This command create new {$this->classType} file.")
                ->addArgument("name", InputArgument::REQUIRED, "{$this->classTypeText} name");

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loader = new \Twig_Loader_Filesystem('system/console/templates/');
        $twig = new \Twig_Environment($loader);
        $className = $input->getArgument("name") . $this->classTypeText;
        $content = $twig->render(
            $this->fileName,
            [
                'name' => $className
            ]
        );

        try{
            $filePath = __BASE_ROOT__ . "{$this->directory}/{$className}.php";
            if(file_exists($filePath)){
                throw new \Exception("This class is exists!");
            }

            $controllerFile = fopen($filePath, "w");
            if(! $controllerFile){
                throw new \Exception("There is a problem in creating file!");
            }
            fwrite($controllerFile, $content);
            fclose($controllerFile);
            $output->writeln($this->classTypeText . " is created successfully! ;D ");
        }
        catch (\Exception $e){
            $output->writeln($e->getMessage());
        }

    }
}