<?php

namespace TheHilkiah\SSUpgrader\Cmds;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
// use Symfony\Component\Console\Command\Command as SymCommand;

class ModifyCode extends Command {

    const DS = DIRECTORY_SEPARATOR;

    public function configure()
    {
        $this->setName('modify-code')
             ->setDescription('Modifies current code to be compatible with latest framework')
             ->setHelp('This command allows you to upgrade legacy code to match new code...')
             ->addArgument('path', InputArgument::REQUIRED, 'The path of the file/folder to modify');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->argument = $this->input->getArgument('path');
        $this->path = realpath($this->argument);

        $this->ModifyCode($this->path);
    }

    private function ModifyCode($path)
    {

        if(!($path = realpath($path))){
            return  $this->output->writeLn("'$in' is not a valid path");
        }
        $this->output = $this->workOnPath($path, $this->output);
        $this->output->writeLn([
            "DONE!"
        ]);
        return $this->output;
    }

    protected function workOnFile($file)
    {
        if(strpos($file, '_originals_') !== false)
            return $this->output;

        $data = file_get_contents($file);

        $this->output->writeLn(["$file"]);

        $data = $this->callbackReplace($file, $data);

        $data = $this->searchNreplace($file, $data);

        return $this->output;
    }

    private function callbackReplace($file, $data)
    {
        $regex_n_callback = [
            '/([a-z]+\s+static|static)\s+(\$(.+?))\s+=/mi' => function($match){
                //'modifying static variables to private'
                return "\n\tprivate static \$$match[2]";
            },
            '/(private\s+static\s+\$db\s+=)/mi' => function($match) use($file){
                $table_name = str_replace('.php', '', pathinfo($file, PATHINFO_BASENAME));
                return "\n\tprivate static \$table_name = '$table_name';\n\t$match[1]";
            },
            '/can(.+?)\(\$member\s+=\s+(null|NULL)\s*\)/mi' => function($match){
                //'modifying static variables to private'
                return 'can'.$match[1].'($member=NULL, $context = [])';
            },
            '/(?:array\s*)\(([^{}]*)\)/msi' => function($match){
                //"ShortHand for arrays"
                return "[$match[1]]";
            }
        ];

        foreach ($regex_n_callback as $regex => $callback) {
            $data = preg_replace_callback($regex, $callback, $data);
        }
        return $data;
    }

    private function searchNreplace($file, $data)
    {

        $search_n_replace = [
            'function Links()' => 'function Links($value = NULL)',
            'function Link()' => 'function Link($value = NULL)'
        ];
        foreach ($search_n_replace as $search => $replace) {
            $data = str_irreplace($search, $replace, $data);
        }
        return $data;
    }


}
