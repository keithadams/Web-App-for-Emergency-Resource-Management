<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/deploy-own-resource', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/deploy-own-resource' GET route");
	$resourceId = $this->request->getParam('resource_id');
	$incidentId = $this->request->getParam('incident_id');
	$redirectUrl = $this->request->getParam('redirect');
	$user = $this->session->getSegment('user')->get('info', null);

	$data = [
		'view' => 'deploy-own-resource',
		'subtitle' => 'Deploy Own Resource',
		'user' => $user,
		'resourceId' => $resourceId,
		'incidentId' => $incidentId,
		'redirectUrl' => $redirectUrl,
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml',$data);
});

$app->post('/deploy-own-resource', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/deploy-own-resource' POST route");
	$resourceId = (int) $this->request->getParam('resource_id');
	$incidentId = (int) $this->request->getParam('incident_id');
	$expectedReturnDate = $this->request->getParam('expected_return_date');

	date_default_timezone_set('America/Los_Angeles');
	$expectedReturnDate = date("Y-m-d",strtotime($expectedReturnDate));

	$redirectUrl = base64_decode($this->request->getParam('redirect'));
	$user = $this->session->getSegment('user')->get('info', null);

	$model = new \App\Models\DeployOwnResource($this);

	$return = $model->deployOwnResource($resourceId,$incidentId, $expectedReturnDate);

	$segment = $this->session->getSegment('message');
	$segment->setFlash('message', 'Immediately Deployed Resource #' . $resourceId . ' to Incident #' . $incidentId);
	$segment->setFlash('message_type', 'info');

	return $response->withRedirect($redirectUrl);
});