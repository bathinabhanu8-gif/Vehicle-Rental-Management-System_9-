<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-shell" id="fleet">
    <div class="section-head">
        <div>
            <span class="eyebrow dark">OUR VERIFIED FLEET</span>
            <h1>Choose Your Next Ride</h1>
            <p class="muted">
                Browse our fleet sorted with <strong>Merge Sort</strong>, search by name with <strong>Binary Search</strong>, 
                and let our <strong>Greedy Matchmaker</strong> find the best vehicle for your budget.
            </p>
        </div>
        <div class="fleet-top-actions">
            <button class="btn accent-btn" onclick="openGreedyModal()">⚡ Greedy Matchmaker</button>
            <a class="btn secondary" href="<?php echo BASE_URL; ?>/algorithms.php">DAA Lab</a>
        </div>
    </div>

    <!-- Enhanced Filter and Sort Bar -->
    <div class="filter-bar advanced-filter-bar">
        <div class="search-field">
            <input id="search" placeholder="Search vehicle name (Binary Search)..." onkeyup="if(event.key==='Enter') loadVehicles()">
        </div>
        <select id="type" onchange="loadVehicles()">
            <option value="">All Types</option>
            <option value="Car">Cars Only</option>
            <option value="Bike">Bikes Only</option>
        </select>
        <select id="status" onchange="loadVehicles()">
            <option value="">All Status</option>
            <option value="Available" selected>Available Only</option>
            <option value="Booked">Booked</option>
            <option value="On Rent">On Rent</option>
            <option value="Maintenance">Maintenance</option>
        </select>
        <select id="sortOption" onchange="loadVehicles()">
            <option value="">Sort: Default</option>
            <option value="price_asc">Price: Low to High (Merge Sort)</option>
            <option value="price_desc">Price: High to Low (Merge Sort)</option>
            <option value="seats_desc">Seats: High to Low (Merge Sort)</option>
            <option value="name_asc">Name: A to Z (Merge Sort)</option>
        </select>
        <button class="btn" onclick="loadVehicles()">Filter Fleet</button>
    </div>

    <!-- Active Filter Chips Bar -->
    <div id="filterMetaBar" class="filter-meta-bar"></div>

    <!-- Vehicles Grid -->
    <div id="vehicles" class="vehicle-grid">
        <div class="empty-card">Loading vehicles...</div>
    </div>
</section>

<!-- Greedy Matchmaker Modal -->
<div id="greedyModal" class="modal">
    <div class="modal-card wide-modal">
        <button class="modal-close" onclick="closeGreedyModal()">×</button>
        <span class="eyebrow dark">DAA ALGORITHM 4 • GREEDY OPTIMIZATION</span>
        <h2>⚡ Intelligent Vehicle Matchmaker</h2>
        <p class="muted">Enter your constraints. The greedy algorithm calculates the vehicle that maximizes budget savings and seat utilization without backtracking.</p>

        <div class="form-grid-three">
            <div class="form-group">
                <label>Total Budget (₹)</label>
                <input type="number" id="mBudget" value="9000" min="500" step="500">
            </div>
            <div class="form-group">
                <label>Seats Needed</label>
                <input type="number" id="mSeats" value="5" min="1" max="10">
            </div>
            <div class="form-group">
                <label>Rental Days</label>
                <input type="number" id="mDays" value="3" min="1" max="30">
            </div>
            <div class="form-group">
                <label>Vehicle Type</label>
                <select id="mType">
                    <option value="Any">Any (Cars & Bikes)</option>
                    <option value="Car" selected>Car</option>
                    <option value="Bike">Bike</option>
                </select>
            </div>
            <div class="form-group">
                <label>Fuel Preference</label>
                <select id="mFuel">
                    <option value="Any" selected>Any Fuel</option>
                    <option value="Petrol">Petrol</option>
                    <option value="Diesel">Diesel</option>
                    <option value="EV">Electric (EV)</option>
                </select>
            </div>
        </div>
        <button class="btn wide" onclick="submitGreedyMatch()">Compute Best Match</button>
        <div id="greedyModalResult" class="modal-result-box"></div>
    </div>
</div>

<script>
let fleet = [];
const isLoggedIn = <?php echo $isCustomer ? 'true' : 'false'; ?>;

const fallback = v => v.type === 'Bike' 
    ? 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=80' 
    : 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80';

// Real-time rental price and interval overlap calculation
async function calc(id) {
    const v = fleet.find(x => x.id === id);
    if (!v) return;
    const s = document.getElementById('s-' + id).value;
    const e = document.getElementById('e-' + id).value;
    const out = document.getElementById('sum-' + id);

    if (!s || !e) {
        out.textContent = 'Select pickup and return dates to calculate total.';
        out.className = 'price-preview';
        return;
    }

    const a = new Date(s + 'T00:00:00');
    const b = new Date(e + 'T00:00:00');
    if (b < a) {
        out.innerHTML = '<span class="status-warning">⚠️ Return date must be on or after pickup date.</span>';
        return;
    }

    const days = Math.round((b - a) / 86400000) + 1;
    const total = days * v.pricePerDay;

    out.innerHTML = `<strong>${days} day${days > 1 ? 's' : ''} × ₹${Number(v.pricePerDay).toLocaleString('en-IN')} = ₹${Number(total).toLocaleString('en-IN')}</strong>`;

    // Perform real-time interval overlap check with backend
    try {
        const checkRes = await fetch(`api/algorithms/overlap.php?vehicleId=${id}&startDate=${s}&endDate=${e}`);
        const checkJson = await checkRes.json();
        if (checkJson.data?.hasConflict) {
            out.innerHTML += `<div class="status-warning" style="margin-top:6px;">⚠️ Dates overlap with another booking! Please choose alternate dates.</div>`;
        } else {
            out.innerHTML += `<div class="status-ok-note" style="margin-top:6px; color:#2f6d2a; font-size:11px;">✓ Dates available (Verified with DAA Interval Overlap)</div>`;
        }
    } catch (_) {}
}

async function loadVehicles() {
    const container = document.getElementById('vehicles');
    const q = document.getElementById('search').value.trim();
    const t = document.getElementById('type').value;
    const s = document.getElementById('status').value;
    const sort = document.getElementById('sortOption').value;

    const params = new URLSearchParams();
    if (q) params.append('search', q);
    if (t) params.append('type', t);
    if (s) params.append('status', s);
    if (sort) params.append('sort', sort);

    try {
        const res = await fetch(`api/vehicles/index.php?${params.toString()}`);
        const j = await res.json();
        fleet = j.data || [];

        // Update meta bar
        const metaBar = document.getElementById('filterMetaBar');
        metaBar.innerHTML = `
            <span>Showing <strong>${fleet.length}</strong> vehicles</span>
            ${sort ? `<span class="badge approved">Sorted: ${sort.replace('_', ' ').toUpperCase()}</span>` : ''}
            ${q ? `<span class="badge pending">Query: "${q}"</span>` : ''}
        `;

        if (!fleet.length) {
            container.innerHTML = '<div class="empty-card">No vehicles match your search criteria. Try clearing filters.</div>';
            return;
        }

        container.innerHTML = fleet.map(v => `
            <article class="vehicle-card" id="card-${v.id}">
                <div class="vehicle-photo" style="background-image:url('${v.image || fallback(v)}')">
                    <span class="status ${v.status === 'Available' ? 'ok' : 'off'}">${v.status}</span>
                    <span class="vehicle-type-badge">${v.type}</span>
                </div>
                <div class="vehicle-body">
                    <div class="vehicle-title">
                        <div>
                            <small>${v.registrationNumber || 'Verified'}</small>
                            <h3>${v.name}</h3>
                        </div>
                        <div class="price">
                            ₹${Number(v.pricePerDay).toLocaleString('en-IN')}
                            <small>/day</small>
                        </div>
                    </div>
                    <div class="spec-grid">
                        <span>👥 ${v.seats} seats</span>
                        <span>⛽ ${v.fuel}</span>
                        <span>⚙️ ${v.transmission}</span>
                    </div>
                    <p class="muted">${v.description || ''}</p>

                    ${v.status === 'Maintenance' 
                        ? '<button class="btn wide disabled" disabled>Under Maintenance</button>' 
                        : `
                            <div class="booking-row">
                                <label>Pickup Date
                                    <input type="date" id="s-${v.id}" min="<?php echo date('Y-m-d'); ?>" onchange="calc('${v.id}')">
                                </label>
                                <label>Return Date
                                    <input type="date" id="e-${v.id}" min="<?php echo date('Y-m-d'); ?>" onchange="calc('${v.id}')">
                                </label>
                            </div>
                            <div class="booking-extra">
                                <select id="pl-${v.id}">
                                    <option value="">Select Pickup Location</option>
                                    <option value="Kempegowda Int'l Airport Hub">Kempegowda Int'l Airport Hub</option>
                                    <option value="Central MG Road Depot">Central MG Road Depot</option>
                                    <option value="Indiranagar 100ft Metro Hub">Indiranagar 100ft Metro Hub</option>
                                    <option value="Koramangala 5th Block Depot">Koramangala 5th Block Depot</option>
                                    <option value="Whitefield ITPL Tech Corridor">Whitefield ITPL Tech Corridor</option>
                                    <option value="Electronic City Tollgate Hub">Electronic City Tollgate Hub</option>
                                </select>
                                <select id="rl-${v.id}">
                                    <option value="">Select Return Location</option>
                                    <option value="Kempegowda Int'l Airport Hub">Kempegowda Int'l Airport Hub</option>
                                    <option value="Central MG Road Depot">Central MG Road Depot</option>
                                    <option value="Indiranagar 100ft Metro Hub">Indiranagar 100ft Metro Hub</option>
                                    <option value="Koramangala 5th Block Depot">Koramangala 5th Block Depot</option>
                                    <option value="Whitefield ITPL Tech Corridor">Whitefield ITPL Tech Corridor</option>
                                    <option value="Electronic City Tollgate Hub">Electronic City Tollgate Hub</option>
                                </select>
                            </div>
                            <div class="price-preview" id="sum-${v.id}">Select dates to view total & interval validation</div>
                            <button class="btn wide" onclick="requestBooking('${v.id}')">
                                ${isLoggedIn ? 'Request Booking' : 'Sign In to Book'}
                            </button>
                        `
                    }
                </div>
            </article>
        `).join('');

        // Apply URL pre-fill if parameters are present
        applyUrlParams();
    } catch (e) {
        container.innerHTML = '<div class="empty-card">Unable to load fleet. Please check server.</div>';
    }
}

async function requestBooking(id) {
    if (!isLoggedIn) {
        window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
        return;
    }

    const startDate = document.getElementById('s-' + id).value;
    const endDate = document.getElementById('e-' + id).value;
    const pickupLocation = document.getElementById('pl-' + id).value.trim();
    const returnLocation = document.getElementById('rl-' + id).value.trim();

    if (!startDate || !endDate || !pickupLocation || !returnLocation) {
        toast('Please select dates and both pickup & return hubs.', 'error');
        return;
    }

    try {
        const res = await fetch('api/bookings/create.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            credentials: 'include',
            body: JSON.stringify({
                vehicleId: id,
                startDate,
                endDate,
                pickupLocation,
                returnLocation
            })
        });
        const j = await res.json();
        toast(j.message, j.success ? 'success' : 'error');
        if (j.success) {
            setTimeout(() => location.href = 'bookings.php', 900);
        }
    } catch (err) {
        toast('Booking request failed.', 'error');
    }
}

function applyUrlParams() {
    const url = new URL(window.location.href);
    const search = url.searchParams.get('search');
    const type = url.searchParams.get('type');
    const start = url.searchParams.get('start');
    const end = url.searchParams.get('end');

    if (search && document.getElementById('search').value === '') {
        document.getElementById('search').value = search;
    }
    if (type && document.getElementById('type').value === '') {
        document.getElementById('type').value = type;
    }

    // Pre-fill date inputs for visible cards
    fleet.forEach(v => {
        const sEl = document.getElementById('s-' + v.id);
        const eEl = document.getElementById('e-' + v.id);
        if (sEl && start && !sEl.value) sEl.value = start;
        if (eEl && end && !eEl.value) {
            eEl.value = end;
            calc(v.id);
        }
    });
}

// Greedy Matchmaker Modal Logic
function openGreedyModal() {
    document.getElementById('greedyModal').classList.add('show');
}

function closeGreedyModal() {
    document.getElementById('greedyModal').classList.remove('show');
}

async function submitGreedyMatch() {
    const b = document.getElementById('mBudget').value;
    const s = document.getElementById('mSeats').value;
    const d = document.getElementById('mDays').value;
    const t = document.getElementById('mType').value;
    const f = document.getElementById('mFuel').value;
    const out = document.getElementById('greedyModalResult');

    out.innerHTML = '<span class="muted">Running Greedy Allocation Algorithm...</span>';
    try {
        const res = await fetch(`api/algorithms/recommend.php?budget=${b}&seats=${s}&days=${d}&type=${t}&fuel=${f}`);
        const json = await res.json();
        const data = json.data;

        if (!data.foundMatch) {
            out.innerHTML = `<div class="status-warning" style="padding:15px; border-radius:10px;">No available vehicle satisfies all constraints within ₹${b}. Try increasing budget.</div>`;
            return;
        }

        const win = data.allocatedVehicle;
        const v = win.vehicle;

        out.innerHTML = `
            <div class="result-banner success-banner">
                <div>
                    <strong>🏆 Optimal Choice: ${v.name}</strong>
                    <p>Total for ${d} days: <strong>₹${Number(win.totalCost).toLocaleString('en-IN')}</strong> (Savings: ₹${Number(win.costSavings).toLocaleString('en-IN')})</p>
                    <small>Greedy Composite Score: ${win.greedyScore} • Seat Utilization: ${win.seatUtilization}</small>
                </div>
                <button class="btn" onclick="selectGreedyVehicle('${v.id}')">Select & Focus</button>
            </div>
        `;
    } catch (e) {
        out.innerHTML = '<div class="message error">Failed to run matchmaker.</div>';
    }
}

function selectGreedyVehicle(id) {
    closeGreedyModal();
    const card = document.getElementById('card-' + id);
    if (card) {
        card.scrollIntoView({behavior: 'smooth', block: 'center'});
        card.style.border = '3px solid var(--dark)';
        setTimeout(() => card.style.border = '', 3000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Check if initial search param was in URL
    const url = new URL(window.location.href);
    if (url.searchParams.get('search')) {
        document.getElementById('search').value = url.searchParams.get('search');
    }
    if (url.searchParams.get('type')) {
        document.getElementById('type').value = url.searchParams.get('type');
    }
    loadVehicles();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
