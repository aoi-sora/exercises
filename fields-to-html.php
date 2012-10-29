<?php
error_reporting(E_ERROR);
require_once 'general.functions.php';
require_once 'FieldsToHtml.class.php';

$f = new FieldsToHtml();
 
debug($f->html_textarea('address',array('value' => 'this is address')));
 
debug($f->html_select('sex',array('male'=>'Male','female' => 'Female'), array('value'=>'female')));
debug($f->html_multi_select('index', array( 'zero', 'one', 'two'), array(2,1,0) ));
debug($f->html_radio('continent', array('asia'           => 'Asia',
                                    'europe'         => 'Europe',
                                    'north_atlantic' => 'North Atlantic',
                                    'south_atlantic' => 'South Atlantic',
                                    'africa'         => 'Africa',
                                    'north_america'  =>  'North America',
                                    'south_america'  =>  'South America'
                                    ),
                              'europe' 
                 )
      
      );

debug($f->html_checkbox('continent', array('asia'           => 'Asia',
                                    'europe'         => 'Europe',
                                    'north_atlantic' => 'North Atlantic',
                                    'south_atlantic' => 'South Atlantic',
                                    'africa'         => 'Africa',
                                    'north_america'  =>  'North America',
                                    'south_america'  =>  'South America'
                                    ),
                              array('europe','africa','north_america') 
                 )
      
      );

 
 
 ?>