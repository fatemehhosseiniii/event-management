# Event Management System

A Laravel-based REST API for managing events and reservations with concurrent booking support.

## Features

- **Event Management**: Create, update, and manage events with capacity control
- **Reservation System**: Book events with race condition protection using database locks
- **Authentication**: OTP-based authentication using Laravel Sanctum
- **Admin Panel**: Separate API endpoints for admin operations
- **Concurrent Booking**: Handles simultaneous reservation requests safely
- **SOLID Principles**: Clean architecture with service layer and validators

## Requirements

- PHP ^8.2
- Laravel ^12.0
- MySQL/MariaDB
- Composer

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## API Endpoints

### Public
- `POST /api/v1/authenticate` - Login/Register
- `POST /api/v1/authenticate/verify` - Verify OTP
- `GET /api/v1/events` - List active events

### Authenticated
- `GET /api/v1/reservs` - List user reservations
- `POST /api/v1/reservs` - Create reservation

### Admin
- `GET /api/v1/panel/events` - Manage events
- `POST /api/v1/panel/events` - Create event
- `PUT /api/v1/panel/events/{id}` - Update event
- `DELETE /api/v1/panel/events/{id}` - Delete event

## Testing

```bash
php artisan test
```