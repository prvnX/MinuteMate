<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Edit Single Requests</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewsinglerequest.css">
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <?php
        $user = "admin";
        $notification = "notification"; // Add "notification-dot" if there's a notification
        $menuItems = [
            "home" => ROOT . "/admin",
            $notification => ROOT . "/admin/notifications",
            "profile" => ROOT . "/admin/viewprofile"
        ];
        require_once("../app/views/components/navbar.php");
        $showAddEvents = false;
        ?>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="#"><img src="<?= ROOT ?>/assets/images/writing.png" alt="writing"> Enter a Memo</a></li>
            <li class="active"><a href="#"><img src="<?= ROOT ?>/assets/images/sticky.png" alt="sticky"> View Edit Requests</a></li>
            <li><a href="#"><img src="<?= ROOT ?>/assets/images/note.png" alt="note"> View Memo Reports</a></li>
            <li><a href="#"><img src="<?= ROOT ?>/assets/images/interface.png" alt="interface"> Review Student Memos</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="heading">Edit request by <span><?= htmlspecialchars($data[0]->full_name) ?></span></h1>

        <table class="request-table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Current Value</th>
                    <th>Requested Change</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Full Name</td>
                    <td><?= htmlspecialchars($data[0]->full_name) ?></td>
                    <td><?= htmlspecialchars($data[0]->new_fullname) ?></td>
                </tr>
                <tr>
                    <td>NIC</td>
                    <td><?= htmlspecialchars($data[0]->nic) ?></td>
                    <td><?= htmlspecialchars($data[0]->new_nic ?? "No change requested") ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= htmlspecialchars($data[0]->email) ?></td>
                    <td><?= htmlspecialchars($data[0]->new_email) ?></td>
                </tr>
                <tr>
                    <td>Telephone Number</td>
                    <td><?= htmlspecialchars($data[0]->tp_no ?? "No current value") ?></td>
                    <td><?= htmlspecialchars($data[0]->new_tp_no) ?></td>
                </tr>
                <tr>
                    <td>Additional Note</td>
                    <td colspan="2"><?= htmlspecialchars($data[0]->message) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="actions">
            <button class="decline-btn" onclick="declineRequest(<?= $data[0]->id ?>)">Decline</button>
            <button class="accept-btn" id="accept-req-btn">Accept Request</button>
        </div>
    </div>

    <script>
        // Decline request function
        async function declineRequest(id) {
            if (confirm("Are you sure you want to decline this request?")) {
                try {
                    const response = await fetch("<?= ROOT ?>/admin/declineRequest", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ id })
                    });

                    if (response.ok) {
                        alert("Request declined successfully.");
                        window.location.href = "<?= ROOT ?>/admin/vieweditrequests";
                    } else {
                        throw new Error("Failed to decline request. Status: " + response.status);
                    }
                } catch (error) {
                    alert("An error occurred while declining the request.");
                }
            }
        }

        // Accept request function
        document.getElementById("accept-req-btn").addEventListener("click", async function () {
            const data = {
                id: "<?= $data[0]->id ?>",
                username: "<?= htmlspecialchars($data[0]->username) ?>",
                new_fullname: "<?= htmlspecialchars($data[0]->new_fullname) ?>",
                new_nic: "<?= htmlspecialchars($data[0]->new_nic) ?>",
                new_email: "<?= htmlspecialchars($data[0]->new_email) ?>",
                new_tp_no: "<?= htmlspecialchars($data[0]->new_tp_no) ?>",
                new_department: "<?= htmlspecialchars($data[0]->new_department ?? '') ?>",
                message: "<?= htmlspecialchars($data[0]->message ?? '') ?>"
            };

            try {
                const response = await fetch("<?= ROOT ?>/admin/acceptRequest", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        alert("Request accepted successfully.");
                        window.location.href = "<?= ROOT ?>/admin/vieweditrequests";
                    } else {
                        alert(result.message || "Failed to accept the request.");
                    }
                } else {
                    throw new Error("Failed to accept request. Status: " + response.status);
                }
            } catch (error) {
                alert("An error occurred while accepting the request.");
            }
        });
    </script>
</body>

</html>
