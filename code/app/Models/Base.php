<?php
namespace App\Models;

use App\Helpers\Queries;

class Base
{
  protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}
}
