<?php

namespace SSUpgrader\Apps;

use SSUpgrader\Cmds\CleanFiles;
use SSUpgrader\Cmds\ModifyCode;
use SSUpgrader\Cmds\SplitClasses;
use SSUpgrader\Cmds\AllAtOnce;
use SSUpgrader\Cmds\UpgradeSS;
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
