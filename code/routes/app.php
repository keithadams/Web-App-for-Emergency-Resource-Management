<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/template-test' route");

	$user = $this->session->getSegment('user')->get('info', null);

	$model = new \App\Models\ResourceStatus($this);

	$inUseCount = count($model->populateResourcesInUse($user));
	$requestedByMeCount = count($model->populateResourcesRequestedByMe($user));
	$receivedByMeCount = count($model->populateResourceRequestsReceivedByMe($user));
	$repairsScheduledCount = count($model->populateResourcesScheduledorInProgress($user));

	$data = [
		'view' => 'main',
		'subtitle' => 'Main Menu',
		'user' => $user,
		'inUseCount' => $inUseCount,
		'requestedByMeCount' => $requestedByMeCount,
		'receivedByMeCount' => $receivedByMeCount,
		'repairsScheduledCount' => $repairsScheduledCount
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml', $data);
});
