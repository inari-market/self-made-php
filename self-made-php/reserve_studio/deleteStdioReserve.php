<?php
//実装時はコメント解除
function delete_stdio_reserve($content) {
 if( is_page( 'stdio_reserves/delete' ))  //固定ページ「sample_cal」の時だけ処理させる
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
            <td><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?id=<?php echo $row['reservation_id']; ?>">削除</a></td>

        </tr>
    <?php } 
        }catch( PDOException $e ){
            echo( '接続失敗: ' . $e->getMessage() . '<br>' );
            exit();
            }
            ?>

                </table>

                <?php
            session_start();
            if(! empty($_SESSION['delete_stdio_reserve'])){
                echo("<br>".$_SESSION['delete_stdio_reserve']."<br>");
                unset($_SESSION['delete_stdio_reserve']);
            }else{
                echo("<br><br>");
            }
            ?>


</div>
    </body>
</html>


<?php
$id = $_GET['id'];
if (! empty($id)) {

    try {
        include_once dirname( __FILE__ ).'/../db.php';
        // SQL文を用意します。
        // :で始まる部分が後から値がセットされるプレースホルダです。
        // 複数回SQL文を実行する必要がある場合はここからexecute()までを繰り返し ます。
        $dbh = DbUtil::Connect();
        $sql = 'DELETE FROM stdio_reserve where reservation_id = :id';
        // SQL文を実行する準備をします。
        $stmt = $dbh->prepare( $sql );
        // プレースホルダに実際の値をバインドします。
        //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
        $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
        // SQL文を実行します。
        $stmt->execute();
        session_start();
        $_SESSION['delete_stdio_reserve']="削除完了";
        echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserves/delete/";</script>';
        exit();

    }catch( PDOException $e ){
        echo( '接続失敗: ' . $e->getMessage() . '<br>' );
        exit();
    }

}
?>

<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'delete_stdio_reserve');

?>