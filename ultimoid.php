<?php
$DB_ADDRESS="/cloudsql/delta-repeater-228411:europe-west3:bdd";
$DB_USER="pablo";
$DB_PASS="pablo";
$DB_NAME="bdd1";


$SQLKEY="secret";
$Consulta="SELECT id from mantenimiento where id=(select max(id) from mantenimiento);";

header('Cache-Control: no-cache, must-revalidate');
error_log(print_r($_POST,TRUE));
header('Content-type: text/csv');

    //$conn = new mysqli($DB_ADDRESS,$DB_USER,$DB_PASS,$DB_NAME);    
    $instance_name = "/cloudsql/delta-repeater-228411:europe-west3:bdd";
    $conn = new mysqli(null, $DB_USER, $DB_PASS, $DB_NAME, 0, $instance_name);
    if($conn->connect_error){                                                           
      header("HTTP/1.0 400 Bad Request");
      echo "ERROR Database Connection Failed: " . $conn->connect_error, E_USER_ERROR;   
    } else {
      $result=$conn->query($Consulta);                                                    
      if($result === false){  // este es el sitio donde he modificado
        header("HTTP/1.0 400 Bad Request");                                            
        echo "Wrong SQL: " . $query . " Error: " . $conn->error, E_USER_ERROR;          
      } else {
        if (strlen(stristr($Consulta,"SELECT"))>0) {                                     
          $csv = '';                                                                    

          $result->data_seek(0);
          while($row = $result->fetch_assoc()){
            foreach ($row as $key => $value) {     
              $csv .= $value.",";
            }
            $csv = rtrim($csv, ",");
          }
          echo $csv;                                                               
        } else {
          header("HTTP/1.0 201 Rows");
          echo "AFFECTED ROWS: " . $conn->affected_rows;       
        }
      }
      $conn->close();                                         
    } 
?>
