<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$floorId = isset($_POST['floor_id']) ? (string)$_POST['floor_id'] : '';
$planId  = isset($_POST['plan_id'])  ? (string)$_POST['plan_id']  : '';
$jsonData = isset($_POST['json_data']) ? $_POST['json_data'] : '';

if (!$floorId || !$jsonData || empty($_SESSION['last_imported_full_root'])) {
    echo json_encode(array('result' => 'error', 'message' => 'missing data'));
    exit;
}

$planData = json_decode($jsonData, true);
if (!is_array($planData)) {
    echo json_encode(array('result' => 'error', 'message' => 'invalid json'));
    exit;
}

// Обновляем данные этажа в сессии
$root = &$_SESSION['last_imported_full_root'];

if (!isset($root['floors'][$floorId])) {
    echo json_encode(array('result' => 'error', 'message' => 'floor not found'));
    exit;
}

// Если plan_id не указан — берём активный
if (!$planId) {
    $planId = isset($root['floors'][$floorId]['plan_id']) 
        ? (string)$root['floors'][$floorId]['plan_id'] 
        : null;
    if (!$planId) {
        reset($root['floors'][$floorId]['plans']);
        $planId = (string)key($root['floors'][$floorId]['plans']);
    }
}

// Сохраняем данные плана
$root['floors'][$floorId]['plans'][$planId]['data'] = $planData;

// Обновляем last_imported_plan_data если это текущий этаж
if (isset($_SESSION['last_imported_floor_id']) && 
    $_SESSION['last_imported_floor_id'] === $floorId) {
    $_SESSION['last_imported_plan_data'] = $jsonData;
}

echo json_encode(array('result' => 'success'));
exit;
?>