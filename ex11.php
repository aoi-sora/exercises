<?php

$a = array(5,4,3,2,1);
selection_sort($a);
print_r($a);

function selection_sort(&$a)
{
  for($i=0;$i<count($a);++$i)
  {
    $min = $i;
    for($j=$i+1;$j<count($a);++$j)
    {
      if($a[$j] < $a[$min] ) {
        $temp = $a[$min];
        $a[$min] = $a[$j];
        $a[$j] = $temp;
      }
    }
  }
}
?>
