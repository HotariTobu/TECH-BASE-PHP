<?php
     echo "start<br>";
     
     $dsn = 'hostname';
     $user = 'username';
     $password = 'password';
     $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

     $sql = "CREATE TABLE IF NOT EXISTS kgbang"
     ." ("
     . "id INT AUTO_INCREMENT PRIMARY KEY,"
     . "name char(32),"
     . "comment TEXT,"
     . "date char(20),"
     . "password char(32)"
     .");";
     $stmt = $pdo->query($sql);

     echo "end<br>";
?>