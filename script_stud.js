// -------------------------
// Initialize page features
// -------------------------

const feedMap = initFeedMap("feed-map");

const searchInput = document.getElementById("search-input");
const searchButton = document.getElementById("search-button");

let allDishes = [];

let nearestMode = false;



// -------------------------
// My Orders drawer
// -------------------------

const myOrdersButton =
    document.getElementById("my-orders-button");

const ordersDrawer =
    document.getElementById("orders-drawer");

const ordersOverlay =
    document.getElementById("orders-overlay");

const closeOrdersButton =
    document.getElementById("close-orders");


function openOrdersDrawer() {

    ordersDrawer.classList.add("open");
    ordersOverlay.classList.add("open");

}


function closeOrdersDrawer() {

    ordersDrawer.classList.remove("open");
    ordersOverlay.classList.remove("open");

}


if (myOrdersButton) {

    myOrdersButton.addEventListener(
        "click",
        openOrdersDrawer
    );

}


if (closeOrdersButton) {

    closeOrdersButton.addEventListener(
        "click",
        closeOrdersDrawer
    );

}


if (ordersOverlay) {

    ordersOverlay.addEventListener(
        "click",
        closeOrdersDrawer
    );

}

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
                    ${dish.credits_per_portion}
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
                            <div class="dish-request-row">
                
                                <div class="portion-controls">
                
                                    <button
                                        type="button"
                                        class="portion-minus"
                                    >
                                        −
                                    </button>
                
                                    <span class="portion-value">
                                        1
                                    </span>
                
                                    <button
                                        type="button"
                                        class="portion-plus"
                                    >
                                        +
                                    </button>
                
                                </div>
                
                                <button class="request-button">
                                    Request dish
                                </button>
                
                            </div>
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

            const minusButton =
            card.querySelector(".portion-minus");
        
        const plusButton =
            card.querySelector(".portion-plus");
        
        const portionValue =
            card.querySelector(".portion-value");
        
        // One variable for THIS dish card
        let selectedPortions = 1;

        
        // -------------------------
        // Portion selector
        // -------------------------
        if (minusButton && plusButton && portionValue) {
        
            minusButton.addEventListener("click", function (event) {
        
                event.stopPropagation();
        
                if (selectedPortions > 1) {
        
                    selectedPortions--;
        
                    portionValue.textContent =
                        selectedPortions;
                }
        
            });
        
        
            plusButton.addEventListener("click", function (event) {
        
                event.stopPropagation();
        
                if (
                    selectedPortions <
                    Number(dish.portions)
                ) {
        
                    selectedPortions++;
        
                    portionValue.textContent =
                        selectedPortions;
                }
        
            });
        
        }


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

                    formData.append(
                        "portions",
                        selectedPortions
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

const nearMeButton = document.getElementById("near-me-button");

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
// Filter Dishes
// -------------------------
if (nearMeButton) {

    nearMeButton.addEventListener("click", function () {

        // If nearest mode is already active,
        // return to the normal dish order
        if (nearestMode) {

            nearestMode = false;

            // Restore normal dish order 
            renderDishes(allDishes);

            // Return map to its default position
            resetFeedMap(feedMap);

            // Restore button 
            nearMeButton.textContent = "◎ Nearest first";

            return;
        }


        // Otherwise activate nearest mode
        centerMapOnCurrentLocation(
            feedMap,

            function (userLat, userLng) {

                const nearbyDishes = allDishes
                    .filter(dish => {
                        return (
                            Number(dish.portions) > 0 &&
                            dish.latitude &&
                            dish.longitude
                        );
                    })
                    .map(dish => {

                        const distance = haversineDistanceKm(
                            userLat,
                            userLng,
                            Number(dish.latitude),
                            Number(dish.longitude)
                        );

                        return {
                            ...dish,
                            distance: distance
                        };

                    })
                    .sort((a, b) => {
                        return a.distance - b.distance;
                    });


                nearestMode = true;

                renderDishes(nearbyDishes);

                nearMeButton.textContent = "✕ Clear location sort";

            }
        );

    });

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