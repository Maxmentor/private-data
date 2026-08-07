<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

if(isset($_POST['add_doc'])) {
    $user_id = $_SESSION['user_id'];
    $doc_name = $_POST['doc_name'];
    $doc_no = $_POST['doc_no'];
    $expiry = $_POST['expiry_date'];

    // Insert Document First
    $conn->query("INSERT INTO documents (user_id, doc_name, doc_no, expiry_date) VALUES ('$user_id', '$doc_name', '$doc_no', '$expiry')");
    $doc_id = $conn->insert_id;

    // Handle Multiple Photo Uploads
    if(isset($_FILES['photos']['name'][0]) && $_FILES['photos']['name'][0] != "") {
        foreach($_FILES['photos']['name'] as $key => $image) {
            $target_dir = "uploads/";
            $file_name = time() . "_" . basename($_FILES['photos']['name'][$key]);
            $target_file = $target_dir . $file_name;
            
            if(move_uploaded_file($_FILES['photos']['tmp_name'][$key], $target_file)) {
                $conn->query("INSERT INTO document_photos (doc_id, photo_path) VALUES ('$doc_id', '$target_file')");
            }
        }
    }
    $success = "Document Added Successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light2">
<div class="container mt-5">
    <a href="dashboard.php" class="btn btn-secondary mb-3">< Back</a>
    <div class="card shadow-sm">
        <div class="card-header text-white"><h4>Add New Document</h4></div>
        <div class="card-body">
            <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Document Name</label>
                        <input type="text" name="doc_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Document No.</label>
                        <input type="text" name="doc_no" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Expiry Date (optional)</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Photos (Select Multiple)</label>
                        <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                    </div>
                </div>
                <button type="submit" name="add_doc" class="btn btn-primary">ADD Document</button>
            </form>
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