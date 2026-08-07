<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location: dashboard.php"); exit; }

if(isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    if($del_id != $_SESSION['user_id']) { // Khud ko delete hone se rokna
        $conn->query("DELETE FROM users WHERE id='$del_id'");
        header("Location: all_members.php?msg=deleted");
    }
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>All Members</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light2">
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <a href="dashboard.php" class="btn btn-secondary">< Back</a>
        <h4>All Members</h4>
    </div>
    
    <?php if(isset($_GET['msg'])) echo "<div class='alert alert-danger'>Member Deleted!</div>"; ?>

    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['mobile']; ?></td>
                    <td><span class="badge bg-<?php echo ($row['role']=='admin') ? 'danger' : 'primary'; ?>"><?php echo strtoupper($row['role']); ?></span></td>
                    <td>
                        <!-- Naye Read-Only View Buttons -->
                        <a href="view_user_documents.php?user_id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Docs</a>
                        <a href="view_user_accounts.php?user_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Accounts</a>
                        
                        <!-- Purane Edit & Delete Buttons -->
                        <a href="edit_member.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php if($row['id'] != $_SESSION['user_id']): ?>
                            <a href="all_members.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete Member?');" class="btn btn-danger btn-sm">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
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