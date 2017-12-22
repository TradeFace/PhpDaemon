<?php

namespace TradeFace\PhpDaemon;

class Main{

    private $config = [];
    
    public function __construct(array $config = []){

        if (sizeof($config) == 0) return;
        if (!isset($config['cycle'])) return;
        $this->config['cycle'] = $config['cycle'];
    }

	public function setConfig(array $config){

		$this->config = $config;
	}

    public function run(){

        while(true){
            pcntl_signal_dispatch();
            $this->main();
            usleep($this->config['cycle']);
            if(Signal::get() == SIGHUP){
                Signal::reset();
                break;
            }
        }
        printf("\n");
    }

    //main process hook to be overwritten
    protected function main(){

	}
}