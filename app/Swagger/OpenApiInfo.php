<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;


#[OA\OpenApi(
    info: new OA\Info(
        title: "Event Management API",
        version: "1.0.0",
        description: "API documentation for Event Management System"
    ),
    components: new OA\Components(
        securitySchemes: [
            new OA\SecurityScheme(
                securityScheme: "bearerAuth",
                type: "http",
                scheme: "bearer",
                bearerFormat: "JWT",
                description: "Enter your access token"
            )
        ]
    )
)]
class OpenApiInfo
{
    //
}

