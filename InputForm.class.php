<?php
/*********************************************************************************************************
 * InputForm by Jei Dela Fuente
 * September 24, 2012
 * MAGIC Goals
 *********************************************************************************************************/

require_once 'FieldsToHtml.class.php';

class InputForm {

    var $formHtml,
        $messageHtml,
        $messageText,
        $errorMessage,
        $errors,
        $errorHtml,
        $fields,
        $fieldsAttrib,
        $fieldsHtml,
        $fieldsTitle,
        $fieldsTitleHtml,
        $fieldset,
        $fieldsetHtml,
        $buttons,
        $buttonsAttrib,
        $buttonsHtml;
    
    /** validation callbacks **/    
    var $fieldChecks;
        
    /** form action **/
    var $action, $method;
    
    /** form generator set as protected **/
    protected $writer  ;
    
    /** onSucess callback **/
    var $onSuccess;
    /** onSubmit method and callback for sanitation **/
    var $onSubmit;    
        
    /**
     *
     * __construct(@fields)
     *
     * where : 
     *         @fields[@string name]['title'] = @string 
     *         @fields[@string name]['type'] = text | textarea | select | multi_select | hidden | password | checkbox | radio
     *         @fields[@string name]['check'] = numeric|email|required|date|integer|float|not_zero or  any of these combinations separated by |
     *         @fields[@string name]['list'] = @array
     *         @fields[@string name]['blank_option'] = @string
     *         
     **/
    function __construct($fields=array(), $attr=array(), $action='', $method='post') {

        $this->action = $action;
        $this->method = $method;
        
        $this->errors   = $this->errorHtml = $this->fieldsTitle = $this->fieldsTitleHtml = $this->fieldsAttrib
                        = $this->buttons  =  $this->buttonsAttrib = $this->buttonsHtml
                        = $this->fieldset = $this->fieldsetHtml = array();
        
        $this->messageText = 'Please correct the errors in form';
        $this->setMessageHtml($this->messageText );
        $this->addButton('submit', 'Submit', 'submit' );
        $this->addButton('reset', 'Reset', 'reset');
        $this->writer = new FieldsToHtml();
        $this->setFields($fields, $attr);
    }
    
    /**
     * void setFields(@fields)
     **/
    function setFields($fields=array(), $attr=array()) {
        foreach($fields as $k=>$v) {
            $this->fields[$k]           = $v;
            $this->fieldsAttrib[$k]     = $attr[$k];  
            $this->setFieldsTitle($k, $v['title']);
            $this->setTitleHtml($k);
        }
    }
    
    function setAttrib($name,$attr=array()) {
        if(is_array($name)) {
            foreach($name as $name=>$attr) {
                $this->fieldsAttrib[$k] = $attr;
            }
        } else {
            $this->fieldsAttrib[$name] = $attr;
        }
    }
    
    function setClass($name,$class) {        
        if(is_array($name)) {
            foreach($name as $name=>$attr) {
                $this->fieldsAttrib[$name]['class'] = $class;
            }
        } else {
           $this->fieldsAttrib[$name]['class'] = $class;
        }        
    }
    
    function getAttrib($name) {
        return $this->fieldsAttrib[$name] ;
    }
    
    function setFieldChecks($name,$callback) {
        if(is_array($name)) {
            foreach($name as $k=>$v) {
                $this->fieldChecks[$k] = $v;
            }
        } else {
            $this->fieldChecks[$name] = $callback;
        }
    }
    
    function fieldsToHtml() {
        
        foreach($this->fields as $name=>$field) {
            $this->convertField($name);
            $this->setTitleHtml($name);
            $this->fieldset[$name] = $this->getTitleHtml($name) . $this->fieldsHtml[$name] .  $this->getErrorHtml($name);
        }    
    }
    
    /**
     *
     *
     **/
    function convertField($name) {
            $f = $this->fields[$name];
            $attr = $this->fieldsAttrib[$name];
            $attr['value'] = $f['value'];
            $opts = $f['list'];
            switch($f['type']) {
                case 'date':
                    $attr['size'] = $attr['maxlength'] = 10;
                    $attr['class'] .= $attr['class'] . ' datepickerui';
                    $this->fieldsHtml[$name] = $this->writer->html_text($name,$attr);
                    // place image here for clickable datepicker of jquery ui
                break;
                case 'textarea':   
                    $this->fieldsHtml[$name] = $this->writer->html_textarea($name,$attr);
                break;
                case 'select':   
                    $this->fieldsHtml[$name] = $this->writer->html_select($name,$opts,$attr, $f['blank_option']);
                break;
                case 'multi-select':
                    $this->fieldsHtml[$name] = $this->writer->html_multi_select($name,$opts,$f['value'],$attr, $f['blank_option']);
                break;            
                case 'radio':
                    $this->fieldsHtml[$name] = $this->writer->html_radio($name,$opts,$f['value'],$attr);
                break;
                case 'checkbox':
                    $this->fieldsHtml[$name] = $this->writer->html_checkbox($name,$opts,$f['value'],$attr);
                break;
                case 'boolean-checkbox':
                    $this->fieldsHtml[$name] = $this->writer->html_boolean_checkbox($name,$attr);
                break;            
                case 'password':
                    $this->fieldsHtml[$name] = $this->writer->html_password($name,$attr);
                break;
                case 'hidden':
                    $this->fieldsHtml[$name] = $this->writer->html_hidden($name,$attr);
                break;            
                default:
                    $this->fieldsHtml[$name] = $this->writer->html_text($name,$attr);
                break;
                
            }        
    }
    
    function getFieldsHtmlArray($wrapper='fieldset') {
        $this->fieldsToHtml();
        foreach($this->fieldset as $name=>$field) {
            $this->fieldsetHtml[$name]  =
            "<{$wrapper}>
                {$this->fieldset[$name]}
            </{$wrapper}>";
        }    
    }
    
    
    function set($name,$value) {
        $this->fields[$name]['value'] = $value; 
    }
    
    function get($name,$value) {
        return $this->fields[$name]['value'];
    }
    
    /*************** ERROR Helper *********************/
    
    function setError($name, $errText) {
        $this->errors[$name] = $errText;
        $this->setErrorHtml($name);
    }
    
    function getError($name) {
        return $this->errors[$name];
    }
    
    function hasError() {
        return count($this->errors) > 0;
    }
    
    function is_error($name) {
        return  trim($this->getError($name) )!= '' ;
    }
    
    function setErrorHtml($name,$wrapper='<span>') {
        $error = $this->errors[$name];
        $this->errorHtml[$name] = "<span class=\"{$name}-error\">{$error}</span>"; 
    }
    
    function getErrorHtml($name) {
         return $this->errorHtml[$name];
    }
    
    function getAllErrorsHtml() {
        if($this->hasError()) {
            foreach($this->errorHtml as $v) {
                $err_items .= "\n\t<li>{$v}</li>";
                 
            }        
            $err_items =  "\n{$this->getMessageHtml()}\n<ul>{$err_items}\n</ul>";
        }
        return $err_items;
    }
    

    /**
     *
     * return array of html formatted errors
     *
     **/ 
    function getErrorHtmlArray() {
        return $this->errorHtml;
    }
    
 
    
    function setMessageHtml($messageText='', $wrapper='<span>') {        
        $this->messageText = $messageText;
        $this->messageHtml = '<span class="form-alert">'.$this->messageText . '</span>';    
    }
    
    function getMessageHtml() {
        return $this->messageHtml;
    }
    
    /******************* Fields Caption Helper **********************/
    function getFieldsTitle($name) {
        return  $this->fieldsTitle[$name];    
    }
    
    function setFieldsTitle($name,$title='') {
        if(is_array($name)) {
            foreach($name as $k=>$v) {
                $title = $v;
                if(empty($title)) {
                    $title = ucwords(str_replace('_',' ', $k));
                }
                
                $this->fieldsTitle[$name] = $title;
                
            }
        }
        else {
            if(empty($title)) {
                $title = ucwords(str_replace('_',' ', $name));
            }
            $this->fieldsTitle[$name] = $title;
        }
    }    
    
    function setTitleHtml($name ) {
        $this->fieldsTitleHtml[$name] = "<label class=\"{$name}-caption\" for=\"{$name}\">{$this->fieldsTitle[$name]}</label>";
    }
    
    function getTitleHtml($name) {
        return $this->fieldsTitleHtml[$name];    
    }

    function getTitleHtmlArray() {
        return $this->fieldsTitleHtml;
    }
    
    /**************** buttons helper **************/
    function addButton($name, $text='Button', $type='button') {
        $this->buttons[$name]['type'] = $type;
        $this->buttons[$name]['text'] = $text;
    }
    
    function removeButton($name) {
        unset($this->buttons[$name]);
    }
    
    function buttonsHtml() {
        foreach($this->buttons as $name=>$button) {
            $this->setButtonHtml($name);
            $buttons .= $this->getButtonHtml($name); 
        }
        
        return "<div class=\"buttons\">\n\t{$buttons}\n</div>";
    }
    
    function setButtonHtml($name) {
        $this->buttonsHtml[$name] = "<input id=\"{$name}\" type=\"{$this->buttons[$name]['type']}\" value=\"{$this->buttons[$name]['text']}\"".
        $this->writer->parse_attrib($this->buttonsAttrib[$name]). "/>";    
    }
    
    function setButtonAttribs($name, $attr=array()) {
        if(!is_array($name)) {
            $this->buttonsAttrib[$name] = $attr;
        } else {
             foreach($name as $k=>$v) {
                $this->buttonsAttrib[$name] = $v;
             }
        }
    }
    
    function getButtonHtml($name) {
        return $this->buttonsHtml[$name];
    }
    
    function getButtonHtmlArray() {
        return $this->buttonsHtml;
    }
    
    
    /************* validation wrapper ***************/    
    function validate() {
        /* loop through each validation callbacks */
         foreach($this->fields as $name=>$field) {            
            $check_arr = explode('|',$field['check']);
            $value = $field['value'];
            
            foreach($check_arr as $check) {
                
                if($this->is_error($name)) {
                     continue;
                }
                
                switch($check) {
                    case 'numeric':
                        if(!check_numeric($value)) {
                            $this->setError($name, $this->getFieldsTitle($name) .  " must be numbers.");
                        }
                    break;
                    case 'integer':
                        if(!is_int($value*1)) {
                            $this->setError($name, $this->getFieldsTitle($name) .  " must be whole number/integer.");
                        }                        
                    break;
                    case 'float':
                        if(!is_float($value*1)) {
                            $this->setError($name, $this->getFieldsTitle($name) .  " must be float number.");
                        }                         
                    break;
                    case 'date':
                        if(!is_date($value)) {
                            $this->setError($name, $this->getFieldsTitle($name) .  " must be a valid date.");
                        }                         
                    break;                
                    case 'email':
                        if(!is_email($value)) {
                            $this->setError($name, $this->getFieldsTitle($name) .  " must be valid email.");
                        }                         
                    break;                
                    case 'required':
                        if(empty($value)) {
                            $this->setError($name, $this->getFieldsTitle($name) .  " is required.");
                        }                         
                    break;
                    case 'not_zero':
                        if($value *1 == 0) {
                            $this->setError($name, $this->getFieldsTitle($name) .  " must be not zero.");
                        }                         
                    break;                    
                    
                }
            }
            
            /* user defined validation call back per each field */

            if(!$this->is_error($name)) {
                if(function_exists($this->fieldChecks[$name])) {
                    $callback = $this->fieldChecks[$name];
                    $callback($value,$this);
                    /*
                     * if call back is assigned as 'checkData' then declare the function such as this :
                     *
                     * function checkData($value,&$form) {
                     *   
                     * }                      
                    */  
                }
            }            

         }
    }
    
    /**
     *
     * run()
     * 
     **/
    function run() {
        
        if($this->is_post()) {

            foreach($this->fields as $name=>$field) {
                $this->set($name, $this->post($name));
            }
            $this->onSubmit();             
            $this->validate();
            if(!$this->hasError()) {
                $this->onSuccess();
            }    
        }
        
         
    }
    /**
    * 2 options to perform something during success : through onSuccess() override or run user assigned callback
    **/
    function onSuccess() {
        if(!empty($this->onSuccess)) {
            if(function_exists($this->onSuccess)) {
                $this->onSuccess($this);
            }
        }
    }
    
    /**
    * 2 options to perform something during submit : through onSubmit() override or run user assigned callback
    *  do sanitation here by overriding whatever value is get from POST
    **/
    function onSubmit() {
        if(!empty($this->onSubmit)) {
            if(function_exists($this->onSubmit)) {
                $this->onSubmit($this);
            }
        }
    }     
    
    function is_post() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
    
    function post($name) {
       return $_POST[$name];    
    }
 
    
    function getHtml() {
        
        $this->getFieldsHtmlArray();
        $form_html = implode("\n\t", $this->fieldsetHtml);
        $button_html = $this->buttonsHtml();

        $err_items = $this->getAllErrorsHtml();
 
        $html= <<<EOS
         {$err_items}
         \n{$this->openFormTag()}
        {$form_html}\n\t{$button_html}
        </FORM> 
EOS;
        return $html;
    }
    
    function openFormTag() {
        return "<FORM action=\"{$this->action}\" method=\"{$this->method}\">";
    }

}

/** ./End of Class **/

/** Validation Helper **/


function check_numeric($data) {
 
 /* check for non digit, exempting . character for floating point and when it's occurence is > 1 */
 preg_match_all('/\D/',$data,$match);
 $non_digits = implode('',$match[0]);
 $no_dec_point= str_replace(".", "", $non_digits);
 $bln = !(substr_count($non_digits,".") > 1 || strlen($no_dec_point) > 0);
 return $bln;
}

function is_email($data) {
    return 1;
}

/**
 * expects date format to be Y-m-d
 *
 **/
function is_date($data) {
    $dt=explode("-", $data);    
    return checkdate($dt[1],$dt[2], $dt[0]);
}


/** ./End of Validation Helper **/

 

 

?>