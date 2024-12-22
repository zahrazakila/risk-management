<?php
require_once '../../includes/init.php';
require_once '../../classes/Mitigation.php';

$mitigation = new Mitigation($conn);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $mitigation->deleteMitigation($id);
}

header("Location: ../../pages/mitigation.php");
exit;
?>
