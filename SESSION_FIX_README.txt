VS TRAVELLS - CUSTOMER/ADMIN SESSION FIX

This build fixes the confirmed refresh/logout conflict.

Changes:
1. Customer portal uses its own PHP session cookie: VS_CUSTOMER.
2. Admin portal uses its own PHP session cookie: VS_ADMIN.
3. Opening Customer Login/Register no longer destroys an Admin session.
4. Opening Admin Login no longer destroys a Customer session.
5. Customer logout clears only the customer session.
6. Admin logout clears only the admin session.
7. Customer APIs read the customer session; admin APIs read the admin session.
8. Refreshing an authenticated customer/admin page should preserve that portal's login.

Important runtime test on XAMPP:
- Login as customer, refresh dashboard several times.
- Login as admin in another tab, refresh admin several times.
- Switch between both tabs; neither login should destroy the other.
- Customer logout should not log out admin; admin logout should not log out customer.
