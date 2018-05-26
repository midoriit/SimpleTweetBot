<?php
  require_once 'vendor/autoload.php';
  use Abraham\TwitterOAuth\TwitterOAuth;

  date_default_timezone_set('Asia/Tokyo');

  $consumer_key = '';                   // Consumer Key (API Key)
  $consumer_secret = '';                // Consumer Secret (API Secret)
  $accessToken = '';                    // Access Token
  $accessTokenSecret = '';              // Access Token Secret

  $mysql_server = '';                   // MySQLのサーバー名
  $mysql_database = '';                 // MySQLのデータベース名
  $mysql_username = '';                 // MySQLのユーザ名
  $mysql_password = '';                 // MySQLのパスワード
  $mysql_tablename = '';                // MySQLのテーブル名

  $mysqli = new mysqli( $mysql_server, $mysql_username, $mysql_password, $mysql_database );

  if ( !$mysqli ) {
    echo $mysqli->error;
    die();
  }
  $mysqli->query("SET NAMES 'utf8mb4'");   // 念の為

  $query = "SELECT id, tweet, count FROM ".$mysql_tablename.
  " WHERE last IS NULL || DATE_FORMAT(last, '%Y-%m-%d') != '".date("Y-m-d")."' ORDER BY count ASC, RAND() LIMIT 1";
  // last（最終ツイート日）!=今日のツイートをcount（ツイート回数）が少ないものからランダムに1件取得
  $ret = $mysqli->query( $query );
  if ( !$ret ) {
    echo $mysqli->error;
  } else {
    $row = $ret->fetch_assoc();
    if( !$row ) {
      echo 'Nothing to tweet.';
      $mysqli->close();
      die();
    }
    $id = $row["id"];
    $count = $row["count"];
    $tweet = $row["tweet"];
    $tweet = str_replace( "\\n", PHP_EOL, $tweet ); 

    try {
      $connection = new TwitterOAuth( $consumer_key, $consumer_secret, $accessToken, $accessTokenSecret );
      // ツイートする
      $result = $connection->post( "statuses/update", array("status" => $tweet) );
    } catch ( Exception $e ) {
      echo 'Twitter statuses/update error: '.$e->getMessage();
      $mysqli->close();
      die();
    }

    $query2 = "UPDATE ".$mysql_tablename.
              " SET count = ".strval($count+1).
              ", last = cast('".date("Y-m-d")."' AS DATE) WHERE id = ".strval($id);
    // countをインクリメントし、lastに今日の日付をセットする
    $ret2 = $mysqli->query( $query2 );
    if ( !$ret2 ) {
      echo $mysqli->error;
    }
  }
  $mysqli->close();
?>
