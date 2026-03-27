<?php
// API Endpoint for Student Operations (Normalized Schema)

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_GET['action']) ? $_GET['action'] : '';

try {
    switch ($method) {
        case 'GET':
            if ($request === 'all') {
                getStudents();
            } elseif ($request === 'single' && isset($_GET['id'])) {
                getStudent($_GET['id']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Missing or invalid parameters']);
            }
            break;
            
        case 'POST':
            addStudent();
            break;
            
        case 'PUT':
            updateStudent();
            break;
            
        case 'DELETE':
            deleteStudent($_GET['id'] ?? null);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function getStudents() {
    global $conn;
    $query = "SELECT 
                s.id, 
                s.name, 
                s.program, 
                s.year_level, 
                s.gpa, 
                s.preferred_track,
                s.created_at,
                s.updated_at,
                GROUP_CONCAT(DISTINCT ss.skill) as skills,
                GROUP_CONCAT(DISTINCT ssu.subject) as completed_subjects
              FROM students s
              LEFT JOIN student_skills ss ON s.id = ss.student_id
              LEFT JOIN student_subjects ssu ON s.id = ssu.student_id
              GROUP BY s.id
              ORDER BY s.id DESC";
    
    $result = $conn->query($query);
    $students = [];
    
    while ($row = $result->fetch_assoc()) {
        $row['skills'] = $row['skills'] ? explode(',', $row['skills']) : [];
        $row['completed_subjects'] = $row['completed_subjects'] ? explode(',', $row['completed_subjects']) : [];
        $row['year_level'] = (int)$row['year_level'];
        $row['gpa'] = (float)$row['gpa'];
        $students[] = $row;
    }
    
    echo json_encode($students);
}

function getStudent($id) {
    global $conn;
    $id = $conn->real_escape_string($id);
    
    $query = "SELECT 
                s.id, 
                s.name, 
                s.program, 
                s.year_level, 
                s.gpa, 
                s.preferred_track,
                s.created_at,
                s.updated_at,
                GROUP_CONCAT(DISTINCT ss.skill) as skills,
                GROUP_CONCAT(DISTINCT ssu.subject) as completed_subjects
              FROM students s
              LEFT JOIN student_skills ss ON s.id = ss.student_id
              LEFT JOIN student_subjects ssu ON s.id = ssu.student_id
              WHERE s.id = '$id'
              GROUP BY s.id
              LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Student not found']);
        return;
    }
    
    $student = $result->fetch_assoc();
    $student['skills'] = $student['skills'] ? explode(',', $student['skills']) : [];
    $student['completed_subjects'] = $student['completed_subjects'] ? explode(',', $student['completed_subjects']) : [];
    $student['year_level'] = (int)$student['year_level'];
    $student['gpa'] = (float)$student['gpa'];
    
    echo json_encode($student);
}

function addStudent() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['id'], $data['name'], $data['program'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $id = $conn->real_escape_string($data['id']);
    $name = $conn->real_escape_string($data['name']);
    $program = $conn->real_escape_string($data['program']);
    $year_level = intval($data['year_level'] ?? 1);
    $gpa = floatval($data['gpa'] ?? 0.0);
    $preferred_track = $conn->real_escape_string($data['preferred_track'] ?? '');
    $skills = $data['skills'] ?? [];
    $completed_subjects = $data['completed_subjects'] ?? [];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert student
        $query = "INSERT INTO students (id, name, program, year_level, gpa, preferred_track)
                  VALUES ('$id', '$name', '$program', $year_level, $gpa, '$preferred_track')";
        
        if (!$conn->query($query)) {
            throw new Exception($conn->error);
        }
        
        // Insert skills
        foreach ($skills as $skill) {
            $skill = $conn->real_escape_string($skill);
            $skill_query = "INSERT INTO student_skills (student_id, skill) VALUES ('$id', '$skill')";
            if (!$conn->query($skill_query)) {
                throw new Exception($conn->error);
            }
        }
        
        // Insert subjects
        foreach ($completed_subjects as $subject) {
            $subject = $conn->real_escape_string($subject);
            $subject_query = "INSERT INTO student_subjects (student_id, subject) VALUES ('$id', '$subject')";
            if (!$conn->query($subject_query)) {
                throw new Exception($conn->error);
            }
        }
        
        $conn->commit();
        http_response_code(201);
        echo json_encode(['id' => $id, 'message' => 'Student added successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function updateStudent() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        return;
    }
    
    $id = $conn->real_escape_string($data['id']);
    $updates = [];
    
    if (isset($data['name'])) {
        $updates[] = "name = '" . $conn->real_escape_string($data['name']) . "'";
    }
    if (isset($data['program'])) {
        $updates[] = "program = '" . $conn->real_escape_string($data['program']) . "'";
    }
    if (isset($data['year_level'])) {
        $updates[] = "year_level = " . intval($data['year_level']);
    }
    if (isset($data['gpa'])) {
        $updates[] = "gpa = " . floatval($data['gpa']);
    }
    if (isset($data['preferred_track'])) {
        $updates[] = "preferred_track = '" . $conn->real_escape_string($data['preferred_track']) . "'";
    }
    
    $conn->begin_transaction();
    
    try {
        // Update student fields
        if (!empty($updates)) {
            $query = "UPDATE students SET " . implode(", ", $updates) . " WHERE id = '$id'";
            if (!$conn->query($query)) {
                throw new Exception($conn->error);
            }
        }
        
        // Update skills
        if (isset($data['skills'])) {
            $conn->query("DELETE FROM student_skills WHERE student_id = '$id'");
            foreach ($data['skills'] as $skill) {
                $skill = $conn->real_escape_string($skill);
                $skill_query = "INSERT INTO student_skills (student_id, skill) VALUES ('$id', '$skill')";
                if (!$conn->query($skill_query)) {
                    throw new Exception($conn->error);
                }
            }
        }
        
        // Update subjects
        if (isset($data['completed_subjects'])) {
            $conn->query("DELETE FROM student_subjects WHERE student_id = '$id'");
            foreach ($data['completed_subjects'] as $subject) {
                $subject = $conn->real_escape_string($subject);
                $subject_query = "INSERT INTO student_subjects (student_id, subject) VALUES ('$id', '$subject')";
                if (!$conn->query($subject_query)) {
                    throw new Exception($conn->error);
                }
            }
        }
        
        $conn->commit();
        echo json_encode(['message' => 'Student updated successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function deleteStudent($id) {
    global $conn;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        return;
    }
    
    $id = $conn->real_escape_string($id);
    
    // CASCADE delete is handled by database foreign keys
    if ($conn->query("DELETE FROM students WHERE id = '$id'")) {
        echo json_encode(['message' => 'Student deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $conn->error]);
    }
}
?>
