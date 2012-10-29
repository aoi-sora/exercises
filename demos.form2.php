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
  $form->getHtml();

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
 <style>
    td.title {
       padding:0 10px 0 0;
       width:100px;
       text-align:right;
       background:#eeeeee;
       
    }
 </style>
 <?php
   echo $form->getAllErrorsHtml();
 ?>
 <table>
   <?php echo $form->openFormTag();?>
   <tr>
      <td class="title"><?php echo $form->fieldsTitleHtml['name']; ?></td>
      <td>  <?php echo $form->fieldsHtml['name']; ?>
            <?php echo $form->errorHtml['name']; ?>
      </td>
      <td width="200"></td>
      <td  class="title"><?php echo $form->fieldsTitleHtml['sex']; ?></td>
      <td>  <?php echo $form->fieldsHtml['sex']; ?>
            <?php echo $form->errorHtml['sex']; ?>
      </td>
   </tr>
   <tr>
      <td  class="title"><?php echo $form->fieldsTitleHtml['zipcode']; ?></td>
      <td>  <?php echo $form->fieldsHtml['zipcode']; ?>
            <?php echo $form->errorHtml['zipcode']; ?>
      </td>
      <td width="200"></td>
      <td  class="title"><?php echo $form->fieldsTitleHtml['country']; ?></td>
      <td>  <?php echo $form->fieldsHtml['country']; ?>
            <?php echo $form->errorHtml['country']; ?>
      </td>
   </tr>
   <tr><td align="center" colspan="4"><?php echo $form->buttonsHtml();?></td></tr>
   </form>
 </table>