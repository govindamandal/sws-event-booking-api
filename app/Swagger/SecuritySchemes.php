<?php

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use a bearer token to access the protected routes",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum"
 * )
 */
