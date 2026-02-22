<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "SuccessResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(property: "data", type: "object")
    ]
)]

#[OA\Schema(
    schema: "ErrorResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "error"),
        new OA\Property(property: "message", type: "string", example: "Error message")
    ]
)]

#[OA\Schema(
    schema: "ValidationErrorResponse",
    type: "object",
    properties: [
        new OA\Property(property: "message", type: "string", example: "The given data was invalid."),
        new OA\Property(
            property: "errors",
            type: "object",
            additionalProperties: new OA\AdditionalProperties(
                type: "array",
                items: new OA\Items(type: "string")
            )
        )
    ]
)]

#[OA\Schema(
    schema: "PaginationMeta",
    type: "object",
    properties: [
        new OA\Property(property: "current_page", type: "integer", example: 1),
        new OA\Property(property: "per_page", type: "integer", example: 15),
        new OA\Property(property: "total", type: "integer", example: 100),
        new OA\Property(property: "last_page", type: "integer", example: 7),
        new OA\Property(property: "from", type: "integer", nullable: true, example: 1),
        new OA\Property(property: "to", type: "integer", nullable: true, example: 15)
    ]
)]

#[OA\Schema(
    schema: "PaginatedResponse",
    type: "object",
    properties: [
        new OA\Property(property: "status", type: "string", example: "success"),
        new OA\Property(
            property: "data",
            type: "object",
            properties: [
                new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object")),
                new OA\Property(property: "current_page", type: "integer", example: 1),
                new OA\Property(property: "per_page", type: "integer", example: 15),
                new OA\Property(property: "total", type: "integer", example: 100),
                new OA\Property(property: "last_page", type: "integer", example: 7)
            ]
        )
    ]
)]

#[OA\Schema(
    schema: "UnauthenticatedResponse",
    type: "object",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
    ]
)]

#[OA\Schema(
    schema: "ForbiddenResponse",
    type: "object",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Forbidden")
    ]
)]
class ResponseSchemas
{
}


