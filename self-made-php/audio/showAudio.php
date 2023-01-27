<?php
    function show_audio($content) {
    if( is_page( 'show-audio' ))  {
        include_once dirname( __FILE__ ).'../../db.php';
?>
        
        <h1>音声情報</h1>
<?php
        $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
        $sql = 'select * from audio1 '; //全部抜く
        $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
        $stmt->execute(); // SQL文を実行します。
        $audios = $stmt->fetchAll(); // 結果をすべて読み出します。
        $count   = count( $audios ); // 作者の総数
        $i = 0;        
        echo 'count:' . $count;
        
        while($i < $count) {
           echo '<p><audio controls src="https://inari-dev.tk/audio/'. $audios[$i][name] .'.mp3" type="audio/mp3"></audio></p>';
           $i++;
        }
    } else {
        return $content;
    }
}

add_filter('the_content', 'show_audio');

?>