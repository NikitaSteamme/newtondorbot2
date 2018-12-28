<?php
/**
 * Created by IntelliJ IDEA.
 * User: User
 * Date: 28.12.2018
 * Time: 11:38
 */

$file = 'index.html';

$new_message = $_POST;
// Пишем содержимое в файл,
file_put_contents($file, $new_message, FILE_APPEND | LOCK_EX);
?>