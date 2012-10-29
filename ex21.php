<?php
$date = new DateTime('2012-09-20');
$date->add(new DateInterval('P10D'));
echo $date->format('Y-m-d') . "<br/>";


$date = new DateTime('2000-01-01');
$date->add(new DateInterval('PT10H30S'));
debug( $date->format('Y-m-d H:i:s')) ;

$date = new DateTime('2000-01-01');
$date->add(new DateInterval('P7Y5M4DT4H3M2S'));
debug($date->format('Y-m-d H:i:s') );

$date = new DateTime('2000-12-31');
$interval = new DateInterval('P1M');

$date->add($interval);
debug($date->format('Y-m-d') );

$date->add($interval);
debug( $date->format('Y-m-d'));

$date = DateTime::createFromFormat('j-M-Y', '15-Feb-2009');
debug( $date->format('Y-m-d'));

 


$date = new DateTime();
debug($date->getTimezone()->getName());
  
 function debug($testvar,$text='',$printvar=1) {
    if($printvar){
        echo '<pre>',print_r($testvar, true), '</pre>';

 
    }
    
    if($testvar) {
         echo '<pre>',print_r($text, true), '</pre>';
    }
 }
?> 