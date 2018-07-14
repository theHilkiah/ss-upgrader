<?php

namespace TheHilkiah\SSUpgrader\Cmds;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
// use Symfony\Component\Console\Command\Command as SymCommand;

class AllAtOnce extends Command {

    const DS = DIRECTORY_SEPARATOR;

    protected function configure()
    {
        $this->setName('all-at-once')
             ->setDescription('Aggregate all the commands required to upgrade one project.')
             ->setHelp('This command allows you to runn all other commands required to upgrade the entire project...')
             ->addArgument('namespace', InputArgument::REQUIRED, 'The namespace to add to the classes')
             ->addArgument('path', InputArgument::REQUIRED, 'The path of the file/folder to split');

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->argument = $this->input->getArgument('path');
        $this->namespace = $this->input->getArgument('namespace');
        $this->path = realpath($this->argument);

        $this->RunCommands($this->WorkOnFile($this->path));
    }

    public function WorkOnFile($path)
    {
        return ['split-classes', 'modify-code', 'clean-files', 'upgrade'];
    }

    private function RunCommands($commands)
    {

        foreach ($commands as $cmd ) {
            $params = [ 'command' => $cmd, 'path' => $this->path ];
            if($cmd == 'upgrade') $params['namespace'] =$this->namespace;
            $this->executeCommand($cmd, new ArrayInput($params), $this->output);
        }
    }

    private function executeCommand(string $cmd, ArrayInput $arguments, OutputInterface $output)
    {
        return $this->getApplication()->find($cmd)->run($arguments, $output);
    }
}

