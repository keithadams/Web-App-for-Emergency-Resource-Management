<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/resource-status', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/resource-status' GET route");

	$user = $this->session->getSegment('user')->get('info', null);

	$model = new \App\Models\ResourceStatus($this);

	$inUse = $model->populateResourcesInUse($user);
	$requestedByMe = $model->populateResourcesRequestedByMe($user);
	$receivedByMe = $model->populateResourceRequestsReceivedByMe($user);
	$repairsScheduled = $model->populateResourcesScheduledorInProgress($user);

	//filter any requests where user is the owner
	foreach ($requestedByMe as $idx=>$row)
        if ($row->resource_owner_name==$user->name)
        	unset($requestedByMe[$idx]);

    //filter any requests where user is the requester because those shouldn't show up
	foreach ($receivedByMe as $idx=>$row)
        if ($row->requester_name==$user->name)
        	unset($receivedByMe[$idx]);

	//The following is to impersonate a user for testing. It overrides session variables.
	//$name = $model->getUserName();

	$data = [
		'view' => 'resource-status',
		'subtitle' => 'Resource Status',
		'user' => $user,
		'model' => $model,
		'inUse' => $inUse,
		'requestedByMe' => $requestedByMe,
		'receivedByMe' => $receivedByMe,
		'repairsScheduled' => $repairsScheduled
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml', $data);
});