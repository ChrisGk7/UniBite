//Initialize map independently
const feedMap = initFeedMap("feed-map");

// Load dishes
fetch("get_dishes_stud.php")
    .then(response => response.json())
    .then(dishes => {

        const foodGrid = document.getElementById("food-grid");

        dishes.sort((a, b) => {
            return Number(b.portions > 0) - Number(a.portions > 0);
        });

        dishes.forEach(dish => {

            const card = document.createElement("div");

            card.classList.add("food-card");

            card.innerHTML = `
                <div class="dish-summary">

                    <div class="food-card-image">
                        🍕
                    </div>

                    <div class="food-card-content">

                        <div class="food-card-top">

                            <div>
                                <h3>${dish.title}</h3>

                                <p class="availability">
                                    ${
                                    Number(dish.portions) > 0
                                        ? `${dish.portions} plates left`
                                        : "Unavailable"
                                    }
                                </p>
                            </div>

                            <span class="rating">
                                ★ 4.8
                            </span>

                        </div>

                    </div>

                </div>


                <div class="dish-details">

                    <p>
                        <strong>Description:</strong>
                        ${dish.description}
                    </p>

                    <p>
                        <strong>Cook:</strong>
                        ${dish.cook}
                    </p>

                    <p>
                        <strong>Credit cost:</strong>
                        ${dish.credit_cost}
                    </p>

                    ${
                        Number(dish.portions) > 0
                            ? `<button class="request-button">Request dish</button>`
                            : `<button class="request-button" disabled>Unavailable</button>`
                    }

                </div>
            `;

            const requestButton = card.querySelector(".request-button");

if (
    requestButton &&
    Number(dish.portions) > 0
) {

    requestButton.addEventListener("click", async function (event) {

        event.stopPropagation();

        const formData = new FormData();

        formData.append("dish_id", dish.id);

        try {

            const response = await fetch("request_dish.php", {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (data.success) {

                requestButton.textContent = "Request sent";
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

    });

}

            // Expand / collapse the card
            card.addEventListener("click", function () {
                card.classList.toggle("expanded");
                if (Number(dish.portions) === 0) {
                    card.classList.add("unavailable");
                }
            });

            foodGrid.appendChild(card);

        });


    })
    
    .catch(error => {
        console.error("Error loading dishes:", error);
    });