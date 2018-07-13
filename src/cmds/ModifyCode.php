<?php

namespace SSUpgrader\Cmds;
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
        if(strpos($file, '_originals_') !== false) return $this->output;

        $data = file_get_contents($file);

        $search_n_replace = [
            '/(\b(.*? )([a-z]+ )|static\s+)\$(.+?)\=/mi' => function($match){
                //'modifying static variables to private'
                return "\n\tprivate static \$$match[4]=";
            },
            '/can(.+?)\(\$member\s+=\s+(null|NULL)\s*\)/mi' => function($match){
                //'modifying static variables to private'
                return 'can'.$match[1].'($member=NULL, $context = [])';
            },
            '/\barray\s*\((\s*(.*?)\s*)\)/msi' => function($match){
                //"ShortHand for arrays"
                return "[$match[1]]";
            },
            '/^(class\s+(.+?)\s+extends\s+(?:.+?))(\s*private\s+static\s+\$db\s*=\s*)/msi' => function($match){
                //'Adding db table_name to classes',
                if(stripos($match[1], 'Controller') !== false) return $match[0];
                return "$match[1]\n\n\tprivate static \$table_name = \"$match[2]\";\n$match[3]";
            }
        ];
        

        $this->output->writeLn(["$file"]);
        $data = preg_replace_callback_array($search_n_replace, $data);
        $data = file_put_contents($file, $data);
        return $this->output;
    }


}
