<html>
    <body>
        <?php
            global $filename;
            global $separator;

            $filename = "mission_3-4.txt";
            $separator = "<>";

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
            }

            if ($submit == "編集" && isset($edit_index)) {
                $set_name = "";
                $set_comment = "";
                load(function($number, $name, $comment, $date) use($edit_index, &$set_name, &$set_comment) {
                    if ($number == $edit_index) {
                        $set_name = $name;
                        $set_comment = $comment;
                    }
                });
            }
        ?>

        <form method="POST">
            <?php echo (isset($edit_index) ? "編集" : "新規投稿"); ?>
            <input type="hidden" name="set_edit_index" value="<?php echo isset($edit_index) ? $edit_index : ""; ?>"><br>
            <input type="text" name="name" placeholder="名前" value = "<?php echo isset($set_name) ? $set_name : ""; ?>"><br>
            <input type="text" name="comment" placeholder="コメント" value = "<?php echo isset($set_comment) ? $set_comment : ""; ?>"><br>
            <input type="submit" name="submit" value="送信"><br>
            <br>
            <input type="text" name="remove_index" placeholder="削除対象番号"><br>
            <input type="submit" name="submit" value="削除"><br>
            <br>
            <input type="text" name="edit_index" placeholder="編集対象番号"><br>
            <input type="submit" name="submit" value="編集"><br>
        </form>

        <?php
            if ($submit == "送信" && isset($name) && isset($comment)) {
                if (isset($set_edit_index)) {
                    $new_name = $name;
                    $new_comment = $comment;

                    $lines = array();

                    load(function($number, $name, $comment, $date) use($set_edit_index, $new_name, $new_comment, &$lines) {
                        if ($number == $set_edit_index) {
                            echo_line($number, $new_name, $new_comment, $date);
                            $lines[] = make_line($number, $new_name, $new_comment, $date);
                        }
                        else {
                            echo_line($number, $name, $comment, $date);
                            $lines[] = make_line($number, $name, $comment, $date);
                        }
                    });

                    $fp = fopen($filename, "w");

                    foreach ($lines as $line) {
                        fwrite($fp, $line . PHP_EOL);
                    }

                    fclose($fp);
                }
                else {
                    $max_number = 0;

                    load(function($number, $name, $comment, $date) use(&$max_number) {
                        if (is_numeric($number) && $max_number < $number) {
                            $max_number = $number;
                        }

                        echo_line($number, $name, $comment, $date);
                    });

                    $max_number++;
                    $date = save($max_number, $name, $comment);

                    echo_line($max_number, $name, $comment, $date);
                }
            }
            elseif ($submit == "削除") {
                $lines = array();

                load(function($number, $name, $comment, $date) use($remove_index, &$lines) {
                    if ($number != $remove_index) {
                        echo_line($number, $name, $comment, $date);
                        $lines[] = make_line($number, $name, $comment, $date);
                    }
                });

                $fp = fopen($filename, "w");

                foreach ($lines as $line) {
                    fwrite($fp, $line . PHP_EOL);
                }

                fclose($fp);
            }
            else {
                load(function($number, $name, $comment, $date) {
                    echo_line($number, $name, $comment, $date);
                });
            }
        ?>

        <?php
            function echo_line($number, $name, $comment, $date) {
                echo "{$number} {$date} {$name} > {$comment}<br>";
            }

            function make_line($number, $name, $comment, $date) {
                global $separator;

                return $number . $separator . $name . $separator . $comment . $separator . $date;
            }

            /*
            load(function($number, $name, $comment, $date) {
            });
            */
            function load($callback) {
                global $filename;
                global $separator;

                if (file_exists($filename)) {
                    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        $tokens = explode($separator, $line);
                        if (count($tokens) != 4) {
                            continue;
                        }

                        $callback($tokens[0], $tokens[1], $tokens[2], $tokens[3]);
                    }
                }
            }

            function save($number, $name, $comment) {
                global $filename;

                $date = date("Y/m/d H:i:s");
                $line = make_line($number, $name, $comment, $date);

                $fp = fopen($filename, "a");
                fwrite($fp, $line . PHP_EOL);
                fclose($fp);

                return $date;
            }
        ?>
    </body>
</html>
