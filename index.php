<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-premium">
    <div class="hero-overlay"></div>
    <div class="hero-inner">
        <div class="hero-content">
            <span class="hero-tag">☁️ CLOUD-HOSTED • DAA ALGORITHM OPTIMIZED</span>
            <h1>Drive Your Next Journey With Intelligent Efficiency.</h1>
            <p>
                Experience seamless car and bike rentals powered by <strong>Server-Side Scripting</strong>, 
                <strong>Cloud MongoDB Atlas</strong>, and <strong>DAA Optimization Algorithms</strong> including 
                Merge Sort, Binary Search, Interval Overlap Collision Prevention, and Dijkstra Route Optimization.
            </p>
            <div class="hero-buttons">
                <a class="btn hero-cta" href="<?php echo BASE_URL; ?>/vehicles.php">Explore Fleet</a>
                <a class="btn hero-secondary" href="<?php echo BASE_URL; ?>/algorithms.php">⚡ Live Algorithm Lab</a>
                <?php if (!$isCustomer): ?>
                    <a class="btn hero-outline" href="<?php echo BASE_URL; ?>/login.php">Customer Login</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Booking Search Widget -->
        <div class="hero-search-card">
            <div class="search-card-header">
                <h3>Quick Vehicle Search</h3>
                <span class="badge ok">Instant Availability</span>
            </div>
            <form id="heroSearchForm" class="search-form-grid" onsubmit="handleHeroSearch(event)">
                <div class="form-group">
                    <label>Vehicle Type</label>
                    <select id="heroType">
                        <option value="">All Vehicles (Cars & Bikes)</option>
                        <option value="Car">Cars Only</option>
                        <option value="Bike">Bikes Only</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pickup Date</label>
                    <input type="date" id="heroStart" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                <div class="form-group">
                    <label>Return Date</label>
                    <input type="date" id="heroEnd" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d', strtotime('+4 days')); ?>">
                </div>
                <div class="form-group">
                    <label>Pickup Location Hub</label>
                    <select id="heroLocation">
                        <option value="BLR_AIRPORT">Kempegowda Int'l Airport Hub</option>
                        <option value="MG_ROAD" selected>Central MG Road Depot</option>
                        <option value="INDIRANAGAR">Indiranagar 100ft Metro Hub</option>
                        <option value="KORAMANGALA">Koramangala 5th Block Depot</option>
                        <option value="WHITEFIELD">Whitefield ITPL Tech Corridor</option>
                        <option value="ELECTRONIC_CITY">Electronic City Tollgate Hub</option>
                    </select>
                </div>
                <button type="submit" class="btn wide search-submit">
                    🔍 Find Available Rides
                </button>
            </form>
            <div class="search-helper">
                <span>✓ Interval collision checked</span>
                <span>✓ Zero double bookings</span>
            </div>
        </div>
    </div>
</section>

<!-- Academic Subjects Triple Showcase Ribbon -->
<section class="section academic-ribbon">
    <div class="ribbon-card">
        <div class="ribbon-icon">💻</div>
        <span class="ribbon-label">Subject 1</span>
        <h3>Server-Side Scripting</h3>
        <p>PHP 8.2 backend with isolated customer & admin sessions, BCrypt password hashing, prepared statement safety, RESTful JSON APIs, and role-based access control.</p>
        <span class="ribbon-stat">18+ REST Endpoints</span>
    </div>

    <div class="ribbon-card featured">
        <div class="ribbon-icon">☁️</div>
        <span class="ribbon-label">Subject 2</span>
        <h3>Cloud Computing</h3>
        <p>Managed Cloud MongoDB Atlas database with automated failover to a resilient local data store, multi-stage Docker containerization, and Render cloud hosting configuration.</p>
        <span class="ribbon-stat">Atlas Cloud + Docker</span>
    </div>

    <div class="ribbon-card">
        <div class="ribbon-icon">⚡</div>
        <span class="ribbon-label">Subject 3</span>
        <h3>Design & Analysis of Algorithms</h3>
        <p>5 working academic algorithms: Binary Search O(log n), Merge Sort O(n log n), Interval Overlap Collision O(k), Greedy Vehicle Matchmaker, and Dijkstra's Shortest Path O((V+E)log V).</p>
        <span class="ribbon-stat">5 Pure DAA Engines</span>
    </div>
</section>

<!-- Featured Fleet Section -->
<section class="section fleet-preview-section">
    <div class="section-head">
        <div>
            <span class="eyebrow dark">VERIFIED FLEET</span>
            <h2>Top Recommended Vehicles</h2>
            <p>Ranked in real-time using Merge Sort with guaranteed O(n log n) efficiency.</p>
        </div>
        <div class="fleet-actions-bar">
            <div class="sort-pills">
                <button class="sort-btn active" onclick="loadFeaturedFleet('price_asc', this)">Price: Low to High</button>
                <button class="sort-btn" onclick="loadFeaturedFleet('price_desc', this)">Price: High to Low</button>
                <button class="sort-btn" onclick="loadFeaturedFleet('seats_desc', this)">Most Seats</button>
            </div>
            <a class="btn secondary" href="<?php echo BASE_URL; ?>/vehicles.php">View All Fleet →</a>
        </div>
    </div>

    <div id="featuredGrid" class="vehicle-grid">
        <div class="empty-card">Loading fleet using Merge Sort...</div>
    </div>
</section>

<!-- Interactive DAA Algorithm Teaser Section -->
<section class="section daa-teaser-section">
    <div class="daa-banner">
        <div class="daa-banner-text">
            <span class="eyebrow">ACADEMIC ALGORITHM SPOTLIGHT</span>
            <h2>Test The Algorithms In Action</h2>
            <p>
                Experience our real-time <strong>DAA Algorithm Lab</strong>. Run interactive test cases for Binary Search, 
                visualize recursion trees in Merge Sort, prevent booking overlaps using interval scheduling mathematics, 
                and optimize delivery routes across city hubs with Dijkstra's algorithm.
            </p>
            <div class="daa-tags-row">
                <span>✓ Binary Search O(log n)</span>
                <span>✓ Merge Sort O(n log n)</span>
                <span>✓ Interval Overlap O(k)</span>
                <span>✓ Greedy Matchmaker</span>
                <span>✓ Dijkstra Route O((V+E)log V)</span>
            </div>
            <a class="btn light" href="<?php echo BASE_URL; ?>/algorithms.php">Open DAA Algorithm Lab →</a>
        </div>

        <div class="mini-dijkstra-card">
            <h3>Quick Route Distance Calculator</h3>
            <p class="muted">Powered by Dijkstra's Shortest Path Algorithm</p>
            <div class="form-group">
                <label>Pickup Location</label>
                <select id="quickDijkstraSrc">
                    <option value="BLR_AIRPORT">BLR International Airport</option>
                    <option value="HEBBAL">Hebbal North Interchange</option>
                    <option value="MG_ROAD" selected>MG Road Central CBD</option>
                    <option value="INDIRANAGAR">Indiranagar Metro Hub</option>
                    <option value="WHITEFIELD">Whitefield Tech Corridor</option>
                </select>
            </div>
            <div class="form-group">
                <label>Dropoff Location</label>
                <select id="quickDijkstraDst">
                    <option value="BLR_AIRPORT" selected>BLR International Airport</option>
                    <option value="ELECTRONIC_CITY">Electronic City Hub</option>
                    <option value="WHITEFIELD">Whitefield Tech Corridor</option>
                    <option value="KORAMANGALA">Koramangala 5th Block</option>
                    <option value="JAYANAGAR">Jayanagar 4th Block</option>
                </select>
            </div>
            <button class="btn wide" onclick="runQuickDijkstra()">Compute Shortest Route</button>
            <div id="quickDijkstraResult" class="quick-route-result"></div>
        </div>
    </div>
</section>

<!-- How It Works (Rental Lifecycle) -->
<section class="section process-section">
    <div class="intro">
        <span class="eyebrow dark">HOW IT WORKS</span>
        <h2>4 Steps To Hit The Road</h2>
        <p class="muted">Strict booking lifecycle designed to prevent conflicts and ensure verified rentals.</p>
    </div>

    <div class="steps-grid">
        <div class="step-card">
            <div class="step-number">1</div>
            <h3>Select Vehicle</h3>
            <p>Browse verified cars and bikes with transparent daily rates, fuel types, and passenger capacity.</p>
        </div>
        <div class="step-card">
            <div class="step-number">2</div>
            <h3>Interval Validation</h3>
            <p>Our server-side algorithm checks for overlapping reservations to guarantee vehicle availability.</p>
        </div>
        <div class="step-card">
            <div class="step-number">3</div>
            <h3>Admin Approval</h3>
            <p>Administrator reviews customer credentials and route details before approving the reservation.</p>
        </div>
        <div class="step-card">
            <div class="step-number">4</div>
            <h3>Demo Pay & Pickup</h3>
            <p>Complete simulation UPI/Card payment to confirm booking and pick up your ride at the scheduled hub.</p>
        </div>
    </div>
</section>

<script>
async function loadFeaturedFleet(sortOption = 'price_asc', btn = null) {
    if (btn) {
        document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
    const container = document.getElementById('featuredGrid');
    try {
        const res = await fetch(`api/vehicles/index.php?sort=${encodeURIComponent(sortOption)}`);
        const json = await res.json();
        const list = json.data || [];
        if (!list.length) {
            container.innerHTML = '<div class="empty-card">No vehicles found.</div>';
            return;
        }

        const previewList = list.slice(0, 6);
        container.innerHTML = previewList.map(v => `
            <article class="vehicle-card">
                <div class="vehicle-photo" style="background-image:url('${v.image || 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80'}')">
                    <span class="status ${v.status === 'Available' ? 'ok' : 'off'}">${v.status}</span>
                    <span class="vehicle-type-badge">${v.type}</span>
                </div>
                <div class="vehicle-body">
                    <div class="vehicle-title">
                        <div>
                            <small>${v.registrationNumber || 'Verified Fleet'}</small>
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
                    <p class="muted vehicle-desc-preview">${v.description || ''}</p>
                    <div class="card-bottom-actions">
                        <a href="vehicles.php?search=${encodeURIComponent(v.name)}" class="btn wide">
                            Book This Vehicle →
                        </a>
                    </div>
                </div>
            </article>
        `).join('');
    } catch (err) {
        container.innerHTML = '<div class="empty-card">Unable to load fleet preview.</div>';
    }
}

function handleHeroSearch(e) {
    e.preventDefault();
    const type = document.getElementById('heroType').value;
    const start = document.getElementById('heroStart').value;
    const end = document.getElementById('heroEnd').value;
    const loc = document.getElementById('heroLocation').value;

    const params = new URLSearchParams();
    if (type) params.append('type', type);
    if (start) params.append('start', start);
    if (end) params.append('end', end);
    if (loc) params.append('pickup', loc);

    window.location.href = `vehicles.php?${params.toString()}`;
}

async function runQuickDijkstra() {
    const src = document.getElementById('quickDijkstraSrc').value;
    const dst = document.getElementById('quickDijkstraDst').value;
    const out = document.getElementById('quickDijkstraResult');

    out.innerHTML = '<span class="muted">Calculating shortest path...</span>';
    try {
        const res = await fetch(`api/algorithms/route.php?source=${encodeURIComponent(src)}&destination=${encodeURIComponent(dst)}`);
        const json = await res.json();
        if (!json.success || !json.data.pathFound) {
            out.innerHTML = '<span class="status-warning">No route found between selected hubs.</span>';
            return;
        }
        const d = json.data;
        const pathHops = d.path.map(p => p.name.split(' ')[0]).join(' ➔ ');
        out.innerHTML = `
            <div class="quick-route-box">
                <div class="route-stat-line">
                    <strong>Route Distance:</strong> <span>${d.totalDistanceKm} km</span>
                </div>
                <div class="route-stat-line">
                    <strong>Est. Drive Time:</strong> <span>${d.estimatedMinutes} mins</span>
                </div>
                <div class="route-stat-line">
                    <strong>Delivery Surcharge:</strong> <span>₹${d.transitSurchargeINR}</span>
                </div>
                <div class="route-path-display">
                    <small>Shortest Node Path:</small>
                    <div class="hops-ribbon">${pathHops}</div>
                </div>
                <a href="algorithms.php#dijkstra" class="text-link">Explore full graph in Algorithm Lab →</a>
            </div>
        `;
    } catch (e) {
        out.innerHTML = '<span class="status-warning">Error running route calculation.</span>';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadFeaturedFleet('price_asc');
    runQuickDijkstra();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
