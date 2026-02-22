<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "EventBasic",
    type: "object",
    properties: [
        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
        new OA\Property(property: "title", type: "string", example: "Event Title"),
        new OA\Property(property: "free_capacity", type: "integer", example: 50),
        new OA\Property(property: "created_at", type: "string", format: "date-time")
    ]
)]

#[OA\Schema(
    schema: "EventDetail",
    type: "object",
    properties: [
        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
        new OA\Property(property: "title", type: "string", example: "Event Title"),
        new OA\Property(property: "description", type: "string", example: "Event description"),
        new OA\Property(property: "free_capacity", type: "integer", example: 50),
        new OA\Property(property: "created_at", type: "string", format: "date-time")
    ]
)]

#[OA\Schema(
    schema: "EventPanel",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "title", type: "string", example: "Event Title"),
        new OA\Property(property: "capacity", type: "integer", example: 100),
        new OA\Property(property: "free_capacity", type: "integer", example: 50),
        new OA\Property(property: "active_reserv", type: "boolean", example: true),
        new OA\Property(property: "creator", type: "string", example: "Admin Name")
    ]
)]

#[OA\Schema(
    schema: "EventPanelDetail",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "title", type: "string", example: "Event Title"),
        new OA\Property(property: "description", type: "string", example: "Event description"),
        new OA\Property(property: "capacity", type: "integer", example: 100),
        new OA\Property(property: "free_capacity", type: "integer", example: 50),
        new OA\Property(property: "start_date", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "end_date", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "is_active", type: "boolean", example: true),
        new OA\Property(property: "active_reserv", type: "boolean", example: true),
        new OA\Property(property: "creator", type: "string", example: "Admin Name")
    ]
)]

#[OA\Schema(
    schema: "EventListResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(
                    property: "events",
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "code", type: "string", format: "uuid", example: "c9870af8-2f8c-4eea-8826-9a01291e86ba"),
                            new OA\Property(property: "title", type: "string", example: "Cum nesciunt deserunt esse."),
                            new OA\Property(property: "free_capacity", type: "integer", example: 1),
                            new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-02-22T03:37:01.000000Z")
                        ]
                    )
                ),
                new OA\Property(
                    property: "page_data",
                    type: "object",
                    properties: [
                        new OA\Property(property: "current_page", type: "integer", example: 1),
                        new OA\Property(property: "per_page", type: "integer", example: 10),
                        new OA\Property(property: "total", type: "integer", example: 4),
                        new OA\Property(property: "last_page", type: "integer", example: 1),
                        new OA\Property(property: "from", type: "integer", nullable: true, example: 1),
                        new OA\Property(property: "to", type: "integer", nullable: true, example: 4)
                    ]
                )
            ]
        )
    ]
)]

#[OA\Schema(
    schema: "EventDetailResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(
                    property: "event",
                    type: "object",
                    properties: [
                        new OA\Property(property: "code", type: "string", format: "uuid", example: "550e8400-0000-0000-0000-000000000000"),
                        new OA\Property(property: "title", type: "string", example: "Event Title"),
                        new OA\Property(property: "description", type: "string", example: "Event description"),
                        new OA\Property(property: "free_capacity", type: "integer", example: 3),
                        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-02-21T09:07:46.000000Z")
                    ]
                )
            ]
        )
    ]
)]

#[OA\Schema(
    schema: "EventPanelListResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(
                    property: "events",
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "title", type: "string", example: "Event Title"),
                            new OA\Property(property: "capacity", type: "integer", example: 3),
                            new OA\Property(property: "free_capacity", type: "integer", example: 0),
                            new OA\Property(property: "active_reserv", type: "boolean", example: false),
                            new OA\Property(property: "creator", type: "string", example: "Admin Name"),
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
    schema: "EventPanelDetailResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(
                    property: "event",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 12),
                        new OA\Property(property: "title", type: "string", example: "Event name"),
                        new OA\Property(property: "capacity", type: "integer", example: 50),
                        new OA\Property(property: "free_capacity", type: "integer", example: 50),
                        new OA\Property(property: "creator", type: "string", example: "Admin User"),
                        new OA\Property(property: "active_reserv", type: "boolean", example: false),
                        new OA\Property(property: "created_at", type: "string", format: "date-time")
                    ]
                )
            ]
        )
    ]
)]
class EventSchemas
{
}

