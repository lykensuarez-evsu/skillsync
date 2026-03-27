<?php
// API Endpoint for Internship Operations (Normalized Schema)

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
                getInternships();
            } elseif ($request === 'single' && isset($_GET['id'])) {
                getInternship($_GET['id']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Missing or invalid parameters']);
            }
            break;
            
        case 'POST':
            addInternship();
            break;
            
        case 'PUT':
            updateInternship();
            break;
            
        case 'DELETE':
            deleteInternship($_GET['id'] ?? null);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function getInternships() {
    global $conn;
    $query = "SELECT 
                i.id, 
                i.title, 
                i.company, 
                i.department, 
                i.location, 
                i.mode, 
                i.track, 
                i.min_gpa,
                i.created_at,
                i.updated_at,
                GROUP_CONCAT(DISTINCT isk.skill) as required_skills,
                GROUP_CONCAT(DISTINCT isub.subject) as preferred_subjects
              FROM internships i
              LEFT JOIN internship_skills isk ON i.id = isk.internship_id
              LEFT JOIN internship_subjects isub ON i.id = isub.internship_id
              GROUP BY i.id
              ORDER BY i.id DESC";
    
    $result = $conn->query($query);
    $internships = [];
    
    while ($row = $result->fetch_assoc()) {
        $row['required_skills'] = $row['required_skills'] ? explode(',', $row['required_skills']) : [];
        $row['preferred_subjects'] = $row['preferred_subjects'] ? explode(',', $row['preferred_subjects']) : [];
        $row['min_gpa'] = (float)$row['min_gpa'];
        $internships[] = $row;
    }
    
    echo json_encode($internships);
}

function getInternship($id) {
    global $conn;
    $id = $conn->real_escape_string($id);
    
    $query = "SELECT 
                i.id, 
                i.title, 
                i.company, 
                i.department, 
                i.location, 
                i.mode, 
                i.track, 
                i.min_gpa,
                i.created_at,
                i.updated_at,
                GROUP_CONCAT(DISTINCT isk.skill) as required_skills,
                GROUP_CONCAT(DISTINCT isub.subject) as preferred_subjects
              FROM internships i
              LEFT JOIN internship_skills isk ON i.id = isk.internship_id
              LEFT JOIN internship_subjects isub ON i.id = isub.internship_id
              WHERE i.id = '$id'
              GROUP BY i.id
              LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Internship not found']);
        return;
    }
    
    $internship = $result->fetch_assoc();
    $internship['required_skills'] = $internship['required_skills'] ? explode(',', $internship['required_skills']) : [];
    $internship['preferred_subjects'] = $internship['preferred_subjects'] ? explode(',', $internship['preferred_subjects']) : [];
    $internship['min_gpa'] = (float)$internship['min_gpa'];
    
    echo json_encode($internship);
}

function addInternship() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['id'], $data['title'], $data['company'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $id = $conn->real_escape_string($data['id']);
    $title = $conn->real_escape_string($data['title']);
    $company = $conn->real_escape_string($data['company']);
    $department = $conn->real_escape_string($data['department'] ?? '');
    $location = $conn->real_escape_string($data['location'] ?? '');
    $mode = $conn->real_escape_string($data['mode'] ?? '');
    $track = $conn->real_escape_string($data['track'] ?? '');
    $min_gpa = floatval($data['min_gpa'] ?? 0.0);
    $required_skills = $data['required_skills'] ?? [];
    $preferred_subjects = $data['preferred_subjects'] ?? [];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert internship
        $query = "INSERT INTO internships (id, title, company, department, location, mode, track, min_gpa)
                  VALUES ('$id', '$title', '$company', '$department', '$location', '$mode', '$track', $min_gpa)";
        
        if (!$conn->query($query)) {
            throw new Exception($conn->error);
        }
        
        // Insert skills
        foreach ($required_skills as $skill) {
            $skill = $conn->real_escape_string($skill);
            $skill_query = "INSERT INTO internship_skills (internship_id, skill) VALUES ('$id', '$skill')";
            if (!$conn->query($skill_query)) {
                throw new Exception($conn->error);
            }
        }
        
        // Insert subjects
        foreach ($preferred_subjects as $subject) {
            $subject = $conn->real_escape_string($subject);
            $subject_query = "INSERT INTO internship_subjects (internship_id, subject) VALUES ('$id', '$subject')";
            if (!$conn->query($subject_query)) {
                throw new Exception($conn->error);
            }
        }
        
        $conn->commit();
        http_response_code(201);
        echo json_encode(['id' => $id, 'message' => 'Internship added successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function updateInternship() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        return;
    }
    
    $id = $conn->real_escape_string($data['id']);
    $updates = [];
    
    if (isset($data['title'])) {
        $updates[] = "title = '" . $conn->real_escape_string($data['title']) . "'";
    }
    if (isset($data['company'])) {
        $updates[] = "company = '" . $conn->real_escape_string($data['company']) . "'";
    }
    if (isset($data['department'])) {
        $updates[] = "department = '" . $conn->real_escape_string($data['department']) . "'";
    }
    if (isset($data['location'])) {
        $updates[] = "location = '" . $conn->real_escape_string($data['location']) . "'";
    }
    if (isset($data['mode'])) {
        $updates[] = "mode = '" . $conn->real_escape_string($data['mode']) . "'";
    }
    if (isset($data['track'])) {
        $updates[] = "track = '" . $conn->real_escape_string($data['track']) . "'";
    }
    if (isset($data['min_gpa'])) {
        $updates[] = "min_gpa = " . floatval($data['min_gpa']);
    }
    
    $conn->begin_transaction();
    
    try {
        // Update internship fields
        if (!empty($updates)) {
            $query = "UPDATE internships SET " . implode(", ", $updates) . " WHERE id = '$id'";
            if (!$conn->query($query)) {
                throw new Exception($conn->error);
            }
        }
        
        // Update skills
        if (isset($data['required_skills'])) {
            $conn->query("DELETE FROM internship_skills WHERE internship_id = '$id'");
            foreach ($data['required_skills'] as $skill) {
                $skill = $conn->real_escape_string($skill);
                $skill_query = "INSERT INTO internship_skills (internship_id, skill) VALUES ('$id', '$skill')";
                if (!$conn->query($skill_query)) {
                    throw new Exception($conn->error);
                }
            }
        }
        
        // Update subjects
        if (isset($data['preferred_subjects'])) {
            $conn->query("DELETE FROM internship_subjects WHERE internship_id = '$id'");
            foreach ($data['preferred_subjects'] as $subject) {
                $subject = $conn->real_escape_string($subject);
                $subject_query = "INSERT INTO internship_subjects (internship_id, subject) VALUES ('$id', '$subject')";
                if (!$conn->query($subject_query)) {
                    throw new Exception($conn->error);
                }
            }
        }
        
        $conn->commit();
        echo json_encode(['message' => 'Internship updated successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function deleteInternship($id) {
    global $conn;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        return;
    }
    
    $id = $conn->real_escape_string($id);
    
    // CASCADE delete is handled by database foreign keys
    if ($conn->query("DELETE FROM internships WHERE id = '$id'")) {
        echo json_encode(['message' => 'Internship deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $conn->error]);
    }
}
?>
