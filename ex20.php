<?php
 $num = cal_days_in_month(CAL_GREGORIAN, 8, 2003); // 31
 debug($num, "there were {$num} days in August 2003");
 
 
 function debug($testvar,$text='',$printvar=1) {
    if($printvar) {
/*$x =<<<'EOD'
<pre>
echo $testvar;
</pre>
EOD;
*/
        echo '<pre>',print_r($testvar, true), '</pre>';

 
    }
    
    if($testvar) {
         echo '<pre>',print_r($text, true), '</pre>';
    }
 }
?> 