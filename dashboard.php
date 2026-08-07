<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Private Data</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light2">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-light" href="#">Private Data</a>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Welcome, <?php echo $_SESSION['username']; ?> (<?php echo ucfirst($_SESSION['role']); ?>)</span>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row g-3">
        <!-- Common Buttons (User & Admin both) -->
        <div class="col-6 col-md-3"><a href="add_document.php" class="btn btn-primary w-100 p-3 shadow-sm">Add Documents</a></div>
        <div class="col-6 col-md-3"><a href="view_documents.php" class="btn btn-info text-white w-100 p-3 shadow-sm">List Documents</a></div>
        <div class="col-6 col-md-3"><a href="add_category.php" class="btn btn-secondary w-100 p-3 shadow-sm">Add AC Category</a></div>
        <div class="col-6 col-md-3"><a href="view_categories.php" class="btn btn-warning w-100 p-3 shadow-sm">View Category</a></div>
        <div class="col-6 col-md-3"><a href="add_account.php" class="btn btn-success w-100 p-3 shadow-sm">Add Accounts</a></div>
        <div class="col-6 col-md-3"><a href="view_accounts.php" class="btn btn-warning text-dark w-100 p-3 shadow-sm">View Accounts</a></div>
        <div class="col-6 col-md-3"><a href="edit_profile.php" class="btn btn-success w-100 p-3 shadow-sm">Edit Profile</a></div>
        

        <!-- Admin Only Buttons -->
        <?php if($_SESSION['role'] == 'admin'): ?>
            <div class="col-6 col-md-3"><a href="add_member.php" class="btn btn-outline-primary w-100 p-3 shadow-sm">Add Member</a></div>
            <div class="col-6 col-md-3"><a href="all_members.php" class="btn btn-info w-100 p-3 shadow-sm">All Members</a></div>
        <?php endif; ?>
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