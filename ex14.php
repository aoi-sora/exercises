<?php
print_r(fibonacci(4));
function fibonacci($n)
{
  if($n==1)
    return 0;
  elseif($n==2)
    return 1;
  else
    return fibonacci($n-1) + fibonacci($n-2);
}
?>
