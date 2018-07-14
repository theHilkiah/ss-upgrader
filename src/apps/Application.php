<?php

namespace TheHilkiah\SSUpgrader\Apps;

use TheHilkiah\SSUpgrader\Cmds\CleanFiles;
use TheHilkiah\SSUpgrader\Cmds\ModifyCode;
use TheHilkiah\SSUpgrader\Cmds\SplitClasses;
use TheHilkiah\SSUpgrader\Cmds\AllAtOnce;
use TheHilkiah\SSUpgrader\Cmds\UpgradeSS;
use Symfony\Component\Console\Application as SymApp;

class Application extends SymApp
{
  public $app = 'SkyWest ExpressJet Silverstripe Upgrader Tool';
  public $ver = ' - v1.0.0';
  public function __construct()
  {
    parent::__construct($this->app, $this->ver);
  }

public function getAllCommands()
  {
    return [
        CleanFiles::class,
        ModifyCode::class,
        SplitClasses::class,
        UpgradeSS::class,
        AllAtOnce::class
    ];
  }
}
