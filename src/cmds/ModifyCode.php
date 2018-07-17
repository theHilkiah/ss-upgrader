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
        $data = preg_replace_callback_array($regex_n_callback = [
            '/([a-z]+\s+static|static)\s+(\$(.+?))\s+=/mi' => function($match){
                //'modifying static variables to private'
                return "\n\tprivate static \$$match[2]";
            },
            '/(class\s+(.+?)\s+extends\s+(?:.+?))\s*\{/mi' => function($match){
                if(stripos($match[2], 'Controller') !== false) return $match[0];
                return "$match[1]\n\n\tprivate static \$table_name = '$match[2]';";
            },
            '/can(.+?)\(\$member\s+=\s+(null|NULL)\s*\)/mi' => function($match){
                //'modifying static variables to private'
                return 'can'.$match[1].'($member=NULL, $context = [])';
            },
            '/(?:array\s*)\(([^{}]*)\)/msi' => function($match){
                //"ShortHand for arrays"
                return "[$match[1]]";
            }
        ], $data);

        // foreach ($regex_n_callback as $regex => $callback) {
        //     $data = preg_replace_callback($regex, $callback, $data);
        // }
        return $data;
    }

    private function searchNreplace($file, $data)
    {
        $tableName = pathinfo($file, PATHINFO_FILENAME);
        if(stripos($tableName, 'Controller') !== false) return $data;

        $search_n_replace = [
            '/(private static \$db\s*=)/msi' => function($m){
                return 'private static $table_name = "'.$tableName."\";\n\t$m[1]";
            }
        ];
        foreach ($search_n_replace as $search => $replace) {
            $data = preg_replace($search, $replace, $data);
        }
        return $data;
    }


}
