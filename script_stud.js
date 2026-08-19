// -------------------------
// Initialize page features
// -------------------------

const feedMap = initFeedMap("feed-map");

const searchInput = document.getElementById("search-input");
const searchButton = document.getElementById("search-button");

let allDishes = [];


// -------------------------
// Load dishes from database
// -------------------------

fetch("get_dishes_stud.php")
    .then(response => response.json())
    .then(dishes => {

        allDishes = dishes;

        // Render all dish cards
        renderDishes(allDishes);

        // Add map markers only once
        addDishMarkers(allDishes);

    })
    .catch(error => {
        console.error(
            "Error loading dishes:",
            error
        );
    });


// -------------------------
// Render dish cards
// -------------------------

function renderDishes(dishes) {

    const foodGrid = document.getElementById("food-grid");

    // Remove the old cards before rendering again
    foodGrid.innerHTML = "";

    // Copy array before sorting so allDishes itself is not changed
    const sortedDishes = [...dishes].sort((a, b) => {
        return Number(b.portions > 0) - Number(a.portions > 0);
    });


    sortedDishes.forEach(dish => {

        const card = document.createElement("div");

        card.classList.add("food-card");


        // Gray unavailable dishes immediately
        if (Number(dish.portions) === 0) {
            card.classList.add("unavailable");
        }


        // Format pickup time
        const pickupDate = dish.pickup_time
            ? new Date(
                dish.pickup_time.replace(" ", "T")
            ).toLocaleString()
            : "Not specified";


        // -------------------------
        // Dish HTML
        // -------------------------

        card.innerHTML = `
            <div class="dish-summary">

                <div class="food-card-image">

                    ${
                        dish.photos_url
                            ? `
                                <img
                                    src="${dish.photos_url}"
                                    alt="${dish.title}"
                                >
                              `
                            : `
                                <span class="food-placeholder">
                                    🍽️
                                </span>
                              `
                    }

                </div>


                <div class="food-card-content">

                    <div class="food-card-top">

                        <div>

                            <h3>
                                ${dish.title}
                            </h3>

                            <p class="availability">

                                ${
                                    Number(dish.portions) > 0
                                        ? `${dish.portions} plates left`
                                        : "Unavailable"
                                }

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <div class="dish-details">

                <p>
                    <strong>Description:</strong>
                    ${dish.description || "No description"}
                </p>

                <p>
                    <strong>Allergens:</strong>
                    ${dish.allergens || "None specified"}
                </p>

                <p>
                    <strong>Cook:</strong>
                    ${dish.cook}
                </p>

                <p>
                    <strong>Available portions:</strong>
                    ${dish.portions}
                </p>

                <p>
                    <strong>Credit cost:</strong>
                    ${dish.credit_cost}
                </p>

                <p>
                    <strong>Pickup location:</strong>
                    ${dish.pickup_location || "Not specified"}
                </p>

                <p>
                    <strong>Pickup time:</strong>
                    ${pickupDate}
                </p>


                ${
                    Number(dish.portions) > 0
                        ? `
                            <button class="request-button">
                                Request dish
                            </button>
                          `
                        : `
                            <button
                                class="request-button"
                                disabled
                            >
                                Unavailable
                            </button>
                          `
                }

            </div>
        `;


        // -------------------------
        // Request dish button
        // -------------------------

        const requestButton =
            card.querySelector(".request-button");


        if (
            requestButton &&
            Number(dish.portions) > 0
        ) {

            requestButton.addEventListener(
                "click",
                async function (event) {

                    // Don't expand/collapse card
                    // when request button is clicked
                    event.stopPropagation();


                    const formData = new FormData();

                    formData.append(
                        "dish_id",
                        dish.id
                    );


                    try {

                        const response =
                            await fetch(
                                "request_dish.php",
                                {
                                    method: "POST",
                                    body: formData
                                }
                            );


                        const data =
                            await response.json();


                        if (data.success) {

                            requestButton.textContent =
                                "Request sent";

                            requestButton.disabled = true;

                            console.log(data.message);

                        } else {

                            console.error(data.message);

                        }

                    } catch (error) {

                        console.error(
                            "Request dish error:",
                            error
                        );

                    }

                }
            );
        }


        // -------------------------
        // Expand / collapse card
        // -------------------------

        card.addEventListener(
            "click",
            function () {

                card.classList.toggle("expanded");

            }
        );


        // Add card to homepage
        foodGrid.appendChild(card);

    });

}


// -------------------------
// Add markers to map
// -------------------------

function addDishMarkers(dishes) {

    dishes.forEach(dish => {

        if (
            dish.latitude &&
            dish.longitude
        ) {

            const lat = Number(dish.latitude);
            const lng = Number(dish.longitude);


            const marker =
                L.marker([lat, lng])
                    .addTo(feedMap);


            marker.bindPopup(`
                <strong>${dish.title}</strong>
                <br>

                ${dish.portions} plates left
                <br>

                Cook: ${dish.cook}
            `);

        }

    });

}


// -------------------------
// Search dishes
// -------------------------

function normalizeText(text) {
    return String(text || "")
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();
}

const searchAliases = {
    pizza: "πιτσα",
    pasta: "μακαρονια",
    burger: "burger",
    burgers: "burger",
    sushi: "sushi",
    salad: "σαλατα",
    salads: "σαλατα",
    dessert: "γλυκο",
    desserts: "γλυκο"
};

function searchDishes() {

    let searchTerm = normalizeText(searchInput.value);

    if (searchAliases[searchTerm]) {
        searchTerm = searchAliases[searchTerm];
    }

    if (searchTerm === "") {
        renderDishes(allDishes);
        return;
    }

    const filteredDishes = allDishes.filter(dish => {

        const title = normalizeText(dish.title);
        const description = normalizeText(dish.description);
        const allergens = normalizeText(dish.allergens);
        const cook = normalizeText(dish.cook);
        const location = normalizeText(dish.pickup_location);

        return (
            title.includes(searchTerm) ||
            description.includes(searchTerm) ||
            allergens.includes(searchTerm) ||
            cook.includes(searchTerm) ||
            location.includes(searchTerm)
        );
    });

    renderDishes(filteredDishes);
}


// -------------------------
// Search button
// -------------------------

if (searchButton) {

    searchButton.addEventListener(
        "click",
        searchDishes
    );

}


// -------------------------
// Press Enter to search
// -------------------------

if (searchInput) {

    searchInput.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Enter") {

                searchDishes();

            }

        }
    );

}