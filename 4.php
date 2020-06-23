<?php
  session_start();
  include("functions.php");

  // var_dump($_POST);
  // // var_dump($_FILES['upfile']);
  // exit();

  // $list = fopen('postalCode2Address.csv', 'r');
  $list = fopen('data.csv', 'r');
  // $list = fopen('data_2.csv', 'r');
  // $list = fopen('testAreaCode2City.csv', 'r');

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
  $pdo = connect_to_db();

  // SQL実行
  $sql = 'INSERT INTO jigokuT_table (id, post_id, address_id) VALUES (NULL, :post_id, :address_id)';

  // SQL実行の加工
  // while ($array = fgetcsv($list, 0, ",")) {
  //   for ($i = 0; $i < count($array); $i++) {
  //     $newarray[$h][$i] = $array[$i];
  //   }
  //   $h++;
  // }
  
  // $h = 0;
  // while($array = fgetcsv($list, 0, ",")){
  //   for($i = 0; $i < count($array); $i++){
  //     $newarray[$h][$i] = $array[$i];
  //     $stmt = $pdo->prepare($sql);
  //     $stmt->bindValue(':post_id', $newarray[$h][$i], PDO::PARAM_INT);
  //     $stmt->bindValue(':address_id', $newarray[$h][$i+1], PDO::PARAM_STR);
  //     $status = $stmt->execute();
  //   }
  //   $h++;
  // }

  // SQL準備&実行
  $j =0;
  for($k = 0; $k <= count($newarray); $k++){
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $newarray[$k][$j], PDO::PARAM_STR);
    $j = $j++;
    $stmt->bindValue(':post_id', $newarray[$k][$j], PDO::PARAM_STR);
    $j = $j++;
    $stmt->bindValue(':address_id', $newarray[$k][$j], PDO::PARAM_STR);
    $status = $stmt->execute();
  }

  // $stmt = $pdo->prepare($sql);
  // $stmt->bindValue(':post_id', $newarray[0][0], PDO::PARAM_INT);
  // $stmt->bindValue(':address_id', $newarray[0][1], PDO::PARAM_STR);
  // $status = $stmt->execute();

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
// }


?>