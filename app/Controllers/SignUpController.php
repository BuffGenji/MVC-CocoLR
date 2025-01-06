<?php

declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class SignUpController extends Page
{

    private $model;
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
        $this->model = new \App\Models\SignUpModel();
    }

    #[Page('/signup', name: 'signup_render', methods: ['get'])]
    public function render(Request $request, Response $response, $args): Response
    {
        return $this->twig->render($response, Page::SIGNUP_TEMPLATE, $args);
    }

    /**
     * Handles the sign-up process.
     * 
     * In a POST request this will contain an associative
     * array in which there are the values in the form found in the  template {@see Page::SIGNUP_TEMPLATE}
     *
     * @param Request $request
     * @param Response $response The response object.
     * @return Response The response object.
     */
    #[Page('/signup', name: 'signup', methods: ['post'])]
    public function signUp(Request $request, Response $response)
    {
        $form_values = $request->getParsedBody();

        if ($this->emailInUse($form_values['email'])) {
            return $this->renderWithError($response, 'Email is in use');
        }
        $this->model->signUp($form_values);
        return $this->redirectTo($request, $response, 'login_render');
    }


    /**
     * Helper functions, which will eb used t single out certain points of the porcess
     * and make code more readable
     */

    private function emailInUse(string $email)
    {
        return $this->model->checkIfInDb($email);
    }

    private function renderWithError(Response $response, string $errorMessage)
    {
        $args['error'] = $errorMessage;
        return $this->twig->render($response, Page::SIGNUP_TEMPLATE, $args);
    }
}
