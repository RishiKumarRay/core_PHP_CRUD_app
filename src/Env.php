<?php

namespace App;

class Env {

    public static function isRunningInDocker(): bool {
        $processStack = explode(PHP_EOL, shell_exec('cat /proc/self/cgroup | grep docker'));
        $processStack = array_filter($processStack); 
        return count($processStack) > 0;
    }

}