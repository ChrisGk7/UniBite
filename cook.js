const listingForm = document.getElementById("listingForm");
const listingContainer = document.getElementById("listingsContainer");
const titleInput = document.getElementById("title");
const imageInput = document.getElementById("image");
const notesInput = document.getElementById("notes");
const allergernsInput = document.getElementById("allergerns");


const deleteModal = document.getElementById("deleteModal");
const confirmDelete = document.getElementById("confirmDelete");
const cancelDelete = document.getElementById("cancelDelete");
let listingToDelete= null;



//Δημιουργία Αγγελίας

listingForm.addEventListener("submit", function(event){
    event.preventDefault();

    const title = titleInput.value.trim();
    const notes = notesInput.value.trim();
    const allergerns = allergernsInput.value.trim();
    const imageFile = imageInput.files[0];

    if(!title){
        alert("Παρακαλώ δώσε έναν τίτλο.");
        return;
    }



    const article = document.createElement("article");
    article.classList.add("listing-card");

    const h3 = document.createElement("h3");
    h3.textContent = title ; 

    const image = document.createElement("img");
    if(image){
        image.src = URL.createObjectURL(imageFile);

        image.classList.add("listing-image");

        
    }

    const notesParagraph = document.createElement("p");
    if(!notes){
        notesParagraph.textContent="Δεν υπάρχουν σημειώσεις."
    }
    else {
        notesParagraph.textContent=notes;
    }


    const allergernsParagraph = document.createElement("p");
    if(allergernsParagraph!==""){
            allergernsParagraph.textContent="Αλλεργιογώνα: "+allergerns;
    }
    else{
        allergernsParagraph.textContent="Δεν υπάρχουν αλλεργιογώνα";
    }

    const editButton = document.createElement("button");
    editButton.textContent = "Επεξεργασία";
    editButton.classList.add("editBtn");


    const deleteButton = document.createElement("button");
    deleteButton.textContent = "Διαγραφή";
    deleteButton.classList.add("deleteBtn");


    article.appendChild(image);
    article.appendChild(h3);
    article.appendChild(notesParagraph);
    article.appendChild(allergernsParagraph);
    article.appendChild(editButton);
    article.appendChild(deleteButton);

    listingContainer.appendChild(article)

    listingForm.reset();

});


listingContainer.addEventListener("click", function(event){

//ΚΟΥΜΠΙ ΕΠΕΞΕΡΓΑΣΙΑΣ
 if(event.target.classList.contains("editBtn")){
    const listing = event.target.closest(".listing-card");

    const titleElement = listing.querySelector("h3");

    const paragraphs = listing.querySelectorAll("p");

    const notesElement = paragraphs[0];
    const allergernsElement = paragraphs[1];

    const newTitle = prompt(
        "Νέος Τίτλος: ",titleElement.textContent
    );

    if(newTitle !==null && newTitle.trim() !==""){
        titleElement.textContent = newTitle.trim();
    }


    const newNotes = prompt(
        "Νέες σημειώσεις: ",notesElement.textContent
    );

    if(newNotes!==null){
        notesElement.textContent= newNotes.trim() !=="" ? newNotes.trim() : "Δεν υπάρχουν σημειώσεις";
    }


    const currentAllergerns = allergernsElement.textContent.replace("Αλλεργιογόνα: ","");


    const newAllergerns = prompt(
        "Νέα αλλεργιογόνα: ", currentAllergerns
    );

    if(newAllergerns !== null){
        allergernsElement.textContent= newAllergerns.trim()!=="" ? "Αλλεργιογόνα: " + newAllergerns.trim() : "Δεν υπάρχουν αλλεργιογόνα.";

    }

 }

//ΚΟΥΜΠΙ ΔΙΑΓΡΑΦΗΣ
if(event.target.classList.contains("deleteBtn")){

    listingToDelete= event.target.closest(".listing-card");

    deleteModal.style.display="flex";

}


});
//Παραθυρο για την διαγραφη


//cancel
cancelDelete.addEventListener("click", function(){
    deleteModal.style.display = "none";

    listingToDelete = null;

});

//confirm
confirmDelete.addEventListener("click", function(){
    if(listingToDelete){
        listingToDelete.remove();
        listingToDelete= null;
        deleteModal.style.display = "none";
    }
});

