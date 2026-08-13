<?php

require_once "database.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){
     $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $allergens = trim($_POST["allergens"]);

    $pickup_location = trim($_POST["pickup_location"]);
    $pickup_time = $_POST["pickup_time"];

    $portions = (int) $_POST["portions"];

    $photo_url=null;//gia twra
    
    $cook="testcook";
  
    //photo save to uploads folder
   $photo_url = null;

if (!empty($_FILES["image"]["name"])) {

    $file_name = $_FILES["image"]["name"];

    $photo_url = "uploads/" . $file_name;

    move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $photo_url
    );
}
    create_dish($cook,$title,$description,$allergens,$photo_url,$pickup_location,$pickup_time,$portions,$conn);

    echo "Η Αγγελία αποθηκεύτηκε!";
}

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

        <form id="listingForm"  method="POST" enctype="multipart/form-data">
            <label>Τίτλος</label>
            <input type="text" id="title" data-error="Please enter a title" name="title" required>
            <span class="error-message" aria-live="polite"></span>

            <label>Φωτογραφία</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <label>Σημειώσεις</label>
            <textarea id="notes" name="description" required></textarea>

            <label>Αλλεργιογόνα</label>
            <input type="text" id="allergerns" name="allergens" placeholder="πχ, Γλουτένη, Γάλα" required>

            <label>Μερίδες</label>
            <input type="number" id="portions" name="portions" min="1" required>

            <label>Τοποθεσία Παραλαβής</label>
            <input type="text" id="pickupLocation" name="pickup_location" required>

            <label>Ώρα Παραλαβής</label>
            <input type="number" id="pickupTime" name="pickup_time" required>

            

            <button type="submit">Δημιουργία Αγγελίας</button>
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
