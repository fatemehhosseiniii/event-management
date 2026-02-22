<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "EventBasicInfo",
    type: "object",
    properties: [
        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
        new OA\Property(property: "title", type: "string", example: "Event Title"),
        new OA\Property(property: "active_reserv", type: "boolean", example: true)
    ]
)]

#[OA\Schema(
    schema: "EventPanelInfo",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
        new OA\Property(property: "title", type: "string", example: "Event Title"),
        new OA\Property(property: "active_reserv", type: "boolean", example: true)
    ]
)]

#[OA\Schema(
    schema: "UserInfo",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "User Name"),
        new OA\Property(property: "email", type: "string", format: "email", example: "admin@admin.com")
    ]
)]

#[OA\Schema(
    schema: "Reservation",
    type: "object",
    properties: [
        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
        new OA\Property(
            property: "event",
            type: "object",
            properties: [
                new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                new OA\Property(property: "title", type: "string", example: "Event Title"),
                new OA\Property(property: "active_reserv", type: "boolean", example: true)
            ]
        ),
        new OA\Property(property: "confirmed", type: "string", example: "Confirmed"),
        new OA\Property(property: "confirmed_key", type: "string", example: "confirmed"),
        new OA\Property(property: "created_at", type: "string", format: "date-time")
    ]
)]

#[OA\Schema(
    schema: "ReservationPanel",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
        new OA\Property(
            property: "event",
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                new OA\Property(property: "title", type: "string", example: "Event Title"),
                new OA\Property(property: "active_reserv", type: "boolean", example: true)
            ]
        ),
        new OA\Property(
            property: "user",
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "User Name"),
                new OA\Property(property: "email", type: "string", format: "email", example: "admin@admin.com")
            ]
        ),
        new OA\Property(property: "confirmed", type: "string", example: "Confirmed"),
        new OA\Property(property: "confirmed_key", type: "string", example: "confirmed"),
        new OA\Property(property: "created_at", type: "string", format: "date-time")
    ]
)]

#[OA\Schema(
    schema: "ReservationListResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(
                    property: "reservs",
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                            new OA\Property(
                                property: "event",
                                type: "object",
                                properties: [
                                    new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                                    new OA\Property(property: "title", type: "string", example: "Event Title"),
                                    new OA\Property(property: "active_reserv", type: "boolean", example: true)
                                ]
                            ),
                            new OA\Property(property: "confirmed", type: "string", example: "Confirmed"),
                            new OA\Property(property: "confirmed_key", type: "string", example: "confirmed"),
                            new OA\Property(property: "created_at", type: "string", format: "date-time")
                        ]
                    )
                ),
                new OA\Property(property: "current_page", type: "integer", example: 1),
                new OA\Property(property: "per_page", type: "integer", example: 15),
                new OA\Property(property: "total", type: "integer", example: 100),
                new OA\Property(property: "last_page", type: "integer", example: 7)
            ]
        )
    ]
)]

#[OA\Schema(
    schema: "ReservationResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(
                    property: "reserv",
                    type: "object",
                    properties: [
                        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                        new OA\Property(
                            property: "event",
                            type: "object",
                            properties: [
                                new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                                new OA\Property(property: "title", type: "string", example: "Event Title"),
                                new OA\Property(property: "active_reserv", type: "boolean", example: true)
                            ]
                        ),
                        new OA\Property(property: "confirmed", type: "string", example: "Confirmed"),
                        new OA\Property(property: "confirmed_key", type: "string", example: "confirmed"),
                        new OA\Property(property: "created_at", type: "string", format: "date-time")
                    ]
                )
            ]
        )
    ]
)]

#[OA\Schema(
    schema: "ReservationPanelListResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(
                    property: "reservs",
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 10),
                            new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                            new OA\Property(
                                property: "event",
                                type: "object",
                                properties: [
                                    new OA\Property(property: "id", type: "integer", nullable: true, example: 8),
                                    new OA\Property(property: "code", type: "string", format: "uuid", nullable: true, example: "550e8400-0000-0000-0000-000000000000"),
                                    new OA\Property(property: "title", type: "string", nullable: true, example: "Event Title"),
                                    new OA\Property(property: "active_reserv", type: "boolean", nullable: true, example: true),
                                    new OA\Property(property: "is_deleted", type: "boolean", nullable: true, example: true)
                                ]
                            ),
                            new OA\Property(
                                property: "user",
                                type: "object",
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "Admin Name"),
                                    new OA\Property(property: "email", type: "string", format: "email", example: "admin@admin.com")
                                ]
                            ),
                            new OA\Property(property: "confirmed", type: "string", example: "Confirmed"),
                            new OA\Property(property: "confirmed_key", type: "string", example: "confirmed"),
                            new OA\Property(property: "created_at", type: "string", format: "date-time")
                        ]
                    )
                ),
                new OA\Property(
                    property: "page_data",
                    type: "object",
                    properties: [
                        new OA\Property(property: "current_page", type: "integer", example: 1),
                        new OA\Property(property: "per_page", type: "integer", example: 10),
                        new OA\Property(property: "total", type: "integer", example: 10),
                        new OA\Property(property: "last_page", type: "integer", example: 1),
                        new OA\Property(property: "from", type: "integer", nullable: true, example: 1),
                        new OA\Property(property: "to", type: "integer", nullable: true, example: 10)
                    ]
                )
            ]
        )
    ]
)]


#[OA\Schema(
    schema: "ReservationPanelResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                new OA\Property(
                    property: "event",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                        new OA\Property(property: "title", type: "string", example: "Event Title"),
                        new OA\Property(property: "active_reserv", type: "boolean", example: true)
                    ]
                ),
                new OA\Property(
                    property: "user",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "User Name"),
                        new OA\Property(property: "email", type: "string", format: "email", example: "admin@admin.com")
                    ]
                ),
                new OA\Property(property: "confirmed", type: "string", example: "Confirmed"),
                new OA\Property(property: "confirmed_key", type: "string", example: "confirmed"),
                new OA\Property(property: "created_at", type: "string", format: "date-time")
            ]
        )
    ]
)]
class ReservationSchemas
{
}


