<?php

namespace dabu\HTMLTable;

class ContentHTMLTable extends HTMLTable {

    private $querystring;
    private $tablename;
    private $columns;
    
    /**
    * @param array $columns - array with columns
    *
    */
    
    public function __construct() {
    $columns = array([
      'name' => 'fruits',
      'label' => 'Fruits',
      'sortable' => true,
    ],
    [
      'name' => 'animals',
      'label' => 'Animals',
      //'sortable' => false,
    ],
    );
    
    $data = array(
      1 => [
      $columns[0]['name'] => 'Apple', 
      $columns[1]['name'] => 'Horse' 
       ],
      2 => [
      $columns[0]['name'] => 'Banana', 
      $columns[1]['name'] => 'Monkey' 
       ],
    );    
        
      }
    
  
      
    /**
     * Build an html table
     *
     * @param array $columns containing label and corresponding name
     * @param array $values containing the data
     * @param array $options search options needed for the query string.
     */
    
    public function createTable($columns, $values=null, $options, $tablename=null) {
        
    $this->querystring = $this->getQueryString($options);    

    $html = "<table id='".$tablename."'>";
    $html .= "<tr>";
    
    foreach ($columns as $column) {
        $orderby = (isset($column['sortable']) && $column['sortable']==true) ? $this->orderby($column['name']) : null;
        $html .= "<th>".$column['label'].$orderby."</th>";
    }
    $html .= "</tr>";
    
    if (!empty($values)) {
    foreach ($values as $row => $value) {
    $html .= "<tr>";
    
        foreach ($columns as $column) {
            $html .= "<td>".$value[$column['name']]."</td>";
        }
    
    $html .= "</tr>";
     
    }
    }
    
    $html .= "</table>";
    return $html;
  }
    
    
  /**
  * Function to create links for sorting
  *
  * @param string $column the name of the database column to sort by
  * @return string with links to order by column.
  */
  public function orderby($column, $query=null) {
        $query = null ? $this->querystring : $query;
        
    return "<span class='orderby'><a href='{$query}&orderby={$column}&order=asc'>&darr;</i></a><a href='{$query}&orderby={$column}&order=desc'>&uarr;</a></span>";
  }
  
      /**
     * Create links for hits per page.
     *
     * @param array $hits a list of hits-options to display.
     * @return string as a link to this page.
     */
    function getHitsPerPage($hits) {
      $nav = "Träffar per sida: ";
      foreach($hits AS $val) {
        $nav .= "<a href='" . $this->getQueryString(array('hits' => $val)) . "'>$val</a> ";
      }  
      return $nav;
    }
  
  
}