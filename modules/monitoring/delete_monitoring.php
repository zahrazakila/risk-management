<?php
require_once '../../includes/init.php';
require_once '../../classes/Monitoring.php';

$monitoring = new Monitoring($conn);

if (isset($_GET['delete'])) {
    $monitoring->deleteMonitoring($_GET['delete']);
}

header("Location: ../../pages/monitoring.php");
exit;
?>
