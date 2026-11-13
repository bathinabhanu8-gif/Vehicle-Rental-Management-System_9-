# LOCAL SETUP & RUN GUIDE (WINDOWS XAMPP)

Follow these simple instructions to run and demonstrate the project on your Windows laptop.

---

## 1. Start XAMPP Apache
1. Open **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. (Note: MySQL is not required since the project uses MongoDB Atlas and our Resilient Local Data Engine).

---

## 2. Access the Application in Your Browser
Open Google Chrome or any web browser and visit:

- **Customer Website & Homepage:**  
  [http://localhost/VehicleRentalSystem/](http://localhost/VehicleRentalSystem/)  
  *(Features: Hero booking search, fleet showcase, 4-step process, live DAA route teaser)*

- **DAA Algorithm Lab (Examiner Viva Showcase):**  
  [http://localhost/VehicleRentalSystem/algorithms.php](http://localhost/VehicleRentalSystem/algorithms.php)  
  *(Features: Interactive testing of all 5 algorithms: Binary Search, Merge Sort, Interval Overlap, Greedy Matchmaker, Dijkstra Route)*

- **Customer Login:**  
  [http://localhost/VehicleRentalSystem/login.php](http://localhost/VehicleRentalSystem/login.php)

- **Admin Console:**  
  [http://localhost/VehicleRentalSystem/admin/login.php](http://localhost/VehicleRentalSystem/admin/login.php)

---

## 3. Pre-Configured Demo Credentials

| Role | Email | Password | Access URL |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@vstravells.com` | `Admin@123` | [admin/login.php](http://localhost/VehicleRentalSystem/admin/login.php) |
| **Customer** | `customer@vstravells.com` | `Customer@123` | [login.php](http://localhost/VehicleRentalSystem/login.php) |

*(You can also register any new customer account with your own email and password via `register.php`)*.

---

## 4. Run the Automated Test Suite (CLI)
Open PowerShell or Command Prompt, navigate to the project directory, and run:

```powershell
cd C:\xampp\htdocs\VehicleRentalSystem
php tests/TestSuite.php
```

You will see:
```text
========================================================================
   VS TRAVELLS - COMPREHENSIVE AUTOMATED TEST SUITE
========================================================================
TEST 01: Database connectivity & collection access.............. [ PASSED ]
TEST 02: Admin user account verification........................ [ PASSED ]
TEST 03: Customer user account verification..................... [ PASSED ]
TEST 04: DAA 1: Merge Sort O(n log n) by Price (ASC)............ [ PASSED ]
TEST 05: DAA 2: Merge Sort O(n log n) by Seats (DESC)........... [ PASSED ]
TEST 06: DAA 3: Binary Search O(log n) exact match.............. [ PASSED ]
TEST 07: DAA 4: Binary Search non-existent element handling..... [ PASSED ]
TEST 08: DAA 5: Interval Overlap Detection - Conflict Detected.. [ PASSED ]
TEST 09: DAA 6: Interval Overlap Detection - Clean Non-Overlapping [ PASSED ]
TEST 10: DAA 7: Greedy Vehicle Allocation - Within Budget & Seats [ PASSED ]
TEST 11: DAA 8: Greedy Allocation - Budget Rejection............ [ PASSED ]
TEST 12: DAA 9: Dijkstra Route Optimization - Airport to MG Road [ PASSED ]
TEST 13: DAA 10: Dijkstra Graph Symmetry & Non-Negative Weights. [ PASSED ]
TEST 14: Booking Lifecycle Stage 1: Pending Creation............ [ PASSED ]
TEST 15: Booking Lifecycle Stage 2: Admin Approval.............. [ PASSED ]
TEST 16: Booking Lifecycle Stage 3: Demo Payment & Confirmation. [ PASSED ]
TEST 17: Booking Lifecycle Stage 4-6: Pickup -> Return -> Complete [ PASSED ]
TEST 18: Double Booking Rejection: Interval Overlap Guard....... [ PASSED ]
========================================================================
   FINAL TEST SUMMARY: 18 / 18 PASSED (0 FAILED)
========================================================================
🎉 ALL TESTS PASSED! Project is in 100% verified working condition.
```

---

## 5. Re-Seeding the Database (If Needed)
To reset the fleet and test accounts back to fresh default state anytime:

```powershell
cd C:\xampp\htdocs\VehicleRentalSystem
php database/seed.php
```

---

## 6. Cloud Deployment Instructions (Render / Docker)
1. Push your repository to GitHub.
2. In [Render Dashboard](https://dashboard.render.com), click **New +** $\rightarrow$ **Web Service**.
3. Select your GitHub repository.
4. Choose **Docker** as the runtime.
5. In **Environment Variables**, add:
   - `MONGODB_URI`: Your MongoDB Atlas connection string.
   - `MONGODB_DATABASE`: `vehicle_rental_system`
6. Click **Deploy Web Service**.
