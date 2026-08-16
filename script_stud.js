fetch("get_dishes.php")
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

            // Expand / collapse the card
            card.addEventListener("click", function () {
                card.classList.toggle("expanded");
                if (Number(dish.portions) === 0) {
                    card.classList.add("unavailable");
                }
            });

            foodGrid.appendChild(card);

        });

        const feedMap = initFeedMap("feed-map");

    })
    
    .catch(error => {
        console.error("Error loading dishes:", error);
    });