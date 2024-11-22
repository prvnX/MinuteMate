<?php 
class BaseController extends Controller {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure the user is logged in
        if (!isset($_SESSION['userDetails'])) {
            redirect("login");
            exit;
        }

        // Role-based access control
        $currentRole = $_SESSION['userDetails']->role;
        $controller = strtolower((new ReflectionClass($this))->getShortName());
        if ($controller !== $currentRole) {
            redirect("login");
            exit;
        }
    }
}
