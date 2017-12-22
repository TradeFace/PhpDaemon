<?php

namespace TradeFace\PhpDaemon;

class Main{

    private $cycle = 1000000;
    
    public function __construct(array $config = []){

        if (sizeof($config) == 0) return;
        if (!isset($config['cycle'])) return;
        $this->cycle = $config['cycle'];
    }

	public function setCycle(int $cycle){

		$this->cycle = $cycle;
	}

    public function run(){

        while(true){
            pcntl_signal_dispatch();
            $this->main();
            usleep($this->cycle);
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