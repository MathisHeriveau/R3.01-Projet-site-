<?php

$id = $_GET['id'];

include '../BD/BD.php';

// Delete the CD
$db->exec("DELETE FROM CD WHERE id = $id");

header("Location: ../mesCD.php");
