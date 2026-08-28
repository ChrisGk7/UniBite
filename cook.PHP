<?php
session_start();
include ('database.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}   

$student = get_student_by_username($_SESSION['username'], $conn);
if($student){
$cook=ensure_cook_registered($student['username'],$conn);//After redirect this function    
}                                                       //this function inserts the student into the table cook


?>




<!DOCTYPE html>

<html>

<head>
    <link rel="stylesheet" href="cook.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">  
    <link rel="icon" type="image/png" href="images/Unibite_icon.png">
    <title>Unibite - Οι Αγγελίες μου</title>
</head>

<body>


<header class="cook-header">
    
    <button type="button" class="requestsBtn">Τα ετοίματά μου</button>
    <h1>Οι Αγγελίες μου</h1>


</header>


<main>

    <section class="new-listing">
        
        <h2>Νέα Αγγελία</h2>
    
        <form id="listingForm" class="listingForm" method="POST" enctype="multipart/form-data" action="create_dish.php" novalidate>
        
        <div class="form-group">
            <label>Τίτλος</label>
            <input type="text" id="title" name="title" required data-error="Please enter a title.">
            <span class="error-message" aria-live="polite"></span>
        </div>

        <div class="form-group">
            <label>Φωτογραφία</label>
            <input type="file" id="image" name="image" accept="image/*">
            <div id="imagePreviewContainer" style="margin-top: 15px; display: none;">
                    <p style="font-weight: bold; margin-bottom: 5px; color: var(--dark-soft);">Προεπισκόπηση:</p>
                    <img id="imagePreview" src="" alt="Preview" class="listing-image" style="max-height: 400px; width: 100%; object-fit: cover; border-radius: 12px; border: 1px solid var(--border);">
                </div>
        </div>

        <div class="form-group">
            <label>Σημειώσεις</label>
            <textarea id="notes" name="description" required data-error="Please enter notes."></textarea>
            <span class="error-message" aria-live="polite" ></span>
        </div>

        <div class="form-group">    
            <label>Αλλεργιογόνα</label>
            <input type="text" id="allergerns" name="allergens" placeholder="πχ, Γλουτένη, Γάλα" required  data-error="Please enter the allergerns.">
            <span class="error-message" aria-live="polite"></span>
        </div>

        <div class="form-group">
            <label>Μερίδες</label>
            <input type="number" id="portions" name="portions" min="1" required data-error="Please enter portions.">
            <span class="error-message" aria-live="polite"></span>
        </div>
        
        <div class="form-group">
            <label>Τοποθεσία Παραλαβής</label>
            <input type="text" id="pickupLocation" name="pickup_location" required data-error="Please enter the pickup location.">
            <span class="error-message" aria-live="polite" ></span>
        </div>
        
        <div class="form-group">
            <label>Σημείο στον χάρτη (κάντε κλικ για να το ορίσετε)</label>
            <div id="pickupMap" style="height: 250px; border-radius: 12px;"></div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <span id="pickupMapError" class="error-message" aria-live="polite"></span>
        </div>
        
        <div class="form-group">
            <label>Ώρα Παραλαβής</label>
            <input type="datetime-local" id="pickupTime" name="pickup_time" required data-error="Please enter the pickup time.">
            <span class="error-message" aria-live="polite"></span>
        </div>
            
        <div class="form-group">
            <button type="submit">Δημιουργία Αγγελίας</button>
        </div>
        </form>

    </section>

    <hr>

    <section class="my-listings">
        <h2>Οι αγγελίες μου</h2>

        <div id="listingsContainer" class="listingsContainer">
            <article class="listing-card">
                <h3>Μακαρόνια με κιμά</h3>
                <p>Σποιτικό Φαγητό</p>

                <p>Αλλεργιογόνα:
                   Γλουτένη
                </p>


                <button class="editBtn">Επεξεργασία</button>

                <button class="deleteBtn">Διαγραφή</button>

            </article>

        </div>

    </section>

</main>

</main>


<div class="requestsModal">

    <div class="requests-modal-content">

        <div class="requests-modal-header">

            <h2>Τα αιτήματά μου</h2>

            <button type="button" class="closeRequestsBtn">
                ×
            </button>

        </div>

        <div class="requests-list">

            <p class="no-requests-message">
                Δεν υπάρχουν αιτήματα αυτή τη στιγμή.
            </p>

        </div>

    </div>

</div>


<div id="deleteModal" class="modal">



<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h2>Διαγραφή αγγελίας</h2>

        <p>Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτήν την αγγελία;"</p>

        <div class="modal-buttons">

        <button id="cancelDelete" class="cancelDelete">Ακύρωση</button>
        <button id="confirmDelete" class="confirmDelete">Διαγραφή</button>
        </div>
    </div>

</div>


<div id="editModal" class="modal">
    
    <div class="edit-modal-content">

        <h2>Επεξεργασία Αγγελίας</h2>
        <form id="editForm" class="editForm">

            <label>Τίτλος</label>
            <input type="text" id="editTitle">

            <label>Φωτογραφία</label>
            <input type="file" id="editImage">

            <label>Σημειώσεις</label>
            <textarea id="editNotes"></textarea>

            <label>Αλλεργιογόνα</label>
            <input type="text" id="editAllegerns">

            <label>Μερίδες</label>
            <input type="number" id="editPortions" min="1">

            <label>Τοποθεσία Παραλαβής</label>
            <input type="text" id="editPickupLocation">

            <label>Σημείο στον χάρτη (κάντε κλικ για να το ορίσετε)</label>
            <div id="editPickupMap" style="height: 250px; border-radius: 12px;"></div>
            <input type="hidden" id="editLatitude">
            <input type="hidden" id="editLongitude">

            <label>Ώρα Παραλαβής</label>
            <input type="datetime-local" id="editPickupTime">


            <div class="edit-modal-buttons">
                
                <button type="submit" id="saveEdit" class="saveEdit">Αποθήκευση Αλλαγών</button>

                <button type="button" id="cancelEdit" class="cancelEdit">Ακύρωση</button>


            </div>

        </form>

    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="map.js"></script>
<script src="form.js"></script>
<script src="cook.js"></script> 

<script>
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('imagePreview');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImage.setAttribute('src', e.target.result);
            previewContainer.style.display = 'block';
        };

        reader.readAsDataURL(file);
    } else {
        previewImage.setAttribute('src', '');
        previewContainer.style.display = 'none';
    }
});
</script>




<script>
const requestBtn = document.querySelector('.requestsBtn');
const requestsModal = document.querySelector('.requestsModal');
const closeRequestsBtn = document.querySelector('.closeRequestsBtn');

requestBtn.addEventListener('click', function() {
    requestsModal.style.display = 'flex';
});

closeRequestsBtn.addEventListener('click', function() {
    requestsModal.style.display = 'none';
});
</script>










</body>

</html>