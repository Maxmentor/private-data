<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$user_id = $_SESSION['user_id']; // Jo user login hai uski ID

// Delete Logic (Secured)
if(isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    
    // Security: Pehle check karein ki ye document isi user ka hai ya nahi
    $check_owner = $conn->query("SELECT id FROM documents WHERE id='$del_id' AND user_id='$user_id'");
    
    if($check_owner->num_rows > 0) {
        // Agar isi user ka hai toh hi delete karein
        $photo_query = $conn->query("SELECT photo_path FROM document_photos WHERE doc_id='$del_id'");
        while($p = $photo_query->fetch_assoc()) {
            if(file_exists($p['photo_path'])) { unlink($p['photo_path']); } 
        }
        $conn->query("DELETE FROM document_photos WHERE doc_id='$del_id'");
        $conn->query("DELETE FROM documents WHERE id='$del_id'");
        header("Location: view_documents.php?msg=deleted");
        exit;
    } else {
        header("Location: view_documents.php?msg=unauthorized");
        exit;
    }
}

// Search Logic & Fetch Data (Secured for Logged In User)
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    // Added user_id condition
    $sql = "SELECT * FROM documents WHERE user_id='$user_id' AND (doc_name LIKE '%$search%' OR doc_no LIKE '%$search%') ORDER BY id DESC";
} else {
    // Added user_id condition
    $sql = "SELECT * FROM documents WHERE user_id='$user_id' ORDER BY id DESC"; 
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Documents</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light2">
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <a href="dashboard.php" class="btn btn-secondary">< Back</a>
        <h4>My Documents</h4>
    </div>

    <!-- Search Bar -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Document Name or No..." value="<?php echo $search; ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="view_documents.php" class="btn btn-outline-danger">Clear</a>
        </div>
    </form>

    <?php if(isset($_GET['msg']) && $_GET['msg']=='deleted') echo "<div class='alert alert-success'>Document Deleted Successfully!</div>"; ?>
    <?php if(isset($_GET['msg']) && $_GET['msg']=='unauthorized') echo "<div class='alert alert-danger'>Access Denied: You cannot delete someone else's document!</div>"; ?>

    <!-- Documents List -->
    <div class="table-responsive bg-white shadow-sm rounded p-3">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Doc Name</th>
                    <th>Doc No.</th>
                    <th>Expiry Date</th>
                    <th>Photos</th>
                    <th>Actions</th>
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
                                <img src="<?php echo $pic['photo_path']; ?>" width="50" height="50" class="rounded border" style="cursor:pointer; object-fit: cover;" data-bs-toggle="modal" data-bs-target="#photoModal<?php echo $pic['id']; ?>">

                                <div class="modal fade" id="photoModal<?php echo $pic['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Document Photo <?php echo $count; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="<?php echo $pic['photo_path']; ?>" class="img-fluid rounded">
                                            </div>
                                            <div class="modal-footer">
                                                <a href="<?php echo $pic['photo_path']; ?>" download class="btn btn-success">Download</a>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php $count++; endwhile; ?>
                        </td>
                        <td>
                            <a href="edit_document.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="view_documents.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete?');" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">No Documents Found.</td></tr>
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