<?php

namespace App\Http\Controllers\Api\Resources;

/**
 * @OA\Info(
 *  version="1.0.0",
 *  title="Sonrisa feliz Services - OpenApi",
 *  description="This API-DOC is designed to give access to Sonrisa Feliz External Services.

### API Tokens

This API uses JWT tokens to authorize your client accounts.We use the token to authenticate users and profiles (if available).<br>
In order to use the API you need to get a token, save it on client side, and use it on each request that requires auth.<br>
JWT tokens use their own standard to be generated, refreshed and blacklisted.In order to generate JWT Tokens you need to call `usuarios/login`.<br>
After that the token generated is valid for 1 hours, after that became `expired` and you need to refresh it in order to continue accessing existing resources.<br>
To accomplish that hit `users/refresh` with the expired token as header, as a result the token will be refreshed to continue.<br>
The only limitation is that after token becomes expired customer has 2 weeks to refresh it if that does not happen a new auth attempt should be needed.

### Note:

Use Authorization header with the value Bearer [api-Token] to be authorized.
The API uses JSON parameters in a raw body request.
- Set Content-Type application/json to force that.
- Set the Accept application/json header to get json responses as well.
",
 * )
 */
/**
 * @OA\SecurityScheme(
 *   securityScheme="Bearer",
 *   scheme="http",
 *   in="header",
 *   type="apiKey",
 *   description="Use token to Authenticate on detailed resources",
 *   name="Authorization",
 * )
 */
/**
 * @OA\Server(
 *   url="/api/v1",
 * ),
 */
/**
 * @OA\Schema(
 *   schema="PackageDeleteResponse",
 *   @OA\Property(
 *     property="message",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="error",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="integer",
 *     format="int32",
 *   ),
 *    @OA\Property(
 *     property="data",
 *     type="object",
 *   ),
 * )
 */
class Documentation
{
}
