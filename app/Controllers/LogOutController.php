<?php
declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * This controller is a controller because I believ 
 */
class LogOutController extends Page
{
     /**
     * Logs out the user by clearing the session, and returning to a know starting point, the login page
     * 
     *
     * @param Request $request The request object.
     * @param Response $response The response object.
     * @return Response The response object.
     */
    public function logOut(Request $request, Response $response): Response
    {
        // delete all sesion info
        session_unset();
        session_destroy();

        // Redirect to login page 
        return $this->render($request,$response);
    }

    /**
     * This is most likely nto best practoc ebut for the sake of keeping evrything in one pot 
     * and to be able to make stronger changes. I have included LogOut into the Page. 
     * 
     * The technicality is that it is actually serving a page, just ot directly linked with its name.
     * It would otherwise have no page, and best case scenario doesn't have a controller (in my opinion)
     * 
     * Standard render method from abstract class Page
     */
    public function render(Request $request, Response $response, $args = []) : Response
    {
        return $this->redirectTo($request,$response, 'login_render');
    }
}