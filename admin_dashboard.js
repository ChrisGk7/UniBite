document.addEventListener('DOMContentLoaded', function () {
    loadAdminStats();
});

async function loadAdminStats() {
    const totalPortionsEl = document.getElementById('statTotalPortions');
    const topDonorEl = document.getElementById('statTopDonor');
    const topRatedListEl = document.getElementById('statTopRatedDishes');

    try {
        const response = await fetch('admin_stats.php');
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Failed to load stats');
        }

        // --- Total portions ---
        totalPortionsEl.textContent = `${data.total_portions_last_month} portions`;

        // --- Top donor ---
        if (data.top_donor) {
            topDonorEl.textContent =
                `${data.top_donor.name} (@${data.top_donor.username}) — ${data.top_donor.total_portions} portions given`;
        } else {
            topDonorEl.textContent = 'No portions have been picked up yet.';
        }

        // --- Top rated dishes ---
        topRatedListEl.innerHTML = ''; 
        if (data.top_rated_dishes && data.top_rated_dishes.length > 0) {
            data.top_rated_dishes.forEach(function (dish) {
                const list = document.createElement("li");
                list.classList.add("listing-card");
                const avg = Number(dish.avg_rating).toFixed(1);
                list.dataset.avg = avg;
                list.textContent = `${dish.title} by ${dish.cook_name} — ${avg}/5 (${dish.rating_count} ratings)`;
                topRatedListEl.appendChild(list);
            });
        } else {
            const list = document.createElement('li');
            list.classList.add("listing-card");
            list.textContent = 'No dishes have been rated yet.';
            topRatedListEl.appendChild(list);
        }

    } catch (error) {
        console.error('Error loading admin stats:', error);
        totalPortionsEl.textContent = 'Failed to load.';
        topDonorEl.textContent = 'Failed to load.';
        topRatedListEl.innerHTML = '<li>Failed to load.</li>';
    }
}
