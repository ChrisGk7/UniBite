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



const editModal=document.getElementById("editModal");
const editForm=document.getElementById("editForm");

const editTitle=document.getElementById("editTitle");
const editNotes=document.getElementById("editNotes");
const editImage=document.getElementById("editImage");
const editAllegerns=document.getElementById("editAllegerns");
const cancelEdit=document.getElementById("cancelEdit");
let listingToEdit=null;


//Validation checkup
function validateField(field) {

    const errorElement = field.nextElementSibling;

    if (!field.validity.valid) {
        errorElement.textContent =
            field.dataset.error || "This field is not valid.";

        return false;
    }

    errorElement.textContent = "";
    return true;
}



function validateForm() {

    const fields = listingForm.querySelectorAll(
        "input[required], textarea[required]"
    );

    let isValid = true;

    fields.forEach(function(field) {

        if (!validateField(field)) {
            isValid = false;
        }

    });

    return isValid;
}


const fields = listingForm.querySelectorAll(
    "input[required], textarea[required]"
);

fields.forEach(function(field) {

    field.addEventListener("blur", function() {
        validateField(field);
    });

});
//Δημιουργία Αγγελίας

listingForm.addEventListener("submit", function(event){
    //event.preventDefault();

    if (!validateForm()) {
        event.preventDefault();
        return;
    }

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

    //listingForm.reset();

});



listingContainer.addEventListener("click", function(event){

//ΚΟΥΜΠΙ ΕΠΕΞΕΡΓΑΣΙΑΣ
 if(event.target.classList.contains("editBtn")){
    listingToEdit= event.target.closest(".listing-card");

    const titleElement = listingToEdit.querySelector("h3");
    const paragraphs = listingToEdit.querySelectorAll("p");

    editTitle.value = titleElement.textContent;

    editNotes.value=paragraphs[0].textContent;

    editAllegerns.value= paragraphs[1].textContent.replace("Αλλεργιογόνα: ", "");

    editModal.style.display = "flex";

 }

//canceledit
cancelEdit.addEventListener("click", function(){

    editModal.style.display="none";
    listingToEdit=null;

})

//SaveEdit
editForm.addEventListener("submit", function(event){
    //event.preventDefault();

    const newTitle = editTitle.value.trim();
    const newNotes = editNotes.value.trim();
    const newAllegerns = editAllegerns.value.trim();
    const titleElement=listingToEdit.querySelector("h3");
    const paragraphs= listingToEdit.querySelectorAll("p");
    const notesElement= paragraphs[0];
    const allergernsElement = paragraphs[1];

    titleElement.textContent= newTitle;

    if(!newTitle){
        alert("Παρακαλώ δώσε έναν νέο τίτλο: ");
        return;
    }

    

    if(newNotes !==""){
        notesElement.textContent= newNotes;
    }
    else{
        notesElement.textContent="Δεν υπάρχουν σημειώσεις.";
    }

    if(newAllegerns!==""){
        allergernsElement.textContent="Αλλεργιογόνα: "+ newAllegerns;
    }
    else{
        allergernsElement.textContent="Δεν υπάρχουν αλλεργιογόνα. ";
    }

    editModal.style.display="none";

    listingToEdit=null;

});



//ΚΟΥΜΠΙ ΔΙΑΓΡΑΦΗΣ
if(event.target.classList.contains("deleteBtn")){

    listingToDelete= event.target.closest(".listing-card");

    deleteModal.style.display="flex";

}

});

//canceldel
cancelDelete.addEventListener("click", function(){
    deleteModal.style.display = "none";

    listingToDelete = null;

});

//confirmdel
confirmDelete.addEventListener("click", function(){
    if(listingToDelete){
        listingToDelete.remove();
        listingToDelete= null;
        deleteModal.style.display = "none";
    }
});




