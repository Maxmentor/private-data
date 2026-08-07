<?php
session_start();
require 'db.php';

// Authentication Check
if(!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit; 
}

// Redirect if ID is missing
if(!isset($_GET['id'])) { 
    header("Location: view_categories.php"); 
    exit; 
}

$cat_id = $_GET['id'];

// Fetch Current Category Data from Database
$cat_query = $conn->query("SELECT * FROM account_categories WHERE id='$cat_id'");
$category = $cat_query->fetch_assoc();

// Update Logic on Form Submit
if(isset($_POST['update_cat'])) {
    $name = $_POST['name'];
    $website = $_POST['website'];

    $sql = "UPDATE account_categories SET name='$name', website='$website' WHERE id='$cat_id'";
    
    if($conn->query($sql)) {
        header("Location: view_categories.php"); // Update hote hi list page par bhej dega
        exit;
    } else {
        $error = "DATABASE ERROR: UPDATE FAILED.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - Private Data</title>
     <link rel="stylesheet" href="style.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Our Custom Stealth CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <a href="view_categories.php" class="btn btn-secondary mb-4">< BACK TO LIST</a>
            
            <div class="card">
                <div class="card-header">
                    <h4>EDIT CATEGORY DATA</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(isset($error)) echo "<div class='alert alert-danger' style='border-radius:0; border: 1px solid #ff0055; background: transparent; color: #ff0055;'>$error</div>"; ?>
                    
                    <form method="POST">
                        <div class="mb-4">
                            <label>CATEGORY NAME</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $category['name']; ?>" required>
                        </div>
                        
                        <div class="mb-5">
                            <label>WEBSITE URL (OPTIONAL)</label>
                            <input type="text" name="website" class="form-control" value="<?php echo $category['website']; ?>">
                        </div>
                        
                        <button type="submit" name="update_cat" class="btn btn-warning w-100 py-3">
                            UPDATE RECORD
                        </button>
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