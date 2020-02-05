<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/reject-request', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/reject-request' GET route");
	$resourceId = $this->request->getParam('resource_id');
	$incidentId = $this->request->getParam('incident_id');
	$user = $this->session->getSegment('user')->get('info', null);

	$model = new \App\Models\RejectRequest($this);

	$return = $model->rejectRequest($resourceId,$incidentId);

	$data = [
		'view' => 'reject-request',
		'subtitle' => 'Reject Request',
		'user' => $user,
		'resourceId' => $resourceId,
		'incidentId' => $incidentId
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml',$data);
});