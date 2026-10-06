**# Vehicle Rental Management System**



**## Project Description**



The Vehicle Rental Management System is a web-based application developed to simplify the process of renting vehicles such as cars and bikes.



Users can create an account, log in, browse available vehicles, search and filter vehicles, book a vehicle for selected dates, and view their booking history.



The system also provides an admin section for managing vehicles and bookings.



**---**



**## Problem Statement**



Traditional vehicle rental processes may require customers to manually contact rental providers to check vehicle availability and make bookings. This can take time and may result in booking conflicts.



The Vehicle Rental Management System provides a simple online platform where users can view vehicles, check their availability, and make bookings based on selected rental dates.



The system also helps prevent multiple users from booking the same vehicle for overlapping dates.



**---**



**## Objectives**



- Develop a simple web-based vehicle rental system.

- Provide secure user registration and login.

- Display available cars and bikes.

- Allow users to view vehicle details.

- Allow users to search and filter vehicles.

- Allow users to book vehicles for selected dates.

- Track vehicle availability.

- Prevent bookings for unavailable vehicles.

- Maintain user booking history.

- Provide an admin section for vehicle management.

- Allow the administrator to manage bookings.

- Implement date-based availability validation.



**---**



**## Target Users**



**### Customers**



Customers can:



- Register for an account.

- Login and logout.

- Browse available vehicles.

- Search and filter vehicles.

- View vehicle details.

- Check vehicle availability.

- Book vehicles.

- View their booking history.



**### Administrator**



The administrator can:



- Login to the system.

- View and manage vehicles.

- Add new vehicles.

- Edit existing vehicle information.

- Delete vehicles.

- View customer bookings.

- Manage booking information.



**---**



**## Functional Requirements**



The main functional requirements of the system are:



- User registration

- User login

- User logout

- Vehicle catalog

- Vehicle details

- Vehicle search

- Vehicle filtering

- Vehicle booking

- Date selection

- Vehicle availability checking

- Date-based availability validation

- Booking history

- Admin management

- Add vehicles

- Edit vehicles

- Delete vehicles

- Manage bookings



**---**



**## Non-Functional Requirements**



The system should provide:



- A simple and user-friendly interface

- Basic application security

- Password protection

- Input validation

- Responsive web pages

- Reliable database connectivity

- Easy navigation

- Maintainable project structure

- Proper error handling

- Reasonable application performance



**---**



**## Technology Stack**



**### Frontend**



- HTML

- CSS

- JavaScript



**### Backend**



- PHP



**### Database**



- MongoDB

- MongoDB Atlas



**### Development Tools**



- Visual Studio Code

- XAMPP

- Apache

- Git

- GitHub

- MongoDB Atlas



**---**



**## Database Design**



The application is planned to use MongoDB with the following main collections:



**### users**



Stores registered user and administrator information.



**### vehicles**



Stores information about vehicles available for rental, such as vehicle name, type, rental price, and availability.



**### bookings**



Stores vehicle booking information including the user, selected vehicle, booking dates, and booking status.



**---**



**## Main System Modules**



The project contains the following major modules:



1. User Authentication

2. Vehicle Catalog

3. Search and Filter

4. Vehicle Booking

5. Availability Tracking

6. Booking History

7. Admin Management

8. Date-Based Availability Validation



**---**



**## Advanced Feature**



The main advanced feature of this project is ****date-based availability validation****.



Before confirming a booking, the system will check whether the selected vehicle has already been booked for the requested date range.



If another booking overlaps with the selected dates, the vehicle will be considered unavailable for that period.



If there is no conflicting booking, the user will be allowed to complete the booking.



**---**



**## Individual Responsibility**



This is an individual project.



I am responsible for the complete development and maintenance of the Vehicle Rental Management System.



My responsibilities include:



- Requirement analysis

- Project structure creation

- Frontend development using HTML, CSS, and JavaScript

- Backend development using PHP

- MongoDB Atlas database design and connectivity

- User authentication implementation

- Vehicle catalog development

- Search and filtering functionality

- Vehicle booking functionality

- Date-based availability validation

- Booking history management

- Admin functionality

- Testing and debugging

- Git and GitHub version control

- Project documentation



**---**



**## GitHub Version Control**



Git and GitHub are used to maintain the development history of this project.



Meaningful commits are created throughout the development process so that project progress can be tracked week by week.



The repository contains the project source code, documentation, configuration files, API files, middleware files, and other required project resources.



**---**



**# Weekly Project Progress**



**## Week 1 - Project Initiation**



**### Work Completed**



- Selected the Vehicle Rental Management System project.

- Defined the project problem statement.

- Defined project objectives.

- Identified target users.

- Identified functional requirements.

- Identified non-functional requirements.

- Selected the technology stack.

- Defined individual project responsibilities.

- Created the initial project folder structure.

- Initialized the Git repository.

- Created and connected the GitHub repository.

- Created the initial project files.

- Created project documentation using README.md.

- Created initial Git commits.

- Pushed the project to GitHub.



**### Week 1 Status**



****Week 1 project initiation completed successfully.****



**---**



**## Week 2 - PHP Foundation and Server Setup**



**### Work Completed**



- Installed the XAMPP development environment.

- Configured the Apache web server.

- Configured PHP for local development.

- Successfully executed PHP through Apache.

- Moved the project into the XAMPP `htdocs` directory.

- Configured a single project folder for VS Code, Git, GitHub, XAMPP, and PHP.

- Created the basic PHP project architecture.

- Created an application configuration file.

- Created reusable header and footer components.

- Created the CSS folder and stylesheet.

- Created the JavaScript folder and script file.

- Created the basic admin folder structure.

- Successfully tested the application using localhost.

- Committed the Week 2 project structure to Git.

- Pushed Week 2 development to GitHub.



**### Local Application**



The application is currently executed locally using:



`http://localhost/VehicleRentalSystem/`



**### PHP Server Test**



The application successfully displays:



`PHP Server Status: Running Successfully`



This confirms that Apache and PHP are processing the Vehicle Rental Management System correctly.



**### Week 2 Project Structure**



```text

VehicleRentalSystem/

│

├── admin/

│   └── index.php

│

├── assets/

│   ├── css/

│   │   └── style.css

│   ├── images/

│   └── js/

│       └── script.js

│

├── config/

│   └── app.php

│

├── database/

│

├── includes/

│   ├── header.php

│   └── footer.php

│

├── index.php

├── login.php

├── register.php

├── logout.php

└── README.md

```



**### Week 2 Status**



****Week 2 PHP server setup and basic project architecture completed successfully.****



**---**



**## Week 3 - API Routes and Request/Response Handling**



**### Work Completed**



- Created the basic API folder structure.

- Created the main API route.

- Created the vehicle API route.

- Implemented PHP request handling.

- Implemented JSON responses.

- Implemented GET request handling.

- Created temporary vehicle data for API testing.

- Successfully tested API endpoints using the browser.

- Added API endpoint documentation.



**### Week 3 API Structure**



```text

api/

│

├── index.php

│

└── vehicles/

    └── index.php

```



**### Available API Endpoints**



| Method | Endpoint | Description |

|---|---|---|

| GET | `/api/` | Checks whether the Vehicle Rental Management System API is running |

| GET | `/api/vehicles/` | Returns the current sample vehicle list |



**### Main API Test**



Local endpoint:



`http://localhost/VehicleRentalSystem/api/`



Example response:



```json

{

    "success": true,

    "message": "Vehicle Rental Management System API is working"

}

```



**### Vehicle API Test**



Local endpoint:



`http://localhost/VehicleRentalSystem/api/vehicles/`



The vehicle endpoint currently returns temporary sample vehicle information for:



- Hyundai Creta

- Honda City

- Royal Enfield Classic 350



These records are currently used only for API testing. Database-driven vehicle information will be implemented during database integration.



**### Request and Response Handling**



PHP uses the incoming HTTP request method to determine what operation should be performed.



The vehicle API checks:



```php

$_SERVER["REQUEST_METHOD"]

```



When the request method is `GET`, the API returns vehicle information in JSON format.



If an unsupported HTTP method is sent, the API returns a method-not-allowed response.



**### HTTP Methods**



The application will use the following HTTP methods:



- ****GET**** - Retrieve information

- ****POST**** - Create new information

- ****PUT**** - Update existing information

- ****DELETE**** - Delete information



Currently, the GET method has been implemented.



POST, PUT, and DELETE operations will be implemented during the CRUD development phase.



**### Week 3 Testing**



The following API endpoints were tested successfully:



- `/api/`

- `/api/vehicles/`



Both endpoints returned valid JSON responses through the local Apache/PHP server.



**### Week 3 Status**



****Week 3 basic API routing, GET request handling, and JSON response handling completed successfully.****



**---**



**## Week 4 - Middleware and Error Handling**



**### Work Completed**



- Created a middleware folder.

- Created custom request logging functionality.

- Created JSON response middleware.

- Created a reusable API response utility.

- Added API error handling.

- Added a custom 404 API response.

- Improved API route organization.

- Used Apache to serve static CSS, JavaScript, and image files.

- Updated the existing API routes to use reusable middleware and response functions.

- Tested all implemented Week 4 API functionality successfully.



**### Middleware Structure**



The following middleware files were created:



```text

middleware/

├── requestLogger.php

└── jsonMiddleware.php

```



**### Request Logging**



Custom request logging was implemented in:



```text

middleware/requestLogger.php

```



The request logger records:



- Request date and time

- HTTP request method

- Requested URI



The request information is stored in:



```text

logs/requests.log

```



This provides a simple record of requests made to the application API.



**### JSON Response Middleware**



JSON response handling was implemented in:



```text

middleware/jsonMiddleware.php

```



This middleware sets the API response content type to JSON.



**### Reusable Response Utility**



A reusable response utility was created in:



```text

utils/response.php

```



This provides a consistent JSON response structure throughout the API.



Example successful response:



```json

{

    "success": true,

    "message": "Vehicle Rental Management System API is working"

}

```



**### Error Handling**



A custom API error response was created using:



```text

api/404.php

```



Example error response:



```json

{

    "success": false,

    "message": "API endpoint not found"

}

```



The error handler returns an HTTP `404` status code.



**### Vehicle API Update**



The existing vehicle API was updated to use the new middleware and reusable response utility.



Endpoint:



`http://localhost/VehicleRentalSystem/api/vehicles/`



Example response:



```json

{

    "success": true,

    "message": "Vehicles retrieved successfully",

    "data": [

        {

            "id": 1,

            "name": "Hyundai Creta",

            "type": "Car",

            "pricePerDay": 2500,

            "status": "Available"

        },

        {

            "id": 2,

            "name": "Honda City",

            "type": "Car",

            "pricePerDay": 2200,

            "status": "Available"

        },

        {

            "id": 3,

            "name": "Royal Enfield Classic 350",

            "type": "Bike",

            "pricePerDay": 1200,

            "status": "Available"

        }

    ]

}

```



**### Static File Handling**



Apache serves the static frontend files used by the application.



The current static file structure is:



```text

assets/

├── css/

│   └── style.css

├── images/

└── js/

    └── script.js

```



**### Route Organization**



API routes are maintained separately from the normal frontend PHP pages.



The current API structure is:



```text

api/

├── index.php

├── 404.php

└── vehicles/

    └── index.php

```



This structure makes the project easier to maintain as additional API routes are added.



**### Week 4 Testing**



The following API endpoints were tested successfully:



- `/api/`

- `/api/vehicles/`

- `/api/404.php`



The main API returned:



```json

{

    "success": true,

    "message": "Vehicle Rental Management System API is working"

}

```



The vehicle API successfully returned the sample vehicle information.



The 404 API returned:



```json

{

    "success": false,

    "message": "API endpoint not found"

}

```



**### Week 4 Status**



****Week 4 middleware-style request handling, request logging, reusable JSON responses, static file handling, error handling, and route organization completed successfully.****



**---**



**

---

## Week 5 - Database Integration

### Work Completed

- Enabled the MongoDB PHP extension in XAMPP/PHP.
- Installed Composer locally for the project.
- Installed the official MongoDB PHP library using Composer.
- Connected the PHP application to MongoDB Atlas.
- Created the `vehicle_rental_system` database.
- Created the `users`, `vehicles`, and `bookings` collections.
- Added initial vehicle records to MongoDB Atlas.
- Created a reusable database connection file.
- Moved sensitive database credentials to a local configuration file.
- Updated the vehicle API to retrieve real vehicle information from MongoDB instead of temporary sample data.
- Successfully tested the MongoDB Atlas connection and vehicle retrieval.

### Database Collections

The MongoDB Atlas database currently contains the following main collections:

- `users`
- `vehicles`
- `bookings`

### Vehicle Data

The initial vehicle records stored in MongoDB include:

- Hyundai Creta
- Honda City
- Royal Enfield Classic 350

### Security of Database Credentials

Database credentials are stored in:

```text
config/database.local.php
```

This file is excluded from Git using `.gitignore` and must not be uploaded to GitHub.

### Week 5 Status

**Week 5 MongoDB Atlas integration and database-driven vehicle retrieval completed successfully.**

---

## Week 6 - CRUD and Query Operations

### Work Completed

- Implemented Create operation for vehicles.
- Implemented Read operation for vehicles.
- Implemented Update operation for vehicles.
- Implemented Delete operation for vehicles.
- Connected all CRUD operations to MongoDB Atlas.
- Used MongoDB ObjectId values to identify vehicle records.
- Added basic request validation.
- Added suitable HTTP status codes and JSON responses.
- Tested all CRUD operations successfully.

### Vehicle CRUD Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/vehicles/` | Retrieve all vehicles |
| POST | `/api/vehicles/create.php` | Create a new vehicle |
| PUT | `/api/vehicles/update.php` | Update an existing vehicle |
| DELETE | `/api/vehicles/delete.php` | Delete an existing vehicle |

### CRUD Testing

A temporary vehicle was created, retrieved, updated, and deleted successfully. After testing, the temporary record was removed and the original vehicle data remained intact.

### Week 6 Status

**Week 6 vehicle CRUD operations and MongoDB query handling completed successfully.**

---

## Week 7 - Authentication and Protected Routes

### Work Completed

- Created user registration API.
- Added email validation.
- Added minimum password-length validation.
- Used PHP `password_hash()` to securely hash passwords before storing them.
- Used PHP `password_verify()` during login.
- Created user login API.
- Implemented PHP session-based authentication.
- Created protected profile API.
- Created logout functionality.
- Added authentication middleware.
- Protected vehicle Create, Update, and Delete operations.
- Tested access with and without a valid login session.

### Authentication Endpoints

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/auth/register.php` | Register a new user |
| POST | `/api/auth/login.php` | Login to the system |
| GET | `/api/auth/profile.php` | Access the logged-in user's profile |
| GET | `/api/auth/logout.php` | Logout and destroy the session |

### Password Security

Passwords are never stored as plain text in MongoDB. PHP password hashing is used during registration, and password verification is performed during login.

### Protected Routes

The following vehicle operations require a valid login session:

- Create vehicle
- Update vehicle
- Delete vehicle

Unauthenticated requests receive an HTTP `401 Unauthorized` response.

### Week 7 Status

**Week 7 user authentication, secure password handling, PHP sessions, logout, and protected routes completed successfully.**

---

## Week 8 - Security and Quality

### Work Completed

- Added stronger input validation.
- Added malformed JSON validation.
- Added positive-number validation for vehicle rental prices.
- Added allowed vehicle type validation.
- Added allowed vehicle status validation.
- Added MongoDB ObjectId format validation.
- Added CORS middleware.
- Configured allowed local development origins.
- Added session ID regeneration after successful login.
- Improved database error handling.
- Prevented internal database connection details from being returned to users.
- Continued request logging for debugging and monitoring.
- Maintained consistent JSON API responses.
- Added the local cookie test file to `.gitignore`.
- Retested database retrieval, validation, login, sessions, and protected profile access.

### Input Validation

Vehicle input is checked before database operations are performed.

Current vehicle rules include:

- Vehicle name is required.
- Vehicle type is required.
- Vehicle type must be `Car` or `Bike`.
- Rental price must be a positive number.
- Vehicle status must be `Available` or `Unavailable`.
- Vehicle IDs must use a valid MongoDB ObjectId format.

Registration and login input is also validated before authentication operations are performed.

### CORS

CORS handling is implemented through:

```text
middleware/corsMiddleware.php
```

The middleware supports the HTTP methods used by the application and allows configured local development origins.

### Session Security

After a successful login, the PHP session ID is regenerated using:

```php
session_regenerate_id(true);
```

This improves session security by reducing session fixation risk.

### Error Handling

Internal database error information is logged for debugging while API users receive a generic error message. This prevents sensitive connection details from being exposed through API responses.

### Security Checklist

- Password hashing: Completed
- Password verification: Completed
- Session authentication: Completed
- Protected routes: Completed
- Input validation: Completed
- Invalid JSON handling: Completed
- CORS handling: Completed
- Database credential protection: Completed
- Safer error responses: Completed
- Request logging: Completed
- Git ignore rules for local secrets/session test files: Completed

### Week 8 Testing

The following tests were completed successfully:

- Retrieved all vehicles from MongoDB Atlas.
- Confirmed the original vehicle records remained available.
- Confirmed negative rental prices are rejected.
- Confirmed user login succeeds with valid credentials.
- Confirmed session cookies preserve authentication.
- Confirmed the protected profile endpoint works for an authenticated user.
- Confirmed protected vehicle operations reject unauthenticated requests.

### Week 8 Status

**Week 8 security improvements, validation, CORS, safer error handling, session security, logging, and quality checks completed successfully.**

---

## Current Project Structure

```text
VehicleRentalSystem/
│
├── admin/
│   └── index.php
│
├── api/
│   ├── auth/
│   │   ├── register.php
│   │   ├── login.php
│   │   ├── profile.php
│   │   └── logout.php
│   │
│   ├── vehicles/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── update.php
│   │   └── delete.php
│   │
│   ├── index.php
│   └── 404.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   └── js/
│       └── script.js
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── database.local.php
│
├── database/
│
├── includes/
│   ├── header.php
│   └── footer.php
│
├── logs/
│   └── requests.log
│
├── middleware/
│   ├── authMiddleware.php
│   ├── corsMiddleware.php
│   ├── jsonMiddleware.php
│   └── requestLogger.php
│
├── utils/
│   └── response.php
│
├── vendor/
├── .gitignore
├── composer.json
├── composer.lock
├── index.php
├── login.php
├── register.php
├── logout.php
└── README.md
```

---

## Current API Summary

| Method | Endpoint | Purpose | Authentication |
|---|---|---|---|
| GET | `/api/` | API status | No |
| GET | `/api/vehicles/` | Retrieve vehicles | No |
| POST | `/api/vehicles/create.php` | Create vehicle | Yes |
| PUT | `/api/vehicles/update.php` | Update vehicle | Yes |
| DELETE | `/api/vehicles/delete.php` | Delete vehicle | Yes |
| POST | `/api/auth/register.php` | Register user | No |
| POST | `/api/auth/login.php` | Login user | No |
| GET | `/api/auth/profile.php` | View authenticated profile | Yes |
| GET | `/api/auth/logout.php` | Logout user | Session based |

---

## Current Project Status

- Week 1 - Project Initiation: **Completed**
- Week 2 - PHP Foundation and Server Setup: **Completed**
- Week 3 - API Routes and Request/Response Handling: **Completed**
- Week 4 - Middleware and Error Handling: **Completed**
- Week 5 - Database Integration: **Completed**
- Week 6 - CRUD and Query Operations: **Completed**
- Week 7 - Authentication: **Completed**
- Week 8 - Security and Quality: **Completed**
- Week 9 - Full Application Integration: **Not Started**
- Week 10 - Deployment and Final Submission: **Not Started**


---

## Week 9 - Full Application Integration

Completed:
- Frontend login and registration pages
- MongoDB vehicle catalog
- Search and filtering
- User booking with start/end dates
- Date-overlap availability validation
- Booking history and cancellation
- Admin-only vehicle create/update/delete
- Admin booking list
- Responsive frontend styling

### Admin Setup
Registration creates normal users only. In MongoDB Atlas, change one trusted test user's `role` field from `user` to `admin`, then log out and log in again.
