fetch("get_dishes.php")
    .then(response => response.json())
    .then(dishes => {

        const foodGrid = document.getElementById("food-grid");

        dishes.forEach(dish => {

            const card = document.createElement("div");

            card.classList.add("food-card");

            card.innerHTML = `
                <div class="food-card-image">
                    🍕
                </div>

                <div class="food-card-content">

                    <div class="food-card-top">

                        <div>
                            <h3>${dish.title}</h3>

                            <p>
                                ${dish.portions} plates left
                            </p>
                        </div>

                        <span class="rating">
                            ★ 4.8
                        </span>

                    </div>

                </div>
            `;

            foodGrid.appendChild(card);

        });

    })
    .catch(error => {
        console.error("Error loading dishes:", error);
    });