<?php
     echo "start<br>";
     
     $dsn = 'hostname';
     $user = 'username';
     $password = 'password';
     $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

     $sql = "CREATE TABLE IF NOT EXISTS tbtest"
     ." ("
     . "id INT AUTO_INCREMENT PRIMARY KEY,"
     . "name char(32),"
     . "comment TEXT"
     .");";
     $stmt = $pdo->query($sql);

     echo "end<br>";
?>