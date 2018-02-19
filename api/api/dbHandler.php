<?php
class DbHandler {
    private $conn;
    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();
    }
    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $result = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result->fetch_assoc();
    }
    /**
     * Fetching All records
     */
    public function getRecords($query) {
        $result = $this->conn->query($query) or die($this->conn->error.__LINE__);
        $arr = array();
        if($result->num_rows > 0)
        {
           while($row = $result->fetch_assoc())
           {
            $arr[] = $row;
           }
          return $arr;
        }
    }
    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
        $result = $this->conn->query($query) or die($this->conn->error.__LINE__);
        if ($result) {
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
            } else {
            return NULL;
        }
    }

    public function insert($query) {
        $result = $this->conn->query($query) ;
        //echo json_encode($result);
        //$result  = $this->conn->query("select LAST_INSERT_ID()") or die($this->conn->error.__LINE__);
        return $this->conn->insert_id;
    }

    /**
     * Update record
     */
    public function updateIntoTable($query) {
        $result = $this->conn->query($query) or die($this->conn->error.__LINE__);
        return $result;
    }
    /**
     * delete record
     */
    public function deleteIntoTable($query) {
        $result = $this->conn->query($query) or die($this->conn->error.__LINE__);
        return $result;
    }
  }
?>
