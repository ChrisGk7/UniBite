<?php




?>




<!DOCTYPE html>

<html>

<head>
    <link rel="stylesheet" href="cook.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">  
    <title>Unibite - Οι Αγγελίες μου</title>
</head>

<body>


<header>Οι αγγελίες μου</header>


<main>

    <section class="new-listing">
        
        <h2>Νέα Αγγελία</h2>

        <form id="listingForm"  method="POST" enctype="multipart/form-data" novalidate>
        <div class="form-group">
            <label>Τίτλος</label>
            <input type="text" id="title" name="title" required data-error="Please enter a title.">
            <span class="error-message" aria-live="polite"></span>
        </div>
        <div class="form-group">
            <label>Φωτογραφία</label>
            <input type="file" id="image" name="image" accept="image/*">
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
        <div>
        <div class="form-group">
            <label>Ώρα Παραλαβής</label>
            <input type="datetime-local" id="pickupTime" name="pickup_time" required data-error="Please enter the pickup time.">
            <span class="error-message" aria-live="polite"></span>
        <div>
            
        <div class="form-group">
            <button type="submit">Δημιουργία Αγγελίας</button>
        </div>
        </form>

    </section>

    <hr>

    <section class="my-listings">
        <h2>Οι αγγελίες μου</h2>

        <div id="listingsContainer">
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


<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h2>Διαγραφή αγγελίας</h2>

        <p>Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτήν την αγγελία;"</p>

        <div class="modal-buttons">

        <button id="cancelDelete">Ακύρωση</button>
        <button id="confirmDelete">Διαγραφή</button>
        </div>
    </div>

</div>


<div id="editModal" class="modal">
    
    <div class="edit-modal-content">

        <h2>Επεξεργασία Αγγελίας</h2>
        <form id="editForm">

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

            <label>Ώρα Παραλαβής</label>
            <input type="datetime-local" id="editPickupTime">


            <div class="edit-modal-buttons">
                
                <button type="submit" id="saveEdit">Αποθήκευση Αλλαγών</button>

                <button type="button" id="cancelEdit">Ακύρωση</button>


            </div>

        </form>

    </div>

</div>

<script src="cook.js"></script> 

<script src="form.js"></script>

</body>

</html>
