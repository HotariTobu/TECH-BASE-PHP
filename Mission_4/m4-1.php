<?php
     echo "start<br>";
     
     $dsn = 'hostname';
     $user = 'username';
     $password = 'password';
     $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

     echo "end<br>";
?>