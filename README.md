# Vehicle Rental Management System

## Project Description

The Vehicle Rental Management System is a web-based application developed to simplify the process of renting vehicles such as cars and bikes.

Users can create an account, log in, browse available vehicles, search and filter vehicles, book a vehicle for selected dates, and view their booking history.

The system also provides an admin section for managing vehicles and bookings.

---

## Problem Statement

Traditional vehicle rental processes may require customers to manually contact rental providers to check vehicle availability and make bookings. This can take time and may result in booking conflicts.

The Vehicle Rental Management System provides a simple online platform where users can view vehicles, check their availability, and make bookings based on selected rental dates.

The system will also prevent multiple users from booking the same vehicle for overlapping dates.

---

## Objectives

* Develop a simple web-based vehicle rental system.
* Provide secure user registration and login.
* Display available cars and bikes.
* Allow users to view vehicle details.
* Allow users to search and filter vehicles.
* Allow users to book vehicles for selected dates.
* Track vehicle availability.
* Prevent bookings for unavailable vehicles.
* Maintain user booking history.
* Provide an admin section for vehicle management.
* Allow the administrator to manage bookings.
* Implement date-based availability validation.

---

## Target Users

### Customers

Customers can:

* Register for an account.
* Login and logout.
* Browse available vehicles.
* Search and filter vehicles.
* View vehicle details.
* Check vehicle availability.
* Book vehicles.
* View their booking history.

### Administrator

The administrator can:

* Login to the system.
* View and manage vehicles.
* Add new vehicles.
* Edit existing vehicle information.
* Delete vehicles.
* View customer bookings.
* Manage booking information.

---

## Functional Requirements

The main functional requirements of the system are:

* User registration
* User login and logout
* Vehicle catalog
* Vehicle details
* Vehicle search
* Vehicle filtering
* Vehicle booking
* Date selection
* Vehicle availability checking
* Date-based availability validation
* Booking history
* Admin management
* Add vehicles
* Edit vehicles
* Delete vehicles
* Manage bookings

---

## Non-Functional Requirements

The system should provide:

* A simple and user-friendly interface.
* Basic application security.
* Password protection.
* Input validation.
* Responsive web pages.
* Reliable database connectivity.
* Easy navigation.
* Maintainable project structure.
* Proper error handling.
* Reasonable application performance.

---

## Technology Stack

### Frontend

* HTML
* CSS
* JavaScript

### Backend

* PHP

### Database

* MongoDB
* MongoDB Atlas

### Development Environment and Tools

* Visual Studio Code
* XAMPP
* Apache
* Git
* GitHub
* MongoDB Atlas

---

## Database Design

The application is planned to use MongoDB with the following main collections:

### users

Stores registered user and administrator information.

### vehicles

Stores vehicle information such as vehicle name, type, rental price, availability, and other vehicle details.

### bookings

Stores booking information including the user, selected vehicle, start date, end date, and booking status.

---

## Main System Modules

The project will contain the following major modules:

1. User Authentication
2. Vehicle Catalog
3. Search and Filter
4. Vehicle Booking
5. Availability Tracking
6. Booking History
7. Admin Management
8. Date-Based Availability Validation

---

## Advanced Feature

The main advanced feature of this project is **date-based availability validation**.

Before confirming a booking, the system will check whether the selected vehicle has already been booked for the requested date range.

If another booking overlaps with the selected dates, the vehicle will be considered unavailable for that period.

If there is no conflicting booking, the user will be allowed to complete the booking.

---

# Weekly Project Progress

## Week 1 - Project Initiation

### Work Completed

* Selected the Vehicle Rental Management System project.
* Defined the project problem statement.
* Defined project objectives.
* Identified target users.
* Identified functional requirements.
* Identified non-functional requirements.
* Selected the technology stack.
* Defined individual project responsibilities.
* Created the initial project folder structure.
* Initialized the Git repository.
* Created and connected the GitHub repository.
* Created the initial project files.
* Created project documentation using README.md.
* Created initial Git commits.
* Pushed the project to GitHub.

### Individual Responsibility

This is an individual project.

I am responsible for the complete development and maintenance of the Vehicle Rental Management System.

My responsibilities include:

* Requirement analysis
* Project structure creation
* Frontend development using HTML, CSS, and JavaScript
* Backend development using PHP
* MongoDB Atlas database design and connectivity
* User authentication implementation
* Vehicle catalog development
* Search and filtering functionality
* Vehicle booking functionality
* Date-based availability validation
* Booking history management
* Admin functionality
* Testing and debugging
* Git and GitHub version control
* Project documentation

### Week 1 Status

**Week 1 project initiation completed successfully.**

---

## Week 2 - PHP Foundation and Server Setup

### Work Completed

* Installed XAMPP development environment.
* Configured the Apache web server.
* Configured PHP for local development.
* Successfully executed PHP through Apache.
* Moved the project into the XAMPP `htdocs` directory.
* Configured a single project folder for VS Code, Git, GitHub, XAMPP, and PHP.
* Created the basic PHP project architecture.
* Created a reusable application configuration file.
* Created reusable header and footer components.
* Created the CSS folder and stylesheet.
* Created the JavaScript folder and script file.
* Created the basic admin folder structure.
* Successfully tested the application using localhost.
* Committed the Week 2 project structure to Git.
* Pushed Week 2 development to GitHub.

### Local Application

The application is currently executed locally using:

`http://localhost/VehicleRentalSystem/`

### PHP Server Test

The application successfully displays:

`PHP Server Status: Running Successfully`

This confirms that Apache and PHP are processing the Vehicle Rental Management System correctly.

### Week 2 Project Structure

```text
VehicleRentalSystem/
│
├── admin/
│   └── index.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   └── js/
│       └── script.js
│
├── config/
│   └── app.php
│
├── database/
│
├── includes/
│   ├── header.php
│   └── footer.php
│
├── index.php
├── login.php
├── register.php
├── logout.php
└── README.md
```

### Week 2 Status

**Week 2 PHP server setup and basic project architecture completed successfully.**

---

## Week 3 - API Routes and Request/Response Handling

### Work Completed

* Created the basic API folder structure.
* Created the main API route.
* Created the vehicle API route.
* Implemented PHP request handling.
* Implemented JSON responses.
* Implemented GET request handling.
* Created temporary vehicle data for API testing.
* Successfully tested API endpoints using the browser.
* Added API endpoint documentation.

### Week 3 API Structure

```text
api/
│
├── index.php
│
└── vehicles/
    └── index.php
```

### Available API Endpoints

| Method | Endpoint         | Description                                                        |
| ------ | ---------------- | ------------------------------------------------------------------ |
| GET    | `/api/`          | Checks whether the Vehicle Rental Management System API is running |
| GET    | `/api/vehicles/` | Returns the current sample vehicle list                            |

### Main API Test

Local endpoint:

`http://localhost/VehicleRentalSystem/api/`

Example response:

```json
{
    "success": true,
    "message": "Vehicle Rental Management System API is working"
}
```

### Vehicle API Test

Local endpoint:

`http://localhost/VehicleRentalSystem/api/vehicles/`

The vehicle endpoint currently returns temporary sample vehicle information for:

* Hyundai Creta
* Honda City
* Royal Enfield Classic 350

These records are currently used only for testing the API. Database-driven vehicle records will be implemented during the database integration and CRUD stages.

### Request and Response Handling

PHP uses the incoming HTTP request method to determine what operation should be performed.

The current vehicle endpoint checks:

```php
$_SERVER["REQUEST_METHOD"]
```

When the request method is `GET`, the API returns vehicle information as JSON.

If an unsupported HTTP method is sent to the current endpoint, the API returns a method-not-allowed response.

### HTTP Methods

The application will use the following HTTP methods:

* **GET** - Retrieve information
* **POST** - Create new information
* **PUT** - Update existing information
* **DELETE** - Delete information

The GET method has currently been implemented for retrieving vehicle information.

POST, PUT, and DELETE operations will be implemented during the CRUD development phase.

### API Response Format

The API uses JSON for responses.

A successful response follows a structure similar to:

```json
{
    "success": true,
    "data": []
}
```

An unsuccessful response can follow a structure similar to:

```json
{
    "success": false,
    "message": "Method not allowed"
}
```

### Week 3 Testing

The following endpoints were tested successfully:

* `/api/`
* `/api/vehicles/`

Both endpoints returned valid JSON responses through the local Apache/PHP server.

### Week 3 Status

**Week 3 basic API routing, GET request handling, and JSON response handling completed successfully.**

---

## GitHub Version Control

Git and GitHub are being used to maintain the development history of the project.

Meaningful commits are created throughout development so that project progress can be monitored week by week.

The repository contains the source code, project documentation, configuration files, and other required project resources.

---

## Current Project Status

* Week 1 - Project Initiation: **Completed**
* Week 2 - PHP Foundation and Server Setup: **Completed**
* Week 3 - API Routes and Request/Response Handling: **Completed**
* Week 4 - Middleware and Error Handling: **Not Started**
* Week 5 - Database Integration: **Not Started**
* Week 6 - CRUD and Query Operations: **Not Started**
* Week 7 - Authentication: **Not Started**
* Week 8 - Security and Quality: **Not Started**
* Week 9 - Full Application Integration: **Not Started**
* Week 10 - Deployment and Final Submission: **Not Started**