<?php
error_reporting(E_ALL ^ E_NOTICE);  
//header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

//GET Request
switch ($method) {
    case 'GET': // read data
    getOperation();
    break;
}

//GET Function
function getOperation(){

  error_reporting(E_ALL ^ E_WARNING); 

    $conn = pg_connect("host=node29.chieferp.com port=5432 dbname=ai_ta user=postgres password=adminxp123");

    if (!$conn) {
    echo "Datebase Connection Error.\n";
    exit;
    }
    $trade_date = date('Y-m-d');
    $delete_query = "DELETE FROM ta.dse where date='{$trade_date}'";
    $result = pg_query($conn, $delete_query);
    
    if (!$result) {
        echo "Error in query execution occured. : ".$delete_query."\n";
        die;
    }

  $sUrl = 'https://dsebd.org/latest_share_price_all_by_change.php';
  $html = file_get_contents($sUrl);
  
  $dom = new DOMDocument();
  @$dom->loadHTML($html);
  $xpath = new DomXPath($dom);
  $a = [];
  
  $elements = $xpath->query('//tr');
  $count = 0;
  foreach($elements as $e){
  
    $len = $e->childNodes->length;
    //echo $len;

        
        echo $trade_date = date('Y-m-d');
        echo $trading_code = $e->childNodes[1]->nodeValue;
        echo $ltp = (int)$e->childNodes[2]->nodeValue;
        echo $high = (int)$e->childNodes[3]->nodeValue;
        echo $low = (int)$e->childNodes[4]->nodeValue;
        echo $closep = (int)$e->childNodes[5]->nodeValue;
        echo $ycp = (int)$e->childNodes[6]->nodeValue;
        echo $change = (int)$e->childNodes[7]->nodeValue;
        echo $trade = (int)$e->childNodes[8]->nodeValue;
        echo $value = (int)$e->childNodes[9]->nodeValue;
        echo $volume = (int)$e->childNodes[10]->nodeValue;
        echo "</br>";

     
        $count++;
       

        if($count > 1){
          $insert_query = "INSERT INTO ta.dse(date, tradeing_code, ltp, high, low, openp, closep, ycp, trade, value, volume) 
          VALUES ('{$trade_date}', '{$trading_code}', '{$ltp}', '{$high}', '{$low}', '{$closep}', '{$ycp}', '{$change}', '{$trade}', '{$value}', '{$volume}');
          ";
        $success_result = pg_query($conn, $insert_query);
        //echo $insert_query;
        //die;
        
        if (!$success_result) {
            echo "Error in query execution occured. : ".$insert_query."\n";
            die;
        }
    }

   

  }
 
  }
   
?>