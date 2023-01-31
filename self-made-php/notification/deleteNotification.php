<?php
function delete_notification($content) {
 if( is_page( 'notifications/delete' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>お知らせ情報削除ページ</title>
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
    <h1>お知らせ情報削除ページ</h1>

                <table width="90%" class ='c'>
                <tr>
                  <th>お知らせID</th>
                  <th>タイトル</th>
                  <th>内容</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM notice';
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['body']; ?></td>    
            <td><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?id=<?php echo $row['id']; ?>">削除</a></td>

        </tr>
    <?php } ?>

                </table>

                <?php
            session_start();
            if(! empty($_SESSION['delete_notification'])){
                echo("<br>".$_SESSION['delete_notification']."<br>");
                unset($_SESSION['delete_notification']);
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
        include_once dirname( __FILE__ ).'/../../db.php';
        $dbh = DbUtil::Connect();
        $sql = 'DELETE FROM notice where id = :id';
        $stmt = $dbh->prepare( $sql );
        $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
        $stmt->execute();
        session_start();
        $_SESSION['delete_notification']="削除完了";
        echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
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

add_filter('the_content', 'delete_notification');

?>