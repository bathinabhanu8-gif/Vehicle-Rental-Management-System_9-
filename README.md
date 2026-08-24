# Vehicle Rental Management System

## Project Description

The Vehicle Rental Management System is a web-based application developed to simplify the process of renting vehicles such as cars and bikes.

Users can create an account, log in, browse available vehicles, search and filter vehicles, book a vehicle for selected dates, and view their booking history.

The system also provides an admin section for managing vehicles and bookings.

## Problem Statement

Traditional vehicle rental processes may require customers to manually contact rental providers to check vehicle availability and make bookings. This can take time and may result in booking conflicts.

The Vehicle Rental Management System provides a simple online platform where users can view vehicles, check their availability, and make bookings based on selected rental dates.

The system also helps prevent multiple users from booking the same vehicle for overlapping dates.

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

## Functional Requirements

The main functional requirements of the system are:

* User registration
* User login
* User logout
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

### Development Tools

* Visual Studio Code
* Git
* GitHub
* XAMPP / Apache
* MongoDB Atlas

## Database Design

The application is planned to use MongoDB with the following main collections:

### users

Stores registered user and administrator information.

### vehicles

Stores information about vehicles available for rental, such as vehicle name, type, price, and availability.

### bookings

Stores vehicle booking information, including the user, selected vehicle, booking dates, and booking status.

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

## Advanced Feature

The main advanced feature of this project is **date-based availability validation**.

Before confirming a booking, the system will check whether the selected vehicle has already been booked for the requested date range.

If another booking overlaps with the selected dates, the vehicle will be considered unavailable for that period.

If there is no conflicting booking, the user will be allowed to complete the booking.

## Individual Responsibility

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

## GitHub Version Control

Git and GitHub are used to maintain the development history of this project.

Meaningful commits will be created throughout the development process so that the progress of the project can be tracked week by week.

The repository will contain the project source code, documentation, database-related files, and other required project resources.

## Current Project Structure

```text
VehicleRentalSystem/
│
├── admin/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── config/
├── database/
├── includes/
│
├── index.php
├── login.php
├── register.php
├── logout.php
└── README.md
```

## Project Status

### Week 1 — Project Initiation

* Project selected: Vehicle Rental Management System
* Problem statement defined
* Project objectives defined
* Target users identified
* Functional requirements identified
* Non-functional requirements identified
* Technology stack selected
* Individual responsibilities defined
* Initial project structure created
* Git repository initialized
* GitHub repository created and connected
* Initial project files committed
* README documentation created

**Status: Week 1 setup completed.**