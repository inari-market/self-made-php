<?php
    function delete_author($content) {
        if( is_page( 'authors/delete' )) {//特定の固定ページの時だけ処理させる 
            $id = $_GET['id'];
            if (empty($id)) {
                // header("https://inari-dev.tk/authors");
                echo '<script type="text/javascript">window.location.href = "https://inari-dev.tk/authors";</script>';
                exit;
            }

            try {
                include_once dirname( __FILE__ ).'/db.php';

                $dbh = DbUtil::Connect();
                $sql = 'DELETE FROM authors2 where id = :id';
                $stmt = $dbh->prepare( $sql );
                $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
                $stmt->execute(); // SQL文を実行します。
                unlink('/var/www/html/img/author/' . $id . '.png');
                // session_start();
                // $_SESSION['delete_exhibitio']="削除完了";
                // wp_redirect('https://inari-dev.tk/authors');
                // header('location:https://inari-dev.tk/authors/');
                // exit();
                echo '<script type="text/javascript">window.location.href = "https://inari-dev.tk/authors";</script>';

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
            }

            // wp_safe_redirect( home_url() );
	        exit;
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'delete_author');
?>