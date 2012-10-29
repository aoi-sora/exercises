<?php
error_reporting(E_ERROR);
 require_once 'TableObject.class.php';
 require_once 'TableInputForm.class.php';
 
 
class myForm extends TableInputForm  {
 
  function __construct( $table, $params=array(), $lookupInfo=array()) {
   parent::__construct($table, $params , $lookupInfo );
  }
    /**
     *
     * customize field helper before rendering final  fields
     **/
    function beforeRender($name) {
     // $this->fields['is_local']['type'] = 'boolean-checkbox';
    
    }
    
    
} 
 
$db = new mysqli('localhost','root','');
$db->select_db('mvc_boilerplate');
 
 
 
 function debug($testvar,$text='',$printvar=1) {
    if($printvar) {
        echo '<pre>',print_r($testvar, true), '</pre>';

 
    }
    
    if($testvar) {
         echo '<pre>',print_r($text, true), '</pre>';
    }
 }
 
 ?>
 
 <html>
  <head>
        <meta charset="UTF-8" />
        <style>
          form label {
            display:inline-block;
            padding:0 15px 0  0;
            width:150px;
            vertical-align: top;
            text-align: right;
          }
        </style>
        <script src="js/jquery-1.8.0.min.js"></script>
        <script>
          (function($){
            $(document).ready(function(){
              $('form').live('submit',function() {
                $.each($('form [multiple]').find(":selected")  , function(index, val) {
 
                   var name = $(this).parent().attr('name') + '_set[]'
                   $(this).parent().after('<input type="hidden" value="'+ $(this).attr('value') +'" name="'+name+'"/>') 
                                                                                                   
                                                                                                    
                  
                });
                
                
                // return false;
               })
             
             })
           
           })(jQuery);
        </script>
  </head>
  <body>
   <?php
     $table = new TableObject('guestbook',$db);
     
      //debug($table->getFields());
     $form = new myForm($table );
     
     $form->run();
     
     echo $form->getHtml();
     
    //  debug($_SERVER);   
   ?>
  </body>
 </html>