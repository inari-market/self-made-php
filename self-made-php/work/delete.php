<?php

function delete_work($content) {
 if( is_page( 'works/delete' ))  
 {

?>

<html>
    <body>
        <h1>作品情報</h1>

            <table width="90%">
            <tr>
            <th>作品ID</th>
            <th>作品名</th>
            <th>作者名</th>
            <th>制作年</th>
            <th>写真</th>
            </tr>

            <?php
            
                include_once dirname( __FILE__ ).'/../../db.php';
                $dbh = DbUtil::Connect();
                $sql = 'SELECT * FROM work'; 
                $stmt = $dbh->prepare( $sql );
                $stmt->execute();
            ?>

            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['author']; ?></td>
                    <td><?php echo $row['year']; ?></td>
                    <td>
                        <?php
                            $img_url = "http://52.54.93.120/img/work/";
                            echo '<figure class="wp-block-image size-full is-resized"><img decoding="async" src="' . $img_url . $row['image'] . '" alt="画像が読み込めませんでした" width="60" height="60"></figure>';
                        ?>
                    </td>
                    <td><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?id=<?php echo $row['id']; ?>">削除</a></td>
                    <td><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?id=<?php echo $row['id']; ?>">削除</a></td>
                </tr>
            <?php } ?>

            </table>
                <?php
                    session_start();
                    if(! empty($_SESSION['delete_work'])){
                        echo("<br>".$_SESSION['delete_work']."<br>");
                        unset($_SESSION['delete_work']);
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
            $sql = 'select * FROM work where id = :id';
            $stmt = $dbh->prepare( $sql );
            $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
            $stmt->execute();
            $img = $stmt->fetch(PDO::FETCH_ASSOC);
            
            unlink('/var/www/html/img/work/' . $img['image']);

            $sql = 'DELETE FROM work where id = :id';
            $stmt = $dbh->prepare( $sql );
            $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
            $stmt->execute();
            session_start();
            $_SESSION['delete_work']="削除完了";
            unset($_GET['id']);
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://52.54.93.120/works/delete";</script>';
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

add_filter('the_content', 'delete_work');

?>