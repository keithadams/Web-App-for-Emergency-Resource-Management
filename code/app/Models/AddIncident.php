<?php
namespace App\Models;

use App\Helpers\Queries;

class AddIncident extends Base
{

	public function getAddIncidentParameters()
	{
		$parameters = [];

		$parameters['submit'] = '';
		$parameters['incident_date'] = '';
		$parameters['incident_description'] = '';
		$parameters['incident_latitude'] = '';
		$parameters['incident_longitude'] = '';
		$parameters['save_success'] = false;

		if (!empty($_GET))
		{
			if (isset($_GET['submit']) && $_GET['submit'] == 'true')
			{
				$user_id = null;
				$description = $_GET['incident_description'];


				$date = $_GET['incident_date'];
				date_default_timezone_set('America/Los_Angeles');
				$date = date("Y-m-d",strtotime($date));

				$latitude = $_GET['incident_latitude'];
				$longitude = $_GET['incident_longitude'];

				Queries::insertIncident($user_id,$description,$date,$latitude,$longitude);

				$parameters['save_success'] = true;

				return $parameters;
			}


			if (isset($_GET['incident_date']))
			{
				$parameters['incident_date'] = $_GET['incident_date'];
			}

			if (isset($_GET['incident_description']))
			{
				$parameters['incident_description'] = $_GET['incident_description'];
			}

			if (isset($_GET['incident_latitude']))
			{
				$parameters['incident_latitude'] = $_GET['incident_latitude'];
			}

			if (isset($_GET['incident_longitude']))
			{
				$parameters['incident_longitude'] = $_GET['incident_longitude'];
			}
		}

		return $parameters;
	}
}