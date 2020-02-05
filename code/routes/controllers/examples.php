<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use \App\Helpers\Queries;

$app->get('/template-test', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/template-test' route");

	$data = [
		'view' => 'main',
		'subtitle' => 'Main Menu'
	];

    // Render index view
    return $this->renderer->render($response, 'default/index.phtml', $data);
});

$app->get('/subroute/test', function (Request $request, Response $response) {

	/**
	* CSS Won't Load Correctly Yet with a sub-sub route like this since
	* the relative CSS/JS inclusions in the templates will try and look relative
	* to the virtual /subroute directory which doesn't physically exist.
	*
	* This is fixed by including the full root URL of your site into the
	* templates, but that needs to be built in a little bit.
	*
	* FIXED: Added a Custom Renderer in that includes a baseUrl() method.
	* It also contains an escape() method that can be used for properly
	* HTML encoding special characters to help prevent XSS from user provided data.
	*
	*/
	$this->logger->info("CS 6400 Application '/subroute/test' route");

    // Render index view
    return $this->renderer->render($response, 'default/login.phtml');
});

$app->get('/example/{variable_name}', function (Request $request, Response $response, $args) {

	$this->logger->info("CS 6400 Application '/example/{variable_name}' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/query', function (Request $request, Response $response) {

	$this->logger->info("CS 6400 Application '/query' route");

	$query = 'SELECT * FROM resources';

	$preparedStatement = $this->db->prepare($query);

	$preparedStatement->execute();

	$data = $preparedStatement->fetchAll();

    // Render index view
    return $this->renderer->render($response, 'results.phtml', $data);
});

$app->get('/bind-query', function (Request $request, Response $response) {
	$output = 'Please provide an ID to bind to for the query (e.g. /bind-query/3).';

	$response->getBody()->write($output);

	return $response;
});

$app->get('/bind-query/{id}', function (Request $request, Response $response, $args) {

	$this->logger->info("CS 6400 Application '/bind-query' route");

	$query = 'SELECT * FROM resources WHERE user_id = :id';

	$preparedStatement = $this->db->prepare($query);

	$preparedStatement->bindValue(':id', $args['id'], PDO::PARAM_INT);

	$preparedStatement->execute();

	$data = $preparedStatement->fetchAll();

    // Render index view
    return $this->renderer->render($response, 'results.phtml', $data);
});

$app->get('/app-code', function (Request $request, Response $response) {
	// Demonstrates class autoloading (PSR-4 style autoloading already defined in our composer.json file):
	$example = new \App\Helpers\Example();
	$output = $example->getExampleResponse();

	$response->getBody()->write($output);

	return $response;
});

$app->get('/hello-world', function (Request $request, Response $response) {
	$output = 'Hello World';

	$response->getBody()->write($output);

	return $response;
});
