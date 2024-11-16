<?php include '../app/views/admin/adminsidebar.view.php'; ?>

<div class="content">
    <h2>Pending Requests</h2>
    <ul class="pending-request-list">
        <?php 
        // Dummy data for now
        $pendingRequests = [
            ['id' => 1, 'name' => 'Chamath'],
            ['id' => 2, 'name' => 'Kenneth']
        ];
        ?>
        
        <?php if (!empty($pendingRequests)): ?>
            <?php foreach ($pendingRequests as $request): ?>
                <li>
                    <!-- Make sure the link is routed correctly to the request details page -->
                    <a href="<?= ROOT ?>/admin/viewRequestDetails?id=<?php echo $request['id']; ?>">
                        <?php echo htmlspecialchars($request['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No pending requests found.</li>
        <?php endif; ?>
    </ul>
</div>

<!-- Include the CSS file here -->
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewpendingRequests.style.css">
