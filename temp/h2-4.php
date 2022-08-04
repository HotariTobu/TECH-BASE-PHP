<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-4</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="str" placeholder="おめでとう！by（名前）">
        <input type="submit" name="submit">
    </form>
    
<?php
    if (!empty($_POST["str"])) {
        $str = $_POST["str"];
    
        if (!empty($str)) {
        $filename="mission_2-4.txt";
        $fp = fopen($filename,"a");
        fwrite($fp, $str.PHP_EOL);
        fclose($fp);
        
        $lines = file($filename,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line){
        echo $line, "<br>";
    }
    }
    }
?>
</body>
</html>