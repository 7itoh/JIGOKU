<?php
  session_start();
  include("functions.php");

  // var_dump($_POST);
  // // var_dump($_FILES['upfile']);
  // exit();

  $list = fopen('areaCode2City.csv', 'r');

  $h = 0;
  while ($array = fgetcsv($list, 0, ",")) {
      for ($i = 0; $i < count($array); $i++) {
          $newarray[$h][$i] = $array[$i];
      }
      $h++;
  }

  // echo ('<pre>');
  // var_dump($newarray); 
  // // var_dump($newarray[0][0]); 
  // // var_dump($newarray[1][0]); 
  // echo ('<pre>');
  // exit();

  // DB接続
  function connect_to_db(){
    $dbn = 'mysql:dbname=jigoku_db;charset=utf8;port=3306;host=localhost';
    $user = 'root';
    $pwd = '';

    try {
      return new PDO($dbn, $user, $pwd);
      } catch (PDOException $e) {
      echo json_encode(["db error" => "{$e->getMessage()}"]);
      exit();
    }
  }

  $pdo = connect_to_db();

  // SQL実行
  $sql = 'INSERT INTO area_codes (id, area_code, city_name) VALUES (:id, :area_code, :city_name)';

  // SQL準備&実行
  for($k = 0; $k <= count($newarray); $k++){
    $j =0;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $newarray[$k][$j], PDO::PARAM_INT);
    $j++;
    $stmt->bindValue(':area_code', $newarray[$k][$j], PDO::PARAM_INT);
    $j++;
    $stmt->bindValue(':city_name', $newarray[$k][$j], PDO::PARAM_STR);
    $status = $stmt->execute();
  }

  // データ登録処理後
  if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
  } else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
    header("Location:4.php");
    exit();
  }

?>