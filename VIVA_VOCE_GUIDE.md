# VIVA VOCE PREPARATION GUIDE & EXAMINER Q&A CHEAT-SHEET

**Project Title:** Cloud-Based Vehicle Rental Management System with Intelligent Booking and Algorithm Optimization  
**Student Companion Guide for Practical Defense & Viva Exam**

---

## SECTION 1: DESIGN AND ANALYSIS OF ALGORITHMS (DAA)

### Q1: Which algorithms did you implement in this project, and why?
**Answer:**
"I implemented five foundational algorithms to solve specific operational problems in the vehicle rental lifecycle:
1. **Binary Search ($O(\log n)$):** Used in vehicle search to locate vehicles in a pre-sorted fleet by name, registration number, or ID in logarithmic time rather than linear $O(n)$ time.
2. **Merge Sort ($O(n \log n)$):** Used to sort vehicles by rental price per day, seating capacity, or model name. We chose Merge Sort because it has a guaranteed worst-case time complexity of $O(n \log n)$ and is stable, meaning equal price vehicles preserve their original catalog order.
3. **Interval Overlap Detection ($O(k)$):** Used on the server side to detect date collisions and prevent double bookings using the mathematical theorem: $\max(s_1, s_2) \le \min(e_1, e_2)$.
4. **Greedy Vehicle Allocation ($O(n)$):** Used for intelligent vehicle matchmaking. Given a customer's budget, required passenger seats, rental duration, and fuel preference, it computes a greedy fitness score balancing cost savings with seat capacity utilization to select the optimal vehicle without backtracking.
5. **Dijkstra's Shortest Path Algorithm ($O((|V| + |E|) \log |V|)$):** Used over a weighted graph of 10 regional rental hubs to calculate the shortest transit route, travel duration, and delivery surcharge between pickup and dropoff points."

---

### Q2: Why did you choose Merge Sort instead of QuickSort?
**Answer:**
"QuickSort has an average-case complexity of $O(n \log n)$, but in the worst case (such as when the pivot is poorly chosen or the array is already sorted/reverse sorted), QuickSort degrades to $O(n^2)$. 
In contrast, **Merge Sort guarantees $O(n \log n)$ in all cases** (best, average, and worst). Additionally, Merge Sort is **stable**, which is crucial in e-commerce and rental listings so secondary attributes maintain their ordering."

---

### Q3: What is the mathematical condition for Interval Overlap Detection?
**Answer:**
"Two closed time intervals $A = [s_A, e_A]$ and $B = [s_B, e_B]$ overlap if and only if:
$$\max(s_A, s_B) \le \min(e_A, e_B)$$
In Boolean logic, this is equivalent to:
$$(s_A \le e_B) \land (e_A \ge s_B)$$
If this condition evaluates to true, the intervals intersect by at least one day, and our system rejects the booking request with HTTP 409 Conflict to protect against double booking."

---

### Q4: How does your Greedy Algorithm choose the vehicle? What are its limitations?
**Answer:**
"The algorithm first applies feasibility filtering: it only evaluates vehicles that are currently `Available`, have $\text{seats} \ge S_{\text{req}}$, and whose total cost $(\text{pricePerDay} \times D) \le \text{Budget}$.
Next, it scores each candidate using:
$$\text{Score}(v) = 0.45 \cdot \text{BudgetSavingsRatio} + 0.35 \cdot \text{SeatUtilizationRatio} + 0.20 \cdot \text{PreferenceBonus}$$
The greedy choice property selects the vehicle that maximizes this score.
**Limitation:** A greedy algorithm makes the locally optimal choice for the current customer without predicting future high-demand bookings (e.g., allocating an SUV today that could have fetched a higher rental rate tomorrow)."

---

### Q5: How did you implement Dijkstra's algorithm? What data structures did you use?
**Answer:**
"We represented the city road network as an undirected weighted graph where vertices $V$ are rental hubs (like Airport, MG Road, Indiranagar) and edge weights $E$ are road distances in kilometers.
We used a **Min-Priority Queue** (`SplPriorityQueue` in PHP) to repeatedly extract the vertex with the minimum tentative distance. When an edge is relaxed, if a shorter path to neighbor $v$ is discovered via $u$, we update `dist[v]` and `prev[v]`. The time complexity is $O((|V| + |E|) \log |V|)$."

---

## SECTION 2: SERVER-SIDE SCRIPTING (PHP & ARCHITECTURE)

### Q6: How did you isolate customer sessions from administrator sessions?
**Answer:**
"In standard PHP applications, a single default session (`PHPSESSID`) causes session collision—if an admin logs in on one tab and a customer on another, one session overwrites the other.
To prevent this, we used **Named Sessions**:
- Customer portal: `session_name('VS_CUSTOMER'); session_start();`
- Administrator console: `session_name('VS_ADMIN'); session_start();`
This guarantees independent session cookies and prevents privilege escalation."

---

### Q7: How do you protect the system against SQL / NoSQL Injection and XSS?
**Answer:**
1. **Injection Defense:** All MongoDB queries use structured filter arrays instead of concatenated query strings. Input strings passed to regex searches are sanitized with `preg_quote()`, and IDs are validated with regex `^[a-f0-9]{24}$` before constructing BSON `ObjectId`s.
2. **XSS Defense:** All dynamic values rendered in HTML are passed through `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
3. **Password Security:** Passwords are never stored in plaintext; they are hashed using `password_hash($password, PASSWORD_BCRYPT)` and validated using constant-time `password_verify()`."

---

### Q8: What is the Booking State Machine lifecycle?
**Answer:**
"The booking follows a strict 6-stage lifecycle:
1. `Pending`: Customer submits a request (validated against date overlaps).
2. `Approved`: Administrator approves the customer request.
3. `Confirmed`: Customer completes simulated Demo Payment (UPI/Card). Pickup remains blocked until this stage!
4. `Active`: Admin marks vehicle as picked up at the hub (vehicle status switches to `On Rent`).
5. `Returned`: Customer returns the vehicle to the depot.
6. `Completed`: Admin inspects and closes the rental (vehicle status reverts to `Available`)."

---

## SECTION 3: CLOUD COMPUTING & DEPLOYMENT

### Q9: Where and how is the database hosted in the cloud?
**Answer:**
"The database is hosted on **MongoDB Atlas** (M0 Free Tier Cluster) on AWS infrastructure (`cluster0.3kl2cby.mongodb.net`). The connection uses the official MongoDB PHP Library with TLS/SSL encryption and XAMPP CA certificate authentication. Credentials are kept out of source code and loaded from environment variables (`MONGODB_URI`) in cloud environments."

---

### Q10: What is your Resilient Local Engine and why did you build it?
**Answer:**
"In college laboratories, internet connections can be slow, captive portals can block external traffic, or campus firewalls can block outgoing MongoDB port 27017.
To guarantee 100% demonstration reliability, our `config/database.php` implements the **Resilient Adapter Pattern**:
- If MongoDB Atlas is reachable, it uses Cloud MongoDB Atlas.
- If MongoDB Atlas is blocked or offline, it automatically and silently activates our `ResilientDB\LocalDatabase` engine, which provides identical CRUD methods (`find`, `findOne`, `insertOne`, `updateOne`, `countDocuments`) storing data atomically in `database/data_store.json`.
This ensures the system never crashes during an evaluation."

---

### Q11: How is the application containerized with Docker?
**Answer:**
"We wrote a production `Dockerfile` based on `php:8.2-apache`:
- Installs PECL MongoDB driver and enables Apache rewrite.
- Installs Composer and optimizes the autoloader (`--optimize-autoloader --no-dev`).
- Uses `docker/start-apache.sh` to dynamically bind Apache to Render's dynamic `$PORT`.
- Configured with `render.yaml` for automated cloud deployment."
