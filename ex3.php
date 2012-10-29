<?php
error_reporting(E_ERROR);
//echo 'sample using return statement for returning a reference from a function<br/>';
echo '<br/>Using heredoc with double quoted identifier uses actual value of pre existing variable within heredoc scope $c is 143<br/>';

$c=143;
$str = <<<"EOD"
function &returns_reference()
{
    echo {$c};
    return $someref;
}

$newref =& returns_reference();
EOD;

echo $str;

echo '<br><br/>','Using heredoc with single quoted identifier to print literals with exception to evaluating the pre existing variables within heredoc scope<br/>';


$str = <<<'EOD'
function &returns_reference()
{
    echo {$c};
    return $someref;
}

$newref =& returns_reference();
EOD;

echo $str;


echo '<br/><br/>', 'sample using return statement for returning a reference from a function<br/>';

$str = <<<'EOD'
function &returns_reference()
{
    return $someref;
}

$newref =& returns_reference();

EOD;

echo $str;


echo '<br/><br/>', 'and another one : sample using return statement for returning a reference from a function<br/>';

$str = <<<'EOD'
function &test()
{
    $c = new stdclass;
    $c->firstname = 'jenneth';
    $c->lastname = 'menina';
    return $c;
}

$newref =& test();

EOD;

echo $str;
function &test()
{
    $c = new stdclass;
    $c->firstname = 'jenneth';
    $c->lastname = 'ho';
    return $c;
}

$newref =& test();
echo '<br/>';
var_dump($newref);

$newref->lastname = 'menina';
// unchanged value from $c of test()
print_r(test());

echo '<br/>';
print_r($newref);

echo '<br/><br/>','Variable Function Demo','<br/><br/>';

function test2($arg)
{
  echo $arg;
}

$b = 'test2';

echo $b('my argument for Variable Function Demo');
?>
