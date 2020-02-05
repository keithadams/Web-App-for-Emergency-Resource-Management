<?php
namespace App\Helpers;

class Queries
{
    protected static $container;
	protected static $db;

    public static function setContainer($container)
    {
        self::$container = $container;

		// TAN: Also establish the DB when
		//		the container is created.
		//	Note: This may require additional work if the ability to introduce DB switching is implemented
		self::$db = self::$container->db;
    }

	/*
		// Sample Stored Procedure usage code with one parameter
		$query = "CALL spGetUserByUsername(:username);";
		$preparedStatement = self::$db->prepare($query);
        $preparedStatement->bindValue(':username', $username);
        $preparedStatement->execute();

        $value = $preparedStatement->fetchObject();

        return $value;


	*/



	// may not need this one anymore
	public static function getUserWithUsernamePassword(
		$username,
		$password
	)
	{

		/* TAN: Updated to Stored Procedure
		return "
			-- The below query is assuming a simple
			-- Cleartext Password check.
			SELECT u.user_id,
			       u.username,
			       u.name
			FROM users u
			WHERE u.username = :username
			AND u.password = :password
		";
		*/
		$query = "CALL spGetUserWithUsernamePassword(:username, :password);";
		$preparedStatement = self::$db->prepare($query);
        $preparedStatement->bindValue(':username', $username);
		$preparedStatement->bindValue(':password', $password);
        $preparedStatement->execute();

        $user = $preparedStatement->fetchObject();

        return $user;

	}

    public static function getUserByUsername($username)
    {

        // $query = 'SELECT * FROM users WHERE username = :username';

        $query = "CALL spGetUserByUsername(:username);";

        $preparedStatement = self::$db->prepare($query);
        $preparedStatement->bindValue(':username', $username);
        $preparedStatement->execute();

        $user = $preparedStatement->fetchObject();

        return $user;

    }

	public static function getUserByUserId($id)
	{

        $query = "CALL spGetUserByUserId(:user_id);";

        $preparedStatement = self::$db->prepare($query);
        $preparedStatement->bindValue(':user_id', $id);
        $preparedStatement->execute();

        $user = $preparedStatement->fetchObject();

        // OMAR:
        // You may not want to use print statements since
        // they could accidentally affect the HTML output
        // This would print to the browser, rather than to
        // STDOUT/console. (it's not as noticeable in the current
        // flow due to the immediate redirect that happens during
        // the login process at the moment).
        //print 'user ID: ' . $user->id;

        return $user;

        /* TAN: Replaced with Stored Procedure
		return "
			-- If you are storing encrypted passwords in the database
			-- and using PHP's password_hash() and password_verify()
			-- functions then the above query might need to be modified
			-- so that the password filter is removed. The password
			-- would need to be returned in the result so that the PHP
			-- application could run it against the password_verify()
			-- function.

			-- DONE: Task sql omar add user_id to view main menu
			    -- OMAR: These changes have been completed.
			    -- It's working, and also integrated the user_type info
			    -- into the OUTER JOIN version.
			-- View Main Menu:
			-- Allow us to bring all of the relevant columns into a single view
			-- Anything non-null will correspond to the type of the user
			SELECT u.user_id,
			       u.username,
			       u.password,
			       u.name,
			       c.headquarters,
			       ga.jurisdiction,
			       i.job_title,
			       i.date_hired,
			       m.population_size,
			       (CASE
			            WHEN (c.headquarters IS NOT NULL) THEN 'company'
			            WHEN (ga.jurisdiction IS NOT NULL) THEN 'government_agency'
			            WHEN (i.job_title IS NOT NULL) THEN 'individual'
			            WHEN (m.population_size IS NOT NULL) THEN 'municipality'
			            ELSE 'unknown'
			       END) user_type
			FROM users u
			LEFT OUTER JOIN
			    companies c
			ON
			    u.user_id = c.user_id
			LEFT OUTER JOIN
			    government_agencies ga
			ON
			    u.user_id = ga.user_id
			LEFT OUTER JOIN
			    individuals i
			ON
			    u.user_id = i.user_id
			LEFT OUTER JOIN
			    municipalities m
			ON
			    u.user_id = m.user_id
			WHERE u.username = :username
		";
        */

	}

	public static function getEsfs()
	{

		/* TAN: Replaced with Stored Procedure
		$query = "
			-- Lookup ESFs:
			SELECT e.esf_id, e.description
			FROM esfs e
			ORDER BY esf_id ASC
		";
		*/

		$query = "CALL spGetEsfs();";
		$preparedStatement = self::$db->prepare($query);
        $preparedStatement->execute();

        $rows = $preparedStatement->fetchAll();

		return $rows;
	}

	public static function getIncidents()
	{
		// TAN: Method created for backwards compatibility
		//		I renamed the below method to show
		//		it can be called with Incident ID
		return self::getIncidentsByUserId(null);
	}

	public static function getIncidentsByUserId($user_id = null)
	{
		// Allows the user_id to be an optional parameter:
		if (is_null($user_id))
		{
			$user = self::$container->session->getSegment('user')->get('info', null);
			$user_id = $user->user_id;
		}


		/* TAN: Converted to Stored Procedure
		$query = "
			-- Lookup Incidents:
			SELECT i.incident_id, i.description
			FROM incidents i
			WHERE user_id = :user_id
			ORDER BY incident_id ASC
		";
		*/
		$query = "CALL spGetIncidentByUserId(:user_id);";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':user_id', $user_id);
        $preparedStatement->execute();

        $rows = $preparedStatement->fetchAll();

		return $rows;
	}

	public static function getIncidentById($incident_id)
	{

		/* TAN: Converted to Stored Procedure
		$query = "
			-- Lookup Incidents:
			SELECT i.incident_id, i.description, i.user_id, i.date, i.latitude, i.longitude
			FROM incidents i
			WHERE incident_id = :incident_id
			ORDER BY incident_id ASC
		";
		*/

		$query = "CALL spGetIncidentById(:incident_id);";
		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':incident_id', $incident_id);
        $preparedStatement->execute();

        $row = $preparedStatement->fetchObject();

		return $row;
	}

	public static function getCostIntervals()
	{

		/* TAN: Converted to Stored Procedure
		$query = "
			-- Lookup Cost Intervals
			SELECT ci.cost_interval_id, ci.description
			FROM cost_intervals ci
			ORDER BY cost_interval_id ASC
		";
		*/
		$query = "CALL spGetCostIntervals();";

		$preparedStatement = self::$db->prepare($query);
        $preparedStatement->execute();

        $rows = $preparedStatement->fetchAll();

		return $rows;
	}

	public static function insertResource($user_id=null,$name,$model,$cost_amount,$cost_interval_id,$primary_esf_id,$latitude,$longitude)
	{
		// Allows the user_id to be an optional parameter:
		if (is_null($user_id))
		{
			$user = self::$container->session->getSegment('user')->get('info', null);
			$user_id = $user->user_id;
		}



		/* TAN: Converted to Stored Procedure
		$query = "
			-- DONE: Task sql omar add user_id to add resource
			INSERT INTO resources (
			    user_id,
			    name,
			    model,
			    cost_amount,
			    cost_interval_id,
			    primary_esf_id,
			    latitude,
			    longitude
			) VALUES (
			    :user_id,
			    :name,
			    :model,
			    :cost_amount,
			    :cost_interval_id,
			    :primary_esf_id,
			    :latitude,
			    :longitude
			);
			SELECT LAST_INSERT_ID() as last_id;
		";
		*/
		$query = "CALL spInsertResource(
			:user_id,
			:name,
			:model,
			:cost_amount,
			:cost_interval_id,
			:primary_esf_id,
			:latitude,
			:longitude
		);
		SELECT LAST_INSERT_ID() as last_id;
		";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':user_id', $user_id);
		$preparedStatement->bindValue(':name', $name);
		$preparedStatement->bindValue(':model', $model);
		$preparedStatement->bindValue(':cost_amount', $cost_amount);
		$preparedStatement->bindValue(':cost_interval_id', $cost_interval_id);
		$preparedStatement->bindValue(':primary_esf_id', $primary_esf_id);
		$preparedStatement->bindValue(':latitude', $latitude);
		$preparedStatement->bindValue(':longitude', $longitude);
        $preparedStatement->execute();

        $preparedStatement->nextRowset();
        $newId = $preparedStatement->fetchColumn();

		return $newId;
	}

	public static function insertCapability($description)
	{
		/* TAN: Converted to Stored Procedure
		$query = "
			INSERT INTO capabilities (
			    description
			) VALUES (
			    :description
			) ON DUPLICATE KEY UPDATE
			    description = :description
			;
		";
		*/

		$query = "CALL spInsertCapability(
			:description
		);
		SELECT LAST_INSERT_ID() as last_id;
		";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':description', $description);
        $preparedStatement->execute();

        $preparedStatement->nextRowset();
        $newId = $preparedStatement->fetchColumn();

		return $newId;
	}

	public static function getCapabilityId($description)
	{
		/* TAN: Converted to Stored Procedure
		$query = "
			SELECT capability_id as cid
			FROM capabilities
			WHERE description = :description;
		";
		*/
		$query = "CALL spGetCapabilityId(:description);";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':description', $description);
        $preparedStatement->execute();

        $capability_id = $preparedStatement->fetchColumn();

		return $capability_id;
	}

	// clear any existing capabilities for a resource
	public static function deleteAllCapabilitiesForResource($resource_id)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- Combination must not exist otherwise
			-- INSERT will fail.
			-- DONE: Consider below
			    -- OMAR: Added in the extra DELETE query.
			-- One strategy might be to delete all entries for a particular
			-- resource prior to inserting the updated list of capabilities.

			-- Clears out the current Capabilities List for a Resource first:
			DELETE FROM resource_capabilities
			WHERE resource_id = :resource_id;

		";
		*/

		$query = "CALL spGetCapabilityId(:resource_id);";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':resource_id', $resource_id);
        $preparedStatement->execute();

		return;
	}

	public static function insertResourceCapability($resource_id,$capability_id)
	{
		/* TAN: Converted to Stored Procedure
		$query = "
			-- Insert the Capabilities for the Resource:
			INSERT INTO resource_capabilities (
			    resource_id,
			    capability_id
			) VALUES (
			    :resource_id,
			    :capability_id
			);
		";
		*/

		$query = "CALL spInsertResourceCapability(
			:resource_id, :capability_id
		);
		SELECT LAST_INSERT_ID() as last_id;
		";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':capability_id', $capability_id);
        $preparedStatement->execute();

        $preparedStatement->nextRowset();
        $newId = $preparedStatement->fetchColumn();

		return $newId;
	}

	// clear any existing additional esfs for a resource to prevent any conflicts when inserting
	public static function deleteAllAdditionalEsfsForResource($resource_id)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- Clear out Additional ESFs for Particular Resource ID:
			DELETE FROM resource_additional_esfs
			WHERE resource_id = :resource_id
		";
		*/

		$query = "CALL spDeleteAllAdditionalEsfsForResource(:resource_id);";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':resource_id', $resource_id);
        $preparedStatement->execute();

		return;
	}

	public static function insertResourceAdditionalEsf($resource_id,$esf_id)
	{


		/* TAN: Converted to Stored Procedure
		$query = "
			-- Insert all Additional ESFs for a Resource:
			INSERT INTO resource_additional_esfs (
			    resource_id,
			    esf_id
			) VALUES (
			    :resource_id,
			    :esf_id
			);
		";
		*/
		$query = "CALL spInsertResourceAdditionalEsf(
			:resource_id, :esf_id
		);
		SELECT LAST_INSERT_ID() as last_id;
		";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':esf_id', $esf_id);
        $preparedStatement->execute();

        $preparedStatement->nextRowset();
        $newId = $preparedStatement->fetchColumn();

		return $newId;
	}

	// remove Primary ESF from Additional ESFs for a given Resource (just in case)
	public static function deleteResourcePrimaryEsfFromAdditionalEsf(
		$primary_esf_id, $resource_id
	)
	{
		return "
			-- If not handled in the application code already
			-- We can additionally ensure that the Primary ESF ID
			-- is not included in the Additional ESF List for a
			-- Resource.
			DELETE FROM resource_additional_esfs
			WHERE esf_id = :primary_esf_id
			AND resource_id = :resource_id
		";

		$query = "CALL spDeleteResourcePrimaryEsfFromAdditionalEsf(
			:resource_id, :esf_id
		)";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':primary_esf_id', $primary_esf_id);
		$preparedStatement->bindValue(':resource_id', $resource_id);
        $preparedStatement->execute();

		return;

	}

	public static function insertIncident($user_id=null,$description,$date,$latitude,$longitude)
	{
		// Allows the user_id to be an optional parameter:
		if (is_null($user_id))
		{
			$user = self::$container->session->getSegment('user')->get('info', null);
			$user_id = $user->user_id;
		}


		/* TAN: Converted to Stored Procedure
		$query = "
			-- Add Incident
			INSERT INTO incidents (
			    user_id,
			    description,
			    date,
			    latitude,
			    longitude
			) VALUES (
			    :user_id,
			    :description,
			    DATE(:date),
			    :latitude,
			    :longitude
			);
		";
		*/
		$query = "CALL spInsertIncident(
			:user_id, :description, :date, :latitude, :longitude
		);
		SELECT LAST_INSERT_ID() as last_id;
		";

		$preparedStatement = self::$db->prepare($query);
		$preparedStatement->bindValue(':user_id', $user_id);
		$preparedStatement->bindValue(':description', $description);
		$preparedStatement->bindValue(':date', $date);
		$preparedStatement->bindValue(':latitude', $latitude);
		$preparedStatement->bindValue(':longitude', $longitude);
        $preparedStatement->execute();

        $preparedStatement->nextRowset();
        $newId = $preparedStatement->fetchColumn();

		return $newId;

	}

	public static function searchResource($search_keyword, $search_esf, $search_radius, $search_incident)
	{

		/* TAN: NOT Converted to Stored Proecdure
				This SQL statement is built dynamically

			TODO: Consider if using SP in future
					Where clauses can be constructed to check with NULL Or VALUE statements
		*/

		$keywordQueryPart = '';
		if (!empty($search_keyword))
		{
			// Prep this variable for use in the LIKE clause:
			$search_keyword = '%' . $search_keyword . '%';

			$keywordQueryPart = " WHERE (
			    r.name LIKE :keyword OR
			    r.model LIKE :keyword OR
			    r.resource_id IN (SELECT rc.resource_id
			                      FROM resource_capabilities rc
			                      WHERE rc.capability_id IN (SELECT c.capability_id
			                                                 FROM capabilities c
			                                                 WHERE c.description LIKE :keyword))
			)";
		}

		$esfQueryPart = '';
		if (!empty($search_esf))
		{
			$esfQueryPartStart = ' WHERE ';
			if (!empty($search_keyword))
			{
				$esfQueryPartStart = ' AND ';
			}
			$esfQueryPart = "
			{$esfQueryPartStart} (
			    r.primary_esf_id = :search_esf OR
			    r.resource_id IN (SELECT rae.resource_id
			                      FROM resource_additional_esfs rae
			                      WHERE rae.esf_id = :search_esf)
			)";
		}

		$radiusQueryPart = '';
		if (!empty($search_radius))
		{
			// Only applicable if an incident has been selected too:
			if (!empty($search_incident))
			{
				$radiusQueryPart = " WHERE results.distance_from_incident_in_km < :search_radius";
			}
		}

		$orderByQueryPart = '';
		$incidentQueryPart = '';
		if (!empty($search_incident))
		{
			$incidentQueryPart = ", get_distance_from_incident(:search_incident_id, r.resource_id, 'km') distance_from_incident_in_km";
			$orderByQueryPart = ' ORDER BY results.distance_from_incident_in_km ASC, results.name ASC';
		}
		else
		{
			$orderByQueryPart = ' ORDER BY results.name ASC';
		}

		$query = "
		-- Searching Resources:

		-- Will need to append/prepend % on keyword placeholder
		-- prior to passing to the query.

		-- Search Queries are Tricky because it makes sense not to
		-- include portions of the query that are not needed
		-- depending on how specific or generic the search is
		-- (so not all of the filters below may be needed all of the time).

		-- DONE: Integrate in Resource Status Info
		-- DONE: Integrate Custom Functions:

		-- TODO: requires 'search_indcident_id' but some searches may not have an associated incident
		-- Version 2: Using our Custom Functions
		SELECT results.*
		FROM (SELECT r.resource_id,
			       r.user_id,
			       u.name owner,
			       r.name,
			       r.model,
			       r.cost_amount,
			       r.cost_interval_id,
			       ci.description cost_interval_description,
			       r.primary_esf_id,
			       r.latitude,
			       r.longitude,
			       get_resource_status(r.resource_id) resource_status
				   {$incidentQueryPart}
			FROM resources r
			INNER JOIN
			    users u
			ON
			    r.user_id = u.user_id
			INNER JOIN
			    cost_intervals ci
			ON
			    r.cost_interval_id = ci.cost_interval_id
			{$keywordQueryPart}
			{$esfQueryPart}
		) as results
		{$radiusQueryPart}
		{$orderByQueryPart}
		";

		$preparedStatement = self::$db->prepare($query);

		if (!empty($search_keyword))
		{
			$preparedStatement->bindValue(':keyword', $search_keyword);
		}

		if (!empty($search_esf))
		{
			$preparedStatement->bindValue(':search_esf', $search_esf, \PDO::PARAM_INT);
		}

		if (!empty($search_radius))
		{
			$preparedStatement->bindValue(':search_radius', $search_radius, \PDO::PARAM_INT);
		}

		if (!empty($search_incident))
		{
			$preparedStatement->bindValue(':search_incident_id', $search_incident, \PDO::PARAM_INT);
		}

        $preparedStatement->execute();

        $rows = $preparedStatement->fetchAll();

        return $rows;
	}

	public static function getInRepairResourcesReturnDate($data)
	{
		$rows = array();

		if (!empty($data))
		{
			$inRepairResourceIds = array();
			foreach($data as $resource)
			{
				if ($resource->resource_status === 'in-repair')
				{
					$inRepairResourceIds[] = (int) $resource->resource_id;
				}
			}

			if (!empty($inRepairResourceIds))
			{
				$query = "SELECT resource_id, end_date
						  FROM repairs
						  WHERE resource_id IN (" . implode(',', $inRepairResourceIds) . ")
						  AND repair_status_id = 2";

				$preparedStatement = self::$db->prepare($query);

				$preparedStatement->execute();

				while ($result = $preparedStatement->fetch(\PDO::FETCH_OBJ))
				{
					$rows[$result->resource_id] = $result;
				}
			}
		}

        return $rows;
	}

	public static function getInUseResourcesReturnDate($data)
	{
		$rows = array();

		if (!empty($data))
		{
			$inUseResourceIds = array();
			foreach($data as $resource)
			{
				if ($resource->resource_status === 'in-use')
				{
					$inUseResourceIds[] = (int) $resource->resource_id;
				}
			}

			if (!empty($inUseResourceIds))
			{
				/*$query = "SELECT resource_id, expected_return_date
						  FROM resource_requests
						  WHERE resource_id IN (" . implode(',', $inUseResourceIds) . ")
						  AND request_status_id = 2";*/

				$query = "SELECT rr.resource_id, rr.expected_return_date, r.end_date
						  FROM resource_requests rr
                          LEFT OUTER JOIN
								repairs r
							ON
								r.resource_id = rr.resource_id
							    AND rr.expected_return_date = DATE_SUB(r.start_date, INTERVAL 1 DAY)
													  WHERE rr.resource_id IN (" . implode(',', $inUseResourceIds) . ")
													  AND rr.request_status_id = 2";

				$preparedStatement = self::$db->prepare($query);

				$preparedStatement->execute();

				while ($result = $preparedStatement->fetch(\PDO::FETCH_OBJ))
				{
					if (!empty($result->end_date))
					{
						$result->expected_return_date = $result->end_date;
					}

					$rows[$result->resource_id] = $result;
				}
			}

		}

        return $rows;
	}

	public static function getPreviouslyDeployedResourcesForIncident($incident)
	{
		$rows = array();

		if (!empty($incident))
		{
			$query = "SELECT resource_id
						FROM `resource_requests`
						WHERE request_status_id = 3
						AND incident_id = :incident_id";

			$preparedStatement = self::$db->prepare($query);

			$preparedStatement->bindValue(':incident_id', $incident->incident_id, \PDO::PARAM_INT);

			$preparedStatement->execute();

			while ($result = $preparedStatement->fetch(\PDO::FETCH_OBJ))
			{
				$rows[$result->resource_id] = $result->resource_id;
			}
		}

        return $rows;
	}

	public static function getResourceStatus($resource_id)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- Resource Status Query
			-- NOTE: This isn't the query for the Resource Status View
			-- in the application...it's simply a query that returns
			-- the current status of a Resource (Available, In Use, In Repair).

			-- Took me a while to get through a few issues
			-- regarding how to correctly assemble the inner
			-- queries for some reason.

			-- If desired both this and the Haversine functionality
			-- can be turned into SQL functions and help improve the
			-- Query Readability...I've never really written any MySQL
			-- functions but it's something worth looking into.

			-- Version 2 (Using our Custom Function)
			SELECT get_resource_status(:resource_id) resource_status;
		";
		*/

		$query = "CALL spGetResourceStatus(:resource_id);";
		$preparedStatement = self::$db->prepare($query);
        $preparedStatement->bindValue(':resource_id', $resource_id);
        $preparedStatement->execute();

        $value = $preparedStatement->fetchObject();

        return $value;

	}

	public static function getResourcesDeployedToUser()
	{
		// TAN: Converted to Stored Procedure
		$query = "
			-- Resources In Use Responding to Current User's Incidents:
			-- DONE: Noticed the Project Description shows a Start Date for this section
			    -- However, the Resource Requests table currently doesn't have such a field.
			SELECT r.resource_id,
			       r.user_id,
			       r.name,
			       r.model,
			       r.cost_amount,
			       r.cost_interval_id,
			       r.primary_esf_id,
			       rr.start_date,
			       rr.expected_return_date,
			       i.incident_id,
			       i.description,
			       u.username resource_owner_username,
			       u.name resource_owner_name
			FROM resource_requests rr
			INNER JOIN
			    request_status rs
			ON
			    rs.request_status_id = rr.request_status_id AND rs.description = 'deployed'
			INNER JOIN
			    incidents i
			ON
			    rr.incident_id = i.incident_id
			    AND
			    -- Only get incidents owned by Current User:
			    i.user_id = :current_user_id
			INNER JOIN
			    resources r
			ON
			    r.resource_id = rr.resource_id
			INNER JOIN
			    users u
			ON
			    -- Need to get the Resource Owner User Info:
			    r.user_id = u.user_id
		";

		/*$query = "CALL spGetResourcesDeployedToUser(
			:current_user_id
		)";*/

    	$db = self::$container->db;

        $preparedStatement = $db->prepare($query);

        $user = self::$container->session->getSegment('user')->get('info', null);
        $user_id = $user->user_id;
        $preparedStatement->bindValue(':current_user_id', $user_id);
        $preparedStatement->execute();
        $rows = $preparedStatement->fetchAll();

        return $rows;

	}

	public static function getResourceRequestsSentFromUser()
	{
		/* TAN: Converted to Stored Procedure
		$query = "
			-- Resources Requested By Me
			--        Description: Resource Requests Created by User:

			SELECT r.resource_id,
			       r.user_id,
			       r.name,
			       r.model,
			       r.cost_amount,
			       r.cost_interval_id,
			       r.primary_esf_id,
			       rr.start_date,
			       rr.expected_return_date,
			       i.incident_id,
			       i.description,
			       u.username resource_owner_username,
			       u.name resource_owner_name
			FROM resources r
			INNER JOIN
			    resource_requests rr
			ON
			    -- Need the Start / Return Dates:
			    r.resource_id = rr.resource_id
			    AND
			    -- Only retrieve pending requests:
			    rr.request_status_id = 1
			INNER JOIN
			    incidents i
			ON
			    -- Include Incident Info:
			    rr.incident_id = i.incident_id
			    AND
			    -- Only get Resources Requested by Current User:
			    i.user_id = :current_user_id
			INNER JOIN
			    users u
			ON
			    -- Need to get the Resource Owner User Info:
			    r.user_id = u.user_id
		";
		*/
		$query = "CALL spGetResourceRequestsSentFromUser(
			:current_user_id
		)";



       $db = self::$container->db;

        $preparedStatement = $db->prepare($query);

        $user = self::$container->session->getSegment('user')->get('info', null);
        $user_id = $user->user_id;
        $preparedStatement->bindValue(':current_user_id', $user_id);
        $preparedStatement->execute();
        $rows = $preparedStatement->fetchAll();

        return $rows;



	}

	public static function getResourceRequestsSentToUser()
	{
		/* TAN: Converted to Stored Procedure
		$query = "
			-- Resource Requests Received by User:
			SELECT r.resource_id,
			       r.user_id,
			       r.name,
			       r.model,
			       r.cost_amount,
			       r.cost_interval_id,
			       r.primary_esf_id,
			       rr.start_date,
			       rr.expected_return_date,
			       i.user_id requester_user_id,
			       u2.username requester_username,
			       u2.name requester_name,
			       i.incident_id,
			       i.description,
			       u.username resource_owner_username,
			       u.name resource_owner_name
			FROM resources r
			INNER JOIN
			    resource_requests rr
			ON
			    -- Need the Start / Return Dates:
			    r.resource_id = rr.resource_id
			    AND
			    -- Only retrieve pending requests:
			    rr.request_status_id = 1
			INNER JOIN
			    incidents i
			ON
			    -- Include Incident Info:
			    rr.incident_id = i.incident_id
			INNER JOIN
			    users u
			ON
			    -- Need to get the Resource Owner User Info:
			    r.user_id = u.user_id
			INNER JOIN
			    users u2
			ON
			    -- Need to get the Resource Owner User Info:
			    i.user_id = u2.user_id
			WHERE
			    -- Only get Resource Requests for Resources owned by Current User:
			    r.user_id = :current_user_id
		";
		*/
		$query = "CALL spGetResourceRequestsSentToUser(
			:current_user_id
		);";



        $preparedStatement = self::$db->prepare($query);
        $user = self::$container->session->getSegment('user')->get('info', null);
        $user_id = $user->user_id;
        $preparedStatement->bindValue(':current_user_id', $user_id);
        $preparedStatement->execute();
        $rows = $preparedStatement->fetchAll();

        return $rows;
	}


	public static function getRepairsScheduledOrInProgress()
	{
		/* TAN: Converted to Stored Procedure
		$query = "
			-- Repairs Scheduled/In-Progress
			SELECT r.resource_id,
			       r.user_id,
			       r.name,
			       r.model,
			       r.cost_amount,
			       r.cost_interval_id,
			       r.primary_esf_id,
			       re.repair_status_id,
			       re.start_date,
			       re.end_date
			FROM resources r
			INNER JOIN
			    repairs re
			ON
			    -- Need the Start / Return Dates:
			    r.resource_id = re.resource_id
			WHERE
			    -- Only Shows Resources Owned by Current User:
			    r.user_id = :current_user_id
			    -- Pending and In-Progress Repairs whose end_date has not passed:
			AND    re.repair_status_id < 3
			AND SYSDATE() <= re.end_date
		";
		*/
		$query = "CALL spGetRepairsScheduledOrInProgress(
			:current_user_id
		);";



        $preparedStatement = self::$db->prepare($query);
        $user = self::$container->session->getSegment('user')->get('info', null);
        $user_id = $user->user_id;

        $preparedStatement->bindValue(':current_user_id', $user_id);
        $preparedStatement->execute();
        $rows = $preparedStatement->fetchAll();

        return $rows;
	}

	public static function getResourceReport()
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- Resource Report
			    -- TODO: Need to add more sample data to help verify if this query is
			    -- doing as it is supposed to.
			SELECT e.esf_id,
			       e.description,
			       COUNT(r.resource_id) total_resources,
			       COUNT(r2.resource_id) total_resources_in_use
			FROM esfs e
			-- Must do Right Outer Join to keep all ESF Rows:
			LEFT OUTER JOIN
			    resources r
			ON
			    -- Join on Primary ESF ID Only:
			    e.esf_id = r.primary_esf_id
			    -- Only Shows Resources Owned by Current User:
			    AND
			    r.user_id = :current_user_id
			LEFT OUTER JOIN
			    resources r2
			ON
			    -- Join on Primary ESF ID Only:
			    e.esf_id = r2.primary_esf_id
			    -- Only Shows Resources Owned by Current User:
			    AND
			    r2.user_id = :current_user_id
			    -- Only Include In-Use Resources:
			    -- TODO: Maybe include in-repair items too?
			    AND
			    get_resource_status(r2.resource_id) = ('in-use' COLLATE utf8mb4_general_ci)
			GROUP BY e.esf_id
			ORDER BY e.esf_id
		";
		*/

		$query = "CALL spGetResourceReport(
			:current_user_id
		);";



        $preparedStatement = self::$db->prepare($query);
        $user = self::$container->session->getSegment('user')->get('info', null);
        $user_id = $user->user_id;

        $preparedStatement->bindValue(':current_user_id', $user_id);
        $preparedStatement->execute();
        $rows = $preparedStatement->fetchAll();

        return $rows;
	}


    public static function getResourceReportTotals($current_user_id)
    {

        $query = "CALL spGetResourceReportTotals(
			:current_user_id
		);";



        $preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':current_user_id', $current_user_id);
        $preparedStatement->execute();
        $rows = $preparedStatement->fetchAll();

        return $rows;
    }

	public static function getResourceInfoById($resource_id)
    {
		$resource = null;

        $query = "SELECT r.*, u.username user_username, u.name as user_name, e.description esf_description, ci.description cost_interval_description
					FROM resources r
                    INNER JOIN
                    	users u
                    ON
                    	r.user_id = u.user_id
					INNER JOIN
						esfs e
					ON
						r.primary_esf_id = e.esf_id
					INNER JOIN
						cost_intervals ci
					ON
						r.cost_interval_id = ci.cost_interval_id
					WHERE r.resource_id = :resource_id";



        $preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
        $preparedStatement->execute();
        $resource = $preparedStatement->fetchObject();

        if (!empty($resource))
        {
			$query = "SELECT ae.esf_id, e.description esf_description
						FROM resource_additional_esfs ae
						INNER JOIN
							esfs e
						ON
							ae.esf_id = e.esf_id
						WHERE ae.resource_id = :resource_id";

	        $preparedStatement = self::$db->prepare($query);
	        $preparedStatement->bindValue(':resource_id', $resource_id);
	        $preparedStatement->execute();
	        $additionalEsfs = $preparedStatement->fetchAll();

	        $resource->additionalEsfs = $additionalEsfs;

	        $query = "SELECT rc.capability_id, c.description capability_description
						FROM resource_capabilities rc
						INNER JOIN
							capabilities c
						ON
							rc.capability_id = c.capability_id
						WHERE rc.resource_id = :resource_id";

	        $preparedStatement = self::$db->prepare($query);
	        $preparedStatement->bindValue(':resource_id', $resource_id);
	        $preparedStatement->execute();
	        $capabilities = $preparedStatement->fetchAll();

	        $resource->capabilities = $capabilities;
        }

        return $resource;
    }

	public static function rejectRequest($resource_id, $incident_id)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- NOTE: Same as Cancel Request. Just delete request.
			-- Reject Request
			DELETE FROM resource_requests
			WHERE resource_id = :resource_id AND incident_id = :incident_id
		";
		*/
		$query = "CALL spRejectRequest(
			:resource_id, :incident_id
		);";

        $preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':incident_id', $incident_id);
        $preparedStatement->execute();

        return true;
	}

	public static function cancelRequest($resource_id, $incident_id)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- NOTE: Same as Reject Request. Just delete request.
			-- Cancel Request
			DELETE FROM resource_requests
			WHERE resource_id = :resource_id AND incident_id = :incident_id
		";
		*/
		$query = "CALL spCancelRequest(
			:resource_id, :incident_id
		);";

		$preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':incident_id', $incident_id);
        $preparedStatement->execute();

        return true;


	}

	public static function deployOwnResource($resource_id, $incident_id, $expected_return_date)
	{
		$query = "INSERT INTO resource_requests
					VALUES (:resource_id, :incident_id, 2, SYSDATE(), :expected_return_date)";

		$preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':incident_id', $incident_id);
		$preparedStatement->bindValue(':expected_return_date', $expected_return_date);
        $preparedStatement->execute();
		return true;
	}

	public static function spDeployResource($resource_id, $incident_id)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- TODO: should we consider alernative to using '2'. should it be done via 'deployed'? is this possible with joins, etc?
			-- TODO: start date set to today. if resource owner took a long time to deploy, it's possible that start_date is after
			--       expected_return_date. decide how to handle this situation
			-- Deploy Resource
			UPDATE resource_requests
			SET request_status_id = 2, start_date = SYSDATE()
			WHERE resource_id = :resource_id AND incident_id = :incident_id
		";
		*/
		$query = "CALL spDeployResource(
			:resource_id, :incident_id
		);";

		$preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':incident_id', $incident_id);
        $preparedStatement->execute();
		return true;
	}

	public static function returnResource($resource_id, $incident_id)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- TODO: should we consider alernative to using '3'. should it be done via 'completed'? is this possible with joins, etc?
			-- TODO: also, this should potentially trigger check for scheduled repairs. if a repair is scheduled,
			--       repair_status should change to 'in-progress'. if not done in SQL, include in php.
			-- Return Resource
			UPDATE resource_requests
			SET request_status_id = 3
			WHERE resource_id = :resource_id AND incident_id = :incident_id
		";
		*/
		$query = "CALL spReturnResource(
			:resource_id, :incident_id
		);";

		$preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':incident_id', $incident_id);
        $preparedStatement->execute();

        return true;

	}

	public static function requestResource($resource_id, $incident_id, $expected_return_date)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- TODO: reminder that expected return date should be >= today. validate in PHP or add SQL trigger that rejects past dates.
			-- Request Resource
			INSERT INTO resource_requests
			VALUES (:resource_id, :incident_id, 1, SYSDATE(), :expected_return_date)
		";
		*/
		$query = "CALL spRequestResource(
			:resource_id, :incident_id, :expected_return_date
		);";

		$preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':incident_id', $incident_id);
		$preparedStatement->bindValue(':expected_return_date', $expected_return_date);

		try {
			$result = $preparedStatement->execute();
		} catch (\PDOException $e) {
			// This is so we can ignore the duplicate key entries without
			// causing an exception to be thrown:
			//die($e->getCode());
			if ($e->getCode() === 23000)
			{
				throw $e;
			}
		}

        return $result;
	}


	public static function schedulePendingRepair($resource_id, $duration)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- TODO: Start and End Date may cause tricky problems. ultimately need to decide how to handle them when repair would be pending.
			--       If resource is unavailable for immediate repair, then we won't know end date. if we don't allow null, then a date must be picked.
			--       The date we pick will have consequences for other functions e.g. 'Scheduled/In-Progress Repairs' view.
			-- Schedule Pending Repair
			INSERT INTO repairs
			VALUES (NULL, :resource_id, 1, :duration, NULL, NULL)
		";
		*/
		$query = "CALL spSchedulePendingRepair(
			:resource_id, :duration
		);";

		$preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':duration', $duration);
        try {
			$result = $preparedStatement->execute();
        } catch(\PDOException $e) {
			// This is so we can ignore the duplicate key entries without
			// causing an exception to be thrown:
			//die($e->getCode());
			if ($e->getCode() === 23000)
			{
				throw $e;
			}
        }

        return $result;
	}


	public static function scheduleImmediateRepair($resource_id, $duration)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- Schedule Immediate Repair
			INSERT INTO repairs
			VALUES (NULL, :resource_id , 2, :duration, SYSDATE(), DATE_ADD(SYSDATE(),INTERVAL :duration DAY));
		";
		*/
		$query = "CALL spScheduleImmediateRepair(
			:resource_id, :duration
		);";

		$preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':resource_id', $resource_id);
		$preparedStatement->bindValue(':duration', $duration);

        try {
			$result = $preparedStatement->execute();
        } catch(\PDOException $e) {
			// This is so we can ignore the duplicate key entries without
			// causing an exception to be thrown:
			//die($e->getCode());
			if ($e->getCode() === 23000)
			{
				throw $e;
			}
        }

        return $rows;
	}

	public static function cancelRepair($repair_id, $resource_id)
	{
		/* TAN: Converted to Stored Procedure
		return "
			-- NOTE: technically only need repair ID to delete but using resource_id since repairs is weak entity
			--       and primary key is defined as (repair_id,resource_id) per lectures.
			-- Cancel Repair
			DELETE FROM repairs
			WHERE repair_id = :repair_id AND resource_id = :resource_id
		";
		*/
		$query = "CALL spCancelRepair(
			:repair_id, :resource_id
		);";

		$preparedStatement = self::$db->prepare($query);

        $preparedStatement->bindValue(':repair_id', $repair_id);
		$preparedStatement->bindValue(':resource_id', $resource_id);
        $preparedStatement->execute();
        return true;

	}

}
