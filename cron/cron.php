<?php

/*
|--------------------------------------------------------------
| CRON JOB BOOTSTRAPPER
|--------------------------------------------------------------
|
| By Jonathon Hill (http://jonathonhill.net)
| CodeIgniter forum member "compwright" (http://codeigniter.com/forums/member/60942/)
|
| Created 08/19/2008
| Version 1.2 (last updated 12/25/2008)
|
|
| PURPOSE
| -------------------------------------------------------------
| This script is designed to enable CodeIgniter controllers and functions to be easily called from the command line on UNIX/Linux systems.
|
|
| SETUP
| -------------------------------------------------------------
| 1) Place this file somewhere outside your web server's document root
| 2) Set the CRON_CI_INDEX constant to the location of your CodeIgniter index.php file
| 3) Make this file executable (chmod a+x cron.php)
| 4) You can then use this file to call any controller function:
|    ./cron.php --run=/controller/method [--show-output] [--log-file=logfile] [--time-limit=N] [--server=http_server_name]
|
|
| OPTIONS
| -------------------------------------------------------------
|   --run=/controller/method   Required   The controller and method you want to run.
|   --show-output              Optional   Display CodeIgniter's output on the console (default: don't display)
|   --log-file=logfile         Optional   Log the date/time this was run, along with CodeIgniter's output
|   --time-limit=N             Optional   Stop running after N seconds (default=0, no time limit)
|   --server=http_server_name  Optional   Set the $_SERVER['SERVER_NAME'] system variable (useful if your application needs to know what the server name is)
|
|
| NOTE: Do not load any authentication or session libraries in controllers you want to run via cron. If you do, they probably won't run right.
|
|
| Contributions:
| -------------------------------------------------------------
|    "BDT" (http://codeigniter.com/forums/member/46597/) -- Fix for undefined constant CRON_FLUSH_BUFFERS error if the --show-output switch is not set (11/17/2008)
|    "electromute" (http://codeigniter.com/forums/member/71433/) -- Idea for [--server] commandline option (12/25/2008)
|
*/
/**
 * get execution time in seconds at current point of call in seconds
 * @return float Execution time at this point of call
 */
    function get_execution_time()
    {
        static $microtime_start = null;
        if($microtime_start === null)
        {
            $microtime_start = microtime(true);
            return 0.0; 
        }    
        return microtime(true) - $microtime_start; 
    }
    get_execution_time();

    define('CRON_CI_INDEX', realpath('./../index.php'));   // Your CodeIgniter main index.php file
    define('CRON', TRUE);   // Test for this in your controllers if you only want them accessible via cron

# Parse the command line
    $script = array_shift($argv);
    $cmdline = implode(' ', $argv);
    $usage = "Usage: cron.php --run=/controller/method [--show-output][-S] [--log-file=logfile] [--time-limit=N] [--server=http_server_name]\n\n";
    $required = array('--run' => FALSE);
    foreach($argv as $arg)
    {
        list($param, $value) = explode('=', $arg) + Array(null, null);
        switch($param)
        {
            case '--run':
                // Simulate an HTTP request
                $_SERVER['PATH_INFO'] = $value;
                $_SERVER['REQUEST_URI'] = $value;
                $required['--run'] = TRUE;
                break;

            case '-S':
            case '--show-output':
                define('CRON_FLUSH_BUFFERS', TRUE);
                break;

            case '--log-file':
                if(is_writable($value)) define('CRON_LOG', $value);
                else die("Logfile $value does not exist or is not writable!\n\n");
                break;

            case '--time-limit':
                define('CRON_TIME_LIMIT', $value);
                break;
                
            case '--server':
                $_SERVER['SERVER_NAME'] = $value;
                break;

            default:
                die($usage);
        }
    }

    if(!defined('CRON_LOG')) define('CRON_LOG', './application/logs/cron.log');
    if(!defined('CRON_TIME_LIMIT')) define('CRON_TIME_LIMIT', 0);

    foreach($required as $arg => $present)
    {
        if(!$present) die($usage);
    }


# Run CI and capture the output
    ob_start();

    chdir(dirname(CRON_CI_INDEX));
    require(CRON_CI_INDEX);           // Main CI index.php file
    $output = ob_get_contents();
    
# Set run time limit
    set_time_limit(CRON_TIME_LIMIT);
	
    if(defined('CRON_FLUSH_BUFFERS')) {
        echo ob_get_flush();
    } else {
        ob_end_clean();
    }


# Log the results of this run
    if(trim($output) != '') {
        error_log("### ".date('Y-m-d H:i:s')." cron.php $cmdline\n", 3, CRON_LOG);
        error_log($output, 3, CRON_LOG);
        error_log("\nMemory peak: " . memory_get_peak_usage(true) / 1024 . " KB\n", 3, CRON_LOG);
        error_log('Time working: ' . round(get_execution_time(), 3) . " s\n", 3, CRON_LOG);
        error_log("\n### \n\n", 3, CRON_LOG);
    }

echo "\n\n";
