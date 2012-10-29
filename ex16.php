<?php
print_r(permutation('xyz', 1, 3));
function permutation($s, $k, $n)
{
  if($k == $n) {
    echo "{$s}<br/>";
    return;
  }
  
  $s2 = $s;
  $i = $k;
  while($i <= $n) {
    $c = $s[($i-1)];
    $s[($i-1)] = $s[($k-1)];
    $s[($k-1)] = $c;
    permutation($s, $k+1, $n);
    $s = $s2;
    ++$i;  
  }
  
}
?>
