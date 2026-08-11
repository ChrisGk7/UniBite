const listingForm = document.getElementById("listingForm");
const listingContainer = document.getElementById("listingsContainer");
const titleInput = document.getElementById("title");
const imageInput = document.getElementById("image");
const notesInput = document.getElementById("notes");
const allergernsInput = document.getElementById("allergerns");
const editPortions = document.getElementById("editPortions");
const editPickupLocation = document.getElementById("editPickupLocation");
const editPickupTime = document.getElementById("editPickupTime");


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


async function loadDishes() {

    try {

        const response = await fetch("get_dishes.php");

        const data = await response.json();

        if (!data.success) {
            console.error("Could not load dishes");
            return;
        }

        listingContainer.innerHTML = "";

        data.dishes.forEach(function(dish) {

            const article = document.createElement("article");
            article.classList.add("listing-card");

            article.dataset.id = dish.id;
            article.dataset.portions = dish.portions;
            article.dataset.pickupLocation = dish.pickup_location;
            article.dataset.pickupTime = dish.pickup_time;

            const image = document.createElement("img");

            if (dish.photos_url) {
                image.src = dish.photos_url;
                image.classList.add("listing-image");
                article.appendChild(image);
            }

            const h3 = document.createElement("h3");
            h3.textContent = dish.title;

            const descriptionParagraph = document.createElement("p");
            descriptionParagraph.textContent = dish.description;

            const allergensParagraph = document.createElement("p");
            allergensParagraph.textContent =
                "Αλλεργιογόνα: " + dish.allergens;

            const portionsParagraph = document.createElement("p");
            portionsParagraph.textContent =
                "Μερίδες: " + dish.portions;

            const pickupLocationParagraph = document.createElement("p");
            pickupLocationParagraph.textContent =
                "Τοποθεσία Παραλαβής: " + dish.pickup_location;

            const pickupTimeParagraph = document.createElement("p");
            pickupTimeParagraph.textContent =
                "Ώρα Παραλαβής: " + dish.pickup_time;

            const editButton = document.createElement("button");
            editButton.textContent = "Επεξεργασία";
            editButton.classList.add("editBtn");

            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Διαγραφή";
            deleteButton.classList.add("deleteBtn");

            article.appendChild(h3);
            article.appendChild(descriptionParagraph);
            article.appendChild(allergensParagraph);
            article.appendChild(portionsParagraph);
            article.appendChild(pickupLocationParagraph);
            article.appendChild(pickupTimeParagraph);
            article.appendChild(editButton);
            article.appendChild(deleteButton);

            listingContainer.appendChild(article);

        }); 

    }
    catch(error) {

        console.error("Load dishes error:", error);

    }
}

//Δημιουργία Αγγελίας

listingForm.addEventListener("submit", async function(event) {

    event.preventDefault();

    if (!validateForm()) {
        return;
    }

    const formData = new FormData(listingForm);

    try {

        const response = await fetch("create_dish.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        console.log(data);

        if (data.success) {

            listingForm.reset();

            loadDishes();
        }

    }
    catch(error) {

        console.error("Create dish error:", error);

    }

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

    editPortions.value = listingToEdit.dataset.portions;

    editPickupLocation.value = listingToEdit.dataset.pickupLocation;


    editPickupTime.value =  listingToEdit.dataset.pickupTime .replace(" ", "T") .slice(0, 16);

    editModal.style.display = "flex";
 }

    //ΚΟΥΜΠΙ ΔΙΑΓΡΑΦΗΣ
if(event.target.classList.contains("deleteBtn")){

    listingToDelete= event.target.closest(".listing-card");

    deleteModal.style.display="flex";

}
}
);

 

//canceledit
cancelEdit.addEventListener("click", function(){

    editModal.style.display="none";
    listingToEdit=null;

})

//SaveEdit







editForm.addEventListener("submit", async function(event) {

    event.preventDefault();

    if (!listingToEdit) {
        return;
    }

    const dishId = listingToEdit.dataset.id;

    const formData = new FormData();

    formData.append("dish_id", dishId);
    formData.append("title", editTitle.value.trim());
    formData.append("description", editNotes.value.trim());
    formData.append("allergens", editAllegerns.value.trim());


    formData.append("portions",editPortions.value);

    formData.append("pickup_location",editPickupLocation.value.trim());

    formData.append("pickup_time",editPickupTime.value);

    
    if (editImage.files[0]) {
        formData.append("image", editImage.files[0]);
    }

    try {

        const response = await fetch("update_dish.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        console.log("UPDATE:", data);

        if (data.success) {

            editModal.style.display = "none";

            listingToEdit = null;

            editForm.reset();

            loadDishes();
        }

    }
    catch(error) {

        console.error("Update dish error:", error);

    }
    
});






//canceldel
cancelDelete.addEventListener("click", function(){
    deleteModal.style.display = "none";

    listingToDelete = null;

});

//confirmdel
// confirm delete
confirmDelete.addEventListener("click", async function() {

    const dishId = listingToDelete.dataset.id;

   

    try {

        const response = await fetch("del_dish.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                dish_id: dishId
            })
        });


        const data = await response.json();

    

        if (data.success) {

            listingToDelete.remove();
            listingToDelete = null;
            deleteModal.style.display = "none";
        }

    }
    catch(error) {

        console.error("Delete dish error:", error);

    }

});

loadDishes();


