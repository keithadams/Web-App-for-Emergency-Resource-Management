<?php
namespace App\Models;

use App\Helpers\Queries;

class SearchResources extends Base
{

	public function getEsfsDropdown($esf_id = '')
	{
		$esfs = Queries::getEsfs();

		$output = '';

		$output .= '<select class="form-control" id="search_esf" name="search_esf">';

		$output .= '<option value="">Please Select an ESF</option>';

		foreach($esfs as $esf)
		{
			$selected = '';
			if ($esf_id === $esf->esf_id)
			{
				$selected = 'selected="selected"';
			}

			$output .= '<option ' . $selected . ' value="' . $esf->esf_id . '">' . $esf->esf_id . ' - ' . $esf->description . '</option>';
		}

		$output .= '</select>';

		return $output;
	}

	public function getIncidentsDropdown($incident_id = '')
	{
		$data = Queries::getIncidents();

		$output = '';

		$output .= '<select class="form-control" id="search_incident" name="search_incident">';

		$output .= '<option value="">Please Select an Incident</option>';

		foreach($data as $row)
		{
			$selected = '';
			if ($incident_id === $row->incident_id)
			{
				$selected = 'selected="selected"';
			}

			$output .= '<option ' . $selected . ' value="' . $row->incident_id . '">' . $row->incident_id . ' - ' . $row->description . '</option>';
		}

		$output .= '</select>';

		return $output;
	}

	public function getSearchParameters()
	{
		$parameters = [];

		$parameters['search'] = false;
		$parameters['search_keyword'] = '';
		$parameters['search_esf'] = '';
		$parameters['search_radius'] = '';
		$parameters['search_incident'] = '';

		if (!empty($_GET))
		{
			$parameters['search'] = true;
			if (isset($_GET['search_keyword']))
			{
				$parameters['search_keyword'] = $_GET['search_keyword'];
			}

			if (isset($_GET['search_esf']))
			{
				$parameters['search_esf'] = $_GET['search_esf'];
			}

			if (isset($_GET['search_radius']))
			{
				$parameters['search_radius'] = $_GET['search_radius'];
			}

			if (isset($_GET['search_incident']))
			{
				$parameters['search_incident'] = $_GET['search_incident'];
			}
		}

		return $parameters;
	}

	public function getSearchIncident()
	{
		if (!empty($_GET))
		{
			$search_incident = (int) $_GET['search_incident'];

			$row = Queries::getIncidentById($search_incident);

			return $row;
		}
	}

	public function getSearchResults()
	{
		if (!empty($_GET))
		{
			$search_keyword =  $_GET['search_keyword'];
			$search_esf = (int)  $_GET['search_esf'];
			$search_radius = (int)  $_GET['search_radius'];
			$search_incident = (int)  $_GET['search_incident'];

			// TODO Validation
			//$usernameValidator = v::alnum('-_.')->noWhitespace()->length(6, 50);
			//$passwordValidator = V::alnum('-_.!@$%#')->length(8, 50);

			/*if (!$usernameValidator->validate($username) || !$passwordValidator->validate($password))
			{
				// Invalid Username or Password
				$segment = $container->session->getSegment('login');
				$segment->setFlash('login_username', $username);
				$segment->setFlash('login_error', 'Username and/or Password do not meet minimum requirements.');
				$segment->setFlash('login_error_type', 'danger');
				return false;
			}*/

			$data = Queries::searchResource($search_keyword, $search_esf, $search_radius, $search_incident);

			return $data;
		}

	}
}