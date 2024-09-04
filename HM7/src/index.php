<?php declare(strict_types=1);

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

session_start();

if ($path === '/login' && $method === 'POST') {
    $_SESSION['authenticated'] = true;
    echo json_encode(['status' => 'logged in']);

}

if ($path === '/logout' && $method === 'POST') {
    $_SESSION = [];

    session_destroy();
    echo json_encode(['status' => 'logged out']);
    return;
}

if (!isset($_SESSION['authenticated']) && $path !== '/logout') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);

}

if (isset($_SESSION['authenticated'])) {
    switch ($path) {
        case '/teachers':
            handleTeachers($method);
            break;

        case '/login':
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
            break;

    }
}


function handleTeachers($method): void
{
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                readTeacher($_GET['id']);
            } else {
                readAllTeachers();
            }
            break;

        case 'POST':
            createTeacher();
            break;

        case 'PUT':
            updateTeacher();
            break;

        case 'PATCH':
            patchTeacher();
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                deleteTeacher($_GET['id']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'No ID provided for deletion']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            break;
    }
}

function readAllTeachers(): void
{
    $teachers = loadTeachersFromFile();
    echo json_encode($teachers);
}

function readTeacher($id): void
{
    $teachers = loadTeachersFromFile();

    foreach ($teachers as $teacher) {
        if ($teacher['id'] === $id) {
            echo json_encode($teacher);
            return;
        }
    }

    http_response_code(404);
    echo json_encode(['error' => 'Teacher not found']);
}


function createTeacher(): void
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['subject']) || !isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        return;
    }

    $teachers = loadTeachersFromFile();
    $newTeacher = [
        'id' => $data['id'],
        'name' => $data['name'],
        'subject' => $data['subject'],
        'email' => $data['email']
    ];

    $teachers[] = $newTeacher;
    saveTeachersToFile($teachers);

    http_response_code(201);
    echo json_encode($newTeacher);
}

function updateTeacher(): void
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['name']) || !isset($data['subject']) || !isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input. Please provide valid JSON format']);
        return;
    }

    $teachers = loadTeachersFromFile();
    foreach ($teachers as &$teacher) {
        if ($teacher['id'] === $data['id']) {

            $teacher['name'] = $data['name'];
            $teacher['subject'] = $data['subject'];
            $teacher['email'] = $data['email'];
            saveTeachersToFile($teachers);
            echo json_encode($teacher);
            return;

        }
    }

    http_response_code(404);
    echo json_encode(['error' => 'Teacher not found']);
}

function patchTeacher(): void
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        return;
    }

    $teachers = loadTeachersFromFile();
    foreach ($teachers as &$teacher) {
        if ($teacher['id'] === $data['id']) {
            if (isset($data['name'])) {
                $teacher['name'] = $data['name'];
            }
            if (isset($data['subject'])) {
                $teacher['subject'] = $data['subject'];
            }
            if (isset($data['email'])) {
                $teacher['email'] = $data['email'];
            }
            saveTeachersToFile($teachers);
            echo json_encode($teacher);
            return;
        }
    }

    http_response_code(404);
    echo json_encode(['error' => 'Teacher not found']);
}

function deleteTeacher($id): void
{
    $teachers = loadTeachersFromFile();

    foreach ($teachers as $key => $teacher) {
        if ($teacher['id'] === $id) {
            unset($teachers[$key]);
            saveTeachersToFile(array_values($teachers));
            echo json_encode(['status' => 'success', 'message' => 'Teacher deleted']);
            return;
        }
    }

    http_response_code(404);
    echo json_encode(['error' => 'Teacher not found']);
}

function loadTeachersFromFile()
{
    $filename = __DIR__ . '/teachers.json';
    if (!file_exists($filename)) {
        echo json_encode(['error' => 'File not found']);
        return [];
    }

    $json = file_get_contents($filename);
    return json_decode($json, true);
}

function saveTeachersToFile($teachers): void
{
    $filename = __DIR__ . '/teachers.json';
    file_put_contents($filename, json_encode($teachers, JSON_PRETTY_PRINT));
}


