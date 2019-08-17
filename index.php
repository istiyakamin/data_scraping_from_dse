<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>

<!-- 
  <input type="date" name="DayEndSumDate1" id="DayEndSumDate1" value="<?php echo date('Y-m-d'); ?>" required="">
  <input type="date" name="DayEndSumDate2" id="DayEndSumDate2" value="<?php echo date('Y-m-d'); ?>" required=""> -->
  <input id="submit" type="submit">

    
  

<?php 


//header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

//GET Request
switch ($method) {
    case 'GET': // read data
    //getOperation();
    break;
}

//GET Function
function getOperation(){

  $DayEndSumDate1;
  $DayEndSumDate2;


  error_reporting(E_ALL ^ E_WARNING); 

  $sUrl = 'https://www.cse.com.bd/market/current_price';
  $html = file_get_contents($sUrl);
  
  $dom = new DOMDocument();
  @$dom->loadHTML($html);
  $xpath = new DomXPath($dom);
  $a = [];
  
  $elements = $xpath->query('//*[@id="dataTable"]/tbody');
  foreach($elements as $e){
  
    $len = $e->childNodes->length;
  
    for($n=0;$n<=$len;$n++){
  
      $scrip = $xpath->query('tr', $e)[$n]->childNodes[2]->nodeValue;
      $ltp = $xpath->query('tr', $e)[$n]->childNodes[4]->nodeValue;
      $open = $xpath->query('tr', $e)[$n]->childNodes[6]->nodeValue;
      $high = $xpath->query('tr', $e)[$n]->childNodes[8]->nodeValue;
      $low = $xpath->query('tr', $e)[$n]->childNodes[10]->nodeValue;
      $ycp = $xpath->query('tr', $e)[$n]->childNodes[12]->nodeValue;
      //$trade = $xpath->query('tr', $e)[$n]->childNodes[14]->nodeValue;
      //$value = $xpath->query('tr', $e)[$n]->childNodes[16]->nodeValue;
      $volume = $xpath->query('tr', $e)[$n]->childNodes[18]->nodeValue;

      if($ycp>0){
        $percentage=(($ltp-$ycp)/$ycp);
      } else {
        $percentage = 0;
      }
  
      $a[] = [
                'scrip_id'=>$scrip,
                'exchange'=>'CSE', 
                'category_id'=>'X',
                'buy_quantity'=>'0',
                'buy_price'=>'0',
                'sale_quantity'=>'0',
                'sale_price'=>'0',
                'ltp'=>$ltp, 
                'change'=>$ltp-$ycp,
                'imgs'=>'0',
                'percentage'=>$percentage,
                'volume'=>$volume,
                'time'=>'0',
                'high_price'=>$high, 
                'low_price'=>$low, 
                'market_pe'=>'0',
                'ycp'=>$ycp, 
                'open_price'=>$open,
                'close_price'=>'0'
              ];
  
    }
    

   $data =  json_encode($a);
   //echo json_encode($data);
  
  }
   

 } ?>
 <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script>
     $(document).ready(function () {
       //'use-strict'

       delete window.document.referrer;
        window.document.__defineGetter__('referrer', function () {
            return "http://www.dsebd.org/data_archive.php";
        });    

        $('#submit').click(function(){
              $.ajax({
              url: 'https://www.dsebd.org/day_end_archive.php',
              method: 'POST',
              beforeSend: function(xhr){
                
                xhr.setRequestHeader('X-Alt-Referer', 'http://www.dsebd.org/data_archive.php');
                $("#loading").css("display", "block");
              },
              data: {
                  'DayEndSumDate1': '2019-08-08',
                  'DayEndSumDate2': '2019-08-08',
                },
              success: function( data ) {
                  console.log(data);
                  //message("Post save successfully");
              },
              complete: function(){
                $("#loading").css("display", "none");
              },
              error: function( error ) {
                console.log( error );
              }
          });
       });
     });
  </script>
</body>
</html>