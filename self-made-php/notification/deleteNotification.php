<?php
    function delete_notification($content) {
        if( is_page( 'notifications/delete' )) {
?>
            <h1>お知らせ情報削除ページ</h1>
            <table width="90%">
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
                    <td style="word-break:break-word"><?php echo $row['body']; ?></td>    
                    <td><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?id=<?php echo $row['id']; ?>">削除</a></td>
                    <td><a href="/notifications/edit/?id=<?php echo $row['id']; ?>">編集</a></td>
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
        } else {
            return $content;
        }
    }

    add_filter('the_content', 'delete_notification');

?>