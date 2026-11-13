    </main>
    <footer class="site-footer">
        <div class="footer-primary">
            <div class="brand footer-logo">
                <span class="brand-mark">VS</span>
                <div>
                    <span>VS TRAVELLS</span>
                    <small>Intelligent Cloud Vehicle Rental Platform</small>
                </div>
            </div>
            <p class="footer-desc">
                An advanced academic capstone project integrating Server-Side Scripting (PHP), Cloud Computing (MongoDB Atlas, Docker containerization), and Design & Analysis of Algorithms (Binary Search, Merge Sort, Interval Overlap Detection, Greedy Allocation, Dijkstra Route Optimization).
            </p>
            <div class="academic-tags">
                <span class="tag">PHP 8.2</span>
                <span class="tag">MongoDB Atlas</span>
                <span class="tag">Docker</span>
                <span class="tag">DAA Optimized</span>
                <span class="tag">RESTful API</span>
            </div>
        </div>

        <div>
            <strong>Navigation</strong>
            <a href="<?php echo BASE_URL; ?>/index.php">Home Page</a>
            <a href="<?php echo BASE_URL; ?>/vehicles.php">Fleet & Pricing</a>
            <a href="<?php echo BASE_URL; ?>/algorithms.php">DAA Algorithm Lab</a>
            <?php if ($isCustomer): ?>
                <a href="<?php echo BASE_URL; ?>/dashboard.php">Customer Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/bookings.php">My Bookings</a>
                <a href="<?php echo BASE_URL; ?>/profile.php">My Profile</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/login.php">Customer Login</a>
                <a href="<?php echo BASE_URL; ?>/register.php">Create Account</a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>/admin/login.php">Administrator Portal</a>
        </div>

        <div>
            <strong>DAA Algorithms</strong>
            <a href="<?php echo BASE_URL; ?>/algorithms.php#binary">Binary Search (O(log n))</a>
            <a href="<?php echo BASE_URL; ?>/algorithms.php#mergesort">Merge Sort (O(n log n))</a>
            <a href="<?php echo BASE_URL; ?>/algorithms.php#interval">Interval Overlap Detection</a>
            <a href="<?php echo BASE_URL; ?>/algorithms.php#greedy">Greedy Allocation Engine</a>
            <a href="<?php echo BASE_URL; ?>/algorithms.php#dijkstra">Dijkstra Shortest Path</a>
        </div>

        <div>
            <strong>Academic Viva Info</strong>
            <span>College Capstone Project</span>
            <span>Local Environment: XAMPP Windows</span>
            <span>Cloud Deployment: Docker & Render</span>
            <span>Database: MongoDB Atlas Cluster</span>
            <span class="status-live-chip">● Cloud & Resilient Engine Active</span>
        </div>

        <div class="copyright">
            <p>© 2026 VS Travells. College Project Submission. All rights reserved.</p>
        </div>
    </footer>

    <script src="<?php echo BASE_URL; ?>/assets/js/script.js"></script>
</body>
</html>
