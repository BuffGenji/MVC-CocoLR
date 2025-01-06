<?php
namespace App\AbstractClasses;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

abstract class Page {

    // The page constanst are the templates that will be used for rendering
    const LOGIN_TEMPLATE = 'LogInPageTemplate.html.twig';
    const HOME_TEMPLATE = 'HomePageTemplate.html.twig';
    const SIGNUP_TEMPLATE = 'SignUpPageTemplate.html.twig';
    const DASHBOARD_TEMPLATE = 'DashboardTemplate.html.twig';
    const TRAJET_TEMPLATE = 'TrajetTemplate.html.twig';
    const TRAJET_CREATOR_TEMPLATE = 'TrajetCreatorTemplate.html.twig';
    const LANDING_PAGE_TEMPLATE = 'LandingPageTemplate.html.twig';
    const CONFIRM_TRAJET_TEMPLATE = 'TrajetConfirmTemplate.html.twig';

    const ERROR_PAGE_NOT_FOUND = 'Error404.html.twig';
    const PROFILE_PAGE_TEMPLATE = 'ProfilePageTemplate.html.twig';



    /**
     * the obligatory render method
     * This is used as a first in all of the  routes
     * if called with a GET request it will render the page
     * if called with a POST will send data and trigger form submission, by JS or PHP
     * then most likely redrirect to another page
     */ 

    abstract public function render(Request $request, Response $response, $args): Response;

    /**
     * We need our own redirectTo function because I created somewhere a scenario in which I didn't conform
     * to some PSR-regulated function and made this instead.
     * Needs route names found in routes.json
     */
    public function redirectTo(Request $request, Response $response, string $routeName): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $url = $routeParser->urlFor(routeName: $routeName);
        return $response->withHeader('Location', $url)->withStatus(302);
    }
}