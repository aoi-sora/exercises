<?php 
error_reporting(E_ERROR);
require_once 'InputForm.class.php';
 
$fields['name']['title'] = 'Name';
$fields['name']['type']  = 'text';
$fields['name']['check']  = 'required';

$fields['sex']['type']  = 'select';
$fields['sex']['list'] = array_combine(array( 'male','female'),array( 'Male','Female'));
$fields['sex']['blank_option'] = '&mdash; Please Select &mdash;';

$fields['zipcode']['type'] = 'text';
$fields['zipcode']['check'] = 'required|numeric';
$fields['zipcode']['value'] = 3020;

$fields['country']['type'] = 'select';
$fields['country']['list'] = array('ph' => 'Philippines', 'us' => 'United States of America', 'jp' => 'Japan', 'fr' => 'France');
$fields['country']['value'] = 'ph';
$fields['country']['blank_option'] = '&mdash; Select Country &mdash;';

$fieldAttribs =  array('name'      => array('extra_attribute' => '1', 'size' => '50'),
                       'zipcode'   => array('extra_attribute' => '2', 'size' => '6', 'maxlength' => '6')
                       );
$form = new InputForm($fields, $fieldAttribs);
$form->run();
 

echo $form->getHtml();

 // debug($form);

 
 function debug($testvar,$text='',$printvar=1) {
    if($printvar) {
        echo '<pre>',print_r($testvar, true), '</pre>';
    }
    
    if($testvar) {
         echo '<pre>',print_r($text, true), '</pre>';
    }
 }
 
 ?>