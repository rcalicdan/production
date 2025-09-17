<?php

namespace App;


use Slim\Psr7\Response;

/**
 * Renders a Blade view and returns it as a complete PSR-7 Response.
 *
 * @param Response $response The response object to write to.
 * @param string $template The name of the Blade template.
 * @param array $data Data to pass to the template.
 * @return Response The PSR-7 response with the rendered HTML.
 */
function view(Response $response, string $template, array $data = []): Response
{
    $html = blade_view($template, $data);
    $response->getBody()->write($html);
    
    return $response;
}
