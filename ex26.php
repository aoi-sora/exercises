<?php
$testvar = 'jenneth';
$x =<<<'EOD'
"<pre>
  '$testvar' 
</pre>"
EOD;

eval("print_r( ". $x ." );");
?>