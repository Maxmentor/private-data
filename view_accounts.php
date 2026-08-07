<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$user_id = $_SESSION['user_id']; // Jo user login hai uski ID

// Delete Logic (Secured)
if(isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    // Added user_id check here so no one can delete others' accounts
    $conn->query("DELETE FROM accounts WHERE id='$del_id' AND user_id='$user_id'");
    header("Location: view_accounts.php?msg=deleted");
    exit;
}

// Search Logic & Fetch Data (Secured for Logged In User)
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT accounts.*, account_categories.name as cat_name 
            FROM accounts 
            JOIN account_categories ON accounts.category_id = account_categories.id 
            WHERE accounts.user_id='$user_id' AND (accounts.email LIKE '%$search%' OR account_categories.name LIKE '%$search%') 
            ORDER BY accounts.id DESC";
} else {
    // Added user_id condition
    $sql = "SELECT accounts.*, account_categories.name as cat_name 
            FROM accounts 
            JOIN account_categories ON accounts.category_id = account_categories.id 
            WHERE accounts.user_id='$user_id'
            ORDER BY accounts.id DESC";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Accounts</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light2">
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <a href="dashboard.php" class="btn btn-secondary">< Back</a>
        <h4>My Saved Accounts</h4>
    </div>

    <!-- Search Bar -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Email or Category..." value="<?php echo $search; ?>">
            <button type="submit" class="btn btn-warning">Search</button>
            <a href="view_accounts.php" class="btn btn-outline-danger">Clear</a>
        </div>
    </form>

    <?php if(isset($_GET['msg']) && $_GET['msg']=='deleted') echo "<div class='alert alert-success'>Account Deleted Successfully!</div>"; ?>

    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-warning">
                <tr>
                    <th>Category</th>
                    <th>Email / Username</th>
                    <th>Password</th>
                    <th>Mobile</th>
                    <th>Recovery Mail</th>
                    <th>Backup Codes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo $row['cat_name']; ?></strong></td>
                        <td><?php echo $row['email']; ?></td>
                        <td class="text-danger fw-bold"><?php echo $row['password']; ?></td>
                        <td><?php echo $row['mobile']; ?></td>
                        <td><?php echo $row['recovery_mail']; ?></td>
                        <td><small><?php echo nl2br($row['backup_code']); ?></small></td>
                        <td>
                            <a href="edit_account.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm mb-1">Edit</a>
                            <a href="view_accounts.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this account?');" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">No Accounts Found.</td></tr>
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