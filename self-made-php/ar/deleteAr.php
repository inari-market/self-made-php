<?php
function delete_ar($content) {
 if( is_page( 'ars/delete' ))  
 {

?>

<html>
    <body>
    <h1>ARデータ表示</h1>

    <?php
        session_start();
        if(! empty($_SESSION['delete_ar'])){
            echo("<br>".$_SESSION['delete_ar']."<br>");
            unset($_SESSION['delete_ar']);
        }else{
            echo("<br><br>");
        }
    ?>

    <table width="90%">
        <tr>
        <th>ARID</th>
        <th>マーカー</th>
        <th>オブジェクト</th>
        <th>音声</th>
        </tr>

        <?php
        include_once dirname( __FILE__ ).'/../../db.php';
        $dbh = DbUtil::Connect();
        $sql = 'SELECT * FROM ar';
        $stmt = $dbh->prepare( $sql );
        $stmt->execute();
        ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
        <td><?php echo $row['id']; ?></td>
        <td>
            <?php
                $img_url = "http://100.24.172.143/ar/marker/";
                echo '<figure class="wp-block-image size-large is-resized"><img decoding="async"  loading="lazy" src="' . $img_url . $row['marker'] .".png" . '" alt="画像が読み込めませんでした" width="100" height="100"></figure>';
            ?>
        </td>
        <td>
            <?php
                $img_url = "http://100.24.172.143/ar/object/";
                echo '<figure class="wp-block-image size-large is-resized"><img decoding="async"  loading="lazy" src="' . $img_url . $row['object'] .".png" . '" alt="画像が読み込めませんでした" width="100" height="100"></figure>';
            ?>
        </td>
        <td>
            <?php
                echo '<p><audio controls src="http://100.24.172.143/ar/sound/'. $row['sound'] .'.mp3" type="audio/mp3"></audio></p>';
            ?>
        </td>
        <td><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?id=<?php echo $row['id']; ?>">削除</a></td>
        </tr>
        <?php } ?>

    </table>

    </body>

    <?php
    $id = $_GET['id'];
    if (! empty($id)) {

        try {
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'DELETE FROM ar where id = :id';
            $stmt = $dbh->prepare( $sql );
            $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
            $stmt->execute();
            session_start();
            $_SESSION['delete_ar']="削除完了";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/delete/";</script>';
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

add_filter('the_content', 'delete_ar');

?>