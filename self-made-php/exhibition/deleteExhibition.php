<?php
//実装時はコメント解除

function delete_exhibition($content) {
 if( is_page( 'exhibitions/delete' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>

<html>
    <body>
        <h1>企画展情報</h1>

            <table width="90%">
            <tr>
            <th>企画展ID</th>
            <th>企画展名</th>
            <th>開始日</th>
            <th>終了日</th>
            <th>主催者名</th>
            <th>概要</th>
            <th>写真</th>
            </tr>

            <?php
                include_once dirname( __FILE__ ).'/../../db.php';
                $dbh = DbUtil::Connect();
                $sql = 'SELECT * FROM exhibition order by start asc';
                $stmt = $dbh->prepare( $sql );
                $stmt->execute();
                ?>

            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['exhibition_id']; ?></td>
                    <td><?php echo $row['exhibition_name']; ?></td>
                    <td><?php echo $row['start']; ?></td>
                    <td><?php echo $row['end']; ?></td>
                    <td><?php echo $row['organizer']; ?></td>
                    <td><?php echo htmlspecialchars($row['introduction'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php
                        $img_url = "http://52.54.93.120/exhibition/";
                        echo '<figure class="wp-block-image size-full is-resized"><img decoding="async" src="' . $img_url . $row['photo_name'] .".png" . '" alt="画像が読み込めませんでした" width="60" height="60"></figure>';
                        ?>
                    </td>
                    <td><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?id=<?php echo $row['exhibition_id']; ?>">削除</a></td>
                </tr>
            <?php } ?>

            </table>
                <?php
                    session_start();
                    if(! empty($_SESSION['delete_exhibition'])){
                        echo("<br>".$_SESSION['delete_exhibition']."<br>");
                        unset($_SESSION['delete_exhibition']);
                    }else{
                        echo("<br><br>");
                    }
                ?>
    </body>
</html>

<?php
    $id = $_GET['id'];
    if (! empty($id)) {

        try {
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'select * FROM exhibition where exhibition_id = :id';
            $stmt = $dbh->prepare( $sql );
            $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
            $stmt->execute();
            $img = $stmt->fetch(PDO::FETCH_ASSOC);
            
            unlink('/var/www/html/img/author/' . $img['photo_name'] . '.png');

            $sql = 'DELETE FROM exhibition where exhibition_id = :id';
            $stmt = $dbh->prepare( $sql );
            $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
            $stmt->execute();
            session_start();
            $_SESSION['delete_exhibition']="削除完了";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/delete/";</script>';
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

add_filter('the_content', 'delete_exhibition');

?>