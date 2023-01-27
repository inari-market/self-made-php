<?php
//実装時はコメント解除
/*
function delete_exhibition_db($content) {
 if( is_page( 'exibitions/delete_db' ))  //固定ページ「sample_cal」の時だけ 処理させる
 {


?>

<?php
$id = $_GET['id'];
if (empty($id)) {
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-44-212-247-129.compute-1.amazonaws.com/delete_exhibition";</script>';
    //header("http://localhost/inari/deleteExhibition_sample1.php");
    exit;
}

try {
    include_once dirname( __FILE__ ).'/../db.php';
    // SQL文を用意します。
    // :で始まる部分が後から値がセットされるプレースホルダです。
    // 複数回SQL文を実行する必要がある場合はここからexecute()までを繰り返し ます。
    $dbh = DbUtil::Connect();
    $sql = 'DELETE FROM exhibition_table where exhibition_id = :id';
    // SQL文を実行する準備をします。
    $stmt = $dbh->prepare( $sql );
    // プレースホルダに実際の値をバインドします。
    //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
    $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
    // SQL文を実行します。
    $stmt->execute();
    session_start();
    $_SESSION['delete_exhibition']="削除完了";
    //header('location:http://localhost/inari/deleteExhibition_sample1.php');
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-44-212-247-129.compute-1.amazonaws.com/delete_exhibition";</script>';
    exit();

}catch( PDOException $e ){
    echo( '接続失敗: ' . $e->getMessage() . '<br>' );
    exit();
}


?>
<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'delete_exhibition_db');
*/
?>