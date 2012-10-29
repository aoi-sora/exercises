<?php
$handle = fopen('http://localhost/exercises/','r');
while($ln = fgets($handle)) {
echo $ln,'<br/>';

}
fclose($handle);
?>
