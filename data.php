<?php
// Cale catre fisierul JSON
$studentsFile = 'students.json';

// Functia de incarcare din JSON
function loadStudents() {
    global $studentsFile;
    if (file_exists($studentsFile)) {
        $json = file_get_contents($studentsFile);
        return json_decode($json, true);
    }
    return array();
}

// Functia de salvare in JSON
function saveStudents($students) {
    global $studentsFile;
    $json = json_encode($students, JSON_PRETTY_PRINT);
    file_put_contents($studentsFile, $json);
}

// Incarcam studentii din JSON
$students = loadStudents();

// Functia de validare a studentilor
function validateStudent($name, $dob, $email, $phone, $series, $group, $address) {
    $errors = array();

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($dob)) {
        $errors[] = "Date of Birth is required";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
        $errors[] = "Invalid Date of Birth format";
    }

    if (empty($address)) {
        $errors[] = "Address is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Invalid phone number format";
    }

    $validSeriess = array("A", "B", "C", "D", "E", "F", "G");
    if (empty($series) || !in_array($series, $validSeriess)) {
        $errors[] = "Invalid series";
    }

    if (empty($group)) {
        $errors[] = "Group is required";
    } elseif (!preg_match("/^\d{3}$/", $group)) {
        $errors[] = "Group must be exactly 3 digits";
    }
    return $errors;
}

// Functia de adaugare student
function addStudent($name, $dob, $email, $phone, $series, $group, $address) {
    global $students;
    // Generate unique ID for new student
    $id = count($students) > 0 ? max(array_column($students, 'id')) + 1 : 1;
    // Add student to array
    $newStudent = array("id" => $id, "name" => $name, "dob" => $dob, "email" => $email, "phone" => $phone, "series" => $series, "group" => $group, "address" => $address);
    $students[] = $newStudent;
    saveStudents($students);
}

// Functia de editare student
function updateStudent($id, $name, $dob, $email, $phone, $series, $group, $address) {
    global $students;
    foreach ($students as &$student) {
        if ($student['id'] == $id) {
            $student['name'] = $name;
            $student['dob'] = $dob;
            $student['email'] = $email;
            $student['phone'] = $phone;
            $student['series'] = $series;
            $student['group'] = $group;
            $student['address'] = $address;
            break;
        }
    }
    saveStudents($students);
}

// Functia de stergere student
function deleteStudent($id) {
    global $students;
    foreach ($students as $key => $student) {
        if ($student['id'] == $id) {
            unset($students[$key]);
            break;
        }
    }
    saveStudents($students);
}

// Procesarea trimiterii de formular
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {
        $action = $_POST["action"];
        $name = $_POST["name"];
        $dob = $_POST["dob"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $series = $_POST["series"];
        $group = $_POST["group"];
        $address = $_POST["address"]; // Adaugam adresa aici
        $id = isset($_POST["id"]) ? $_POST["id"] : null;

        switch ($action) {
            case "add":
                $errors = validateStudent($name, $dob, $email, $phone, $series, $group, $address); // Adaugam adresa aici
                if (empty($errors)) {
                    addStudent($name, $dob, $email, $phone, $series, $group, $address); // Adaugam adresa aici
                }
                break;
            case "update":
                $errors = validateStudent($name, $dob, $email, $phone, $series, $group, $address); // Adaugam adresa aici
                if (empty($errors)) {
                    updateStudent($id, $name, $dob, $email, $phone, $series, $group, $address); // Adaugam adresa aici
                }
                break;
            case "delete":
                deleteStudent($id);
                break;
        }
    }
}

// Afiseaza paginat studentii existenti
foreach ($students as $student) {
    echo "<tr>";
    echo "<td>" . $student['name'] . "</td>";
    echo "<td>" . $student['dob'] . "</td>";
    echo "<td>" . $student['address'] . "</td>";
    echo "<td>" . $student['email'] . "</td>";
    echo "<td>" . $student['phone'] . "</td>";
    echo "<td>" . $student['series'] . "</td>";
    echo "<td>" . $student['group'] . "</td>";
    // Butoanele de editare si stergere
    echo "<td><button class='btn btn-sm btn-warning edit-btn' data-id='" . $student['id'] . "'>Edit</button></td>";
    echo "<td><button class='btn btn-sm btn-danger delete-btn' data-id='" . $student['id'] . "'>Delete</button></td>";
    echo "</tr>";
}
?>
