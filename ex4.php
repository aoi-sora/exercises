<?php
function __autoload($class_name) {
    include $class_name . '.php';
}
echo 'Using __autoload to load the class files which aren\'t defined yet.','<br/>';
$obj  = new MyClass1();
echo '<br>';
var_dump($obj); 

?>
