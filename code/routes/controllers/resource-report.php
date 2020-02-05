<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/resource-report', function (Request $request, Response $response) {

    $this->logger->info("CS 6400 Application '/resource-report' GET route");

    $user = $this->session->getSegment('user')->get('info', null);

    $model = new \App\Models\ResourceReport($this);

    $resourceReport = $model->populateResourceReport($user);
    $resourceReportTotals = $model->populateResourceReportTotals($user);

    //The following is to impersonate a user for testing. It overrides session variables.
    $name = $model->getUserName();


    $data = [
        'view' => 'resource-report',
        'subtitle' => 'Resource Report',
        'user' => $user,
        'name'=> $name,
        'model' => $model,
        'resourceReport' => $resourceReport,
        'resourceReportTotals' => $resourceReportTotals
    ];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml', $data);
});