<?php
$db = new mysqli('localhost','root','','it-review');
$result = $db->query('select * from `my_books`');
dbug($result);

while($r = $result->fetch_object()) {
echo $r->book_title,'<br/>';
//dbug($r);

}
$result->close();


function dbug($obj) {
  echo '<pre>', print_r($obj,true), '</pre>';
}
?>
