<?php
    echo "start<br>";
    
    $dsn = 'hostname';
    $user = 'username';
    $password = 'password';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = 'SHOW CREATE TABLE tbtest';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        //echo $row[0];
        echo $row[1];
        echo '<br>';
    }
    echo "<hr>";

    echo "end<br>";
?>