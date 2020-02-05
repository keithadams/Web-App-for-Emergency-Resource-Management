<?php
namespace App\Middleware;

class RequireAuthentication
{
	protected $app = null;
	protected $loginRoute = null;

	public function __construct($app, $loginRoute = 'login')
	{
		$this->app = $app;
		$this->loginRoute = $loginRoute;
	}

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
    	$segment = $this->app->getContainer()->session->getSegment('authenticated');
		$isAuthenticated = $segment->get('isAuthenticated', false);
		$currentRoute = $request->getUri()->getPath();

		if ($currentRoute !== 'install')
		{
			if (!$isAuthenticated)
			{
				// Without this secondary check we'd create an infinite loop of redirects:
				if ($currentRoute !== $this->loginRoute)
				{
					return $response->withRedirect($this->app->getContainer()->renderer->baseUrl().'login');
				}
			}
		}

        $response = $next($request, $response);

        return $response;
    }
}