<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <title>View Memo Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/secretary/viewmemodetails.style.css">
</head>

<body>

    <div class="navbar">
        <?php
        $user = "lecturer";
        $notification = "notification"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT . "/lecturer",
            $notification => ROOT . "/lecturer/notifications",
            "profile" => ROOT . "/lecturer/viewprofile"
        ]; //pass the menu items here (key is the name of the page, value is the url)
        require_once("../app/views/components/navbar.php"); //call the navbar component
        $memo = (object) [
            'memo_id' => 1,
            'memo_title' => 'Sample Memo Title',
            'status' => 'Pending',
            'submitted_by' => 'John Doe',
            'memo_content' => '<p>This is a sample memo content.<ul><li>For testing</li><ul></p>'
        ];
        ?>
    </div>

        <div>
             <h1 class="heading">Memo Details</h1>
        </div>

    <div class="memo-details-container">
        <?php if (!empty($memo)) : ?>
         
            <p class="memo-detail"><strong>ID:</strong> <span class="memo-id"><?= htmlspecialchars($memo->memo_id) ?></span></p>
            <p class="memo-detail"><strong>Title:</strong> <span class="memo-title"><?= htmlspecialchars($memo->memo_title) ?></span></p>
            <p class="memo-detail"><strong>Status:</strong> <span class="memo-status"><?= htmlspecialchars($memo->status) ?></span></p>
            <p class="memo-detail"><strong>Submitted By:</strong> <span class="memo-submitted-by"><?= htmlspecialchars($memo->submitted_by) ?></span></p>
            <p class="memo-detail"><strong>Content:</strong></p>
            <div class="memo-content-box">
                <?php
                $memoContent = $memo->memo_content;
                echo html_entity_decode($memoContent);
                ?>
            </div>

            

            <!-- Buttons for Accept and Decline -->
            <div class="action-buttons">
    <!-- Accept Button -->
                <form method="POST" action="<?= ROOT ?>/lecturer/acceptmemo" style="display:inline;">
                    <input type="hidden" name="memo_id" value="<?= $memo->memo_id ?>">
                    <input type="hidden" name="action" value="accept">
                    <button type="submit" class="btn-accept">Accept</button>
                </form>

                <!-- Decline Button -->
                <form method="POST" action="<?= ROOT ?>/lecturer/acceptmemo" style="display:inline;">
                    <input type="hidden" name="memo_id" value="<?= $memo->memo_id ?>">
                    <input type="hidden" name="action" value="decline">
                    <button type="submit" class="btn-decline">Decline</button>
                </form>
            </div>

            <a href="<?= ROOT ?>/lecturer/reviewstudentmemo" class="btn-back">Back </a>
        <?php else : ?>
            <p class="memo-error">Memo not found.</p>
        <?php endif; ?>
    </div>


</body>

</html>
