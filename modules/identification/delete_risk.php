<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../classes/Risk.php';


$risk = new Risk($db);

$id = $_GET['id'];
$risk->deleteRisk($id);

header("Location: ../../pages/identification.php");
exit;
?>
