<?php
session_start();
require 'db.php';
// Sirf admin access kar sakta hai
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location: dashboard.php"); exit; }

if(!isset($_GET['user_id'])) { header("Location: all_members.php"); exit; }
$target_user_id = $_GET['user_id'];

// Target user ka naam nikalna heading ke liye
$user_query = $conn->query("SELECT username FROM users WHERE id='$target_user_id'");
$target_user = $user_query->fetch_assoc();

$sql = "SELECT accounts.*, account_categories.name as cat_name 
        FROM accounts 
        JOIN account_categories ON accounts.category_id = account_categories.id 
        WHERE accounts.user_id='$target_user_id' ORDER BY accounts.id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Accounts</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <a href="all_members.php" class="btn btn-secondary">< Back to Members</a>
        <h4>Accounts of: <span class="text-primary"><?php echo $target_user['username']; ?></span></h4>
    </div>

    <div class="table-responsive bg-white shadow-sm p-3">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-warning">
                <tr>
                    <th>Category</th>
                    <th>Email / Username</th>
                    <th>Password</th>
                    <th>Mobile</th>
                    <th>Recovery Mail</th>
                    <th>Backup Codes</th>
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
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">No Accounts Found.</td></tr>
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