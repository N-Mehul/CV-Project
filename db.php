<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "plant_disease_db"
);

if(!$conn){
    die("DB Error: ".mysqli_connect_error());
}
?>
