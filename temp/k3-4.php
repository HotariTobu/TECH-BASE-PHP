<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-1</title>
</head>
<body>
    <form method="post">
        <!--コメントの入力フォーム-->
        <input type="text" name="comment" style = width:500px;height:30px, placeholder="comment">
        <!--名前の入力フォーム-->
        <input type="text" name="name" style = width:250px;height:30px, placeholder="name">
        <!--送信ボタン-->
        <input type="submit" value="送信" style = width:50px;height:30px;>
    </form>

<!-- 削除するフォームを作る -->
<form action="" method="POST">
  削除したい番号は？<br/>
 <input type="text" name="deleteNo" size="10" value=""/><br/>
 <input type="submit" name ="delete" value="削除する"/><br/>
</form>





<table border="1"
  <tr>
    <td>書き込み内容：</td>
    <td>

    <?php
      $filename = 'misson_3-3.txt';
      // もし何も入力されなかったらはじく
      if (isset($_POST["name"]) && isset($_POST["comment"])) {
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $fp = fopen($filename, 'a');
        fwrite($fp, "<>".$name."<>".$comment."<>".date("Y年m月d日 H時i分s秒")."\n");
        fclose($fp);
        // ファイルを配列に入れる
        $ret_array = file( $filename );
        
        // 取得したファイルデータ(配列)を全て表示する
        for( $i = 0; $i < count($ret_array); ++$i ) {
        // 配列を順番にばらばらにする
           // echo $i++;
           $fileNum = (($i+1)."<>".$ret_array[$i]);
           $filepieces = explode("<>", $fileNum);

           // ばらばらに分けた要素を表示
           for( $e = 0; $e < count($filepieces); ++$e ) {
             echo $filepieces[$e];
           }
           echo "<br>\n";
           echo "<br>\n";
        }
        
        

      }elseif (isset($_POST["delete"])) {
        $delete = $_POST["deleteNo"];
        $delCon = file($filename);
        for ($j = 0; $j < count($delCon); $j++) {
          $delDate = explode("<>", $delCon[$j]);
          if ($delDate[0] == $delete) {
            array_splice($delCon, $j, 1);
            file_put_contents($filename, implode("\n", $delCon));

          }
        }
        $newFile = file($filename);
        for($i = 0; $i < count($newFile) ; ++$i ) {
        // 配列を順番にばらばらにする
           $fileNum = (($i+1)."<>".$newFile[$i]);
           $filepieces = explode("<>", $fileNum);

           // ばらばらに分けた要素を表示
           for( $e = 0; $e < count($filepieces); ++$e ) {
             echo $filepieces[$e];
           }
           echo "<br>\n";
           echo "<br>\n";
        }

      } else {
        echo '入力してください';
      }
    ?>