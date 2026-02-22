# Event Management System

A Laravel-based REST API for managing events and reservations with concurrent booking support.

## Features

- **Event Management**: Create, update, and manage events with capacity control
- **Reservation System**: Book events with race condition protection using database locks
- **Authentication**: OTP-based authentication using Laravel Sanctum
- **Admin Panel**: Separate API endpoints for admin operations
- **Concurrent Booking**: Handles simultaneous reservation requests safely
- **SOLID Principles**: Clean architecture with service layer and validators
- **API Documentation**: Complete Swagger/OpenAPI documentation

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
php artisan l5-swagger:generate
```

## API Documentation

The API is fully documented using Swagger/OpenAPI. After generating the documentation, you can access it at:

- **Swagger UI**: `http://localhost:8000/api/documentation`

To regenerate the documentation after making changes:

```bash
php artisan l5-swagger:generate
```

## API Endpoints

### Public (No Authentication Required)
- `POST /api/v1/authenticate` - Login/Register (sends OTP)
- `POST /api/v1/authenticate/verify` - Verify OTP and get access token
- `GET /api/v1/events` - List active events (paginated)

### Authenticated (Bearer Token Required)
- `GET /api/v1/events` - List active events (paginated)
- `GET /api/v1/events/{event}` - Get event details by UUID
- `GET /api/v1/reservs` - List current user's reservations (paginated)
- `POST /api/v1/reservs` - Create a new reservation
- `PUT /api/v1/reservs/{reserv}` - Update reservation status

### Admin (Bearer Token + Admin Role Required)
- `GET /api/v1/panel/events` - List all events (paginated)
- `GET /api/v1/panel/events/{event}` - Get event details
- `POST /api/v1/panel/events` - Create a new event
- `PUT /api/v1/panel/events/{event}` - Update an event
- `DELETE /api/v1/panel/events/{event}` - Delete an event
- `GET /api/v1/panel/reservs` - List all reservations (paginated)
- `PUT /api/v1/panel/reservs/{reserv}` - Approve or reject reservation
- `DELETE /api/v1/panel/reservs/{reserv}` - Delete a reservation

## Authentication

The API uses OTP-based authentication:

1. Send a POST request to `/api/v1/authenticate` with your email
2. Receive an OTP code (in development, check logs)
3. Send a POST request to `/api/v1/authenticate/verify` with email and OTP code
4. Receive an access token
5. Include the token in subsequent requests: `Authorization: Bearer {token}`

## Response Format

All API responses follow a consistent format:

```json
{
  "status": "success",
  "data": {
    // Response data
  }
}
```

Error responses:

```json
{
  "status": "error",
  "message": "Error message"
}
```

## Testing

```bash
php artisan test
```
