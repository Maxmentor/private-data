<?php
session_start();
require 'db.php';

// Sirf admin access kar sakta hai
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { 
    header("Location: dashboard.php"); 
    exit; 
}

if(!isset($_GET['user_id'])) { 
    header("Location: all_members.php"); 
    exit; 
}

$target_user_id = $_GET['user_id'];

// Target user ka naam nikalna heading ke liye
$user_query = $conn->query("SELECT username FROM users WHERE id='$target_user_id'");
$target_user = $user_query->fetch_assoc();

$sql = "SELECT * FROM documents WHERE user_id='$target_user_id' ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Documents - Admin View</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS for Modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <a href="all_members.php" class="btn btn-secondary">< BACK TO MEMBERS</a>
        <h4>DOCUMENTS OF: <span class="text-primary"><?php echo strtoupper($target_user['username']); ?></span></h4>
    </div>

    <div class="table-responsive bg-white shadow-sm p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Doc Name</th>
                    <th>Doc No.</th>
                    <th>Expiry Date</th>
                    <th>Photos</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo $row['doc_name']; ?></strong></td>
                        <td><?php echo $row['doc_no']; ?></td>
                        <td><?php echo $row['expiry_date']; ?></td>
                        <td>
                            <?php 
                            $doc_id = $row['id'];
                            $photos = $conn->query("SELECT * FROM document_photos WHERE doc_id='$doc_id'");
                            $count = 1;
                            while($pic = $photos->fetch_assoc()):
                            ?>
                                <!-- Photo Thumbnail (Clickable) -->
                                <img src="<?php echo $pic['photo_path']; ?>" width="60" height="60" class="border" style="cursor:pointer; object-fit: cover; margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#photoModal<?php echo $pic['id']; ?>">

                                <!-- Full Screen Photo Modal (Popup) -->
                                <div class="modal fade" id="photoModal<?php echo $pic['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">DOCUMENT PHOTO <?php echo $count; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="<?php echo $pic['photo_path']; ?>" class="img-fluid" style="max-height: 70vh;">
                                            </div>
                                            <div class="modal-footer">
                                                <!-- Download Button -->
                                                <a href="<?php echo $pic['photo_path']; ?>" download class="btn btn-success">DOWNLOAD</a>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php $count++; endwhile; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">NO DOCUMENTS FOUND FOR THIS USER.</td></tr>
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