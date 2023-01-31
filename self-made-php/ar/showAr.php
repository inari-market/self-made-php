<?php
function show_ar($content) {
 if( is_page( 'ars' ))  
 {

?>

<html>
    <body>
    <h1>ARデータ表示</h1>

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
        </tr>
        <?php } ?>

    </table>

    </body>
<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'show_ar');

?>