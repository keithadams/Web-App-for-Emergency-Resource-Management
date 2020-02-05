<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/add-resource', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/add-resource' GET route");

	$user = $this->session->getSegment('user')->get('info', null);

	$model = new \App\Models\AddResource($this);
	$parameters = $model->getAddResourceParameters();
	// $results = $model->getSearchResults();
	// $incident = $model->getSearchIncident();

	$data = [
		'view' => 'add-resource',
		'subtitle' => 'Add Resource',
		'user' => $user,
		'model' => $model,
		'parameters' => $parameters
		// 'results' => $results
		// 'incident' => $incident,
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml', $data);
});

