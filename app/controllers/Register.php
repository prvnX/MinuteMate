<?php

class Register extends Controller {
    use Database;

    public function index($param1 = "", $param2 = "", $param3 = "") {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user = new User();

            // Collect and sanitize form data
            $fullName = htmlspecialchars(trim($_POST['username']));
            $roles = isset($_POST['userType']) ? $_POST['userType'] : []; // array
            $roleString = implode(',', $roles);
            $nic = htmlspecialchars(trim($_POST['nic']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); // Sanitizing email
            $tpno = htmlspecialchars(trim($_POST['tpno']));

            $studentId = $_POST['stdrep-id'] ?? null;
            $lecturerId = $_POST['lec-id'] ?? null;
            $lecStuId = $studentId ?? $lecturerId ?? null;

            if (!$lecStuId) {
                echo "<script>alert('Please enter a valid Student ID or Lecturer ID.');</script>";
                return;
            }

            
            if ($user->usernameExists($lecStuId)) {
                echo "<script>alert('This ID is already registered.try again with correct ID'); window.history.back();</script>";
                return;
            }

            // Handle optional fields with default values
            $additionalTpno = !empty($_POST['additional_tp_no']) ? htmlspecialchars(trim($_POST['additional_tp_no'])) : "Not Provided";

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email format.";
                return;
            }

            // Validate tpno and additional_tpno
            if (!preg_match('/^\d{10}$/', $tpno)) {
                echo "<script>alert('Primary contact number must be exactly 10 digits.');</script>";
                return;
            }

            if ($additionalTpno !== "Not Provided" && !preg_match('/^\d{10}$/', $additionalTpno)) {
                echo "<script>alert('Additional contact number must be exactly 10 digits if provided.');</script>";
                return;
            }


            // Database connection using PDO
            $db = $this->connect();

            try {
                // Prepare the SQL statement to insert data
                $stmt = $db->prepare("INSERT INTO user_requests (full_name, role, lec_stu_id, nic, email, tp_no, additional_tp_no, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $status = 'pending';

                // Bind parameters
                $stmt->bindParam(1, $fullName, PDO::PARAM_STR);
                $stmt->bindParam(2, $roleString, PDO::PARAM_STR);
                $stmt->bindParam(3, $lecStuId, PDO::PARAM_STR);
                $stmt->bindParam(4, $nic, PDO::PARAM_STR);
                $stmt->bindParam(5, $email, PDO::PARAM_STR);
                $stmt->bindParam(6, $tpno, PDO::PARAM_STR);
                $stmt->bindParam(7, $additionalTpno, PDO::PARAM_STR);
                $stmt->bindParam(8, $status, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    // Success: Inject JavaScript for alert
                    echo "<script>
                        alert('Your request has been successfully sent to the admin!');
                        window.location.href = '" . ROOT . "/register/success';
                    </script>";
                    exit();
                } else {
                    // Handle errors
                    echo "<script>
                        alert('An error occurred while processing your request. Please try again.');
                    </script>";
                }
                

            } catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            $this->view("register");
        }
    }
}
?>

