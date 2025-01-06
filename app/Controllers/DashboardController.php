<?php
declare(strict_types=1);

namespace App\Controllers;

use App\AbstractClasses\Page;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\DashboardModel;
use App\Services\Session;
use Slim\Views\Twig;

class DashboardController extends Page
{

    private $model;
    private $twig;
    public function __construct(Twig $twig)
    {
        $this->model = new DashboardModel();
        $this->twig = $twig;
    }

    /**
     * Since the user is authenticated, we can get the user's trips
     */
    #[Page('/dashboard', name: 'dashboard_render', methods: ['get'])]
    public function render(Request $request, Response $response, $args): Response
    {
        // setting things  for args in the template ( this could be done by creating an "entity" but for the template)
        // we could make it os that it would populate the args automatically with the variables that are needed in the template
        $args['trajets'] = $this->model->getuserTrajets();
        $args['name'] = Session::get('name');
        return $this->twig->render($response, Page::DASHBOARD_TEMPLATE, $args);
    }
}