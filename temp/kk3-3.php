<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
</head>
<body>
<form method="POST" action="">
    <!--入力フォーム-->
    <input type="text" name="name" placeholder="お名前"><br>
    <input type="text" name="comment" placeholder="コメント">
    <input type="submit" name="submit" value="送信"><br>
</form>
    <!--削除フォーム-->
<form method="POST" action="">
    <input type="text" name="delete" placeholder="削除対象番号">
    <input type="submit" name="submit" value="削除">
</form>
</body>
</html>

<?php
    $filename="mission_3-3.txt";
    $name="";
    $comment="";
    $delete="";
    $lines=file($filename,FILE_IGNORE_NEW_LINES);
  
    //入力フォームの内容をファイルに書き込み
     if(!empty($_POST["name"]) and !empty($_POST["comment"])){
        
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $num=date("Y/m/d H:i:s");
        $lines=file($filename,FILE_IGNORE_NEW_LINES);
        $max=0;
        foreach($lines as $line){
            $elements=explode("<>",$line);
            if($max<$elements[0]){
                $max=$elements[0];
            }
        }
        $max++;
        $str="$max<>$name<>$comment<>$num";
        $fp=fopen($filename,"a");
        fwrite($fp,$str.PHP_EOL);
        fclose($fp);
    }   
    
    if (!empty($_POST["delete"])){//削除フォームの処理
        $delete=$_POST["delete"];
        $fp=fopen($filename,"w");
        $max=0;
        foreach($lines as $line){
            $elements=explode("<>",$line);
            if($max<$elements[0]){
                $max=$elements[0];
            }
            if($max==$delete){
                fwrite($fp,"");
            }else{
                fwrite($fp,$line.PHP_EOL);
            }
        }
        fclose($fp);
    }
   
    //ファイルの中身を表示
    $lines=file($filename,FILE_IGNORE_NEW_LINES);
    foreach($lines as $line){
        echo $line."<br>";
    }
?>