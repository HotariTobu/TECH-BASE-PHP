<html>
    <head>
        <title>KGBANG</title>
    </head>
    <body>
        <?php
            $submit = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!empty($_POST["submit"])) {
                    $submit = $_POST["submit"];
                }
                
                if (!empty($_POST["name"])) {
                    $name = $_POST["name"];
                }
                
                if (!empty($_POST["comment"])) {
                    $comment = $_POST["comment"];
                }
                
                if (!empty($_POST["remove_index"])) {
                    $remove_index = $_POST["remove_index"];
                }
                
                if (!empty($_POST["edit_index"])) {
                    $edit_index = $_POST["edit_index"];
                }
                
                if (!empty($_POST["set_edit_index"])) {
                    $set_edit_index = $_POST["set_edit_index"];
                }
                
                if (!empty($_POST["add_password"])) {
                    $password = $_POST["add_password"];
                }

                if (!empty($_POST["remove_password"])) {
                    $password = $_POST["remove_password"];
                }

                if (!empty($_POST["edit_password"])) {
                    $password = $_POST["edit_password"];
                }
            }
        ?>
        <?php
            class Result {
                public static $SUCCESS = 0;
                public static $FAILED_INSERTION = 1;
                public static $FAILED_DELETION = 2;
                public static $FAILED_UPDATE = 3;
            }

            class Post {
                function __construct($row) {
                    $this->id = $row["id"];
                    $this->name = $row["name"];
                    $this->comment = $row["comment"];
                    $this->date = $row["date"];
                }

                function echo() {
                    echo "{$this->id} {$this->date} {$this->name} > {$this->comment}<br><hr>";
                }
            }

            class Posts {
                private $pdo;

                function __construct() {
                    $dsn = "hostname";
                    $user = "username";
                    $password = "password";
                    $this->pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                }
    
                function echo_rows() {
                    $query = 'SELECT * FROM kgbang';
                    $statement = $this->pdo->query($query);
    
                    $results = $statement->fetchAll();
                    foreach ($results as $row){
                        $post = new Post($row);
                        $post->echo();
                        //echo "{$row["id"]} {$row["date"]} {$row["name"]} > {$row["comment"]}<br>";
                    }
                }

                function insert_row($name, $comment, $password) {
                    $date = date("Y/m/d H:i:s");
    
                    $query = 'INSERT INTO kgbang (name, comment, date, password) VALUES (:name, :comment, :date, :password)';
                    $statement = $this->pdo->prepare($query);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":comment", $comment, PDO::PARAM_STR);
                    $statement->bindParam(":date", $date, PDO::PARAM_STR);
                    $statement->bindParam(":password", $password, PDO::PARAM_STR);
    
                    return $statement->execute();
                }
    
                function delete_row($id, $password) {
                    if (empty($password)) {
                        return false;
                    }
                    
                    $query = 'DELETE FROM kgbang WHERE id=:id AND password=:password';
                    $statement = $this->pdo->prepare($query);
                    $statement->bindParam(":id", $id, PDO::PARAM_INT);
                    $statement->bindParam(":password", $password, PDO::PARAM_STR);
    
                    return $statement->execute();
                }
    
                function update_row($id, $name, $comment, $password) {
                    if (empty($password)) {
                        return false;
                    }
                    
                    $query = 'UPDATE kgbang SET name=:name, comment=:comment WHERE id=:id AND password=:password';
                    $statement = $this->pdo->prepare($query);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":comment", $comment, PDO::PARAM_STR);
                    $statement->bindParam(":password", $password, PDO::PARAM_STR);
                    $statement->bindParam(":id", $id, PDO::PARAM_INT);
    
                    return $statement->execute();
                }
    
                function try_row($id, $password) {
                    if (empty($password)) {
                        return false;
                    }

                    $query = 'SELECT * FROM kgbang WHERE id=:id AND password=:password';
                    $statement = $this->pdo->prepare($query);
                    $statement->bindParam(":id", $id, PDO::PARAM_INT);
                    $statement->bindParam(":password", $password, PDO::PARAM_STR);
                    
                    if (!$statement->execute()) {
                        return false;
                    }

                    $results = $statement->fetch(); 
                    if ($results) {
                        return true;
                    }
                    
                    return false;
                }
                
                function get_row($id) {
                    $query = 'SELECT * FROM kgbang WHERE id=:id';
                    $statement = $this->pdo->prepare($query);
                    $statement->bindParam(":id", $id, PDO::PARAM_INT);
                    
                    if (!$statement->execute()) {
                        return null;
                    }

                    return new Post($statement->fetch());
                }
            }
        ?>
        <?php
            $posts = new Posts();

            if (!isset($password)) {
                $password = "";
            }

            if ($submit == "??????" && isset($edit_index)) {
                if ($posts->try_row($edit_index, $password)) {
                    $post = $posts->get_row($edit_index);
                    $set_name = $post->name;
                    $set_comment = $post->comment;
                    $set_password = $password;
                }
                else{
                    unset($edit_index);
                    $result = Result::$FAILED_UPDATE;
                }
            }
        ?>

        <h1>KGBANG</h1>
        <form method="POST">
            <?php echo (isset($edit_index) ? "??????" : "????????????"); ?>
            <input type="hidden" name="set_edit_index" value="<?php echo isset($edit_index) ? $edit_index : ""; ?>"><br>
            <input type="text" name="name" placeholder="??????" value = "<?php echo isset($set_name) ? $set_name : ""; ?>"><br>
            <input type="text" name="comment" placeholder="????????????" value = "<?php echo isset($set_comment) ? $set_comment : ""; ?>"><br>
            <input type="<?php echo isset($set_password) ? "hidden" : "password"; ?>" name="add_password" placeholder="???????????????" value = "<?php echo isset($set_password) ? $set_password : ""; ?>"><br>
            <input type="submit" name="submit" value="??????"><br>
            <br>
            <input type="text" name="remove_index" placeholder="??????????????????"><br>
            <input type="password" name="remove_password" placeholder="???????????????"><br>
            <input type="submit" name="submit" value="??????"><br>
            <br>
            <input type="text" name="edit_index" placeholder="??????????????????"><br>
            <input type="password" name="edit_password" placeholder="???????????????"><br>
            <input type="submit" name="submit" value="??????"><br>
        </form><hr>
        
        <?php
            if ($submit == "??????" && isset($name) && isset($comment)) {
                if (isset($set_edit_index)) {
                    if (!$posts->update_row($set_edit_index, $name, $comment, $password)) {
                        $result = Result::$FAILED_UPDATE;
                    }
                }
                else {
                    if (!$posts->insert_row($name, $comment, $password)) {
                        $result = Result::$FAILED_INSERTION;
                    }
                }
            }
            elseif ($submit == "??????") {
                if (!$posts->delete_row($remove_index, $password)) {
                        $result = Result::$FAILED_DELETION;
                }
            }

            if (isset($result)) {
                echo "<br>";

                switch ($result) {
                    case Result::$FAILED_INSERTION:
                        echo "????????????????????????????????????";
                        break;
                    
                    case Result::$FAILED_DELETION:
                        echo "??????????????????????????????";
                        break;
                    
                    case Result::$FAILED_UPDATE:
                        echo "??????????????????????????????";
                        break;
                }

                echo "<br><br><hr>";
            }
            
            $posts->echo_rows();
        ?>
    </body>
</html>