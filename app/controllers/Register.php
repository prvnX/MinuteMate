<?php

class Register extends Controller {
    use Database;

    public function index($param1 = "", $param2 = "", $param3 = "") {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $old = $_POST;

            $user = new User();

            $fullName = htmlspecialchars(trim($_POST['username']));
            $roles = $_POST['userType'] ?? [];
            $roleString = implode(',', $roles);
            $nic = htmlspecialchars(trim($_POST['nic']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $tpno = htmlspecialchars(trim($_POST['tpno']));
            $studentId = $_POST['stdrep-id'] ?? null;
            $lecturerId = $_POST['lec-id'] ?? null;
            $lecStuId = $studentId ?? $lecturerId ?? null;
            $additionalTpno = !empty($_POST['additional_tp_no']) ? htmlspecialchars(trim($_POST['additional_tp_no'])) : "Not Provided";

            if ($studentId && !preg_match('/^stdrep\d+$/', $studentId)) {
                $errors['stdrep-id'] = 'Student Representative ID must start with "stdrep" followed by digits.';
            }
            
            if ($lecturerId && !preg_match('/^lec\d+$/', $lecturerId)) {
                $errors['lec-id'] = 'Lecturer ID must start with "lec" followed by digits.';
            }
            

            if (!$lecStuId) {
                $errors['lecStuId'] = 'Please enter a valid Student ID or Lecturer ID.';
            }

            if ($user->usernameExists($lecStuId)) {
                $errors['lecStuId'] = 'This ID is already registered. Try again with a correct ID.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format.';
            }

            if (!preg_match('/^\d{10}$/', $tpno)) {
                $errors['tpno'] = 'Primary contact number must be exactly 10 digits.';
            }

            if ($additionalTpno !== "Not Provided" && !preg_match('/^\d{10}$/', $additionalTpno)) {
                $errors['additional_tp_no'] = 'Additional contact number must be exactly 10 digits if provided.';
            }

            if (!preg_match('/^(\d{12}|\d{10}[vV])$/', $nic)) {
                $errors['nic'] = 'Invalid NIC. Please enter either 12 digits or 10 digits followed by V.';
            }

            if (!empty($errors)) {
                return $this->view("register", [
                    'errors' => $errors,
                    'old' => $old
                ]);
            }

            $userRequest = new user_requests();
            
            $notification = new Notification();
            $userRoles = new UserRoles();
            $adminUsername = $userRoles->getAdminUsername();
            $status = 'pending';

            // Insert the request and check for success
            $result = $userRequest->insertRequest($fullName, $roleString, $lecStuId, $nic, $email, $tpno, $additionalTpno, $status);

            if ($result) {
                $id = $userRequest->getLastInsertID(); 
                $requestDetails = $userRequest->getRequestById($id);
                $lec_stu_id = $requestDetails->lec_stu_id;

                $notification->insert([
                    'reciptient' => $adminUsername,
                    'notification_message' => "New request submitted by $lec_stu_id,Review Now",
                    'notification_type' => 'notifications',
                    'Ref_ID' => $id,
                    'link'=>"viewRequestDetails?id=$id"]);

                $this->view("showsuccessregistration"); 
                // echo "<script>
                //     alert('Your request has been successfully sent to the admin!');
                //     window.location.href = '" . ROOT . "/register';
                // </script>";
                exit();
            } else {
                echo "<script>alert('There was an error with your request. Please try again.');</script>";
            }
        }

        $this->view("register", ['errors' => [], 'old' => []]);
    }
}

?>