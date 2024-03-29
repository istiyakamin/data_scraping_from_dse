<?php
//error_reporting(E_ALL ^ E_NOTICE);  
//header('Content-Type: application/json');


getOperation();


//GET Function
function getOperation(){

  error_reporting(E_ALL ^ E_WARNING); 

    $conn = pg_connect("host=yourhost.com port=5432 dbname=dbname user=username password=password");

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

    echo $trade_date = date('Y-m-d');
    echo $trading_code = $e->childNodes[1]->nodeValue;
    echo $ltp = str_replace(',', '', $e->childNodes[2]->nodeValue);
    echo $high = str_replace(',', '', $e->childNodes[3]->nodeValue);
    echo $low = str_replace(',', '', $e->childNodes[4]->nodeValue);
    echo $closep = str_replace(',', '', $e->childNodes[5]->nodeValue);
    echo $ycp = str_replace(',', '', $e->childNodes[6]->nodeValue);
    echo $change = str_replace(',', '', $e->childNodes[7]->nodeValue);
    echo $trade = str_replace(',', '', $e->childNodes[8]->nodeValue);
    echo $value = str_replace(',', '', $e->childNodes[9]->nodeValue);
    echo $volume = str_replace(',', '', $e->childNodes[10]->nodeValue);
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
   
