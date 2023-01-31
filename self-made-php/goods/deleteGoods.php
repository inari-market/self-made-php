<?php
//実装時はコメント解除
function delete_goods($content) {
 if( is_page( 'goods/delete' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>商品削除ページ</title>
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
    <h1>商品削除ページ</h1>

                <table width="90%" class ='c'>
                <tr>
                  <th>商品ID</th>
                  <th>商品名</th>
                  <th>価格</th>
                  <th>概要</th>
                  <th>写真</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../../db.php';
            // データベースに接続します。
            $dbh = DbUtil::Connect();

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを>繰り返します。
            $sql = 'SELECT * FROM goods';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['goods_id']; ?></td>
            <td><?php echo $row['goods_name']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
            <?php
        $img_url = "http://100.24.172.143/photo/";
        echo '<figure class="wp-block-image size-full is-resized"><img decoding="async" src="' . $img_url . $row['photo_name'] .".png" . '" alt="画像が読み込めませんでした" width="60" height="60"></figure>';
        ?>
            </td>
            <td><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?id=<?php echo $row['goods_id']; ?>">削除</a></td>

        </tr>
    <?php } ?>

                </table>

                <?php
            session_start();
            if(! empty($_SESSION['delete_goods'])){
                echo("<br>".$_SESSION['delete_goods']."<br>");
                unset($_SESSION['delete_goods']);
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
        $sql = 'DELETE FROM goods where goods_id = :id';
        // SQL文を実行する準備をします。
        $stmt = $dbh->prepare( $sql );
        // プレースホルダに実際の値をバインドします。
        //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
        $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
        // SQL文を実行します。
        $stmt->execute();
        session_start();
        $_SESSION['delete_goods']="削除完了";
        echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/goods/delete/";</script>';
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

add_filter('the_content', 'delete_goods');

?>