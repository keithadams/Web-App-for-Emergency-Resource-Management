<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/search-resources', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/search-resources' GET route");

	$user = $this->session->getSegment('user')->get('info', null);

	$model = new \App\Models\SearchResources($this);
	$parameters = $model->getSearchParameters();
	$results = $model->getSearchResults();
	$inUseReturnDates = Queries::getInUseResourcesReturnDate($results);
	$inRepairReturnDates = Queries::getInRepairResourcesReturnDate($results);
	$incident = $model->getSearchIncident();
	$previouslyDeployedResources = Queries::getPreviouslyDeployedResourcesForIncident($incident);

	$resourcesInUseByMe = Queries::getResourcesDeployedToUser();
	$resourcesRequestedByMe = Queries::getResourceRequestsSentFromUser();

	$inUseResourcesList = array();
	$requestedResourcesList = array();
	if (!empty($incident))
	{
		if (!empty($resourcesInUseByMe))
		{
			foreach($resourcesInUseByMe as $resourceInUse)
			{
				if ($incident->incident_id === $resourceInUse->incident_id)
				{
					$inUseResourcesList[$resourceInUse->resource_id] = true;
				}
			}
		}

		if (!empty($resourcesRequestedByMe))
		{
			foreach($resourcesRequestedByMe as $requestedResource)
			{
				if ($incident->incident_id === $requestedResource->incident_id)
				{
					$requestedResourcesList[$requestedResource->resource_id] = true;
				}
			}
		}
	}


	$flashMessage = $this->session->getSegment('message');

	$message = $flashMessage->getFlash('message', '');
	$message_type = $flashMessage->getFlash('message_type', 'info');

	$data = [
		'view' => 'search-resources',
		'subtitle' => 'Search Resources',
		'message' => $message,
		'message_type' => $message_type,
		'user' => $user,
		'model' => $model,
		'parameters' => $parameters,
		'results' => $results,
		'incident' => $incident,
		'inUseReturnDates' => $inUseReturnDates,
		'inRepairReturnDates' => $inRepairReturnDates,
		'previouslyDeployedResources' => $previouslyDeployedResources,
		'inUseResourcesList' => $inUseResourcesList,
		'requestedResourcesList' => $requestedResourcesList,
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml', $data);
});

