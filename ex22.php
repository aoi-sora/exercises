<?php
$mysqli = new mysqli("localhost", "root", "" );

$mysqli->select_db('demos_greenorange');

$r = $mysqli->query('select * from wp_posts');

debug($mysqli->affected_rows);

while($p = $r->fetch_object() ) {
  debug($p);  
}

 function debug($testvar,$text='',$printvar=1) {
    if($printvar){
        echo '<pre>',print_r($testvar, true), '</pre>';

 
    }
    
    if($testvar) {
         echo '<pre>',print_r($text, true), '</pre>';
    }
 }
?>
<img src="ex23.php?text=jenneth"/>