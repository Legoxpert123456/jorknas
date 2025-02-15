<?php 
// Database connection 
$servername = "localhost"; // Change if needed 
$username = "username"; // Your database username 
$password = "password"; // Your database password 
$dbname = "database"; // Your database name 
 
$conn = new mysqli($servername, $username, $password, $dbname); 
 
// Check connection 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
 
// File upload handling 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) { 
        $fileTmpPath = $_FILES['image']['tmp_name']; 
        $fileName = $_FILES['image']['name']; 
        $fileSize = $_FILES['image']['size']; 
        $fileType = $_FILES['image']['type']; 
         
        // Validate file type 
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; 
        if (in_array($fileType, $allowedTypes)) { 
            // Move the file to a specific directory 
            $uploadFileDir = './uploads/'; 
            $destPath = $uploadFileDir . $fileName; 
             
            if (move_uploaded_file($fileTmpPath, $destPath)) { 
                // Insert file info into the database 
                $stmt = $conn->prepare("INSERT INTO images (filename, uploaded_at) VALUES (?, NOW())"); 
                $stmt->bind_param("s", $fileName); 
                 
                if ($stmt->execute()) { 
                    echo "File is successfully uploaded."; 
                } else { 
                    echo "Database error: " . $stmt->error; 
                } 
                 
                $stmt->close(); 
            } else { 
                echo "There was an error moving the uploaded file."; 
            } 
        } else { 
            echo "Upload failed. Allowed file types: JPEG, PNG, GIF."; 
        } 
    } else { 
        echo "No file uploaded or there was an upload error."; 
    } 
} 
 
$conn->close(); 
?> 
