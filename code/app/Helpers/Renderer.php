<?php
namespace App\Helpers;

class Renderer extends \Slim\Views\PhpRenderer
{
	/**
	* Reference to the Slim App Instance
	*
	* @var object
	*/
	protected $app = null;

	public function baseUrl($ltrim = false)
	{
		$baseUrl = $_SERVER['SCRIPT_NAME'];
		$baseUrl = str_replace('index.php', '', $baseUrl);

		if ($ltrim)
		{
			$baseUrl = ltrim($baseUrl, '/');
		}

		return $baseUrl;
	}

	public function carbon($dateString = null)
	{
		$carbon = new \Carbon\Carbon();

		if (!empty($dateString))
		{
			$carbon = $carbon->parse($dateString);
		}

		return $carbon;
	}

	public function escape($output, $mode = ENT_COMPAT)
	{
		// Escape the output.
		return htmlspecialchars($output, $mode, 'UTF-8');
	}

	public function getCsrf()
	{
	    $csrf_value = $this->app->getContainer()->session->getCsrfToken()->getValue();
		$csrf = '<input type="hidden" name="__csrf_value" value="' . $this->escape($csrf_value, ENT_QUOTES) . '" />';
	    return $csrf;
	}

	public function validateCsrf()
	{
		$csrf_value = $_POST['__csrf_value'];
    	$csrf_token = $this->app->getContainer()->session->getCsrfToken();

    	$valid = $csrf_token->isValid($csrf_value);

    	if (!$valid)
    	{
			$response = $response->withStatus(403, "Invalid CSRF Token");
			$response->write("Invalid CSRF Token");
			return $response;
    	}


    	return $valid;
	}

	public function setApp($app)
	{
		$this->app = $app;
	}
}
