#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';


$config = [
    'uid' => 1000,
    'gid' => 1000
];

use TradeFace\PhpDaemon\Daemon;
use TradeFace\PhpDaemon\Main;

class App extends Main{

    public function __construct(){

        $this->setConfig(['cycle' => 1000000]);
    }

    public function main(){

        echo 'Next'.PHP_EOL;
    }
}


$daemon = new Daemon($config, new App());
$daemon->main($argv);