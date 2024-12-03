<?php 
include '../includes/header.php'; 
?>
<div class="container comp-card">
<h2>My Resume</h2>

<!-- Embed PDF Resume -->
<div class="card">
    
    <div class="card-body">
        <object data="../uploads/resume.pdf" type="application/pdf" width="100%" height="600px">
            <p>Your browser does not support embedded PDFs. You can <a href="../uploads/resume.pdf" target="_blank">download the resume here</a>.</p>
        </object>
    </div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
