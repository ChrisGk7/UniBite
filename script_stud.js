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

    loadMyOrders();

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
// Dish expiration
// -------------------------

function getDishStatus(dish) {

    // If reg_date is missing, do not expire the dish
    if (!dish.reg_date) {
        return Number(dish.portions) > 0
            ? "available"
            : "unavailable";
    }

    const createdAt =
        new Date(
            dish.reg_date.replace(" ", "T")
        );

    const now =
        new Date();

    const ageMs =
        now - createdAt;

    const fortyEightHoursMs =
        48 * 60 * 60 * 1000;


    if (ageMs >= fortyEightHoursMs) {
        return "deleted";
    }


    if (Number(dish.portions) <= 0) {
        return "unavailable";
    }


    return "available";
}

// -------------------------
// Load dishes from database
// -------------------------

fetch("get_dishes_stud.php")
    .then(response => response.json())
    .then(dishes => {

        // Keep only dishes that have not expired
        allDishes = dishes.filter(dish => {

            return getDishStatus(dish) !== "deleted";

        });

        // Show active / unavailable dishes
        renderDishes(allDishes);

        // Only non-expired dishes get map markers
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
                    dish.cook === currentUsername
                        ? `
                            <div class="dish-request-row">
                
                                <span class="own-dish-pill">
                                    Your dish
                                </span>
                
                            </div>
                          `
                
                        : Number(dish.portions) > 0
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


document.addEventListener(
    "click",
    async function (event) {

        const pickupButton =
            event.target.closest(
                ".student-pickup-button"
            );

        if (!pickupButton) {
            return;
        }


        const orderCard =
            pickupButton.closest(
                ".student-order-card"
            );

        if (!orderCard) {
            return;
        }


        const requestId =
            orderCard.dataset.requestId;


        const formData =
            new FormData();

        formData.append(
            "request_id",
            requestId
        );


        try {

            const response =
                await fetch(
                    "student_pickup.php",
                    {
                        method: "POST",
                        body: formData
                    }
                );

            const data =
                await response.json();


            if (!data.success) {

                alert(data.message);

                return;
            }


            loadMyOrders();


        } catch (error) {

            console.error(
                "Pickup error:",
                error
            );

        }

    }
);


// -------------------------
// Check rating penalties
// -------------------------

async function checkRatingPenalties() {

    try {

        const response =
            await fetch(
                "check_rating_penalties.php"
            );

        const data =
            await response.json();


        if (!data.success) {

            console.error(
                "Rating penalty error:",
                data.message
            );

            return;
        }


        if (data.penalties > 0) {

            console.log(
                `${data.penalties} rating penalty applied.`
            );

        }


    } catch (error) {

        console.error(
            "Rating penalty error:",
            error
        );

    }
}


async function loadMyOrders() {

    const ordersContainer =
        document.getElementById("orders-container");

    if (!ordersContainer) {
        return;
    }

    try {

        const response =
            await fetch("get_my_orders.php");

        const data =
            await response.json();


        if (!data.success) {

            ordersContainer.innerHTML = `
                <div class="orders-empty-state">
                    <p>${data.message}</p>
                </div>
            `;

            return;
        }


        if (data.orders.length === 0) {

            ordersContainer.innerHTML = `
                <div class="orders-empty-state">
                    <p>No accepted orders yet.</p>
                </div>
            `;

            return;
        }


        ordersContainer.innerHTML = "";


        data.orders.forEach(order => {

            const orderCard =
                document.createElement("div");

            orderCard.classList.add(
                "student-order-card"
            );

            orderCard.dataset.requestId =
                order.request_id;


            const pickupDate =
                order.pickup_time
                    ? new Date(
                        order.pickup_time.replace(" ", "T")
                      ).toLocaleString()
                    : "Not specified";


            const pickedUp =
                order.pickup_status === "picked_up";


            const alreadyRated =
            order.rating !== null;


            orderCard.innerHTML = `

                <div class="student-order-header">

                    <div>

                        <h3>
                            ${order.title}
                        </h3>

                        <p>
                            Cook:
                            <strong>
                                ${order.cook}
                            </strong>
                        </p>

                    </div>


                    <span
                        class="
                            student-order-status
                            ${pickedUp ? "picked-up" : "accepted"}
                        "
                    >
                        ${pickedUp ? "Picked up" : "Accepted"}
                    </span>

                </div>


                <div class="student-order-info">

                    <p>
                        <strong>Portions:</strong>
                        ${order.portions}
                    </p>

                    <p>
                        <strong>Cost:</strong>
                        ${order.credit_cost} credits
                    </p>

                    <p>
                        <strong>Location:</strong>
                        ${order.pickup_location}
                    </p>

                    <p>
                        <strong>Pickup time:</strong>
                        ${pickupDate}
                    </p>

                </div>


                ${
                    !pickedUp
                        ? `
                            <div class="student-order-actions">

                                <button
                                    type="button"
                                    class="student-pickup-button"
                                >
                                    Pick up
                                </button>

                            </div>
                          `
                        : ""
                }


                <div class="
    student-rating-section
    ${pickedUp ? "show-rating" : ""}
">

    <div class="rating-divider"></div>

    ${
        alreadyRated
            ? `
                <div class="rating-completed">

                    <h4>
                        Your rating
                    </h4>

                    <div class="star-rating completed">

                        ${[1, 2, 3, 4, 5]
                            .map(star =>
                                star <= Number(order.rating)
                                    ? "★"
                                    : "☆"
                            )
                            .join("")}

                    </div>

                    <p class="rating-thank-you">
                        Rating submitted
                    </p>

                </div>
              `
            : `
                <h4>
                    How was your meal?
                </h4>

                <p class="rating-description">
                    Rate your experience with this dish.
                </p>

                <div class="star-rating">

                    <button type="button" data-rating="1">☆</button>
                    <button type="button" data-rating="2">☆</button>
                    <button type="button" data-rating="3">☆</button>
                    <button type="button" data-rating="4">☆</button>
                    <button type="button" data-rating="5">☆</button>

                </div>

                <button
                    type="button"
                    class="submit-rating-button"
                >
                    Submit Rating
                </button>
              `
    }

</div>
            `;


            ordersContainer.appendChild(
                orderCard
            );

        });

    } catch (error) {

        console.error(
            "My Orders error:",
            error
        );

    }
}


document.addEventListener("click", function (event) {

    const star =
        event.target.closest(".star-rating button");

    if (!star) {
        return;
    }

    const starContainer =
        star.closest(".star-rating");

    const orderCard =
        star.closest(".student-order-card");

    if (!starContainer || !orderCard) {
        return;
    }

    const selectedRating =
        Number(star.dataset.rating);

    orderCard.dataset.rating =
        selectedRating;

    const stars =
        starContainer.querySelectorAll("button");

    stars.forEach(button => {

        const value =
            Number(button.dataset.rating);

        button.textContent =
            value <= selectedRating
                ? "★"
                : "☆";
    });

});


document.addEventListener(
    "click",
    async function (event) {

        const submitButton =
            event.target.closest(
                ".submit-rating-button"
            );

        if (!submitButton) {
            return;
        }


        const orderCard =
            submitButton.closest(
                ".student-order-card"
            );

        if (!orderCard) {
            return;
        }


        const requestId =
            orderCard.dataset.requestId;

        const rating =
            Number(orderCard.dataset.rating);


        if (!rating) {

            alert(
                "Please select a rating first."
            );

            return;
        }


        const formData =
            new FormData();

        formData.append(
            "request_id",
            requestId
        );

        formData.append(
            "rating",
            rating
        );


        try {

            const response =
                await fetch(
                    "rate_order.php",
                    {
                        method: "POST",
                        body: formData
                    }
                );


            const data =
                await response.json();


            if (!data.success) {

                alert(data.message);

                return;
            }


            submitButton.textContent =
                "Rating submitted";

            submitButton.disabled =
                true;


            const stars =
                orderCard.querySelectorAll(
                    ".star-rating button"
                );

            stars.forEach(star => {
                star.disabled = true;
            });


        } catch (error) {

            console.error(
                "Rating error:",
                error
            );

        }

    }
);

checkRatingPenalties();