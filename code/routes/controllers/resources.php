<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/resources', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/resources' GET route");

    // Render index view
    return $response->withRedirect($this->renderer->baseUrl().'resource-status');
});

$app->get('/resources/{id}', function (Request $request, Response $response, $args) {

	$this->logger->info("CS 6400 Application '/resources/{id}' GET route");

	$user = $this->session->getSegment('user')->get('info', null);

	$resource_id = (int) $args['id'];

	$resource = Queries::getResourceInfoById($resource_id);

	$data = [
		'view' => 'resources',
		'subtitle' => 'Resource #' . $resource_id . ' Information',
		'user' => $user,
		'resource' => $resource
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml', $data);
});