<?php
//実装時はコメント解除
function delete_workshop_reserve($content) {
 if( is_page( 'workshop_reserves/delete' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ワークショップ予約削除ページ</title>
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
    <h1>ワークショップ予約情報表示</h1>

                <table width="90%" class ='c'>
                <tr>
                  <th>ワークショップ名</th>
                  <th>予約者ID</th>
                  <th>氏名</th>
                  <th>携帯電話番号</th>
                  <th>メールアドレス</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../db.php';
        try {   
            // データベースに接続します。
            $dbh = DbUtil::Connect();

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを>繰り返します。
            $sql = 'SELECT * FROM workshop_reserve, workshop where workshop.workshop_id = workshop_reserve.workshop_id order by workshop.deadline asc';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['workshop_name']; ?></td>
            <td><?php echo $row['reservation_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
            <td><?php echo $row['mail']; ?></td>
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
            if(! empty($_SESSION['delete_workshop_reserve'])){
                echo("<br>".$_SESSION['delete_workshop_reserve']."<br>");
                unset($_SESSION['delete_workshop_reserve']);
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
        $dbh = DbUtil::Connect();
        $sql = 'DELETE FROM workshop_reserve where reservation_id = :id';
        // SQL文を実行する準備をします。
        $stmt = $dbh->prepare( $sql );
        // プレースホルダに実際の値をバインドします。
        //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
        $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
        // SQL文を実行します。
        $stmt->execute();
        session_start();
        $_SESSION['delete_workshop_reserve']="削除完了";

    }catch( PDOException $e ){
        echo( '接続失敗: ' . $e->getMessage() . '<br>' );
        exit();
    }

    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/workshop_reserves/delete/";</script>';
        exit();

}
?>

<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'delete_workshop_reserve');

?>