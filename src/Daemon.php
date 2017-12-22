<?php

namespace TradeFace\PhpDeamon;

class Daemon {

    const sleep    	 = 5;

    protected $config    = [];

    public function __construct(array $config, $class) {

        $this->pidfile = '/var/run/'.basename(get_class($class), '.php').'.pid';
       
        $this->config = $config;
        $this->uid = $this->config['uid'];
        $this->gid = $this->config['gid'];
        $this->class = $class;
        $this->classname = get_class($class);
       
        $this->signal();
    }

    public function signal(){

        pcntl_signal(SIGHUP,  function($signo) /*use ()*/{
            printf("The process has been reload.\n");
            Signal::set($signo);
        });

    }

    private function daemon(){

        if (file_exists($this->pidfile)) {
            echo "The file $this->pidfile exists.\n";
            exit();
        }

        $pid = pcntl_fork();
        if ($pid == -1) {
             die('could not fork');
        } else if ($pid) {
            // we are the parent
            exit($pid);
        } else {
            file_put_contents($this->pidfile, getmypid());
            posix_setuid($this->uid);
            posix_setgid($this->gid);
            return(getmypid());
        }
    }

    private function run(){

    	$this->class->setConfig($this->config);//update config
        while(true){   
            $this->class->run();
        }
    }

    private function foreground(){

        $this->run();
    }

    private function start(){

        $pid = $this->daemon();
        for(;;){
            $this->run();
            sleep(self::sleep);
        }
    }

    private function stop(){

        if (file_exists($this->pidfile)) {
            $pid = file_get_contents($this->pidfile);
            posix_kill($pid, 9);
            unlink($this->pidfile);
        }
    }

    private function reload(){

        if (file_exists($this->pidfile)) {
            $pid = file_get_contents($this->pidfile);
            posix_kill($pid, SIGHUP);
        }
    }   

    private function status(){

        if (file_exists($this->pidfile)) {
            $pid = file_get_contents($this->pidfile);
            system(sprintf("ps ax | grep %s | grep -v grep", $pid));
        }
    }

    private function help($proc){

        printf("%s start | stop | restart | status | foreground | help \n", $proc);
    }

    public function main($argv){

        if(count($argv) < 2){
            $this->help($argv[0]);
            printf("please input help parameter\n");
            exit();
        }
        if($argv[1] === 'stop'){
            $this->stop();
        }else if($argv[1] === 'start'){
            $this->start();
        }else if($argv[1] === 'restart'){
            $this->stop();
            $this->start();
        }else if($argv[1] === 'status'){
            $this->status();
        }else if($argv[1] === 'foreground'){
            $this->foreground();
        }else if($argv[1] === 'reload'){
            $this->reload();
        }else{
            $this->help($argv[0]);
        }
    }
}

