VS TRAVELLS - COMPLETE UPGRADE

1. Keep your existing C:\xampp\htdocs\VehicleRentalSystem folder.
2. Copy EVERYTHING inside this package into that folder.
3. Choose "Replace the files in the destination" when Windows asks.
4. Do NOT delete config/database.local.php or vendor from your existing project.
5. Restart Apache.
6. Open http://localhost/VehicleRentalSystem/

NEW FLOW
Customer: Register/Login -> Customer Dashboard -> Browse Vehicles -> Booking Request (Pending) -> Admin Approves -> Demo Payment -> Confirmed.
Admin: Separate Admin Dashboard -> Revenue -> Pending Requests -> Approve/Reject -> All Bookings -> Vehicles -> Customer Profiles.

PAYMENT IS DEMO ONLY. No real payment gateway or real money is used.
Revenue is calculated from Confirmed bookings after demo payment.

IMPORTANT: Existing old Confirmed bookings will still appear as Confirmed and may count in revenue because they already exist in MongoDB. You may remove old test bookings in Atlas if you want a clean demo.
