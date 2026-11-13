<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-shell algo-lab-shell">
    <div class="algo-header">
        <span class="eyebrow dark">ACADEMIC SUBJECT 3 • DESIGN AND ANALYSIS OF ALGORITHMS</span>
        <h1>DAA Algorithm Optimization Lab</h1>
        <p class="muted">
            Interactive verification and testing of 5 pure algorithms implemented in this project: 
            Binary Search, Merge Sort, Interval Overlap Detection, Greedy Vehicle Allocation, and Dijkstra's Shortest Path Algorithm.
        </p>
    </div>

    <!-- Algorithm Tab Navigation -->
    <div class="algo-tabs">
        <button class="algo-tab-btn active" onclick="switchAlgoTab('binary', this)">
            <span class="tab-num">1</span>
            <div>
                <strong>Binary Search</strong>
                <small>O(log n) • Search</small>
            </div>
        </button>
        <button class="algo-tab-btn" onclick="switchAlgoTab('mergesort', this)">
            <span class="tab-num">2</span>
            <div>
                <strong>Merge Sort</strong>
                <small>O(n log n) • Divide & Conquer</small>
            </div>
        </button>
        <button class="algo-tab-btn" onclick="switchAlgoTab('interval', this)">
            <span class="tab-num">3</span>
            <div>
                <strong>Interval Overlap</strong>
                <small>O(k) • Double-Booking Guard</small>
            </div>
        </button>
        <button class="algo-tab-btn" onclick="switchAlgoTab('greedy', this)">
            <span class="tab-num">4</span>
            <div>
                <strong>Greedy Matchmaker</strong>
                <small>O(n) • Optimal Allocation</small>
            </div>
        </button>
        <button class="algo-tab-btn" onclick="switchAlgoTab('dijkstra', this)">
            <span class="tab-num">5</span>
            <div>
                <strong>Dijkstra Route</strong>
                <small>O((V+E)log V) • Graph Shortest Path</small>
            </div>
        </button>
    </div>

    <!-- TAB 1: BINARY SEARCH -->
    <div id="tab-binary" class="algo-content-tab active">
        <div class="algo-grid-layout">
            <div class="algo-panel interactive-panel">
                <div class="panel-head">
                    <h2>Live Binary Search Test</h2>
                    <span class="chip">Pre-sorted Array</span>
                </div>
                <p class="muted">Search for any vehicle in the sorted fleet by model name.</p>
                <div class="form-group">
                    <label>Enter Vehicle Name to Search</label>
                    <div class="search-input-wrap">
                        <input type="text" id="binarySearchInput" placeholder="e.g. Swift, Creta, Thar, Innova, BMW..." value="Thar">
                        <button class="btn" onclick="runBinarySearch()">Execute Binary Search</button>
                    </div>
                </div>

                <div id="binaryResults" class="algo-results-box">
                    <div class="empty-card">Click "Execute Binary Search" to trace the algorithm.</div>
                </div>
            </div>

            <div class="algo-panel theory-panel">
                <div class="theory-card">
                    <span class="eyebrow">THEORETICAL ANALYSIS</span>
                    <h3>Binary Search Complexity</h3>
                    <table class="algo-matrix-table">
                        <tr><td><strong>Best-Case Time</strong></td><td><code>O(1)</code> (Target found at mid element on step 1)</td></tr>
                        <tr><td><strong>Average-Case Time</strong></td><td><code>O(log n)</code></td></tr>
                        <tr><td><strong>Worst-Case Time</strong></td><td><code>O(log n)</code></td></tr>
                        <tr><td><strong>Space Complexity</strong></td><td><code>O(1)</code> Iterative implementation</td></tr>
                        <tr><td><strong>Precondition</strong></td><td>Input array must be sorted monotonically</td></tr>
                    </table>

                    <h4>How It Works In This Project:</h4>
                    <p class="muted">
                        Before executing Binary Search, the fleet is sorted alphabetically by vehicle name using Merge Sort. 
                        The search space $[low, high]$ is halved on each iteration by comparing the target with $arr[mid]$.
                        If target matches $arr[mid]$, the item is returned immediately in $O(1)$ to $O(\log n)$ steps instead of 
                        checking every vehicle sequentially.
                    </p>
                    <div class="pseudocode-box">
                        <pre><code>function BinarySearch(A, target):
    low = 0, high = length(A) - 1
    while low &lt;= high:
        mid = floor((low + high) / 2)
        if A[mid].name == target:
            return A[mid]
        else if A[mid].name &lt; target:
            low = mid + 1
        else:
            high = mid - 1
    return NOT_FOUND</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: MERGE SORT -->
    <div id="tab-mergesort" class="algo-content-tab">
        <div class="algo-grid-layout">
            <div class="algo-panel interactive-panel">
                <div class="panel-head">
                    <h2>Live Merge Sort Test</h2>
                    <span class="chip">Divide & Conquer</span>
                </div>
                <p class="muted">Sort the entire vehicle fleet dynamically by selected attribute.</p>
                <div class="sort-controls-row">
                    <div class="form-group">
                        <label>Sort By Attribute</label>
                        <select id="mergeSortKey">
                            <option value="pricePerDay" selected>Price Per Day (₹)</option>
                            <option value="seats">Passenger Seats</option>
                            <option value="name">Vehicle Name (Alphabetical)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Direction</label>
                        <select id="mergeSortOrder">
                            <option value="ASC" selected>Ascending (Low to High)</option>
                            <option value="DESC">Descending (High to Low)</option>
                        </select>
                    </div>
                    <button class="btn" onclick="runMergeSort()">Execute Merge Sort</button>
                </div>

                <div id="mergeSortResults" class="algo-results-box">
                    <div class="empty-card">Click "Execute Merge Sort" to inspect sorted results and recursive metrics.</div>
                </div>
            </div>

            <div class="algo-panel theory-panel">
                <div class="theory-card">
                    <span class="eyebrow">THEORETICAL ANALYSIS</span>
                    <h3>Merge Sort Complexity</h3>
                    <table class="algo-matrix-table">
                        <tr><td><strong>Best-Case Time</strong></td><td><code>O(n log n)</code></td></tr>
                        <tr><td><strong>Average-Case Time</strong></td><td><code>O(n log n)</code></td></tr>
                        <tr><td><strong>Worst-Case Time</strong></td><td><code>O(n log n)</code></td></tr>
                        <tr><td><strong>Space Complexity</strong></td><td><code>O(n)</code> (Auxiliary memory for merge buffers)</td></tr>
                        <tr><td><strong>Stability</strong></td><td>Stable (preserves original order of equal keys)</td></tr>
                    </table>

                    <h4>Recurrence Relation:</h4>
                    <p class="muted">
                        $$T(n) = 2T(n/2) + \Theta(n)$$
                        By the Master Theorem (Case 2), where $a=2, b=2, d=1$, $\log_b a = 1 = d$, the solution is strictly $\Theta(n \log n)$.
                    </p>
                    <div class="pseudocode-box">
                        <pre><code>function MergeSort(A):
    if length(A) &lt;= 1: return A
    mid = floor(length(A) / 2)
    left = MergeSort(A[0 .. mid-1])
    right = MergeSort(A[mid .. end])
    return Merge(left, right)</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: INTERVAL OVERLAP DETECTION -->
    <div id="tab-interval" class="algo-content-tab">
        <div class="algo-grid-layout">
            <div class="algo-panel interactive-panel">
                <div class="panel-head">
                    <h2>Live Interval Overlap Collision Check</h2>
                    <span class="chip">Scheduling Guard</span>
                </div>
                <p class="muted">Test any prospective booking date interval against currently reserved bookings.</p>
                <div class="form-grid-two">
                    <div class="form-group">
                        <label>Proposed Start Date</label>
                        <input type="date" id="intervalStart" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
                    </div>
                    <div class="form-group">
                        <label>Proposed End Date</label>
                        <input type="date" id="intervalEnd" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d', strtotime('+5 days')); ?>">
                    </div>
                </div>
                <button class="btn wide" onclick="runIntervalCheck()">Check Interval Collision</button>

                <div id="intervalResults" class="algo-results-box">
                    <div class="empty-card">Click "Check Interval Collision" to test server-side date validation.</div>
                </div>
            </div>

            <div class="algo-panel theory-panel">
                <div class="theory-card">
                    <span class="eyebrow">MATHEMATICAL THEOREM</span>
                    <h3>Interval Intersection Theorem</h3>
                    <div class="math-callout">
                        $$\text{Intervals } [s_1, e_1] \text{ and } [s_2, e_2] \text{ intersect} \iff \max(s_1, s_2) \le \min(e_1, e_2)$$
                    </div>
                    <table class="algo-matrix-table">
                        <tr><td><strong>Collision Check Time</strong></td><td><code>O(k)</code> for $k$ active reservations</td></tr>
                        <tr><td><strong>Sorted Sweep-Line</strong></td><td><code>O(k log k)</code> to find all open gaps</td></tr>
                        <tr><td><strong>Space Complexity</strong></td><td><code>O(1)</code> auxiliary space</td></tr>
                        <tr><td><strong>False Positive Rate</strong></td><td>0% (Exact mathematical equality)</td></tr>
                    </table>

                    <h4>Why Ordinary Database Queries Fall Short:</h4>
                    <p class="muted">
                        Ordinary applications often rely on simplistic equality checks or client-side validation that fail under 
                        concurrent race conditions or inverted date inputs. Our server-side <code>IntervalOverlap</code> engine 
                        guarantees robust mathematical interval overlap detection before any booking state transition can occur.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 4: GREEDY VEHICLE ALLOCATION -->
    <div id="tab-greedy" class="algo-content-tab">
        <div class="algo-grid-layout">
            <div class="algo-panel interactive-panel">
                <div class="panel-head">
                    <h2>Live Greedy Vehicle Matchmaker</h2>
                    <span class="chip">Heuristic Optimizer</span>
                </div>
                <p class="muted">Allocate the optimal available vehicle matching customer budget and requirements.</p>

                <div class="form-grid-three">
                    <div class="form-group">
                        <label>Total Budget (₹)</label>
                        <input type="number" id="greedyBudget" value="8000" min="500" step="500">
                    </div>
                    <div class="form-group">
                        <label>Passengers / Seats Needed</label>
                        <input type="number" id="greedySeats" value="5" min="1" max="10">
                    </div>
                    <div class="form-group">
                        <label>Rental Duration (Days)</label>
                        <input type="number" id="greedyDays" value="3" min="1" max="30">
                    </div>
                    <div class="form-group">
                        <label>Vehicle Type</label>
                        <select id="greedyType">
                            <option value="Any" selected>Any (Cars & Bikes)</option>
                            <option value="Car">Car</option>
                            <option value="Bike">Bike</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fuel Preference</label>
                        <select id="greedyFuel">
                            <option value="Any" selected>Any Fuel</option>
                            <option value="Petrol">Petrol</option>
                            <option value="Diesel">Diesel</option>
                            <option value="EV">Electric (EV)</option>
                        </select>
                    </div>
                </div>
                <button class="btn wide" onclick="runGreedyMatch()">Compute Greedy Optimal Allocation</button>

                <div id="greedyResults" class="algo-results-box">
                    <div class="empty-card">Enter criteria and click to run the Greedy Matchmaker.</div>
                </div>
            </div>

            <div class="algo-panel theory-panel">
                <div class="theory-card">
                    <span class="eyebrow">THEORETICAL ANALYSIS</span>
                    <h3>Greedy Choice Property</h3>
                    <div class="math-callout">
                        $$\text{Score}(v) = 0.45 \cdot \left(1 - \frac{\text{Cost}(v)}{B}\right) + 0.35 \cdot \left(\frac{S_{\text{req}}}{\text{seats}(v)}\right) + 0.20 \cdot \text{Bonus}$$
                    </div>
                    <table class="algo-matrix-table">
                        <tr><td><strong>Time Complexity</strong></td><td><code>O(n)</code> where $n = \text{fleet size}$</td></tr>
                        <tr><td><strong>Space Complexity</strong></td><td><code>O(1)</code> auxiliary space</td></tr>
                        <tr><td><strong>Selection Strategy</strong></td><td>Greedy choice at each candidate evaluation</td></tr>
                        <tr><td><strong>Heuristic Limitation</strong></td><td>Does not predict future multi-day rental demand spikes</td></tr>
                    </table>

                    <h4>Academic Explanation:</h4>
                    <p class="muted">
                        The Greedy Allocation algorithm evaluates each available vehicle against hard feasibility constraints 
                        (capacity $\ge S_{\text{req}}$ and total cost $\le B$). It assigns a composite fitness score balancing 
                        budget savings with seat efficiency so a solo traveler is not allocated an empty 7-seater Innova when an 
                        affordable 2-seater or hatchback is available.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 5: DIJKSTRA'S SHORTEST PATH -->
    <div id="tab-dijkstra" class="algo-content-tab">
        <div class="algo-grid-layout">
            <div class="algo-panel interactive-panel">
                <div class="panel-head">
                    <h2>Live Dijkstra Route Optimizer</h2>
                    <span class="chip">Graph Min-Priority Queue</span>
                </div>
                <p class="muted">Compute the exact shortest transit path between rental depots and airport hubs.</p>

                <div class="form-grid-two">
                    <div class="form-group">
                        <label>Origin / Pickup Hub (Source Node)</label>
                        <select id="dijkstraSource">
                            <option value="BLR_AIRPORT">Kempegowda Int'l Airport Hub</option>
                            <option value="HEBBAL">Hebbal North Interchange Depot</option>
                            <option value="MG_ROAD" selected>Central CBD (MG Road)</option>
                            <option value="INDIRANAGAR">Indiranagar 100ft Metro Hub</option>
                            <option value="KORAMANGALA">Koramangala 5th Block Depot</option>
                            <option value="WHITEFIELD">Whitefield ITPL Tech Corridor</option>
                            <option value="ELECTRONIC_CITY">Electronic City Tollgate Hub</option>
                            <option value="JAYANAGAR">Jayanagar 4th Block Terminal</option>
                            <option value="MYSORE_ROAD">Mysore Road Satellite Depot</option>
                            <option value="YESHWANTHPUR">Yeshwanthpur Junction Hub</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Destination / Dropoff Hub (Target Node)</label>
                        <select id="dijkstraDest">
                            <option value="BLR_AIRPORT" selected>Kempegowda Int'l Airport Hub</option>
                            <option value="HEBBAL">Hebbal North Interchange Depot</option>
                            <option value="MG_ROAD">Central CBD (MG Road)</option>
                            <option value="INDIRANAGAR">Indiranagar 100ft Metro Hub</option>
                            <option value="KORAMANGALA">Koramangala 5th Block Depot</option>
                            <option value="WHITEFIELD">Whitefield ITPL Tech Corridor</option>
                            <option value="ELECTRONIC_CITY">Electronic City Tollgate Hub</option>
                            <option value="JAYANAGAR">Jayanagar 4th Block Terminal</option>
                            <option value="MYSORE_ROAD">Mysore Road Satellite Depot</option>
                            <option value="YESHWANTHPUR">Yeshwanthpur Junction Hub</option>
                        </select>
                    </div>
                </div>
                <button class="btn wide" onclick="runDijkstraOptimization()">Compute Shortest Route Path</button>

                <div id="dijkstraResults" class="algo-results-box">
                    <div class="empty-card">Select hubs and run Dijkstra to inspect optimal path and distance.</div>
                </div>
            </div>

            <div class="algo-panel theory-panel">
                <div class="theory-card">
                    <span class="eyebrow">THEORETICAL ANALYSIS</span>
                    <h3>Dijkstra's Algorithm Complexity</h3>
                    <table class="algo-matrix-table">
                        <tr><td><strong>Time Complexity (Min-Heap)</strong></td><td><code>O((|V| + |E|) log |V|)</code></td></tr>
                        <tr><td><strong>Time (Adjacency Matrix)</strong></td><td><code>O(|V|^2)</code></td></tr>
                        <tr><td><strong>Space Complexity</strong></td><td><code>O(|V|)</code> (Distance and predecessor maps)</td></tr>
                        <tr><td><strong>Weight Constraint</strong></td><td>Edge weights must be non-negative ($w(u,v) \ge 0$)</td></tr>
                    </table>

                    <h4>Network Graph Topology:</h4>
                    <p class="muted">
                        The transit network represents $10$ key rental depots ($|V| = 10$) and $13$ bidirectional road connections ($|E| = 13$).
                        Dijkstra's algorithm relaxes adjacent edges sequentially using a Min-Priority Queue to discover the path of minimal road kilometers.
                    </p>
                    <div class="pseudocode-box">
                        <pre><code>function Dijkstra(Graph, source):
    for each vertex v in Graph:
        dist[v] = infinity
        prev[v] = null
    dist[source] = 0
    Q = MinPriorityQueue(vertices keyed by dist)
    while Q is not empty:
        u = Q.extractMin()
        for each neighbor v of u:
            alt = dist[u] + weight(u, v)
            if alt &lt; dist[v]:
                dist[v] = alt
                prev[v] = u
                Q.decreaseKey(v, alt)
    return dist, prev</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function switchAlgoTab(tabId, btn) {
    document.querySelectorAll('.algo-content-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.algo-tab-btn').forEach(b => b.classList.remove('active'));

    const targetTab = document.getElementById('tab-' + tabId);
    if (targetTab) targetTab.classList.add('active');
    if (btn) btn.classList.add('active');
}

// 1. Binary Search Runner
async function runBinarySearch() {
    const query = document.getElementById('binarySearchInput').value.trim();
    const box = document.getElementById('binaryResults');
    if (!query) {
        box.innerHTML = '<div class="message error">Please enter a search query.</div>';
        return;
    }

    box.innerHTML = '<div class="empty-card">Executing Binary Search...</div>';
    try {
        const res = await fetch(`api/algorithms/search.php?query=${encodeURIComponent(query)}&key=name`);
        const json = await res.json();
        if (!json.success) {
            box.innerHTML = `<div class="message error">${json.message}</div>`;
            return;
        }

        const d = json.data;
        const traceRows = d.trace.map(t => `
            <tr>
                <td>Step ${t.step}</td>
                <td>${t.low}</td>
                <td><strong>${t.mid}</strong></td>
                <td>${t.high}</td>
                <td><strong>${t.currentValue}</strong></td>
                <td><span class="badge ${t.comparison === 'MATCH' ? 'confirmed' : 'pending'}">${t.comparison}</span></td>
            </tr>
        `).join('');

        box.innerHTML = `
            <div class="algo-metric-bar">
                <span>Algorithm: <strong>${d.algorithm}</strong></span>
                <span>Time Complexity: <strong>${d.timeComplexity}</strong></span>
                <span>Space: <strong>${d.spaceComplexity}</strong></span>
                <span>Execution: <strong>${d.executionTimeMs} ms</strong></span>
                <span>Steps: <strong>${d.totalSteps}</strong></span>
            </div>

            ${d.found ? `
                <div class="result-banner success-banner">
                    <div>
                        <strong>✓ Exact Match Found at Index ${d.matchIndex}!</strong>
                        <h3>${d.item.name} (${d.item.type})</h3>
                        <p>₹${Number(d.item.pricePerDay).toLocaleString('en-IN')}/day • ${d.item.seats} seats • ${d.item.fuel}</p>
                    </div>
                    <a class="btn" href="vehicles.php?search=${encodeURIComponent(d.item.name)}">Book Now</a>
                </div>
            ` : `
                <div class="result-banner warning-banner">
                    <strong>No exact match for "${query}" in sorted array.</strong>
                    <p>However, prefix search found ${d.prefixMatches?.length || 0} candidate vehicles.</p>
                </div>
            `}

            <h4>Binary Search Iteration Trace:</h4>
            <div class="table-wrap">
                <table class="table trace-table">
                    <thead>
                        <tr><th>Step</th><th>Low</th><th>Mid Index</th><th>High</th><th>Current Value A[mid]</th><th>Decision</th></tr>
                    </thead>
                    <tbody>${traceRows}</tbody>
                </table>
            </div>
        `;
    } catch (e) {
        box.innerHTML = '<div class="message error">Failed to run Binary Search.</div>';
    }
}

// 2. Merge Sort Runner
async function runMergeSort() {
    const key = document.getElementById('mergeSortKey').value;
    const order = document.getElementById('mergeSortOrder').value;
    const box = document.getElementById('mergeSortResults');

    box.innerHTML = '<div class="empty-card">Executing Merge Sort (Divide & Conquer)...</div>';
    try {
        const res = await fetch(`api/algorithms/sort.php?key=${encodeURIComponent(key)}&order=${encodeURIComponent(order)}`);
        const json = await res.json();
        if (!json.success) {
            box.innerHTML = `<div class="message error">${json.message}</div>`;
            return;
        }

        const d = json.data;
        const sortedCards = d.sortedData.map((v, i) => `
            <div class="sorted-item-row">
                <span class="rank-badge">#${i + 1}</span>
                <div class="sorted-info">
                    <strong>${v.name}</strong>
                    <small>${v.type} • ${v.seats} seats • ${v.fuel} • ${v.status}</small>
                </div>
                <div class="sorted-val">
                    ₹${Number(v.pricePerDay).toLocaleString('en-IN')}/day
                </div>
            </div>
        `).join('');

        box.innerHTML = `
            <div class="algo-metric-bar">
                <span>Algorithm: <strong>${d.algorithm}</strong></span>
                <span>Complexity: <strong>${d.timeComplexity}</strong></span>
                <span>Elements: <strong>${d.itemCount}</strong></span>
                <span>Comparisons: <strong>${d.comparisons}</strong></span>
                <span>Recursive Calls: <strong>${d.recursiveCalls}</strong></span>
                <span>Time: <strong>${d.executionTimeMs} ms</strong></span>
            </div>

            <h4>Sorted Results (${key} • ${order}):</h4>
            <div class="sorted-list-box">${sortedCards}</div>
        `;
    } catch (e) {
        box.innerHTML = '<div class="message error">Failed to run Merge Sort.</div>';
    }
}

// 3. Interval Overlap Runner
async function runIntervalCheck() {
    const s = document.getElementById('intervalStart').value;
    const e = document.getElementById('intervalEnd').value;
    const box = document.getElementById('intervalResults');

    if (!s || !e) {
        box.innerHTML = '<div class="message error">Please select both start and end dates.</div>';
        return;
    }

    box.innerHTML = '<div class="empty-card">Checking interval overlap across all active reservations...</div>';
    try {
        const res = await fetch(`api/algorithms/overlap.php?startDate=${encodeURIComponent(s)}&endDate=${encodeURIComponent(e)}`);
        const json = await res.json();
        const d = json.data;

        if (d.hasConflict) {
            box.innerHTML = `
                <div class="result-banner danger-banner">
                    <strong>⚠️ Collision Detected! Overlapping reservation found.</strong>
                    <p>The proposed dates (${d.requestedStart} → ${d.requestedEnd}) conflict with ${d.conflictCount} active booking(s).</p>
                </div>
                <h4>Conflicting Intervals:</h4>
                <div class="conflicts-list">
                    ${d.conflicts.map(c => `
                        <div class="conflict-item">
                            <div>
                                <strong>${c.vehicleName}</strong>
                                <span>Existing: ${c.existingStart} → ${c.existingEnd} (Status: ${c.status})</span>
                            </div>
                            <span class="badge rejected">Overlap: ${c.overlapStartDate} → ${c.overlapEndDate} (${c.overlapDays} days)</span>
                        </div>
                    `).join('')}
                </div>
            `;
        } else {
            box.innerHTML = `
                <div class="result-banner success-banner">
                    <strong>✓ Interval Available! Zero double-booking collisions.</strong>
                    <p>The proposed dates (${d.requestedStart} → ${d.requestedEnd}) are completely clear of existing active reservations.</p>
                </div>
                <div class="algo-metric-bar">
                    <span>Algorithm: <strong>${d.algorithm}</strong></span>
                    <span>Intervals Checked: <strong>${d.totalIntervalsChecked}</strong></span>
                    <span>Execution Time: <strong>${d.executionTimeMs} ms</strong></span>
                </div>
            `;
        }
    } catch (err) {
        box.innerHTML = '<div class="message error">Failed to execute interval overlap algorithm.</div>';
    }
}

// 4. Greedy Matchmaker Runner
async function runGreedyMatch() {
    const budget = document.getElementById('greedyBudget').value;
    const seats = document.getElementById('greedySeats').value;
    const days = document.getElementById('greedyDays').value;
    const type = document.getElementById('greedyType').value;
    const fuel = document.getElementById('greedyFuel').value;
    const box = document.getElementById('greedyResults');

    box.innerHTML = '<div class="empty-card">Evaluating candidate vehicles using Greedy fitness function...</div>';
    try {
        const res = await fetch(`api/algorithms/recommend.php?budget=${budget}&seats=${seats}&days=${days}&type=${type}&fuel=${fuel}`);
        const json = await res.json();
        const d = json.data;

        if (!d.foundMatch) {
            box.innerHTML = `
                <div class="result-banner warning-banner">
                    <strong>No vehicle satisfies all constraints within ₹${Number(budget).toLocaleString('en-IN')}.</strong>
                    <p>Try increasing your budget or reducing passenger seats.</p>
                </div>
            `;
            return;
        }

        const win = d.allocatedVehicle;
        const v = win.vehicle;

        box.innerHTML = `
            <div class="algo-metric-bar">
                <span>Algorithm: <strong>${d.algorithm}</strong></span>
                <span>Candidates Evaluated: <strong>${d.totalCandidatesEvaluated} / ${d.totalFleetSize}</strong></span>
                <span>Execution Time: <strong>${d.executionTimeMs} ms</strong></span>
            </div>

            <div class="greedy-winner-card">
                <div class="winner-badge">🏆 TOP GREEDY CHOICE (Score: ${win.greedyScore})</div>
                <div class="winner-body">
                    <div>
                        <h3>${v.name}</h3>
                        <p class="muted">${v.type} • ${v.fuel} • ${v.seats} Seats • ${v.transmission}</p>
                        <div class="greedy-score-breakdown">
                            <span>Cost Conservation: <strong>${win.breakdown.budgetComponent}</strong></span>
                            <span>Seat Efficiency: <strong>${win.breakdown.seatComponent}</strong></span>
                            <span>Preference Match: <strong>${win.breakdown.preferenceComponent}</strong></span>
                        </div>
                    </div>
                    <div class="winner-pricing">
                        <small>Total For ${d.inputCriteria.days} Days</small>
                        <strong>₹${Number(win.totalCost).toLocaleString('en-IN')}</strong>
                        <span class="savings-chip">Savings: ₹${Number(win.costSavings).toLocaleString('en-IN')}</span>
                        <a href="vehicles.php?search=${encodeURIComponent(v.name)}" class="btn wide">Reserve Optimal Vehicle</a>
                    </div>
                </div>
            </div>

            ${d.alternativeCandidates.length ? `
                <h4>Alternative Ranked Contenders:</h4>
                <div class="mini-list">
                    ${d.alternativeCandidates.map(c => `
                        <div>
                            <strong>${c.vehicle.name} (${c.vehicle.type})</strong>
                            <span>₹${Number(c.totalCost).toLocaleString('en-IN')} • Greedy Score: ${c.greedyScore} • Seat fit: ${c.seatUtilization}</span>
                        </div>
                    `).join('')}
                </div>
            ` : ''}
        `;
    } catch (e) {
        box.innerHTML = '<div class="message error">Failed to run Greedy Matchmaker.</div>';
    }
}

// 5. Dijkstra Shortest Path Runner
async function runDijkstraOptimization() {
    const src = document.getElementById('dijkstraSource').value;
    const dst = document.getElementById('dijkstraDest').value;
    const box = document.getElementById('dijkstraResults');

    box.innerHTML = '<div class="empty-card">Computing shortest path with Dijkstra min-priority queue...</div>';
    try {
        const res = await fetch(`api/algorithms/route.php?source=${encodeURIComponent(src)}&destination=${encodeURIComponent(dst)}`);
        const json = await res.json();
        const d = json.data;

        if (!d.pathFound) {
            box.innerHTML = '<div class="message error">No path exists between selected nodes in the graph.</div>';
            return;
        }

        const hopsHtml = d.path.map((h, i) => `
            <div class="hop-item">
                <span class="hop-circle">${i + 1}</span>
                <div>
                    <strong>${h.name}</strong>
                    <small>Cumulative: ${h.cumulativeKm} km</small>
                </div>
            </div>
        `).join('<div class="hop-connector">➔</div>');

        box.innerHTML = `
            <div class="algo-metric-bar">
                <span>Algorithm: <strong>${d.algorithm}</strong></span>
                <span>Time Complexity: <strong>${d.timeComplexity}</strong></span>
                <span>Space Complexity: <strong>${d.spaceComplexity}</strong></span>
                <span>Execution Time: <strong>${d.executionTimeMs} ms</strong></span>
            </div>

            <div class="dijkstra-summary-banner">
                <div class="summary-metric">
                    <span>Shortest Distance</span>
                    <strong>${d.totalDistanceKm} km</strong>
                </div>
                <div class="summary-metric">
                    <span>Estimated Drive Time</span>
                    <strong>${d.estimatedMinutes} mins</strong>
                </div>
                <div class="summary-metric">
                    <span>Vehicle Transit Surcharge</span>
                    <strong>₹${d.transitSurchargeINR}</strong>
                </div>
                <div class="summary-metric">
                    <span>Intermediate Hops</span>
                    <strong>${d.hopCount} stops</strong>
                </div>
            </div>

            <h4>Shortest Path Hop-by-Hop Node Route:</h4>
            <div class="hops-timeline">${hopsHtml}</div>

            <h4>Priority Queue Extraction Trace:</h4>
            <div class="table-wrap">
                <table class="table trace-table">
                    <thead>
                        <tr><th>Step</th><th>Extracted Hub Node</th><th>Hub Name</th><th>Relaxed Distance (km)</th></tr>
                    </thead>
                    <tbody>
                        ${d.algorithmTrace.map(t => `
                            <tr>
                                <td>${t.step}</td>
                                <td><code>${t.currentHub}</code></td>
                                <td>${t.name}</td>
                                <td><strong>${t.currentDistanceKm} km</strong></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    } catch (e) {
        box.innerHTML = '<div class="message error">Failed to run Dijkstra Shortest Path.</div>';
    }
}

// Auto-run Binary Search and Dijkstra on page load
document.addEventListener('DOMContentLoaded', () => {
    runBinarySearch();
    runMergeSort();
    runIntervalCheck();
    runGreedyMatch();
    runDijkstraOptimization();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
