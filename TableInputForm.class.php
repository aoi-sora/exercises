<?php
/*********************************************************************************************************
 * TableInputForm by Jei Dela Fuente
 * September 24, 2012
 * MAGIC Goals
 *********************************************************************************************************/

 require_once 'InputForm.class.php';

 
 class TableInputForm extends InputForm {
    
    /** table object **/
    var $table;
    
    /** **/
    var $params;
    
    /**
     *
     * __construct(@fields)
     *
     * where : @fields[@string name]['name'] = @string
     *         @fields[@string name]['title'] = @string 
     *         @fields[@string name]['type'] = text | textarea | select | multi_select | hidden | password | checkbox | radio
     *         @fields[@string name]['check'] = numeric|email|required|date|integer|float|not_zero or  any of these combinations separated by |
     *         @fields[@string name]['list'] = @array
     *         @fields[@string name]['blank_option'] = @string
     *         @lookupInfo[fieldname] = associative array of options
     *         
     **/
    function __construct($table, $params=array(), $lookupInfo=array(), $action='', $method='post', $success_url='') {
        parent::__construct();
        $this->table = $table;
        $this->action = $this->make_url();
        $this->method = $method;        
        $this->params = $params;
        $this->successUrl = $success_url;
        
        $this->lookupInfo = $lookupInfo;
        
        $id =  $_GET[($this->table->_key)];

        if(!empty($id)) {
            $this->table->get($id);
        }
        $this->loadFields();
    }
    
    /**
     *
     *
     **/
    function loadFields() {
        $f = $this->table->getFields();
        unset($f[$this->table->_key]);
        foreach($f as $col=>$data) {
            $c          = explode('(', $data['type']);
            $type       = $c[0];
            $size       = str_replace(')','', $c[1]);
            
            $fieldcheck = array();
            
            $fields[$col]['type']           = 'text';
            $fields[$col]['blank_option']   = 'Please Select';
            $fields[$col]['check']          = $fieldcheck;
            $fieldsAttrib[$col]['size']     = $this->parseSize($col,$type,$f);
            
            if($data['null'] == 'NO') {
                $fieldcheck['required']     = 'required';
            }
            
            switch($type) {
                case 'enum':
                    $fields[$col]['type']           = 'select';
                    $fields[$col]['list']           = $this->parseOptions($col, $type, $f);
                break;
                case 'set':
                    $fields[$col]['type']           = 'multi-select';
                    $fields[$col]['list']           = $this->parseOptions($col, $type, $f);
                break;
                case 'boolean':
                case 'tinyint':
                    $size = $this->parseSize($col,$type,$f);
                    if($size == 1) {
                        $fields[$col]['type']           = 'boolean-checkbox';                        
                        unset($fieldcheck['numeric']);
                        unset($fieldcheck['integer']);
                    } else {
                        $fields[$col]['type']           = 'select';
                        $fields[$col]['list']           = array( 1 => 'Yes', '0' => 'No');
                        unset($fieldcheck['numeric']);
                        unset($fieldcheck['integer']);                        
                    }
                break;   
                case 'date':
                    $fields[$col]['type']           = 'date';
                    $fieldcheck['date'] = 'date';
                case 'datetime':
                case 'timestamp':
                case 'time':
                case 'year':
                break;    
                default:
                    if($this->table->isBlob($col)) :
                        $fieldsAttrib[$col]['rows'] = 10;
                        $fieldsAttrib[$col]['cols'] = 40;
                        $fields[$col]['type']       = 'textarea';
                    endif;
                break;
            }
            
            if($this->table->isNumeric($col)) {
                $fieldcheck['numeric'] = 'numeric';
                
            }
            
            if($this->table->isInteger($col)) {
                $fieldcheck['integer'] = 'integer';
                
            }            
            
            if($this->table->isFloat($col)) {
                $fieldcheck['float'] = 'float';
                
            }
            
            /* lookup declaration from fields of table object */         
            if($this->table->isLookup[$col]) {
                $fields[$col]['type']    = 'select';
                $fields[$col]['list']    = $this->table->lookUpList($col);
            }
            
            $fields[$col]['check'] = implode('|', $fieldcheck);
            
        }
        
        $this->setFields($fields, $fieldsAttrib);
        $this->setFieldsTitle($this->table->getTitles());
         
        
    }
      
    
    /**
     *
     *
     **/    
    function parseOptions($name, $type, $fields) {
        $t = explode("{$type}(",$fields[$name]['type']);

        $t = explode(')',$t[1]);
      
        array_pop($t);
        
        $t = explode(',',$t[0]);
        foreach($t as $k=>$v) {
            $opt = substr($v,1,-1);
            $item[$opt] = $opt;
        }
        
        return $item;
    }

    /**
     *
     *
     **/    
    function parseSize($name, $type, $fields) {
        $t = explode("{$type}(",$fields[$name]['type']);

        $t = explode(')',$t[1]);
        array_pop($t);     
        
        if(count(explode(',',$t)) > 0) {
            $t = explode(',', $t);         
        }

        $size = 0;
        foreach($t as $k=>$v) {
            $size += $v; 
        }
        
        return $size;
    }    
    
    
    /**
     * override
     *
     **/
    function convertField($name) {
        $this->beforeRender($name);
        if($this->isLookUp($name)) {
            $this->fields[$name]['type']            = 'select';
            $this->fields[$name]['list']            = $this->lookupInfo[$name];
            $this->fields[$name]['blank_option']    = 'Please Select';
        }        
        parent::convertField($name);
    }
    
    /**
     *
     **/
    function make_url($prepend_param='') {
        $protocol   = explode('/',$_SERVER['SERVER_PROTOCOL']);
        $protocol   = strtolower($protocol[0]) . '://';
        
        $url = $protocol. $_SERVER['HTTP_HOST'] .  $_SERVER['PHP_SELF'] .'?';
        
        foreach($this->params as $k=>$v) {
            if(!isset($_GET[$k])) {
                $url_param .= "{$k}={$v}&";
            }
        }
        
        foreach($_GET as $k=>$v) {
            $url_param .= "{$k}={$v}&";
        } 
        
        if(!empty($prepend_param)) {
            $url_param = $prepend_param . '&'.$url_param;
        }
 
        $url .= $url_param;
        return $url;
        
    }
    
    
    /**
     *
     * customize field helper before rendering final  fields
     **/
    function beforeRender($name) {
        
    }
    
    function isLookUp($name) {
        return !empty($this->lookupInfo[$name]);
    }
    
    
    /**
     *
     * run()
     * 
     **/
    function run() {
        
        if($this->is_post()) {

            foreach($this->fields as $name=>$field) {
                
                /* work around for fields with set type/multi-select to capture all selected items during POST */
                if($field['type'] == 'multi-select') {
                    $this->fields[$name]['value'] =  $this->post("{$name}_set") ;
                } else {
                    $this->set($name, $this->post($name));
                }
            }
            $this->onSubmit();
            
            $this->validate();            
            if(!$this->hasError()) {
                $this->onSuccess();
            } 
        } else {
            $this->populate();
        }

    }
    
    /**
     *
     * populate form values from the table object's current row.
     **/
    function populate() {
        $row = $this->table->currentRow();
        foreach($this->fields as $name=>$field) {
            if(!empty($row->$name)) {
                switch($this->table->getFieldType($name)) {
                    case 'set':
                       $this->set($name,explode(",", $row->$name));    
                    break;
                    default:
                        $this->set($name, $this->table->$name );
                    break;
                }
            }
            
        }        
    }
    
    /**
    * 2 options to perform something during success : through onSuccess() override or run user assigned callback
    **/
    function onSuccess() {
        /* collate the data */
        foreach($this->fields as $k=>$v) {
            if(isset($this->table->_fields[$k])) {
                $data[$k] = $v['value'];
            }
        }
        
        if($this->is_add( )) {
           $success = $this->table->add($data);
           if(!$success) {
                $this->setError('add_error',$this->table->_dbError);
           } 
        } else {
             $success =$this->table->update($data, array($this->table->_key => $_GET[$this->table->_key]));
             if(! $success) { 
                $this->setError('add_error',$this->table->_dbError);
             }
                
        }
        
        /* add or update is successful */
        if($success) {
            if(!empty($this->onSuccess)) {
                if(function_exists($this->onSuccess)) {
                    $this->onSuccess($this); 
                }
            }
     
            if(!empty($this->successUrl)) {
                header('Location: '.$this->successUrl);
            } else {
                if($this->is_add()) {
                    $param = "{$this->table->_key}={$this->table->lastId}";
                }
                header('Location: '.$this->make_url($param));    
                 
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
    
    /**
     *
     **/
    function is_add() {
         return empty($_GET[$this->table->_key]);
    }
    
 
    
 }
?>