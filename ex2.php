<?php

function test()
{
  $c = new stdclass;
  $c->firstname = 'jenneth';
  $c->lastname = 'lee';
  
  return $c;
}

$b = test();
$a=&$b;
var_dump($b);
echo '<br/>';
$a->lastname = 'menina';
var_dump($a);
echo '<br/>';
var_dump($b);
echo '<br/>';
echo '<br/>','demo of variable variable<br/>';

$a = 'hello';
$$a = 'world';

echo '$a = \'hello\';','<br/>','$$a = \'world\';','<br/>Produces : <br/>', 'echo "$a ${a}"; <br/>', "$a ${$a}";


echo '<br/> sample magic constant __FILE__ : <br/>', __FILE__ , '<br/>demo of do-while... logic construct <br/>';

$i = 0;
do {
  echo "{$i}",'<br/>';
} while($i != 0);





?>
