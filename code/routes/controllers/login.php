<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/login', function (Request $request, Response $response) {


    // TAN Testing
    // $queries = new \App\Helpers\Queries($container);
    //Queries::setContainer($this->session->container);
    //$obj = Queries::getUserByUserId(3);


	$this->logger->info("CS 6400 Application '/login' GET route");

	$login = $this->session->getSegment('login');
	$login_username = $login->getFlash('login_username', '');
	$login_error = $login->getFlash('login_error', '');
	$login_error_type = $login->getFlash('login_error_type', 'info');

	$hits = $this->session->getSegment('app')->get('hits', 0);
	$hits++;
	$this->session->getSegment('app')->set('hits', $hits);


	$data = [
		'hits' => $hits,
		'login_username' => $login_username,
		'login_error' => $login_error,
		'login_error_type' => $login_error_type,
	];


    // Render index view
    return $this->renderer->render($response, 'default/login.phtml', $data);
});

$app->post('/login', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/login' POST route");

	$this->renderer->validateCsrf();

	$authenticationFailed = !(\App\Helpers\Authentication::check($this));

	if ($authenticationFailed)
	{
		return $response->withRedirect($this->renderer->baseUrl().'login');
	}

    // Render index view
    return $response->withRedirect($this->renderer->baseUrl());
});

$app->get('/logout', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/logout' GET route");

	// Destroy the session:
	$this->session->clear();

	$segment = $this->session->getSegment('login');
	$segment->setFlash('login_error', 'Thank you for using ERMS!');

	return $response->withRedirect($this->renderer->baseUrl().'login');
});