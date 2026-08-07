<?php
session_start();
require 'db.php';

// Security Check: Only Admin can access this page
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { 
    header("Location: dashboard.php"); 
    exit; 
}

// Redirect if ID is missing
if(!isset($_GET['id'])) { 
    header("Location: all_members.php"); 
    exit; 
}

$member_id = $_GET['id'];

// --- DELETE MEMBER LOGIC ---
if(isset($_POST['delete_member'])) {
    if($member_id != $_SESSION['user_id']) { // Khud ka account delete hone se rokna
        $conn->query("DELETE FROM users WHERE id='$member_id'");
        header("Location: all_members.php?msg=deleted");
        exit;
    } else {
        $error = "ACTION DENIED: You cannot delete your own admin account!";
    }
}

// --- UPDATE MEMBER LOGIC ---
if(isset($_POST['update_member'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Admin ko khud ko 'user' banane se rokna (Lockout prevention)
    if($member_id == $_SESSION['user_id'] && $role == 'user') {
        $error = "ACTION DENIED: You cannot downgrade your own Admin role!";
    } else {
        $sql = "UPDATE users SET username='$username', email='$email', mobile='$mobile', password='$password', role='$role' WHERE id='$member_id'";
        if($conn->query($sql)) {
            header("Location: all_members.php");
            exit;
        } else {
            $error = "DATABASE ERROR: Update Failed.";
        }
    }
}

// Fetch Current Member Data
$query = $conn->query("SELECT * FROM users WHERE id='$member_id'");
$member = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Our Sharp Corporate Light CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <a href="all_members.php" class="btn btn-secondary mb-3">BACK TO MEMBERS LIST</a>
            
            <div class="card">
                <div class="card-header">
                    <h4>EDIT MEMBER RECORD</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $member['username']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $member['email']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Mobile Number</label>
                            <input type="text" name="mobile" class="form-control" value="<?php echo $member['mobile']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Password</label>
                            <!-- Password text format me dikhaya hai taaki admin edit kar sake -->
                            <input type="text" name="password" class="form-control" value="<?php echo $member['password']; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label>Account Role</label>
                            <select name="role" class="form-select" required>
                                <option value="user" <?php if($member['role'] == 'user') echo 'selected'; ?>>USER</option>
                                <option value="admin" <?php if($member['role'] == 'admin') echo 'selected'; ?>>ADMIN</option>
                            </select>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <!-- Update Button -->
                            <button type="submit" name="update_member" class="btn btn-warning w-50">
                                UPDATE RECORD
                            </button>
                            
                            <!-- Delete Button (Khud ka account delete karne ka option disable kar diya hai) -->
                            <?php if($member['id'] != $_SESSION['user_id']): ?>
                                <button type="submit" name="delete_member" class="btn btn-danger w-50" onclick="return confirm('WARNING: Are you sure you want to permanently delete this user?');">
                                    DELETE MEMBER
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-danger w-50" disabled title="You cannot delete yourself">
                                    DELETE MEMBER
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>

                </div>
            </div>

        </div>
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