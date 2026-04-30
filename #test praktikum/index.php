<?php
include_once "controllers/MahasiswaControllers.php";
$controller = new MahasiswaController();
if(isset($_GET['hapus'])) {

$controller->model->delete($_GET['hapus']);
header("Location: index.php");
}
include_once "views/List.php";
?>
