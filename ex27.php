<?php
 
 debug(is_date('2012-02-30'),'2012-02-30 is valid date');
  
 
check_numeric('123450');

check_numeric('1,y23450..00');

check_numeric('13450.00');

is_date("10/05/2012");

 
 
 
function check_numeric($data) {
 
// debug(preg_match_all("/\D/",$data,$p));
// debug($p);
 
 /* check for non digit, exempting . character for floating point and when it's occurence is > 1 */
 preg_match_all('/\D/',$data,$match);
 
 debug($match);
 $non_digits = implode('',$match[0]);
 debug($non_digits);
 
 $no_dec_point= str_replace(".", "", $non_digits);
 
 debug(!(substr_count($non_digits,".") > 1 || strlen($no_dec_point) > 0), $data. ' - valid number');
    
}

function is_date($data ) {
       $dt=explode("-", $data);
      
    return checkdate($dt[1],$dt[2], $dt[0]);
    
    
}

 function debug($testvar,$text='',$printvar=1) {
    if($printvar) {
        echo '<pre>',print_r($testvar, true), '</pre>';

 
    }
    
    if($testvar) {
         echo '<pre>',print_r($text, true), '</pre>';
    }
 }
 
 ?>