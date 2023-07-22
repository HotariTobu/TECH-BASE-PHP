<form action="#" method="post">
    <input type="text" name="comment" placeholder="コメント">
    <input type="submit" value="送信">
</form>
<?php
    
    if(!empty($_POST["comment"])){
        $str=$_POST["comment"];
        $filename="mission_2-2.txt";
        $fp=fopen($filename,"a");
        fwrite($fp,$str.PHP_EOL);
        fclose($fp);
        echo $str;
    }elseif($str=="完成！"){
        echo "おめでとう！";
    }
    
     if(file_exists($filename)){
        $datas=file("mission_2-2.txt", FILE_IGNORE_NEW_LINES);
        foreach($datas as$data){
            echo $data."<br>";
        }
     }
?>