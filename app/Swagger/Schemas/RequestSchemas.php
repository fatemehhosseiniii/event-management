<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "LoginRequest",
    type: "object",
    required: ["email"],
    properties: [
        new OA\Property(property: "email", type: "string", format: "email", maxLength: 250, example: "admin@admin.com", description: "User email address")
    ]
)]

#[OA\Schema(
    schema: "VerifyRequest",
    type: "object",
    required: ["email", "code"],
    properties: [
        new OA\Property(property: "email", type: "string", format: "email", maxLength: 250, example: "admin@admin.com", description: "User email address"),
        new OA\Property(property: "code", type: "string", pattern: "^[0-9]{6}$", example: "123456", description: "6-digit OTP code")
    ]
)]

#[OA\Schema(
    schema: "ReservRequest",
    type: "object",
    required: ["event_code"],
    properties: [
        new OA\Property(property: "event_code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000", description: "Event UUID")
    ]
)]

#[OA\Schema(
    schema: "EventRequest",
    type: "object",
    required: ["title", "capacity"],
    properties: [
        new OA\Property(property: "title", type: "string", maxLength: 80, example: "Event Title", description: "Event title (unique)"),
        new OA\Property(property: "description", type: "string", maxLength: 250, nullable: true, example: "Event description"),
        new OA\Property(property: "capacity", type: "integer", minimum: 1, example: 100, description: "Event capacity"),
        new OA\Property(property: "start_date", type: "string", format: "date-time", nullable: true, example: "2024-01-01 10:00", description: "Event start date (Y-m-d H:i)"),
        new OA\Property(property: "end_date", type: "string", format: "date-time", nullable: true, example: "2024-01-01 18:00", description: "Event end date (Y-m-d H:i, must be after start_date)")
    ]
)]

#[OA\Schema(
    schema: "ReservUpdateRequest",
    type: "object",
    required: ["is_confirmed"],
    properties: [
        new OA\Property(
            property: "is_confirmed",
            type: "string",
            enum: ["1", "3"],
            example: "confirmed",
            description: "Reservation status: confirmed=1 or rejected_confirmed=3"
        )
    ]
)]

#[OA\Schema(
    schema: "LoginResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(property: "email", type: "string", format: "email", example: "admin@admin.com")
            ]
        )
    ]
)]
#[OA\Schema(
    schema: "VerifyResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(property: "access_token", type: "string", example: "1|1234567890")
            ]
        )
    ]
)]
class RequestSchemas
{
}


