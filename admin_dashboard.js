document.addEventListener('DOMContentLoaded', function () {
    loadAdminStats();
});

async function loadAdminStats() {
    const totalPortionsEl = document.getElementById('statTotalPortions');
    const topDonorEl = document.getElementById('statTopDonor');
    const topRatedListEl = document.getElementById('statTopRatedDishes');
    const adminListEl = document.getElementById('adminList');

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
            topDonorEl.textContent =`${data.top_donor.name} (@${data.top_donor.username}) : ${data.top_donor.total_portions} portions given.`;
        } else {
            topDonorEl.textContent = 'No portions have been picked up yet.';
        }

        // --- Top rated dishes ---
        topRatedListEl.innerHTML = ''; 
        
        if (data.top_rated_dishes && data.top_rated_dishes.length > 0) {
            data.top_rated_dishes.forEach(function (dish) {
                const list = document.createElement("li");
                const avg = Number(dish.avg_rating).toFixed(1);
                list.dataset.avg = avg;
                
                list.innerHTML += `
                <div >
                    <h3>${dish.title} by ${dish.cook_username}</h3>    
                    <img src="${dish.image_url}" class="stats-icon-image" alt="Dish Icon" style="width: 200px; height: 100px; object-fit: cover; border-radius: 8px;"R>
                    <hr>
                    <span>${avg}/5.0 Ratings:(${dish.rating_count})</span>
                </div>
                `;

                topRatedListEl.appendChild(list);
                
                
            });
        } else {
            const list = document.createElement('li');
            
            list.textContent = 'No dishes have been rated yet.';
            topRatedListEl.appendChild(list);
        }

        // --- Admins ---
        adminListEl.innerHTML = '';
        if (data.admins && data.admins.length > 0) {
            data.admins.forEach(function (admin) {
                const list = document.createElement("li");
                list.classList.add("admin-card");
              
                list.innerHTML += `
                <div class="contact-item">
                <img src="images/user-icon.png" class= contact-icon-image alt="Admin Avatar">
                </img> 
               <span>${admin.name} (@${admin.username})</span>
               </div>
                <div class="contact-item">

                      <img
                        src="images/mail_icon.png"
                        alt="Email"
                        class="contact-icon-image"
                        title="Click to send an email"
                        onclick="window.location.href='mailto:${admin.email}'"  
                        style="cursor: pointer;"
                       >

                        <span >
                            ${admin.email}
                        </span>
                </div>`;
                          
                
                adminListEl.appendChild(list);
            });
        } else {
            const list = document.createElement('li');
            list.textContent = 'No admins found.';
            adminListEl.appendChild(list);
        }

    } catch (error) {
        console.error('Error loading admin stats:', error);
        totalPortionsEl.textContent = 'Failed to load.';
        topDonorEl.textContent = 'Failed to load.';
        topRatedListEl.innerHTML = '<li>Failed to load.</li>';
        adminListEl.innerHTML = '<li>Failed to load.</li>';
    }
}

