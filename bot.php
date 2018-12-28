<?php
/**
 * Created by IntelliJ IDEA.
 * User: User
 * Date: 28.12.2018
 * Time: 11:38
 */

$file = 'index.php';

$new_message = var_dump($_POST);
// Пишем содержимое в файл,
file_put_contents($file, $new_message, FILE_APPEND | LOCK_EX);
?>