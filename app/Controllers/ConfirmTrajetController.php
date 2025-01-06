<?php

declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\ConfirmTrajetModel;
use App\Services\Session;
use Slim\Views\Twig;

class ConfirmTrajetController extends Page
{
    private $model;
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->model = new ConfirmTrajetModel();
        $this->twig = $twig;
    }

    public function render(Request $request, Response $response, $args): Response
    {
        $args['trajet'] = Session::get('trajet_data');
        return $this->twig->render($response, Page::CONFIRM_TRAJET_TEMPLATE, $args);
    }

    /**
     * This function used to have a purpose now it is a placebo, pay no special mind.
     * 
     * This fucntion just redirects after users confirms trajets (handled on form submission)
     * {@see Page::CONFIRM_TRAJET_TEMPLATE}
     */
    public function confirm(Request $request, Response $response): Response
    {
        return $this->redirectTo($request, $response, 'dashboard_render');  
    }
}
