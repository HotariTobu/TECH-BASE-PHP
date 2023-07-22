<form method="POST" action="">
    お名前：<input type="text" name="name"><br>
    コメント：<input type="text" name="comment"><br>
    <input type="submit" name="submit" value="送信">
</form>

<?php
    $filename="mission_3-1.txt";
    $name="";
    $comment="";
    
    if(!empty($_POST["name"]) and !empty($_POST["comment"])){
        
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $num=date("Y/m/d H:i:s");
        $lines=file($filename,FILE_IGNORE_NEW_LINES);
        $max=0;
        foreach($lines as $line){
            echo $line;
            $elements=explode("<>",$line);
            if($max<$elements[0]){
                $max=$elements[0];
            }
        }
        $max++;
        $str="$max<>$name<>$comment<>$num";
        $fp=fopen($filename,"a");
        $filename="mission_3-1.txt";
        fwrite($fp,$str.PHP_EOL);
        echo $str;
        fclose($fp);
    }
    
   
?>
