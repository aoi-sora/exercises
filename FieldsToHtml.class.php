<?php
/*********************************************************************************************************
 * FieldsToHtml by Jei Dela Fuente
 * September 24, 2012
 * MAGIC Goals
 *********************************************************************************************************/

  class FieldsToHtml {

      /**
      * @string html_text(@name, @attr)
      * 
      **/
     function html_text($name, $attr=array()) {
       
       $def_attrib = array('id' =>$name,
                          'name' => $name,
                          'size' => 15
                          );
      return  "\n<input type=\"text\"" . $this->html_attribs($attr, $def_attrib) . ">";
     }
     
     /**
      * @string html_textarea(@name, @attr)
      * 
      **/ 
      function html_textarea($name, $attr=array()) {
       
       $def_attrib = array('id' =>$name,
                          'name' => $name,
                          'cols' => 20,
                          'rows' => 5
                          );
      $value = $this->extract_value($attr);
      return  "\n<textarea " . $this->html_attribs($attr, $def_attrib) . ">{$value}</textarea>";
     }
     
     /**
      * @string html_submit(@name, @attr)
      * 
      **/ 
      function html_submit($name, $attr=array()) {
       
       $def_attrib = array('id'   => $name,
                          'name'  => $name,
                          'value' => 'Submit'
                          );   
      return  "\n<input type=\"submit\"" . $this->html_attribs($attr, $def_attrib) . ">";
     }
     
     
      /**
      * @string html_reset(@name, @attr)
      * 
      **/
      function html_reset($name, $attr=array()) {
       
       $def_attrib = array('id'   =>$name,
                          'name'  => $name,
                          'value' =>  'Reset'
                          );   
      return  "\n<input type=\"reset\"" . $this->html_attribs($attr, $def_attrib) . ">";
     }
     
     /**
      * @string html_button(@name, @attr)
      * 
      **/ 
      function html_button($name, $attr=array()) {
       
       $def_attrib = array('id' =>$name,
                          'name' => $name 
                          );   
      return  "\n<button ". $this->html_attribs($attr, $def_attrib) . "></button>";
     }
     
     /**
      * @string html_upload(@name, @attr)
      * 
      **/ 
      function html_upload($name, $attr=array()) {
       
       $def_attrib = array('id' =>$name,
                          'name' => $name,
                          'size' => 15
                          );   
      return  "\n<input type=\"file\"" . $this->html_attribs($attr, $def_attrib) . ">";
     }
     
     /**
      * @string html_select(@name, @opts, @attr)
      * 
      **/ 
      function html_select($name, $opts=array(), $attr=array(), $blank_text='') {
       
       $def_attrib = array('id' =>$name,
                          'name' => $name 
                          );
      $value= $this->extract_value($attr);
      $html =   "\n<select " . $this->html_attribs($attr, $def_attrib) . ">";
      
      foreach($opts as $k=>$v) {
       $is_select = ($k == $value ? 'selected' : '');
       $option .= "\n\t<option value=\"{$k}\" {$is_select}>{$v}</option>";
      }
      
      $opt1 = empty($blank_text) ? '' : "<option value=\"\">{$blank_text}</option>";
      $option = "\n\t{$opt1}" . $option;
      $html = $html . $option . "\n\t</select>";
      return $html;
     }
     
     /**
      * @string html_multi_select(@name, @opts, @select_opts, @attr)
      * 
      **/ 
     function html_multi_select($name, $opts=array(), $select_opts=array(), $attr=array(), $blank_text='') {
       
       $def_attrib = array('id' =>$name,
                          'name' => $name,
                          'size' => 5
                          );
      unset($attr['value']); 
      $html =  "\n<select multiple=\"multiple\"" . $this->html_attribs($attr, $def_attrib) . ">";
      
      foreach($opts as $k=>$v) {
       $is_select = ( in_array($k,$select_opts) === TRUE  ? 'selected' : '');
       $option .= "\n\t<option value=\"{$k}\" {$is_select}>{$v}</option>";
      }
      
      $opt1 = empty($blank_text) ? '' : "<option value=\"\" disabled>{$blank_text}</option>";
      $option = "\n\t{$opt1}" . $option;
      $html = $html . $option . "\n\t</select>";
      return $html;  
      
     } 
      
      /**
       * @string html_radio(@name , @opts, @select_opt, @attr)
       * 'layout' : 'vertical' or 'horizontal'
       * 'label_position' : 'before' or 'after'
       **/ 
      function html_radio($name, $opts=array(), $select_opt='', $attr=array()) {
      
      $def_attrib = array(
                          'layout'        => 'horizontal',
                          'label_position' => 'after'
                          );
       
       unset($attr['id']);
       unset($attr['name']);
       
       $attributes = $this->merge_attrib($attr,$def_attrib);
       unset($def_attrib['layout']);
       unset($def_attrib['label_position']);   
       
       foreach($opts as $k=>$v) { 
       
        $id = "{$name}[{$k}]";
        $name="{$name}";
        $is_checked = (!empty($select_opt) && $k == $select_opt) ? ' checked' : '';
        $radio = "<input type=\"radio\" id=\"{$id}\" name=\"{$name}\" value=\"{$k}\" {$is_checked}" . $this->html_attribs($attr, $def_attrib) .">";
       
        switch($attributes['label_position']) {
         case 'after':
          $opt_block = "<td>{$radio}</td><td><label for=\"{$id}\">{$v}</label></td>";
         break;
         default:
          $opt_block = "<td><label for=\"{$id}\">{$v}</label></td><td>{$radio}</td>";
         break;
         
        }
        
        $radio_html .=  ($attributes['layout'] == 'vertical' ? '<tr>' : '') . "\n\t\t" .
                     $opt_block .
                     ($attributes['layout'] == 'vertical' ? '</tr>' : '');
        
       }
       
       $radio_html = "\n<table>\n". ($attributes['layout'] == 'horizontal' ? '<tr>' : '') .
                      $radio_html .
                      ($attributes['layout'] == 'horizontal' ? '</tr>' : '') . 
                      "\n</table>";
       return $radio_html;
       
       
     }
     
     /**
      *
      * @string html_checkbox(@name, @opts, @select_opts, @attr)
      **/
     
     function html_checkbox($name, $opts=array(), $select_opts=array(), $attr=array()) {
      $def_attrib = array(
                          'layout'        => 'horizontal',
                          'label_position' => 'after'
                          );
       
       unset($attr['id']);
       unset($attr['name']);
       
       $attributes = $this->merge_attrib($attr,$def_attrib);
       unset($def_attrib['layout']);
       unset($def_attrib['label_position']);   
       
       foreach($opts as $k=>$v) { 
       
        $is_checked = (!empty($select_opts) && in_array($k,$select_opts) === TRUE) ? ' checked' : '';
        $checkbox = "<input type=\"checkbox\" id=\"{$name}[{$k}]\" name=\"{$name}[{$k}]\" value=\"{$k}\" {$is_checked}" . $this->html_attribs($attr, $def_attrib) .">";
       
        switch($attributes['label_position']) {
         case 'after':
          $opt_block = "<td>{$checkbox}</td><td><label for=\"{$name}[{$k}]\">{$v}</label></td>";
         break;
         default:
          $opt_block = "<td><label for=\"{$name}[{$k}]\">{$v}</label></td><td>{$checkbox}</td>";
         break;
         
        }
        
        $checkbox_html .=  ($attributes['layout'] == 'vertical' ? '<tr>' : '') . "\n\t\t" .
                     $opt_block .
                     ($attributes['layout'] == 'vertical' ? '</tr>' : '');
        
       }
       
       $checkbox_html = "\n<table>\n". ($attributes['layout'] == 'horizontal' ? '<tr>' : '') .
                      $checkbox_html .
                      ($attributes['layout'] == 'horizontal' ? '</tr>' : '') . 
                      "\n</table>";
       return $checkbox_html;
     }
     
     /**
      * 1 or 0
      * @string html_boolean_checkbox(@name, @opts, @select_opts, @attr)
      **/
     
     function html_boolean_checkbox($name, $attr=array()) {
      $def_attrib = array(
                          'layout'        => 'horizontal',
                          'label_position' => 'after'
                          );
       
       unset($attr['id']);
       unset($attr['name']);
       
       $attributes = $this->merge_attrib($attr,$def_attrib);
       unset($def_attrib['layout']);
       unset($def_attrib['label_position']);   
       
      $is_checked = $attr['value'] ? 'checked' : '';
      $checkbox_html = "<input type=\"checkbox\" id=\"{$name}\" name=\"{$name}\" value=\"1\" {$is_checked}" . $this->html_attribs($attr, $def_attrib) .">";
 
       return $checkbox_html;
     }     
     
     /**
      *
      * @string html_hidden(@name, @attr)
      **/ 
     function html_hidden($name, $attr=array()) {
      $def_attrib = array('id' =>$name,
                          'name' => $name 
                          );   
      return  "\n<input type=\"hidden\"" . $this->html_attribs($attr, $def_attrib) . ">";
      
     }
     
     /**
     *
     * @string html_password(@name, @attr)
     **/
     function html_password($name, $attr=array()) {
     $def_attrib = array('id' =>$name,
                          'name' => $name,
                          'size' => 15
                          );   
      return  "\n<input type=\"password\"" . $this->html_attribs($attr, $def_attrib) . ">";
      
     }
      
     /**
      * @string html_attribs(@attr, @def_attrib)
      **/
     function html_attribs($attr=array(), $def_attrib=array()) {
       return $this->parse_attrib($this->merge_attrib($attr, $def_attrib));
     }
    
     /**
      *
      * @string parse_attrib(@attr)
      **/
     function parse_attrib($attr=array()) {
      foreach($attr as $k=> $v) {
        $attrib.= " {$k}=\"{$v}\"";
       
      }
      return $attrib;
     }
     
     
     /**
      *
      * @array merge_attrib(@attr, $def_attrib)
      **/
     function merge_attrib($attr=array(), $def_attrib=array()) {
      $ret = array();
      foreach($def_attrib as $k=>$v) {
        if(!isset($attr[$k])){
          $ret[$k] = $v;
        } else {
          $ret[$k] = $attr[$k];
        }
      }
      
      foreach($attr as $k=>$v) {
        if(array_key_exists($k, $def_attrib)=== FALSE) {
          $ret[$k] = $v;
        }
      }
      return $ret;
     }
     
     /**
      * @mixed extract_value($attr)
      **/ 
     function extract_value(&$attr) {
       extract($attr);
       unset($attr['value']);
       return $value;
       
     }
  }
  
/** ./End of Class **/  
 
 

?>