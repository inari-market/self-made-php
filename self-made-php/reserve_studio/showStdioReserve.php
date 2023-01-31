<?php
//実装時はコメント解除
function show_stdio_reserve($content) {
 if( is_page( 'stdio_reserves' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>アトリエ予約表示ページ</title>
        <style type = "text/css">
    <!--
    .c{
        text-align:center;
    }
    .pos{
        position:absolute; bottom:0%; right:0%;
     }
    -->
    </style>
    </head>
    <body>
        <div class='c'>
    <h1>アトリエ予約情報表示</h1>

                <table width="90%" class ='c'>
                <tr>
                  <th>アトリエ予約ID</th>
                  <th>開始</th>
                  <th>終了</th>
                  <th>目的</th>
                  <th>冷暖房</th>
                  <th>氏名</th>
                  <th>住所</th>
                  <th>携帯電話番号</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../../db.php';
        try {   
            // データベースに接続します。
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM stdio_reserve order by start_date asc';
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['reservation_id']; ?></td>
            <td><?php echo $row['start_date']."日".$row['start_time']."時"; ?></td>
            <td><?php echo $row['end_date']."日".$row['end_time']."時"; ?></td>
            <td><?php echo $row['purpose']; ?></td>
            <td><?php 
                    if($row['air'] == 1){
                        echo("〇");
                    }else{
                        echo("×");
                    }
                ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
        </tr>
    <?php } 
        }catch( PDOException $e ){
            echo( '接続失敗: ' . $e->getMessage() . '<br>' );
            exit();
            }
            ?>

                </table>

</div>
    </body>
</html>
<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'show_stdio_reserve');

?>