<?php
//実装時はコメント解除
function show_workshop_reserve($content) {
 if( is_page( 'workshopReserves' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ワークショップ予約表示ページ</title>
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
                  <th>ワークショップID</th>
                  <th>予約者ID</th>
                  <th>氏名</th>
                  <th>携帯電話番号</th>
                  <th>メールアドレス</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../db.php';
            // データベースに接続します。
            $dbh = DbUtil::Connect();

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを>繰り返します。
            $sql = 'SELECT * FROM workshop_reserve group by exhibition_id';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['workshop_id']; ?></td>
            <td><?php echo $row['reservation_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
            <td><?php echo $row['mail']; ?></td>
            <td><?php echo htmlspecialchars($row['introduction'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    <?php } ?>

                </table>

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

add_filter('the_content', 'show_workshop_reserve');

?>