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

// Χαρτης Συνάρτηση για validation στο input
const pickupMap = initPickupMap('pickupMap', 'latitude', 'longitude');
let editMap = null; // lazily initialized the first time the edit modal opens

function validatePickupPoint() {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const errorEl = document.getElementById('pickupMapError');

    if (!latInput.value || !lngInput.value) {
        if (errorEl) errorEl.textContent = 'Please select a pickup point on the map.';
        return false;
    }
    return true;
}



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
            article.dataset.latitude = dish.latitude;
            article.dataset.longitude = dish.longitude;

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

  
    if (!listingForm.checkValidity()) {
        const firstInvalid = listingForm.querySelector(":invalid");
        if (firstInvalid) firstInvalid.focus();
        return;
    }

    if (!validatePickupPoint()) {
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
            //242 243 for hiding preview image
            const previewContainer = document.getElementById('imagePreviewContainer');
            if (previewContainer) previewContainer.style.display = 'none';
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

    document.getElementById('editLatitude').value = listingToEdit.dataset.latitude || '';
    document.getElementById('editLongitude').value = listingToEdit.dataset.longitude || '';

    editModal.style.display = "flex";

    // Leaflet can't size a map correctly while its container is display:none,
    // so we initialize it lazily (first open) and always fix its size /
    // re-center it after the modal becomes visible.
    if (!editMap) {
        editMap = initPickupMap('editPickupMap', 'editLatitude', 'editLongitude');
    }
    setTimeout(function () {
        if (editMap) {
            editMap.invalidateSize();
            const lat = parseFloat(listingToEdit.dataset.latitude);
            const lng = parseFloat(listingToEdit.dataset.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                editMap.setView([lat, lng], 15);
            }
        }
    }, 0);
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

    formData.append("latitude", document.getElementById('editLatitude').value);
    formData.append("longitude", document.getElementById('editLongitude').value);

    
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

async function loadCookRequests() {

    try {

        const response =
            await fetch("get_requests.php");

        const data =
            await response.json();

        console.log("REQUEST DATA:", data);

        if (!data.success) {

            console.error(data.message);
            return;
        }

        renderCookRequests(data.requests);

    } catch (error) {

        console.error(
            "Error loading requests:",
            error
        );

    }
}


function renderCookRequests(requests) {

    const requestsList =
        document.querySelector(".requests-list");

    requestsList.innerHTML = "";


    if (requests.length === 0) {

        requestsList.innerHTML = `
            <p class="no-requests-message">
                Δεν υπάρχουν αιτήματα αυτή τη στιγμή.
            </p>
        `;

        return;
    }


    requests.forEach(request => {

        const card =
            document.createElement("article");

        card.classList.add("request-card");


        card.innerHTML = `
            <div class="request-card-header">

                <div>
                    <h3>${request.title}</h3>

                    <p>
                        Από:
                        <strong>
                            ${request.stu_username}
                        </strong>
                    </p>
                </div>

                <span class="request-status ${request.status}">
                    ${request.status}
                </span>

            </div>


            <div class="request-card-info">

                <p>
                    <strong>Μερίδες:</strong>
                    ${request.portions}
                </p>

                <p>
                    <strong>Κόστος:</strong>
                    ${request.credit_cost} credits
                </p>

                <p>
                    <strong>Τοποθεσία:</strong>
                    ${request.pickup_location}
                </p>

                <p>
                    <strong>Ώρα Παραλαβής:</strong>
                    ${request.pickup_time}
                </p>

                <p>
                    <strong>Κατάσταση παραλαβής:</strong>
                    ${request.pickup_status || "-"}
                </p>

            </div>


            ${
                request.status === "pending"

                    ? `
                        <div class="request-actions">

                            <button
                                type="button"
                                class="approveRequestBtn"
                                data-request-id="${request.id}"
                            >
                                Αποδοχή
                            </button>

                            <button
                                type="button"
                                class="rejectRequestBtn"
                                data-request-id="${request.id}"
                            >
                                Απόρριψη
                            </button>

                        </div>
                    `

                    : request.status === "accepted" &&
                      request.pickup_status === "awaiting_pickup"

                    ? `
                        <div class="request-actions">

                            <button
                                type="button"
                                class="pickedUpBtn"
                                data-request-id="${request.id}"
                            >
                                Παραλήφθηκε
                            </button>

                            <button
                                type="button"
                                class="noShowBtn"
                                data-request-id="${request.id}"
                            >
                                Δεν παραλήφθηκε
                            </button>

                        </div>
                    `

                    : ""
            }
        `;


        requestsList.appendChild(card);

    });

}
    


async function respondToRequest(
    requestId,
    action
) {

    const formData =
        new FormData();

    formData.append(
        "request_id",
        requestId
    );

    formData.append(
        "action",
        action
    );


    try {

        const response =
            await fetch(
                "respond_request.php",
                {
                    method: "POST",
                    body: formData
                }
            );


        const data =
            await response.json();


        console.log(
            "REQUEST RESPONSE:",
            data
        );


        if (!data.success) {

            alert(data.message);

            return;
        }


        loadCookRequests();

        loadDishes();


    } catch (error) {

        console.error(
            "Respond request error:",
            error
        );

    }
}



const requestsList =
    document.querySelector(".requests-list");


if (requestsList) {

    requestsList.addEventListener(
        "click",
        function (event) {


            // -------------------------
            // ACCEPT REQUEST
            // -------------------------

            if (
                event.target.classList.contains(
                    "approveRequestBtn"
                )
            ) {

                const requestId =
                    event.target.dataset.requestId;

                respondToRequest(
                    requestId,
                    "accept"
                );
            }


            // -------------------------
            // REJECT REQUEST
            // -------------------------

            if (
                event.target.classList.contains(
                    "rejectRequestBtn"
                )
            ) {

                const requestId =
                    event.target.dataset.requestId;

                respondToRequest(
                    requestId,
                    "reject"
                );
            }


            // -------------------------
            // PICKED UP
            // -------------------------

            if (
                event.target.classList.contains(
                    "pickedUpBtn"
                )
            ) {

                const requestId =
                    event.target.dataset.requestId;

                updatePickupStatus(
                    requestId,
                    "picked_up"
                );
            }


            // -------------------------
            // NO SHOW
            // -------------------------

            if (
                event.target.classList.contains(
                    "noShowBtn"
                )
            ) {

                const requestId =
                    event.target.dataset.requestId;

                updatePickupStatus(
                    requestId,
                    "no_show"
                );
            }

        }
    );
}




async function updatePickupStatus(
    requestId,
    pickupAction
) {

    const formData =
        new FormData();

    formData.append(
        "request_id",
        requestId
    );

    formData.append(
        "pickup_action",
        pickupAction
    );


    try {

        const response =
            await fetch(
                "update_pickup.php",
                {
                    method: "POST",
                    body: formData
                }
            );


        const data =
            await response.json();


        console.log(
            "PICKUP RESPONSE:",
            data
        );


        if (!data.success) {

            alert(data.message);

            return;
        }


        loadCookRequests();

        loadDishes();


    } catch (error) {

        console.error(
            "Update pickup error:",
            error
        );

    }
}








loadDishes();



const requestsBtn =
    document.querySelector(".requestsBtn");

const requestsModal =
    document.querySelector(".requestsModal");

const closeRequestsBtn =
    document.querySelector(".closeRequestsBtn");


if (requestsBtn) {

    requestsBtn.addEventListener(
        "click",
        function () {

            requestsModal.style.display = "flex";

            loadCookRequests();
        }
    );
}


if (closeRequestsBtn) {

    closeRequestsBtn.addEventListener(
        "click",
        function () {

            requestsModal.style.display = "none";
        }
    );
}



