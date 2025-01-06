<?php

declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use App\Entities\Trajet;
use App\Models\TrajetCreatorModel;
use App\Services\Session;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class TrajetCreatorController extends Page
{

    private $twig;
    private $model;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
        $this->model = new TrajetCreatorModel();
    }

    #[Page('/trajet/create', name: 'trajet_creator', methods: ['get'])]
    public function render(Request $request, Response $response, $args): Response
    {
        return $this->twig->render($response, Page::TRAJET_CREATOR_TEMPLATE, $args);
    }

    #[Page('/trajet/create', name: 'createTrajet', methods: ['post'])]
    public function createTrajet(Request $request, Response $response, $args): Response
    {
        $user_input = $request->getParsedBody();
        $trajet = $this->model->createTrajet($user_input); // create trajet also uses Session
        Session::set('trajet_data', $trajet);
        return $this->redirectTo(request: $request, 
                                 response: $response, 
                                 routeName: 'render_confirm_trajet');
    }
}
