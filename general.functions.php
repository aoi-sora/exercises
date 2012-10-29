<?php
 function debug($testvar,$text='',$printvar=1) {
    if($printvar) {
        echo '<pre>',print_r($testvar, true), '</pre>';
    }
    
    if($testvar && !empty($text)) {
         echo '<pre>',print_r($text, true), '</pre>';
    }
 }
?>