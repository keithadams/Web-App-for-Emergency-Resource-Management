<?php
namespace App\Models;

use App\Helpers\Queries;

class AddResource extends Base
{

	public function getPrimaryEsfsDropdown($esf_id = '')
	{
		$esfs = Queries::getEsfs();

		$output = '';

		$output .= '<select required class="form-control" id="resource_primary_esf" name="resource_primary_esf">';

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

	public function getCostIntervalsDropdown($cost_interval_id = '')
	{
		$cost_intervals = Queries::getCostIntervals();



		$output = '';

		$output .= '<select required class="form-control" style="display: inline-block !important;width: 100%;" id="resource_cost_interval" name="resource_cost_interval">';

		$output .= '<option value="">Cost Interval</option>';

		foreach($cost_intervals as $cost_interval)
		{
			$selected = '';
			if ($cost_interval_id === $cost_interval->cost_interval_id)
			{
				$selected = 'selected="selected"';
			}

			$output .= '<option ' . $selected . ' value="' . $cost_interval->cost_interval_id . '">' . $cost_interval->description . '</option>';
		}

		$output .= '</select>';

		return $output;
	}

	public function getAdditionalEsfsDropdown($additional_esfs = array())
	{
		$esfs = Queries::getEsfs();

		$output = '';

		$output .= '<select multiple class="form-control" style="height: 280px;" id="resource_additional_esfs" name="resource_additional_esfs[]">';

		// $output .= '<option value="">Please Select an ESF</option>';

		foreach($esfs as $esf)
		{
			$selected = '';
			
			foreach($additional_esfs as $additional_esf)
			{
				if ($additional_esf === $esf->esf_id)
				{
					$selected = 'selected="selected"';
				}

			}

			$output .= '<option ' . $selected . ' value="' . $esf->esf_id . '">' . $esf->esf_id . ' - ' . $esf->description . '</option>';
		}

		$output .= '</select>';

		return $output;
	}

	public function getAddResourceParameters()
	{
		$parameters = [];

		// $parameters['search'] = false;
		$parameters['submit'] = '';
		$parameters['add_capability'] = '';
		$parameters['resource_name'] = '';
		$parameters['resource_primary_esf'] = '';
		$parameters['resource_additional_esfs'] = array();
		$parameters['resource_model'] = '';
		$parameters['resource_capability'] = '';
		$parameters['resource_capabilities'] = array();
		$parameters['resource_latitude'] = '';
		$parameters['resource_longitude'] = '';
		$parameters['resource_cost_amount'] = '';
		$parameters['resource_cost_interval'] = '';
		$parameters['save_success'] = false;

		if (!empty($_GET))
		{
			if (isset($_GET['submit'])){
				if($_GET['submit'] == 'save_resource')
				{
					$user_id = null;
					$name = $_GET['resource_name'];
					$cost_amount = $_GET['resource_cost_amount'];
					$cost_interval_id = $_GET['resource_cost_interval'];
					$primary_esf_id = $_GET['resource_primary_esf'];
					$latitude = $_GET['resource_latitude'];
					$longitude = $_GET['resource_longitude'];


					// check if model is set, else NULL
					if ($_GET['resource_model'] != '')
					{
						$model = $_GET['resource_model'];
					}
					else
					{
						$model = null;
					}


					// insert resource and return auto generated resource_id
					$resource_id = Queries::insertResource($user_id,$name,$model,$cost_amount,$cost_interval_id,$primary_esf_id,$latitude,$longitude);


					// add additional ESFs
					if (isset($_GET['resource_additional_esfs']))
					{
						foreach ($_GET['resource_additional_esfs'] as $additional_esf){
							if ($additional_esf != $_GET['resource_primary_esf'])
							{
							    Queries::insertResourceAdditionalEsf($resource_id,$additional_esf);
							}
						}
					}

					
					// add capabilities
					if (isset($_GET['resource_capabilities']))
					{
						foreach ($_GET['resource_capabilities'] as $capability){

							// adds capability, succeeds even if capability already in DB
							Queries::insertCapability($capability);

							// gets capability_id based on capability's description =
							$cid = Queries::getCapabilityId($capability);

							// inserts relationship that conncets resource and capability  
						    Queries::insertResourceCapability($resource_id,$cid);
						}
					}




					$parameters['save_success'] = true;

					return $parameters;
				}
			}

			if (isset($_GET['submit']))
			{
				$parameters['submit'] = $_GET['submit'];
			}

			if (isset($_GET['resource_name']))
			{
				$parameters['resource_name'] = $_GET['resource_name'];
			}

			if (isset($_GET['resource_primary_esf']))
			{
				$parameters['resource_primary_esf'] = $_GET['resource_primary_esf'];
			}

			if (isset($_GET['resource_additional_esfs']))
			{
				$tmp = array();

				foreach ($_GET['resource_additional_esfs'] as $additional_esf){
					if ($additional_esf != $parameters['resource_primary_esf'])
					{
						array_push($tmp, $additional_esf);
					}
				    
				}
				$parameters['resource_additional_esfs'] = $tmp;
			}

			if (isset($_GET['resource_model']))
			{
				$parameters['resource_model'] = $_GET['resource_model'];
			}


			if (isset($_GET['resource_capabilities']))
			{
				$tmp = array();

				foreach ($_GET['resource_capabilities'] as $capability){

					if ($capability != ''){
				    	array_push($tmp, $capability);
					}
				}
				$parameters['resource_capabilities'] = array_unique($tmp);
			}


			if (isset($_GET['submit'])){
				if($_GET['submit'] == 'add_capability')
				{
					if (isset($_GET['resource_capability']))
					{
						// $parameters['resource_capability'] = $_GET['resource_capability'];
						if ($_GET['resource_capability'] != ''){
					    	// array_push($tmp, $capability);
					    	array_push($parameters['resource_capabilities'],$_GET['resource_capability']);
						}
						$parameters['resource_capabilities'] = array_unique($parameters['resource_capabilities']);
					}

				}
			}

			if(isset($_GET['delete_capability']))
			{
				$parameters['resource_capabilities'] = array_diff($parameters['resource_capabilities'], array($_GET['delete_capability']));
			}

			if (isset($_GET['resource_latitude']))
			{
				$parameters['resource_latitude'] = $_GET['resource_latitude'];
			}

			if (isset($_GET['resource_longitude']))
			{
				$parameters['resource_longitude'] = $_GET['resource_longitude'];
			}

			if (isset($_GET['resource_cost_rate']))
			{
				$parameters['resource_cost_rate'] = $_GET['resource_cost_rate'];
			}

			if (isset($_GET['resource_cost_amount']))
			{
				$parameters['resource_cost_amount'] = $_GET['resource_cost_amount'];
			}

			if (isset($_GET['resource_cost_interval']))
			{
				$parameters['resource_cost_interval'] = $_GET['resource_cost_interval'];
			}


		}

		return $parameters;
	}

}