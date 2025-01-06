<?php

declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use App\Models\TrajetModel;
use App\Services\Query;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class TrajetController extends Page
{
    private $twig;
    private $model;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
        $this->model = new TrajetModel();
    }


    #[Page('/trajet/{id}', name: 'render_trajet', methods: ['get'])]
    public function render(Request $request, Response $response, $args): Response
    {
        $params = $request->getQueryParams();
        $trajet = $this->model->getTrajetData($params['id']);
        $args['trajet'] = $trajet;
        return $this->twig->render($response, Page::TRAJET_TEMPLATE, $args);
    }

    #[Page('/trajet/delete/{id}', name: 'delete_trajet', methods: ['get'])]
    public function delete(Request $request,Response $response, $args)
    {        
        $this->model->deleteTrajet($args['id']);
        return $this->redirectTo($request, $response, 'dashboard_render');
    }



}
