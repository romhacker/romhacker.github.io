<?php
<?php
// Явно указываем те же параметры сессии
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_name('PHPSESSID');
session_id('4nd8511c32mua5c2io2gs4dd0'); // временно хардкодим для теста
session_start();
file_put_contents(__DIR__ . '/debug.txt', 
    'save_path: ' . session_save_path() . "\n" .
    'session_id: ' . session_id() . "\n" .
    'cookie PHPSESSID: ' . (isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : 'not set') . "\n",
    FILE_APPEND
);

header('Content-Type: application/json; charset=utf-8');

$objectId = isset($_POST['object_id']) ? (string)$_POST['object_id'] : '';
$planId   = isset($_POST['plan_id'])   ? (string)$_POST['plan_id']   : '';
$floorUid = isset($_POST['floor_uid']) ? (string)$_POST['floor_uid'] : '';
$level    = isset($_POST['level'])     ? $_POST['level']             : array();

error_log('floor_add_new: floor_uid=' . $floorUid . ' session=' . (empty($_SESSION['last_imported_full_root']) ? 'empty' : 'ok'));

if (!$floorUid || empty($_SESSION['last_imported_full_root'])) {
    echo json_encode(array('result' => 'error', 'message' => 'missing data'));
    exit;
}

$root = &$_SESSION['last_imported_full_root'];

// Проверяем что такой floor_uid ещё не существует
foreach ($root['floors'] as $fid => $floor) {
    if (isset($floor['floor_uid']) && (string)$floor['floor_uid'] === $floorUid) {
        echo json_encode(array('result' => 'error', 'message' => 'floor already exists'));
        exit;
    }
}

// Генерируем новый floor_id и plan_id
$newFloorId = (string)(time() . rand(100, 999));
$newPlanId  = (string)(time() . rand(100, 999) . '1');

// Пустой план
$emptyPlan = array(
    'v'          => 3,
    'id'         => $objectId,
    'plan_id'    => $newPlanId,
    'walls'      => new stdClass(),
    'rigels'     => array(),
    'pillars'    => array(),
    'rooms2'     => new stdClass(),
    'items'      => new stdClass(),
    'cables'     => new stdClass(),
    'drawings'   => array(),
    'total_height' => 270,
    'errors'     => array(
        'roomRebuild'    => 0,
        'wallWrongLabel' => 0,
        'wallTooShort'   => 0,
        'wallOverWall'   => 0,
        'wallNoPointS'   => 0,
        'wallNoPointE'   => 0,
    ),
    'final'      => true,
    'version'    => 1,
    'session'    => 1,
);

// Если нужно скопировать исходный план текущего этажа (level содержит 1)
if (in_array('1', (array)$level) || in_array(1, (array)$level)) {
    $currentFloorId = isset($_SESSION['last_imported_floor_id']) 
        ? $_SESSION['last_imported_floor_id'] : null;
    if ($currentFloorId && isset($root['floors'][$currentFloorId])) {
        $currentFloor  = $root['floors'][$currentFloorId];
        $currentPlanId = $currentFloor['plan_id'];
        if (isset($currentFloor['plans'][$currentPlanId]['data'])) {
            $emptyPlan = $currentFloor['plans'][$currentPlanId]['data'];
            $emptyPlan['plan_id'] = $newPlanId;
            $emptyPlan['id']      = $objectId;
        }
    }
}

// Добавляем новый этаж в сессию
$root['floors'][$newFloorId] = array(
    'floor_uid'   => $floorUid,
    'plan_id'     => $newPlanId,
    'plans_count' => 1,
    'plans'       => array(
        $newPlanId => array(
            'label' => 'Планировка 1',
            'data'  => $emptyPlan,
        )
    )
);

// Обновляем floors_list в сессии
$floorsList = array();
foreach ($root['floors'] as $fid => $floor) {
    $floorsList[$fid] = array(
        'floor_uid'   => isset($floor['floor_uid']) ? $floor['floor_uid'] : $fid,
        'plan_id'     => isset($floor['plan_id'])   ? (string)$floor['plan_id'] : '',
        'plans_count' => isset($floor['plans'])     ? count($floor['plans']) : 0,
    );
}
$_SESSION['last_imported_floors_list'] = $floorsList;

// Возвращаем новый plan_id — планировщик сделает редирект на него
echo json_encode(array(
    'status'   => 'success',
    'plan_id'  => $newPlanId,
    'floor_id' => $newFloorId,
));
exit;
?>