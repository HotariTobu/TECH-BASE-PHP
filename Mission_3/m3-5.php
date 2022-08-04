<html>
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
                public static $WRONG_NUMBER = 1;
                public static $INVALID_OPERATION_SINCE_EMPTY_PASSWORD = 2;
                public static $WRONG_PASSWORD = 3;
            }

            class Post {
                function __construct($number, $name, $comment, $date, $password) {
                    $this->number = $number;
                    $this->name = $name;
                    $this->comment = $comment;
                    $this->date = $date;
                    $this->password = $password;
                }

                function echo() {
                    echo "{$this->number} {$this->date} {$this->name} > {$this->comment}<br>";
                }
            }

            class Posts {
                private static $filename = "mission_3-5.txt";
                private static $separator = "<>";

                private $max_number = 0;
                private $posts = array();
                private $is_updated = false;

                function __construct() {
                    if (!file_exists(Posts::$filename)) {
                        return;
                    }
                    
                    $lines = file(Posts::$filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    
                    foreach ($lines as $line) {
                        $post = Posts::parse_line($line);
                        if ($post == null) {
                            continue;
                        }
                        
                        if ($this->max_number < $post->number) {
                            $this->max_number = $post->number;
                        }

                        $this->posts[$post->number] = $post;
                    }
                }

                function store() {
                    if (!$this->is_updated) {
                        return;
                    }

                    $fp = fopen(Posts::$filename, "w");
    
                    foreach ($this->posts as $post) {
                        $line = Posts::make_line($post);
                        fwrite($fp, $line . PHP_EOL);
                    }
    
                    fclose($fp);

                    $this->is_updated = false;
                }

                private static function parse_line($line) {
                    $tokens = explode(Posts::$separator, $line);
                    if (count($tokens) == 6) {
                        return new Post($tokens[0], $tokens[1], $tokens[2], $tokens[3], $tokens[4]);
                    }

                    return null;
                }

                private static function make_line($post) {
                    return 
                        $post->number . Posts::$separator .
                        $post->name . Posts::$separator .
                        $post->comment . Posts::$separator .
                        $post->date . Posts::$separator .
                        $post->password . Posts::$separator;
                }

                function echo() {
                    foreach ($this->posts as $post) {
                        $post->echo();
                    }
                }

                function access($number, $password, $callback) {
                    if (!isset($this->posts[$number])) {
                        return Result::$WRONG_NUMBER;
                    }

                    $post = $this->posts[$number];
                    if ($post->password == "") {
                        return Result::$INVALID_OPERATION_SINCE_EMPTY_PASSWORD;
                    }

                    if ($post->password != $password) {
                        return Result::$WRONG_PASSWORD;
                    }
                    
                    $callback($post);
                    return Result::$SUCCESS;
                }

                function add($name, $comment, $password) {
                    $number = ++$this->max_number;
                    $date = date("Y/m/d H:i:s");
                    $post = new Post($number, $name, $comment, $date, $password);
                    $this->posts[$number] = $post;
                    $this->is_updated = true;
                }

                function remove($number, $password) {
                    return $this->access($number, $password, function($post) use($number) {
                        unset($this->posts[$number]);
                        $this->is_updated = true;
                    });
                }

                function edit($number, $name, $comment, $password) {
                    return $this->access($number, $password, function($post) use($name, $comment) {
                        $post->name = $name;
                        $post->comment = $comment;
                        $this->is_updated = true;
                    });
                }
            }
        ?>
        <?php
            $posts = new Posts();

            if (!isset($password)) {
                $password = "";
            }

            if ($submit == "編集" && isset($edit_index)) {
                $result = $posts->access($edit_index, $password, function($post) use(&$set_name, &$set_comment, &$set_password) {
                    $set_name = $post->name;
                    $set_comment = $post->comment;
                    $set_password = $post->password;
                });

                if ($result != Result::$SUCCESS) {
                    unset($edit_index);
                }
            }
        ?>

        <form method="POST">
            <?php echo (isset($edit_index) ? "編集" : "新規投稿"); ?>
            <input type="hidden" name="set_edit_index" value="<?php echo isset($edit_index) ? $edit_index : ""; ?>"><br>
            <input type="text" name="name" placeholder="名前" value = "<?php echo isset($set_name) ? $set_name : ""; ?>"><br>
            <input type="text" name="comment" placeholder="コメント" value = "<?php echo isset($set_comment) ? $set_comment : ""; ?>"><br>
            <input type="<?php echo isset($set_password) ? "hidden" : "password"; ?>" name="add_password" placeholder="パスワード" value = "<?php echo isset($set_password) ? $set_password : ""; ?>"><br>
            <input type="submit" name="submit" value="送信"><br>
            <br>
            <input type="text" name="remove_index" placeholder="削除対象番号"><br>
            <input type="password" name="remove_password" placeholder="パスワード"><br>
            <input type="submit" name="submit" value="削除"><br>
            <br>
            <input type="text" name="edit_index" placeholder="編集対象番号"><br>
            <input type="password" name="edit_password" placeholder="パスワード"><br>
            <input type="submit" name="submit" value="編集"><br>
        </form>
        
        <?php
            if ($submit == "送信" && isset($name) && isset($comment)) {
                if (isset($set_edit_index)) {
                    $result = $posts->edit($set_edit_index, $name, $comment, $password);
                }
                else {
                    $posts->add($name, $comment, $password);
                }
            }
            elseif ($submit == "削除") {
                $result = $posts->remove($remove_index, $password);
            }

            if (isset($result)) {
                switch ($result) {
                    case Result::$WRONG_NUMBER:
                        echo "<br>番号が間違っています。<br><br>";
                        break;
                    
                    case Result::$INVALID_OPERATION_SINCE_EMPTY_PASSWORD:
                        echo "<br>パスワードが設定されていないので、削除も編集もできません。<br><br>";
                        break;
                    
                    case Result::$WRONG_PASSWORD:
                        echo "<br>パスワードが間違っています。<br><br>";
                        break;
                }
            }
            
            $posts->echo();
            $posts->store();
        ?>
    </body>
</html>