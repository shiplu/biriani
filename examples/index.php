<?php

require_once "Console/Table.php";
require_once '../bootstrap.php';

$Table  = new Console_Table();
$Table->setHeaders(array('Script name', 'Execution time in seconds'));

function utime() { 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec);
}

function sort_by_modification($a, $b){
    return filemtime($a)-filemtime($b);
}

// get all the php files in current directory
$files = glob("*.php");

// sort them by file modification date
usort($files , 'sort_by_modification');

// get the most recent modified file.
$taget_file = array_pop($files);


// invoke it.
$___start = utime();
require_once $taget_file;
$___end = utime();

$___execution_time = $___end - $___start;
$Table->addRow(array($taget_file, number_format($___execution_time,3,'.',',')));

echo PHP_EOL, $Table->getTable(), PHP_EOL;
?>
