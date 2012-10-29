<?php
/*********************************************************************************************************
 * TableObject by Jei Dela Fuente
 * September 24, 2012
 * MAGIC Goals
 *********************************************************************************************************/
 class TableObject {
    var $_db,
        $_fields,
        $_tablename,
        $_id,
        $_key,
        $lastId,
        $_row,
        $_lookupInfo,
        $_dbError,
        $_fieldsTitle=array();
        
    /**
     *
     *
     *  @tablename = string
     *  @db = mysqli connection object
     *  @id = id value
     *  @lookupInfo[fieldname] = querystring
     *
     *
     *
     *
     **/   
    function __construct($tablename='',$db, $id='', $lookupInfo=array() ) {
        
        $this->_db          = $db;        
        $this->_tablename   = $tablename;
        $this->_id          = $id;
        $this->_row         = new stdClass;
        $this->_lookupInfo  = $lookupInfo;
        
        if(!empty($this->_tablename)) {
            $this->metadata();
        }
        
        $this->get($this->_id);
         
    }
    
    function get($id) {
        $id = empty($id) ? $this->_id : $id;
        if(!empty($id)) {
            $id = $this->_db->real_escape_string($id);
            $result = $this->_db->query("SELECT * FROM `{$this->_tablename}` WHERE `{$this->_key}` = '{$id}'");
            $row = $this->loadRows($result);
            foreach($row[0] as $name=>$field) {
                $this->$name = $field;
            }
            $this->_row = $row[0];
        }
    }
    
    function metadata() {
        $result = $this->_db->query("SHOW FULL COLUMNS FROM `{$this->_tablename}`");
        while($row = $result->fetch_object()) {
            foreach($row as $col=>$data) {
                if($col == 'Key' &&  $data == 'PRI') :
                    $this->_key = $row->Field;
                endif;                
                if($col == 'Field') :
                    continue;
                endif;

                $meta[(strtolower($col))] = $data;
                
            }
            $this->_fields[($row->Field)] = $meta;
            $this->_fieldsTitle[($row->Field)] = ucwords(str_replace('_',' ', $row->Field));
        }

    }
    
    function getFields() {
        return $this->_fields;
    }
    
    function getFieldType($name) {
        $data = $this->_fields[$name];
        $c          = explode('(', $data['type']);
        $type       = $c[0];
        return $type;        
    }
    
    function getTitles() {
        return $this->_fieldsTitle;
    }
    function getFieldTitle($name) {
        return $this->_fieldsTitle[$name];    
    }
    
    function currentRow() {
        return $this->_row;    
    }
    
    /**
    *
    *
    * override this when needed in example during the integration in CodeIgniter framework
    **/
    function add($data=array()) { 
        
        foreach($data as $k=>$v) {
            
            if(!is_array($v)) {
                $v = $this->_db->real_escape_string($v);
            } else {
                foreach($v as $val) :
                    $_value[] = $this->_db->real_escape_string($val);
                endforeach;    
                $v = implode(',',$_value);
            }
            
            if($this->isIntBoolean($k) ) {
                $v = $v *1 ; // handle optional tinyint length of 1
            }

            $delim = $this->toDelimit($k) ? "'" : "";
            $fieldvalues .= "{$delim}{$v}{$delim},";
            $fieldnames  .= "`{$k}`,";
        }
        
        $fieldvalues = substr($fieldvalues,0, -1);
        $fieldnames = substr($fieldnames,0, -1);
        $query = "INSERT INTO `{$this->_tablename}` ( {$fieldnames} ) VALUES( {$fieldvalues} );";
        $bln = $this->_db->query($query);

        // debug($query);
        if(!$bln) {
            $this->_dbError = $this->_db->error;
            return false;
        }
        $this->lastId = $this->_db->insert_id;
        
        return true;        
       
        
    }
    
    
    /**
    *
    *
    * override this when needed in example during the integration in CodeIgniter framework
    **/    
    function update($data, $filter=array()) {
        foreach($data as $k=>$v) {            
            if(!is_array($v)) {
                $v = $this->_db->real_escape_string($v);
            } else {
                foreach($v as $val) :
                    $_value[] = $this->_db->real_escape_string($val);
                endforeach;    
                $v = implode(',',$_value);
            }
            if($this->isIntBoolean($k) ) {
                $v = $v *1 ; // handle optional tinyint length of 1
            }            
            
            $delim = $this->toDelimit($k) ? "'" : "";
            $fieldset .= "`{$k}` = {$delim}{$v}{$delim},";
        }
        
        $where = is_array($filter) ? $this->parseKeyValue($filter) : $filter;
 
        $fieldset = substr($fieldset,0, -1);
        $query = "UPDATE `{$this->_tablename}` SET {$fieldset} WHERE {$where}";
         
        $bln = $this->_db->query($query);        
        /* load the newly updated row */
        $this->get();
        if(!$bln) {
            $this->_dbError = $this->_db->error;
            return false;
        }
        return true;
    }
    
    
    /**
    *
    *
    * override this when needed in example during the integration in CodeIgniter framework
    **/    
    function delete($filter=array()) {
        $where = is_array($filter) ? $this->parseKeyValue($filter) : $filter;
 
        $fieldset = substr($fieldset,0, -1);
        $query = "DELETE FROM `{$this->_tablename}`  WHERE {$where}";
        $bln = $this->_db->query($query);
        if(!$bln) {
            $this->_dbError = $this->_db->error;
            return false;
        }
        return true;
    }
    
    /**
    *
    *
    * override this when needed in example during the integration in CodeIgniter framework
    **/    
    function getRows($querystr='') {
        $querystr = !empty($querystr) ? $querystr  :
                    "SELECT * FROM `{$this->_tablename}`";
        $result = $this->_db->query($querystr);
        return $this->loadRows($result);
    }
    
    
    function lookUp($querystr) {
        /* query here */
        $result = $this->_db->query($querystr);
        $lookup = $this->loadRows($result);
    }
    
    function lookUpList($name) {
        if(!empty($this->_lookupInfo[$name])) {
            return $this->lookUp($this->_lookupInfo[$name]);
        }
    }
    
    function getLookupInfo($name) {
        return $this->_lookupInfo[$name];
    }
    
    function setLookupInfo($name, $querystr='') {
        $this->_lookupInfo[$name] = $querystr;    
    }
    
    function isLookup($name) {
        return !empty($this->_lookupInfo[$name]);
    }
    /**
     *
     * query result iterator utility, returns resulting records as array of objects
     **/
    function loadRows($result) {
        while($row = $result->fetch_object()) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    /**
     *
     * this is limited to AND operations for query purpose
     **/
    function parseKeyValue($data=array()) {
        foreach($data as $k=>$v) {
            $v = $this->_db->real_escape_string($v);
            $delim = $this->toDelimit($k) ? "'" : "";
            $fieldset .= "`{$k}` = {$delim}{$v}{$delim} AND ";
        }
        return substr($fieldset,0, -4);
    }
    
    /**
     *
     *
     **/     
    function toDelimit($name) {
        $types = array('varchar','text','date','datetime','timestamp','time','year','char','tinytext','longtext','mediumtext','binary','varbinary','blob','tinyblob','mediumblob','longblob','enum','set');
        $types = "|" . implode("|",$types) . "|";
        $f_type = explode('(',$this->_fields[$name]['type']);
        $f_type = $f_type[0];
        $field_type = "|{$f_type}|";
        
        return (strpos($types,$field_type) !== FALSE) ;
        
    }    
    
    /**
     *
     *
     **/     
    function isNumeric($name) {
        $types = array('tinyint','smallint','mediumint','int','bigint','decimal','float','double','real','bit','boolean','serial');
        $types = "|" . implode("|",$types) . "|";
        $f_type = explode('(',$this->_fields[$name]['type']);
        $f_type = $f_type[0];
        $field_type = "|{$f_type}|";
        
        return (strpos($types,$field_type) !== FALSE) ;
        
    }
    
    /**
     *
     *
     **/
    function isInteger($name) {
        $types = array('tinyint','smallint','mediumint','int','bigint');
        $types = "|" . implode("|",$types) . "|";
        $f_type = explode('(',$this->_fields[$name]['type']);
        $f_type = $f_type[0];
        $field_type = "|{$f_type}|";
        
        return (strpos($types,$field_type) !== FALSE) ;          
    }
    
    /**
     *
     *
     **/
    function isFloat($name) {
        $types = array('decimal','float','double','real','bit','boolean','serial');
        $types = "|" . implode("|",$types) . "|";
        $f_type = explode('(',$this->_fields[$name]['type']);
        $f_type = $f_type[0];
        $field_type = "|{$f_type}|";
        
        return (strpos($types,$field_type) !== FALSE) ;          
    }    
 
    /**
     *
     *
     **/     
    function isDateTime($name) {
        $types = array('date','datetime','timestamp','time','year');
        $types = "|" . implode("|",$types) . "|";
        $f_type = explode('(',$this->_fields[$name]['type']);
        $f_type = $f_type[0];
        $field_type = "|{$f_type}|";
        
        return (strpos($types,$field_type) !== FALSE) ;
        
    }    
    /**
     *
     *
     **/     
    function isText($name) {
        $types = array('varchar','text','char','tinytext','longtext','mediumtext','blob','tinyblob','mediumblob','longblob');
        $types = "|" . implode("|",$types) . "|";
        $f_type = explode('(',$this->_fields[$name]['type']);
        $f_type = $f_type[0];
        $field_type = "|{$f_type}|";
        
        return (strpos($types,$field_type) !== FALSE) ;
        
    }
    /**
     *
     *
     **/     
    function isBlob($name) {
        $types = array('text','tinytext','longtext','mediumtext','blob','tinyblob','mediumblob','longblob' );
        $types = "|" . implode("|",$types) . "|";
        $f_type = explode('(',$this->_fields[$name]['type']);
        $f_type = $f_type[0];
        $field_type = "|{$f_type}|";
        
        return (strpos($types,$field_type) !== FALSE) ;
        
    }
    /**
     *
     *
     **/
    function isIntBoolean($name) {
        $f_type = explode('(',$this->_fields[$name]['type']);
        $f_size = str_replace(')','',$f_type[1]);
        $f_type = $f_type[0];
        
        return $f_type == 'tinyint' && $f_size == 1;
        
    }
    
    
 }
 
 ?>