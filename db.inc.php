<?php

//default port and user/password
$conn = new Mongo("mongodb://localhost:27017//admin:admin");
//select database, also：$db = $conn->selectDB('blog');
$db = $conn->blog;
//select collection
$collection = $db->posts;

?>