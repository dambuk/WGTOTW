<?php

namespace dabu\HTMLTable;

class HTMLTable extends SimpleHTMLTable {

    private $querystring;
    private $tablename;
    
    /**
    * @param array $columns - array with columns
    *
    */
    
    public function __construct() {
    
      }
    
  
  /**
     * Use the current querystring as base, modify it according to $options and return the modified query string.
     *
     * @param array $options to set/change.
     * @param string $prepend this to the resulting query string
     * @return string with an updated query string.
     */
    function getQueryString($options=array(), $prepend='?') {
      // parse query string into array
      $query = array();
      parse_str($_SERVER['QUERY_STRING'], $query);
     
      // Modify the existing query string with new options
      $query = array_merge($query, $options);
     
      // Return the modified querystring
      return $prepend . http_build_query($query);
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