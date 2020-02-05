<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/cancel', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/cancel' GET route");
	$resource_id = $this->request->getParam('resource_id');
	$incident_id = $this->request->getParam('incident_id');
	$repair_id = $this->request->getParam('repair_id');

	$user = $this->session->getSegment('user')->get('info', null);

	$data = [
		'view' => 'Cancel',
		'subtitle' => 'Cancel',
		'resource_id' => $resource_id,
		'user' => $user
	];

	if (isset($incident_id)) {

		$return = Queries::cancelRequest($resource_id, $incident_id);
		$data['incident_id'] = $incident_id;

	} elseif (isset($repair_id)) {

		$return = Queries::cancelRepair($repair_id, $resource_id);
		$data['repair_id'] = $repair_id;
	}

	$redirectUrl = base64_decode($this->request->getParam('redirect'));

	if (!empty($redirectUrl))
	{
		$segment = $this->session->getSegment('message');
		$segment->setFlash('message', 'Cancelled Request for Resource #' . $resource_id . ' for use in Incident #' . $incident_id);
		$segment->setFlash('message_type', 'danger');

		return $response->withRedirect($redirectUrl);
	}
	else
	{
		// Render index view
    	return $this->renderer->render($response, 'default/index.phtml',$data);
	}

});