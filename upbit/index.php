<?
$m = "0";
$o = "d";
if(isset($_GET["m"]))
 $m = $_GET["m"];
if(isset($_GET["o"]))
 $o = $_GET["o"];
require_once("DB/dbconfig.php");
$sql="SELECT * FROM log where name='START'";
$resultnow = mysqli_query($db,$sql);
$row=mysqli_fetch_array($resultnow);
$starttime = $row['time'];

$lastdate="SELECT * FROM log where name='PARSING'";;
$last = mysqli_query($db,$lastdate);
$l=mysqli_fetch_array($last);
$lasttime = $l['time'];
?>
<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 위 3개의 메타 태그는 *반드시* head 태그의 처음에 와야합니다; 어떤 다른 콘텐츠들은 반드시 이 태그들 *다음에* 와야 합니다 -->
    <title>Upbit Parsing</title>

    <!-- 부트스트랩 -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      body{
        color:#000;
        font-size:12px;
      }
      .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
        background-color: aqua;
      }
      th {
      text-align: right;
      }
      .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
      padding-left: 0px;

      }
    </style>
    <!-- IE8 에서 HTML5 요소와 미디어 쿼리를 위한 HTML5 shim 와 Respond.js -->
    <!-- WARNING: Respond.js 는 당신이 file:// 을 통해 페이지를 볼 때는 동작하지 않습니다. -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="background-color: #FAFAFA;">
    <div class="row">
    
    <div class="col-md-10">
      <h6>Start Date : <?=$starttime;?> </h1>
      <h6><mark>Last Date : <?=$lasttime?> <mark></h6>
      </div>
    </div>
    <div class="row">
      <div class="col-md-10">
      <table class="table table-bordered table-hover" style="text-align:right">
        <thead >
          <tr >
            <th>코인
             <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=0&o=u" class="glyphicon glyphicon-chevron-up" aria-hidden="true"></a>
            </button>
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=0&o=d" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></a>
            </button>
           </th>
            <th>현재가</th>
            <th>1일 OPEN</th>
            <th>변동금액</th>
            <th>변동률</th>
            <th class="seperate">1일
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=1&o=u" class="glyphicon glyphicon-chevron-up" aria-hidden="true"></a>
            </button>
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=1&o=d" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></a></th>
            <th>4시간 OPEN</th>
            <th>변동금액</th>
            <th>변동률</th>
            <th  class="seperate">4시간
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=2&o=u" class="glyphicon glyphicon-chevron-up" aria-hidden="true"></a>
            </button>
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=2&o=d" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></a></th>
            <th>1시간 OPEN</th>
            <th>변동금액</th>
            <th>변동률</th>
            <th>1시간
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=3&o=u" class="glyphicon glyphicon-chevron-up" aria-hidden="true"></a>
            </button>
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=3&o=d" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></a></th>
            <!--
            <th>Total
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=0&o=u" class="glyphicon glyphicon-chevron-up" aria-hidden="true"></a>
            </button>
            <button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
              <a href ="?m=0&o=d" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></a>
            </button>
            </th>
          -->
          </tr>
        </thead>
        <tbody>
        <?
          if($m == "0"){
            $order = "DESC";
            if($o =="u" )
              $order = "ASC";
            $sql="SELECT * FROM coin order by coin_short ".$order;
          }
          elseif ($m == "1") {
            # code...
            $order = "DESC";
            if($o =="u" )
              $order = "ASC";
            $sql="SELECT * FROM coin order by transaction_day ".$order;

          }
          elseif ($m == "2") {
            # code...
            $order = "DESC";
            if($o =="u" )
              $order = "ASC";
            $sql="SELECT * FROM coin order by transaction_4hr ".$order;

          }
          elseif ($m == "3") {
            # code...
            $order = "DESC";
            if($o =="u" )
              $order = "ASC";
            $sql="SELECT * FROM coin order by transaction_1hr ".$order;

          }


          $resultnow = mysqli_query($db,$sql);
          while($row=mysqli_fetch_array($resultnow))
          {
            $diff_1day = $row['price_now'] - $row['open_day'];
            if ($diff_1day <= 0){
              $diff_1day =  '<font style="color:blue">'.number_format($diff_1day).'</font>';
            }
            else{
              $diff_1day = '<font style="color:red">'.number_format($diff_1day).'</font>';
            }
            $per_1day = ($row['price_now'] - $row['open_day'])/$row['open_day']*100;
            if ($per_1day >= 0){
              $per_1day =  '<code style="color:red">'.number_format($per_1day,2).'%</code>';
            }
            else{
             $per_1day =  '<code style="color:blue">'.number_format($per_1day,2).'%</code>'; 
            }

            //4시간
            $diff_4h = $row['price_now'] - $row['open_4hr'];
            if ($diff_4h <= 0){
              $diff_4h =  '<font style="color:blue">'.number_format($diff_4h).'</font>';
            }
            else{
              $diff_4h =  '<font style="color:red">'.number_format($diff_4h).'</font>';
            }
            $per_4hr = ($row['price_now'] - $row['open_4hr'])/$row['open_4hr']*100;
            if ($per_4hr >= 0){
              $per_4hr =  '<code style="color:red">'.number_format($per_4hr,2).'%</code>';
            }
            else{
             $per_4hr =  '<code style="color:blue">'.number_format($per_4hr,2).'%</code>'; 
            }
            // 1시간

            $diff_1h = $row['price_now'] - $row['open_1hr'];
            if ($diff_1h <= 0){
              $diff_1h =  '<font style="color:blue">'.number_format($diff_1h).'</font>';
            }
            else{
              $diff_1h =  '<font style="color:red">'.number_format($diff_1h).'</font>';
            }
            $per_1hr = ($row['price_now'] - $row['open_1hr'])/$row['open_1hr']*100;
            if ($per_1hr >= 0){
              $per_1hr =  '<code style="color:red">'.number_format($per_1hr,2).'%</code>';
            }
            else{
             $per_1hr =  '<code style="color:blue">'.number_format($per_1hr,2).'%</code>'; 
            }

            echo '
             <tr>
              <th scope="row">'.$row['coin_short'].'</th>
              <td>'.number_format($row['price_now']).'</td>
              <td>'.number_format($row['open_day']).'</td>
              <td>'.$diff_1day.'</td>
              <td>'.$per_1day.'</td>
              <td class="seperate">'.number_format($row['transaction_day']).'</td>
              <td>'.number_format($row['open_4hr']).'</td>
              <td>'.$diff_4h.'</td>
              <td>'.$per_4hr.'</td>
              <td class="seperate">'.number_format($row['transaction_4hr']).'</td>
              <td>'.number_format($row['open_1hr']).'</td>
              <td>'.$diff_1h.'</td>
              <td>'.$per_1hr.'</td>
              <td>'.number_format($row['transaction_1hr']).'</td>
            </tr>
             ';
             /*
             <td><code>'.$value3m.'%</code> ('.round($row3m['sum(buy)'],4).'/'.round($row3m['sum(buy)']+$row3m['sum(sell)'],4).')</td>
              <td><code>'.$value5m.'%</code> ('.round($row5m['sum(buy)'],4).'/'.round($row5m['sum(buy)']+$row5m['sum(sell)'],4).')</td>
              <td><code>'.$value10m.'%</code> ('.round($row10m['sum(buy)'],4).'/'.round($row10m['sum(buy)']+$row10m['sum(sell)'],4).')</td>
              <td><code>'.$value30m.'%</code> ('.round($row30m['sum(buy)'],4).'/'.round($row30m['sum(buy)']+$row30m['sum(sell)'],4).')</td>
              <td><code>'.$value60m.'%</code> ('.round($row60m['sum(buy)'],4).'/'.round($row60m['sum(buy)']+$row60m['sum(sell)'],4).')</td>
              <td><code>'.$valuetotal.'%</code> ('.round($rowtotal['sum(buy)'],4).'/'.round($rowtotal['sum(buy)']+$rowtotal['sum(sell)'],4).')</td>
              */
          }
        ?>
         
      </table>
      </div>
    </div>
    <script language='javascript'> 
      window.setTimeout('window.location.reload()',30000); //60초마다 새로고침
    </script>
    <!-- jQuery (부트스트랩의 자바스크립트 플러그인을 위해 필요합니다) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- 모든 컴파일된 플러그인을 포함합니다 (아래), 원하지 않는다면 필요한 각각의 파일을 포함하세요 -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>