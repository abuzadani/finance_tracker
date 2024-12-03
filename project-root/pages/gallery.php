<?php 
include '../includes/header.php'; 
?>

<div class="container comp-card">
    <h2>Picture Gallery</h2>

    <!-- Gallery Container -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php
    // Define the path to the images folder
        $image_dir = '../uploads/gallery/';
        
    // Get all image files in the gallery directory (you may want to add specific file extensions check for images)
        $images = array_diff(scandir($image_dir), array('..', '.'));

        foreach ($images as $image): ?>
            <div class="col">
                <a href="#" class="thumbnail" data-bs-toggle="modal" data-bs-target="#imageModal" data-bs-image="<?php echo $image_dir . $image; ?>">
                    <img src="<?php echo $image_dir . $image; ?>" class="img-fluid rounded" alt="Gallery Image" style="cursor: pointer;">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<!-- Modal to show the large image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Large Image">
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript to handle clicking on the thumbnail and displaying the image in the modal
    document.querySelectorAll('.thumbnail').forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            var imageSrc = this.getAttribute('data-bs-image');
            document.getElementById('modalImage').src = imageSrc;
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
