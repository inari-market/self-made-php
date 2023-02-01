<?php
    function edit_workshop($content) {
        if( is_page( 'workshops/edit' )) {//特定の固定ページの時だけ処理させる 
            include_once dirname( __FILE__ ).'/../../db.php';
            if(isset($_POST['submit'])) { // updateの処理
                session_start();
                try {
                    $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                    $sql = 'update workshop set workshop_name = :workshop_name, introduction=:introduction, capacity=:capacity, organizer=:organizer, cost=:cost, start=:start, end=:end, deadline=:deadline where workshop_id = :id';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
                    
                    // プレースホルダに実際の値をバインドします。
                    $stmt->bindValue( ':id', $_POST['id'], PDO::PARAM_STR );
                    $stmt->bindValue( ':workshop_name', $_POST[workshop_name], PDO::PARAM_STR );
                    $stmt->bindValue( ':introduction', $_POST[introduction], PDO::PARAM_STR );
                    $stmt->bindValue( ':capacity', $_POST[capacity], PDO::PARAM_INT );
                    $stmt->bindValue( ':organizer', $_POST[organizer], PDO::PARAM_STR );
                    $stmt->bindValue( ':cost', $_POST[cost], PDO::PARAM_INT );
                    $stmt->bindValue( ':start', $_POST[start], PDO::PARAM_STR );
                    $stmt->bindValue( ':end', $_POST[end], PDO::PARAM_STR );
                    $stmt->bindValue( ':deadline', $_POST[deadline], PDO::PARAM_STR );
                    $stmt->execute(); // sqlの実行
                    echo 'hoge1';
                } catch( PDOException $e ) {
                    echo 'hogeerror';
                    $_SESSION['edit_workshop'] = '接続失敗: ' . $e->getMessage() . '<br>';
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                    exit();
                }


                $_SESSION['edit_workshop'] = '更新成功';
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                exit();
            }
            
            try { // editページの生成
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select * from workshop where workshop_id = :id'; // 該当するカラムを抜く
                $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。

                $stmt->bindValue( ':id', $_GET['id'], PDO::PARAM_STR );
                $stmt->execute(); // SQL文を実行します。

                $result = $stmt->fetch(); // 結果を読み出します。             

                echo '<div class="entry-body">';
                echo '<div class="wp-block-columns">&nbsp;</div>';
                    ?>
                <html>
                <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

                    <br>
                    <p>ワークショップ名</p>
                        <input type="text" name="workshop_name" placeholder="ワークショップ名を入力" maxlength="64" value="<?php echo $result['workshop_name']; ?>"> <br>
                    <p>主催者</p>
                        <input type="text" name="organizer" placeholder="主催者名を入力" maxlength="32" value="<?php echo $result['organizer']; ?>"> <br>
                    <p>概要</p> 
                        <textarea  name="introduction" cols="40" maxlength="1024" placeholder="ワークショップの概要を入力"><?php echo $result['introduction']?></textarea> <br>
                    <p>参加可能人数</p>
                        <input type="text" name="capacity" placeholder="参加可能人数を入力" maxlength="11" value="<?php echo $result['capacity']; ?>"> <br>
                    <p>一人あたりの参加料金</p>
                        <input type="text" name="cost" placeholder="一人あたりの参加料金を入力" maxlength="11" value="<?php echo $result['cost']; ?>"> <br>
                    <p>開始日</p>
                        <input type="date" name="start" value="<?php echo $result['start']; ?>"> <br><br>
                    <p>終了日</p>
                        <input type="date" name="end" value="<?php echo $result['end']; ?>"> <br><br>  
                    <p>予約締切日</p>
                        <input type="date" name="deadline" value="<?php echo $result['deadline']; ?>"> <br><br> 

                        <?php
                            session_start();
                            if(! empty($_SESSION['edit_workshop'])){
                                echo("<br>".$_SESSION['edit_workshop']."<br>");
                                unset($_SESSION['edit_workshop']);
                            }else{
                                echo("<br><br>");
                            }
                        ?><br>

                        <input type="submit" name = "submit" value="更新"> <br>

                </form>  
                </html>
                <?php
                echo '</div>';
                
            }catch( PDOException $e ){
                $_SESSION['edit_workshop'] =  '接続失敗: ' . $e->getMessage() . '<br>' ;
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                exit();
            }       
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'edit_workshop');
    
?>