<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/repair-resource', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/repair-resource' GET route");
	$resourceId = $this->request->getParam('resource_id');
	$resourceStatus = $this->request->getParam('resource_status');
	//$incidentId = $this->request->getParam('incident_id');
	$redirectUrl = $this->request->getParam('redirect');
	$user = $this->session->getSegment('user')->get('info', null);

	$data = [
		'view' => 'repair-resource',
		'subtitle' => 'Repair Resource',
		'user' => $user,
		'resourceId' => $resourceId,
		'resourceStatus' => $resourceStatus,
		//'incidentId' => $incidentId,
		'redirectUrl' => $redirectUrl,
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml',$data);
});

$app->post('/repair-resource', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/repair-resource' POST route");
	$resourceId = (int) $this->request->getParam('resource_id');
	$resourceStatus = $this->request->getParam('resource_status');
	$duration = (int) $this->request->getParam('duration');

	$redirectUrl = base64_decode($this->request->getParam('redirect'));
	$user = $this->session->getSegment('user')->get('info', null);

	$model = new \App\Models\RepairResource($this);

	if ($resourceStatus === 'in-use')
	{
		$return = $model->repairInUseResource($resourceId, $duration);
	}
	elseif($resourceStatus === 'available')
	{
		$return = $model->repairAvailableResource($resourceId, $duration);
	}

	$segment = $this->session->getSegment('message');
	$segment->setFlash('message', 'Scheduled Repair for Resource #' . $resourceId);
	$segment->setFlash('message_type', 'info');

	return $response->withRedirect($redirectUrl);
});