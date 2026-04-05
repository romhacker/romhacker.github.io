<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

$objectId = isset($_GET['object_id']) ? (string)$_GET['object_id'] : '';
$planId   = isset($_GET['plan_id']) ? (string)$_GET['plan_id'] : '';

// После строки $planId = isset($_GET['plan_id']) ...
$floorId = isset($_GET['floor_id']) ? (string)$_GET['floor_id'] : '';

if (($objectId === '' || $objectId === '0') && !empty($_SESSION['last_imported_full_root'])) {
    $root = $_SESSION['last_imported_full_root'];
    $importedObjectId = isset($_SESSION['last_imported_object_id']) 
        ? (string)$_SESSION['last_imported_object_id'] : '0';

    // Если передан floor_id — берём данные этого этажа
    if ($floorId !== '' && isset($root['floors'][$floorId])) {
        $floor  = $root['floors'][$floorId];
        $pid    = isset($floor['plan_id']) ? (string)$floor['plan_id'] : '';
        if (!$pid || !isset($floor['plans'][$pid])) {
            reset($floor['plans']);
            $pid = (string)key($floor['plans']);
        }
        $planObj   = $floor['plans'][$pid];
        $planData  = isset($planObj['data']) ? $planObj['data'] : $planObj;
        $planData['id'] = $importedObjectId;
    } else {
        // Дефолтный первый этаж
        $floorId  = isset($_SESSION['last_imported_floor_id']) ? $_SESSION['last_imported_floor_id'] : '';
        $pid      = isset($_SESSION['last_imported_plan_id'])  ? $_SESSION['last_imported_plan_id']  : '';
        $planData = json_decode($_SESSION['last_imported_plan_data'], true);
    }

    // Строим список этажей для меню
    $floorsList = isset($_SESSION['last_imported_floors_list']) 
        ? $_SESSION['last_imported_floors_list'] 
        : array();

    echo json_encode(array(
        'object_id'       => $importedObjectId,
        'visitor_id'      => session_id(),
        'session_id'      => session_id(),
        'client_id'       => '941524',
        'object_save_id'  => '37',
        'save_id'         => '19',
        'plan_id'         => $pid,
        'floor_id'        => $floorId,
        'data'            => json_encode($planData),
        'chat_new_messages' => 0,
        'direct'          => array('id' => '3', 'plan' => 'init', 'html' => ''),
        'floors'          => $floorsList,
        'units'           => 'cm'
    ));
    exit;
}

/*
 * Во всех остальных случаях отдаём ваш исходный planner/index.html как есть.
 * Это не ломает обычную загрузку.
 */
$indexHtml = __DIR__ . DIRECTORY_SEPARATOR . 'index.html';

if (file_exists($indexHtml)) {
    readfile($indexHtml);
    exit;
}

echo json_encode(array(
    'result' => 'error',
    'message' => 'planner/index.html not found'
));
exit;
?>