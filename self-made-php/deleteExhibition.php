<?php
//実装時はコメント解除

function delete_exhibition($content) {
 if( is_page( 'delete_exhibition' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>展示会情報削除ページ</title>
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
    <h1>展示会情報</h1>

                <table width="70%" class ='c'>
                <tr>
                  <th>展示会ID</th>
                  <th>展示会名</th>
                  <th>開始日</th>
                  <th>終了日</th>
                  <th>主催者名</th>
                  <th>概要</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../db.php';
            // データベースに接続します。
            $dbh = DbUtil::Connect();

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを>繰り返します。
            $sql = 'SELECT * FROM exhibition_table';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['exhibition_id']; ?></td>
            <td><?php echo $row['exhibition_name']; ?></td>
            <td><?php echo $row['start']; ?></td>
            <td><?php echo $row['end']; ?></td>
            <td><?php echo $row['organizer']; ?></td>
            <td><?php echo htmlspecialchars($row['introducation'], ENT_QUOTES, 'UTF-8'); ?></td>
           <td><a href="http://ec2-44-212-247-129.compute-1.amazonaws.com/wp-admin/delete_exhibition_db?id=<?php echo $row['exhibition_id']; ?>">削除</a></td>
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
</div>
    </body>
</html>
<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'delete_exhibition');

?>