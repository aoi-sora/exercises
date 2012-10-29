<?php
print_r(reverse('jenneth'));

function reverse($str)
{
  $n = strlen($str);
  if($n == 1)
  {
    return $str;
  }
  else
  {
    $n--;
    return reverse(substr($str,1, $n)) . substr($str, 0, 1);
  }
}
?>
