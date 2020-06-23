<?php
  session_start();
  include("functions.php");

  // var_dump($_POST);
  // // var_dump($_FILES['upfile']);
  // exit();

  $list = fopen('postalCode2Address.csv', 'r');
  // $list = fopen('phone.csv', 'r');
  // $list = fopen('data.csv', 'r');
  // $list = fopen('data_2.csv', 'r');
  // $list = fopen('testAreaCode2City.csv', 'r');

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
  $pdo = connect_to_db();

  // SQL実行
  $sql = 'INSERT INTO jigokuT2_table (id, phone_id, ken_id, city_id, area_id) VALUES (NULL, :phone_id, :ken_id, :city_id, :area_id)';

  // SQL準備&実行
  $j = 0;
  for($k = 0; $k <= count($newarray); $k++){
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $newarray[$k][$j], PDO::PARAM_INT);
    $j++;
    $stmt->bindValue(':phone_id', $newarray[$k][$j], PDO::PARAM_INT);
    $j++;
    $stmt->bindValue(':ken_id', $newarray[$k][$j], PDO::PARAM_STR);
    $j++;
    $stmt->bindValue(':city_id', $newarray[$k][$j], PDO::PARAM_STR);
    $j++;
    $stmt->bindValue(':area_id', $newarray[$k][$j], PDO::PARAM_STR);
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