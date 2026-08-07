<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

// Delete Category Logic (Secured for Admin Only)
if(isset($_GET['delete'])) {
    // Check if the user is admin before deleting
    if($_SESSION['role'] == 'admin') {
        $del_id = $_GET['delete'];
        
        // Note: Agar aap category delete karte hain, toh usse jude hue accounts ki category ID blank/orphan ho sakti hai.
        $conn->query("DELETE FROM account_categories WHERE id='$del_id'");
        header("Location: view_categories.php?msg=deleted");
        exit;
    } else {
        // Agar normal user delete URL try kare
        header("Location: view_categories.php?msg=unauthorized");
        exit;
    }
}

// Search Logic & Fetch Data
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM account_categories WHERE name LIKE '%$search%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM account_categories ORDER BY id DESC";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Categories</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Nayi CSS File Link -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light2">
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4 align-items-center">
        <a href="dashboard.php" class="btn btn-secondary">< BACK TO DASHBOARD</a>
        <h4 class="m-0" style="color: #00ffcc; text-transform: uppercase; letter-spacing: 2px;">All Categories</h4>
    </div>

    <!-- Search Bar -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="SEARCH CATEGORY..." value="<?php echo $search; ?>">
            <button type="submit" class="btn btn-primary">SEARCH</button>
            <a href="view_categories.php" class="btn btn-outline-danger">RESET</a>
        </div>
    </form>

    <!-- Alert Messages -->
    <?php if(isset($_GET['msg']) && $_GET['msg']=='deleted') echo "<div class='alert alert-danger' style='border-radius: 0; background: transparent; border: 1px solid #ff0055; color: #ff0055;'>CATEGORY PURGED FROM DATABASE!</div>"; ?>
    
    <?php if(isset($_GET['msg']) && $_GET['msg']=='unauthorized') echo "<div class='alert alert-danger' style='border-radius: 0; background: transparent; border: 1px solid #ff0055; color: #ff0055;'>ACCESS DENIED: ADMIN PRIVILEGES REQUIRED!</div>"; ?>

    <!-- Category List Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Website URL</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo $row['name']; ?></strong></td>
                        <td><?php echo !empty($row['website']) ? $row['website'] : '<span class="text-muted">N/A</span>'; ?></td>
                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                        <td>
                            <!-- ADMIN CHECK START -->
                            <?php if($_SESSION['role'] == 'admin'): ?>
                                <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">EDIT</a>
                                <!-- Delete par javascript confirmation warning -->
                                <a href="view_categories.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('WARNING: Deleting this category will affect linked accounts. Proceed?');" class="btn btn-danger btn-sm">DELETE</a>
                            <?php else: ?>
                                <!-- User ko ye dikhega -->
                                <span class="text-muted" style="font-size: 0.8rem; font-weight: 600;">ADMIN ONLY</span>
                            <?php endif; ?>
                            <!-- ADMIN CHECK END -->
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">NO CATEGORIES FOUND</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- COPYRIGHT FOOTER WITH INLINE CSS -->
<div id="max-footer" style="position: fixed; bottom: 0; left: 0; width: 100%; background-color: #ffffff; color: #1e293b; text-align: center; padding: 15px 0; font-family: 'Inter', sans-serif; font-size: 0.85rem; border-top: 1px solid #cbd5e1; box-shadow: 0 -4px 10px rgba(0,0,0,0.03); z-index: 99999;">
    Copyright &copy; <a href="https://github.com/maxmentor" style="color: #2563eb; text-decoration: none; font-weight: 700;">@Maxmentor</a> | <a href="https://t.me/maxmentor" style="color: #2563eb; text-decoration: none; font-weight: 700;">Telegram</a>
</div>

<!-- ANTI-TAMPER SECURITY SCRIPT -->
<script>(function() {
    function triggerLockdown() {
        document.body.innerHTML = "<div style='background-color:#000000; color:#ff0000; height:100vh; width:100vw; position:fixed; top:0; left:0; z-index:99999999; display:flex; align-items:center; justify-content:center; font-size:2.5rem; font-weight:900; font-family:sans-serif; text-align:center;'>SYSTEM LOCKED.<br>INSPECT MODE DETECTED.</div>";
        while(true) { debugger; }
    }

    function enforceFooterProtection() {
        var footer = document.getElementById('max-footer');
        var isTampered = false;

        if (!footer) {
            isTampered = true;
        } else {
            var rawText = footer.textContent.replace(/\s+/g, '').toLowerCase();
            var expectedText = "copyright©@maxmentor|telegram";
            var links = footer.getElementsByTagName('a');

            if (rawText !== expectedText || 
                links.length !== 2 || 
                links[0].href !== "https://github.com/maxmentor" || 
                links[1].href !== "https://t.me/maxmentor") {
                isTampered = true;
            }
        }

        if (isTampered) {
            alert("Dont be smart.....Fuck You");
            triggerLockdown();
        }
    }

    // DevTools / Inspect Element Detection using Debugger Timing
    function detectDevTools() {
        const start = performance.now();
        debugger; // Jab inspect open hoga, ye execution ko pause kar dega aur time badh jayega
        const end = performance.now();
        
        if (end - start > 100) {
            triggerLockdown();
        }
    }

    window.addEventListener('load', function() {
        enforceFooterProtection();
        setInterval(enforceFooterProtection, 1500);
        setInterval(detectDevTools, 1000); // Har 1 second me inspect mode check karta rahega
    });
})();
</script>
</body>
</html>