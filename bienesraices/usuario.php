<?php

require "includes/config/database.php";
$db = conectarDB();

$email = "correo@correo.com";
$password = password_hash("123456", PASSWORD_DEFAULT);

$query = "INSERT INTO usuarios(email,password) VALUES ('$email','$password')";
echo $query;

mysqli_query($db, $query);