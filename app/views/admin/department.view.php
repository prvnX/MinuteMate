<?php 

$user="admin";
$notification="notification"; //use notification-dot if there's a notification
$menuItems = [ 
  "home" => ROOT."/admin", 
  $notification => ROOT."/admin/notifications", 
  "profile" => ROOT."/admin/viewprofile", 
  "logout" => ROOT."/admin/confirmlogout"
];
require_once("../app/views/components/admin_navbar.php");
require_once("../app/views/components/admin_sidebar.php"); 
?>

<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/department.style.css">

<div class="main-content">
     <h2 class="page-title">Departments</h2>

     <div class="card-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department</th>
                    <th>Department Head</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($department)): ?>
                    <?php foreach ($department as $dept): ?>
                        <tr>
                            <td><?= $dept->id ?></td>
                            <td><?= $dept->dep_name ?></td>
                            <td><?= $dept->department_head ?></td>
                            <td><?= $dept->dep_email ?></td>
                            <td>
                                    <button class="edit-button"
                                            onclick="openEditModal('<?= $dept->id ?>', '<?= $dept->dep_name ?>', '<?= $dept->department_head ?>', '<?= $dept->dep_email ?>')">
                                        Edit
                                    </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                     <td colspan="5">No departments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
  </div>

  <div class="add-button-wrapper">
      <button class="add-btn" onclick="openAddModel()">+ Add New Department</button>
  </div>

  <div id="departmentModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 id="modalTitle">Add Department</h3>
        <form action="<?= ROOT ?>/admin/saveDepartment" method="post">
                <input type="hidden" name="id" id="dept_id">
                <div>
                    <label>Department Name;</label>
                    <input type="text" name="dep_name" id="dep_name" required>
                </div>
                <div>
                    <label>Department Head:</label>
                    <input type="text" name="department_head" id="department_head" required>
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" name="dep_email" id="dep_email" required>
                </div>
                <div>
                    <button type="submit" class="save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModel(){
        document.getElementById("modalTitle").innerText = "Add Department";
        document.getElementById("dept_id").value = "";
        document.getElementById("dep_name").value = "";
        document.getElementById("department_head").value = "";
        document.getElementById("dep_email").value = "";
        document.getElementById("departmentModal").style.display = "block";
    }

    function openEditModal(id, name, head, email) {
        document.getElementById("modalTitle").innerText = "Edit Department";
        document.getElementById("dept_id").value = id;
        document.getElementById("dep_name").value = name;
        document.getElementById("department_head").value = head;
        document.getElementById("dep_email").value = email;
        document.getElementById("departmentModal").style.display = "block";
    }

    function closeModal(){
        document.getElementById("departmentModal").style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target === document.getElementById("departmentModal")) {
            closeModal();
        }
    }

</script>