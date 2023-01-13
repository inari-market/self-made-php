# ここはいなりマーケットのback-end班のソースコード置き場です
## 使い方
- wordpressでテーマとしてlightningを使うことを前提としています．
- /var/www/html/wp-content/themes/lightningで'git clone git@github.com:inari-market/self-made-php.git .' としてください

## db.php
- 認証情報を含むためgithbubにはアップロードしていないが，/var/www/html/wp-content/themes/lightning/db.phpが必要である．ソースコードは以下
```
<?php
    class DbUtil{
        const _DNS = 'mysql:host=127.0.0.1;dbname=test;charset=utf8'; //ホストをAWSのIPアドレスに
        const _USER = 'db_username'; // dbのユーザネーム
        const _PASS = 'password'; // dbのパスワード

        private static $_dbh;

        public static function Connect(){
            if (!isset(self::$_dbh)) {
                self::$_dbh = new PDO( self::_DNS, self::_USER, self::_PASS );
                self::$_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$_dbh;
        }
    }
?>
```