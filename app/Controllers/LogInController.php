<?php

declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use App\Models\LogInModel;
use App\Services\Cookie;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Services\Session;

class LogInController extends Page
{
    private $model;
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->model = new LogInModel();
        $this->twig = $twig;
    }

    /**
     * Simple rendering
     */
    #[Page('/login', name: 'login_render', methods: ['get'])]
    public function render(Request $request, Response $response, $args): Response
    {
        return $this->twig->render($response, Page::LOGIN_TEMPLATE, $args);
    }

    #[Page('/login', name: 'login', methods: ['post'])]
    public function login(Request $request, Response $response, $args)
    {
        $form_values = $request->getParsedBody();
        $in_database = $this->model->checkIfInDb($form_values['email'], $form_values['password']);
        if (!$in_database) {
            $args['error'] = 'Invalid credentials';
            return $this->twig->render($response, Page::LOGIN_TEMPLATE, $args);
        } else {
            // filling session so it can be used throughout the application
            // for weak spots (missing info) so that no unneccessary calls to db need to be made
            $this->model->fillSession($form_values['email']);
            if (Session::get('name') !== null) {
                // redirect to dashboard
                return $this->redirectTo($request, $response, 'dashboard_render'); // Always a GET, keep in mind
            } else {
                $args['error'] = 'Session not set';
                return $this->twig->render($response, Page::LOGIN_TEMPLATE, $args);
            }
        }
    }
}
