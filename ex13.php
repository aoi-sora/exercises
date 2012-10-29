<?php

print_r(IsPrime(11));
function IsPrime($n)
{
  for($i=$n-1;$i > 1; --$i)
  {
    if($n %$i ==0)
    {
      return false;
    }
  }
  return true;
}

?>
