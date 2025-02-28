<?php

class Register extends Controller {
    use Database;

    public function index($param1 = "", $param2 = "", $param3 = "") {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect and sanitize form data
            $fullName = htmlspecialchars(trim($_POST['username']));
            $role = htmlspecialchars(trim($_POST['userType']));
            $lecStuId = htmlspecialchars(trim($_POST['lec-id']));
            $nic = htmlspecialchars(trim($_POST['nic']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); // Sanitizing email
            $tpno = htmlspecialchars(trim($_POST['tpno']));

            // Handle optional fields with default values
            $additionalTpno = !empty($_POST['additional_tp_no']) ? htmlspecialchars(trim($_POST['additional_tp_no'])) : "Not Provided";
            $department = !empty($_POST['department']) ? htmlspecialchars(trim($_POST['department'])) : "Not Specified";

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email format.";
                return;
            }

            // Database connection using PDO
            $db = $this->connect();

            try {
                // Prepare the SQL statement to insert data
                $stmt = $db->prepare("INSERT INTO user_requests (full_name, role, lec_stu_id, nic, email, tp_no, additional_tp_no, department, status) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $status = 'pending';

                // Bind parameters
                $stmt->bindParam(1, $fullName, PDO::PARAM_STR);
                $stmt->bindParam(2, $role, PDO::PARAM_STR);
                $stmt->bindParam(3, $lecStuId, PDO::PARAM_STR);
                $stmt->bindParam(4, $nic, PDO::PARAM_STR);
                $stmt->bindParam(5, $email, PDO::PARAM_STR);
                $stmt->bindParam(6, $tpno, PDO::PARAM_STR);
                $stmt->bindParam(7, $additionalTpno, PDO::PARAM_STR);
                $stmt->bindParam(8, $department, PDO::PARAM_STR);
                $stmt->bindParam(9, $status, PDO::PARAM_STR);

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

