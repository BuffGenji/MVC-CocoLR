<?php

declare(strict_types=1);

namespace App\Services\Authenication;

use App\Config\Database;
use App\Services\Session;
use Exception;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Routing\RouteContext;

class Authentication
{
    private $response_factory;
    public function __construct()
    {
        $this->response_factory = new ResponseFactory();
    }

    public function __invoke(Request $request, Handler $handler)
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        if (empty($route)) {
            return $this->redirectTo($request, 'render_error');
        }

        if (Session::get('name') !== null) {
            return $handler->handle($request);
        } else {
            return $this->redirectTo($request, 'login_render');
        }
    }

    /**
     * This is the same as the redirectTo found in the ControllerHelper, but it has been modified to accept 2 parameters
     * so that it complies with the slim middleware native functions.
     */
    private function redirectTo(Request $request, string $routeName)
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $response = $this->response_factory->createResponse();
        return $response
            ->withHeader('Location', $routeParser->urlFor($routeName))
            ->withStatus(302);
    }


    /**
     * This function will allow us to check if a user is authenticated
     * it will do so by accessing a token in the session, and then searching for this token in the database
     * if it exists and the values tied to it matchh the user, then the user is authenticated
     * @return bool
     */
    public function IsAuthenticated($apples)
    {
        return false;
    }

    /**
     * This fucntion is to make the user experience better, 
     * we remeber what the user was doing before they clicked off the page.
     */
    public function hasCurrentSession()
    {
        // we check the cookie for a session id, then we check the database for the session id
        // which will be a linked to a proofile, of which there will eb a property "last_seen_on".
        //  And we will redirect to that page
    }
}
