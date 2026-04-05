<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// 👉 если есть импортированный план — отдаём его
if (isset($_SESSION['last_imported_plan_data'])) {

    $planData = json_decode($_SESSION['last_imported_plan_data'], true);

    $objectId = $_SESSION['last_imported_object_id'] ?? '0';
    $planId   = $_SESSION['last_imported_plan_id'] ?? '';

    echo json_encode([
        'object_id' => $objectId,
        'plan_id'   => $planId,
        'data'      => $planData,
        'floors'    => isset($_SESSION['last_imported_full_root'])
                        ? $_SESSION['last_imported_full_root']['floors']
                        : []
    ]);

    exit;
}


// 👉 fallback — если нет архива, идём к разработчику

$object_id = $_GET['object_id'] ?? '5533608';
$plan_id   = $_GET['plan_id'] ?? '636628';

$url = "https://remplanner.ru/setup/api/planner/?object_id=" . urlencode($object_id) .
       "&plan_id=" . urlencode($plan_id);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

header('Content-Type: application/json; charset=utf-8');
echo $response;

curl_close($ch);