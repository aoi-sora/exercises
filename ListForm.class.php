<?php
/*********************************************************************************************************
 * ListForm by Jei Dela Fuente
 * September 24, 2012
 * MAGIC Goals
 *
 *
 * - takes care of get parameter handling for pagination, sorting, and query of search values
 * - provides pagination links
 * - other fancy appearance of list form or grid
 * - TODO a child extension of this supporting ajax searching, editing, sorting and navigation
 *********************************************************************************************************/

 require_once 'Iterator.class.php';

class ListForm {
    var $iterator;

    function __construct($iterator = new Iterator()) {
        $this->iterator = $iterator;
    }
    
}
?>