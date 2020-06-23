<?php
  session_start();
  include("functions.php");

  // var_dump($_POST);
  // // var_dump($_FILES['upfile']);
  // exit();

  $list = fopen('postalCode2Address.csv', 'r');

  $h = 0;
  while ($array = fgetcsv($list, 0, ",")) {
      for ($i = 0; $i < count($array); $i++) {
          $newarray[$h][$i] = $array[$i];
      }
      $h++;
  }

  // $a = 0;
  // $b = 0;
  // echo ('<pre>');
  // var_dump($newarray); 
  // var_dump($newarray[$a][$b]); 
  // var_dump($newarray[$a][$b+1]); 
  // // var_dump($newarray[$a][$b+3]); 
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
  $sql = 'INSERT INTO postal_codes (id, postal_code, prefecture, city_name, place_name) VALUES (:id, :postal_code, :prefecture, :city_name, :place_name)';

  // SQL準備&実行
  $j = 0;
  for($k = 0; $k <= count($newarray); $k++){
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $newarray[$k][$j], PDO::PARAM_INT);
    $j++;
    $stmt->bindValue(':postal_code', $newarray[$k][$j], PDO::PARAM_INT);
    $j++;
    $stmt->bindValue(':prefecture', $newarray[$k][$j], PDO::PARAM_STR);
    $j++;
    $stmt->bindValue(':city_name', $newarray[$k][$j], PDO::PARAM_STR);
    $j++;
    $stmt->bindValue(':place_name', $newarray[$k][$j], PDO::PARAM_STR);
    $status = $stmt->execute();
  }

  // データ登録処理後
  if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
  } else {
    header("Location:5.php");
    exit();
  }
// }


?>