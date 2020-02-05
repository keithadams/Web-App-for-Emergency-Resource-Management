/************************************************************
    Team 072 - Phase III SQL Stored Procedure Creation Statements
    
    Craig Foster - cfoster46         
    Keith Adams - kadams62 
    Thomas Neuman - tneuman6     
    Omar Ramos - oramos6 

    Designed to run on RackSpace database phpMyAdmin Server
    
************************************************************/

DELIMITER //

-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetUserWithUsernamePassword//
CREATE PROCEDURE spGetUserWithUsernamePassword (
	in _username varchar(50),
	in _password varchar(255)
)
BEGIN
	-- The below query is assuming a simple 
	-- Cleartext Password check.
	SELECT u.user_id,
		   u.username,
		   u.name
	FROM users u
	WHERE u.username = _username
	AND u.password = _password;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetUserByUserId//
CREATE PROCEDURE spGetUserByUserId (
    in _user_id int(11) unsigned
)
BEGIN
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
    WHERE u.user_id = _user_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetUserByUserName//
CREATE PROCEDURE spGetUserByUserName (
	in _username varchar(50)
)
BEGIN
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
	WHERE u.username = _username;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetEsfs//
CREATE PROCEDURE spGetEsfs ()
BEGIN
	-- Lookup ESFs:
	SELECT e.esf_id, e.description
	FROM esfs e
	ORDER BY esf_id ASC;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetIncidentByUserId//
CREATE PROCEDURE spGetIncidentByUserId (
	in _user_id int(11) unsigned
)
BEGIN
	-- Lookup Incidents:
	SELECT i.incident_id, i.description
	FROM incidents i
	WHERE user_id = _user_id
	ORDER BY incident_id ASC;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetIncidentById//
CREATE PROCEDURE spGetIncidentById (
	in _incident_id int(11) unsigned
)
BEGIN
	-- Lookup Incidents:
	SELECT i.incident_id, i.description, i.user_id, i.date, i.latitude, i.longitude
	FROM incidents i
	WHERE incident_id = _incident_id
	ORDER BY incident_id ASC;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetCostIntervals//
CREATE PROCEDURE spGetCostIntervals ()
BEGIN
	-- Lookup Cost Intervals
	SELECT ci.cost_interval_id, ci.description
	FROM cost_intervals ci
	ORDER BY cost_interval_id ASC;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spInsertResource//
CREATE PROCEDURE spInsertResource (
		in _user_id int(11) unsigned,
		in _name varchar(50),
		in _model varchar(50),
		in _cost_amount int(11) unsigned,
		in _cost_interval_id int(11) unsigned,
		in _primary_esf_id int(11) unsigned,
		in _latitude decimal(10, 7),
		in _longitude decimal(10,7)
)
BEGIN
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
		_user_id,
		_name,
		_model,
		_cost_amount,
		_cost_interval_id,
		_primary_esf_id,
		_latitude,
		_longitude
	);
	-- Additionally, the following select statement is executed from the Queries.php class file
	-- SELECT LAST_INSERT_ID() as last_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spInsertCapability//
CREATE PROCEDURE spInsertCapability (
	in _description varchar(50)
)
BEGIN
	-- Prior to inserting must check if
	-- record already exists, otherwise
	-- INSERT will fail.
	INSERT INTO capabilities (
		description
	) VALUES (
		_description
	) ON DUPLICATE KEY UPDATE
		description = _description;
	-- Additionally, the following select statement is executed from the Queries.php class file
	-- SELECT LAST_INSERT_ID() as last_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetCapabilityId//
CREATE PROCEDURE spGetCapabilityId (
	in _description varchar(50)
)
BEGIN
	SELECT capability_id as cid
	FROM capabilities
	WHERE description = _description;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spDeleteAllCapabilitiesForResource//
CREATE PROCEDURE spDeleteAllCapabilitiesForResource (
	in _resource_id int(11) unsigned
)
BEGIN
	-- Combination must not exist otherwise
	-- INSERT will fail.
	-- DONE: Consider below
		-- OMAR: Added in the extra DELETE query.
	-- One strategy might be to delete all entries for a particular
	-- resource prior to inserting the updated list of capabilities.

	-- Clears out the current Capabilities List for a Resource first:
	DELETE FROM resource_capabilities
	WHERE resource_id = _resource_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spInsertResourceCapability//
CREATE PROCEDURE spInsertResourceCapability (
	in _resource_id int(11) unsigned,
	in _capability_id int(11) unsigned
)
BEGIN
	-- Insert the Capabilities for the Resource:
	INSERT INTO resource_capabilities (
		resource_id,
		capability_id
	) VALUES (
		_resource_id,
		_capability_id
	);
	-- Additionally, the following select statement is executed from the Queries.php class file
	-- SELECT LAST_INSERT_ID() as last_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spDeleteAllAdditionalEsfsForResource//
CREATE PROCEDURE spDeleteAllAdditionalEsfsForResource (
	in _resource_id int(11) unsigned
)
BEGIN
	-- Clear out Additional ESFs for Particular Resource ID:
	DELETE FROM resource_additional_esfs
	WHERE resource_id = _resource_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spInsertResourceAdditionalEsf//
CREATE PROCEDURE spInsertResourceAdditionalEsf (
	in _resource_id int(11) unsigned,
	in _esf_id int(11) unsigned
)
BEGIN
	-- Insert all Additional ESFs for a Resource:
	INSERT INTO resource_additional_esfs (
		resource_id,
		esf_id
	) VALUES (
		_resource_id,
		_esf_id
	);
	-- Additionally, the following select statement is executed from the Queries.php class file
	-- SELECT LAST_INSERT_ID() as last_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spDeleteResourcePrimaryEsfFromAdditionalEsf//
CREATE PROCEDURE spDeleteResourcePrimaryEsfFromAdditionalEsf (
	in _primary_esf_id int(11) unsigned,
	in _resource_id int(11) unsigned
)
BEGIN
	-- If not handled in the application code already
	-- We can additionally ensure that the Primary ESF ID
	-- is not included in the Additional ESF List for a
	-- Resource.
	DELETE FROM resource_additional_esfs
	WHERE esf_id = _primary_esf_id
	AND resource_id = _resource_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spInsertIncident//
CREATE PROCEDURE spInsertIncident (
	in _user_id int(11) unsigned,
	in _description varchar(100),
	in _date datetime,
	in _latitude decimal(10, 7),
	in _longitude decimal(10, 7)

)
BEGIN
	-- Add Incident
	INSERT INTO incidents (
		user_id,
		description,
		date,
		latitude,
		longitude
	) VALUES (
		_user_id,
		_description,
		_date,
		_latitude,
		_longitude
	);
	-- Additionally, the following select statement is executed from the Queries.php class file
	-- SELECT LAST_INSERT_ID() as last_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spSearchResource//
CREATE PROCEDURE spSearchResource (
	in _search_incident_id int(11) unsigned,
	in _keyword varchar(1000),
	in _search_esf_id int(11) unsigned,
	in _distance_km int(11) unsigned
)
BEGIN
	/* *********************************************************************************
	 Special Note:
		The SQL for SearchResource is built dynamically in PHP.
		This choice was made as a way for out team to explore alternative
			SQL coding methods from an application


		We are retaining the Stored Procedure in the event we wish to modify
			it for use over the dynamically built code. However, it would
			require additional modification to check for a value of null OR the 
			user selected value.
			
		Code shown from the Queries.php method SearchResources
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

			$incidentQueryPart = '';
			if (!empty($search_incident))
			{
				$incidentQueryPart = ", get_distance_from_incident(:search_incident_id, r.resource_id, 'km') distance_from_incident_in_km";
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
			";			
		
	********************************************************************************* */


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
	SELECT r.resource_id,
		   r.user_id,
		   r.name,
		   r.model,
		   r.cost_amount,
		   r.cost_interval_id,
		   r.primary_esf_id,
		   get_resource_status(r.resource_id) resource_status,
		   get_distance_from_incident(_search_incident_id, r.resource_id, 'km') distance_from_incident_in_km
	FROM resources r
	INNER JOIN
		users u
	ON 
		r.user_id = u.user_id
	WHERE (
		r.name LIKE _keyword OR 
		r.model LIKE _keyword OR 
		r.resource_id IN (SELECT rc.resource_id
						  FROM resource_capabilities rc
						  WHERE rc.capability_id IN (SELECT c.capability_id
													 FROM capabilities c
													 WHERE c.description LIKE _keyword))
	)
	AND (
		r.primary_esf_id = _search_esf_id OR
		r.resource_id IN (SELECT rae.resource_id
						  FROM resource_additional_esfs rae
						  WHERE rae.esf_id = _search_esf_id)
	) 
    AND distance_from_incident_in_km <= _distance_km;
	-- DONE: Do not use constant for km parameter
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetResourceStatus//
CREATE PROCEDURE spGetResourceStatus (
	in _resource_id int(11) unsigned
)
BEGIN
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
	SELECT get_resource_status(_resource_id) resource_status;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetResourcesDeployedToUser//
CREATE PROCEDURE spGetResourcesDeployedToUser (
	in _current_user_id int(11) unsigned
)
BEGIN
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
		i.user_id = _current_user_id
	INNER JOIN 
		resources r
	ON 
		r.resource_id = rr.resource_id
	INNER JOIN
		users u
	ON
		-- Need to get the Resource Owner User Info:
		r.user_id = u.user_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetResourceRequestsSentFromUser//
CREATE PROCEDURE spGetResourceRequestsSentFromUser (
	in _current_user_id int(11) unsigned
)
BEGIN
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
		i.user_id = _current_user_id
	INNER JOIN
		users u
	ON
		-- Need to get the Resource Owner User Info:
		r.user_id = u.user_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetResourceRequestsSentToUser//
CREATE PROCEDURE spGetResourceRequestsSentToUser (
	in _current_user_id int(11) unsigned
)
BEGIN
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
		r.user_id = _current_user_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetRepairsScheduledOrInProgress//
CREATE PROCEDURE spGetRepairsScheduledOrInProgress (
	in _current_user_id int(11) unsigned
)
BEGIN
	-- Repairs Scheduled/In-Progress
	SELECT r.resource_id,
		   r.user_id,
		   r.name,
		   r.model,
		   r.cost_amount,
		   r.cost_interval_id,
		   r.primary_esf_id,
		   re.repair_id,
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
		r.user_id = _current_user_id
		-- Pending and In-Progress Repairs whose end_date has not passed:
	AND (
		(re.repair_status_id = 1) 
		OR ( re.repair_status_id = 2 AND SYSDATE() <= re.end_date));
	
	-- TODO: Consider usage of Const 3 for status id
END//

-- --------------------------------------------------------------

DROP PROCEDURE IF EXISTS spGetResourceReport//
CREATE PROCEDURE spGetResourceReport (
	in _current_user_id int(11) unsigned
)
BEGIN
	-- Resource Report
    -- Updated to demonstrate use of a nested subquery
	SELECT e.esf_id,
		   e.description,
           
           (SELECT Count(*) 
				FROM resources r 
                WHERE e.esf_id = r.primary_esf_id AND 
					r.user_id = _current_user_id
			) As total_resources,
		   (SELECT Count(*) 
				FROM resources r 
                WHERE e.esf_id = r.primary_esf_id AND 
					r.user_id = _current_user_id AND
                    get_resource_status(r.resource_id) = ('in-use' ) 
			) As total_resources_in_use
	FROM esfs e
    
	GROUP BY e.esf_id
	ORDER BY e.esf_id;
        
        
	/* Revised to demonstrate nested subqueries
		SELECT e.esf_id,
			   e.description,
			   COUNT(DISTINCT(r.resource_id)) total_resources,
			   COUNT(DISTINCT(r2.resource_id)) total_resources_in_use
		FROM esfs e
		-- Must do Right Outer Join to keep all ESF Rows:
		LEFT OUTER JOIN
			resources r
		ON 
			-- Join on Primary ESF ID Only:
			e.esf_id = r.primary_esf_id
			-- Only Shows Resources Owned by Current User:
			AND
			r.user_id = _current_user_id
		LEFT OUTER JOIN
			resources r2
		ON 
			-- Join on Primary ESF ID Only:
			e.esf_id = r2.primary_esf_id
			-- Only Shows Resources Owned by Current User:
			AND
			r2.user_id = _current_user_id
			-- Only Include In-Use Resources:
			-- TODO: Maybe include in-repair items too?
			AND
			-- TODO: Removed the COLLATE statement below. Need to review this with Omar.
			get_resource_status(r2.resource_id) = ('in-use' ) -- COLLATE utf8mb4_general_ci)
		GROUP BY e.esf_id
		ORDER BY e.esf_id;    
    */
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spGetResourceReportTotals//
CREATE PROCEDURE spGetResourceReportTotals (
	in _current_user_id int(11) unsigned
)
BEGIN
	-- Resource Report Totals
    -- Updated to demonstrate use of a nested subquery
	SELECT _current_user_id as user_id,           
           (SELECT Count(*) 
				FROM resources r 
                WHERE r.user_id = _current_user_id
			) As sum_total_resources,
		   (SELECT Count(*) 
				FROM resources r 
                WHERE r.user_id = _current_user_id AND
                    get_resource_status(r.resource_id) = ('in-use' ) 
			) As sum_total_resources_in_use
	FROM esfs e
    LIMIT 1;
    
    /* Revised to demonstrate nested subqueries
		SELECT r.user_id, 
			   COUNT(DISTINCT(r.resource_id)) sum_total_resources,
			   COUNT(DISTINCT(r2.resource_id)) sum_total_resources_in_use
		FROM esfs e
		-- Must do Right Outer Join to keep all ESF Rows:
		LEFT OUTER JOIN
			resources r
		ON 
			-- Join on Primary ESF ID Only:
			e.esf_id = r.primary_esf_id
			-- Only Shows Resources Owned by Current User:
			AND
			r.user_id = _current_user_id
		LEFT OUTER JOIN
			resources r2
		ON 
			-- Join on Primary ESF ID Only:
			e.esf_id = r2.primary_esf_id
			-- Only Shows Resources Owned by Current User:
			AND
			r2.user_id = _current_user_id
			-- Only Include In-Use Resources:
			-- TODO: Maybe include in-repair items too?
			AND
			-- TODO: Removed the COLLATE statement below. Need to review this with Omar.
			get_resource_status(r2.resource_id) = ('in-use' ) -- COLLATE utf8mb4_general_ci)
		GROUP BY r.user_id
		Having r.user_id = _current_user_id
		ORDER BY r.user_id;     
    */ 
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spRejectRequest//
CREATE PROCEDURE spRejectRequest (
	in _resource_id int(11) unsigned,
	in _incident_id int(11) unsigned
)
BEGIN
	-- NOTE: Same as Cancel Request. Just delete request.
	-- Reject Request
	DELETE FROM resource_requests
	WHERE resource_id = _resource_id AND incident_id = _incident_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spCancelRequest//
CREATE PROCEDURE spCancelRequest (
	in _resource_id int(11) unsigned,
	in _incident_id int(11) unsigned
)
BEGIN
	-- NOTE: Same as Reject Request. Just delete request.
	-- Cancel Request
	DELETE FROM resource_requests
	WHERE resource_id = _resource_id AND incident_id = _incident_id;
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spDeployResource//
CREATE PROCEDURE spDeployResource (
	in _resource_id int(11) unsigned,
	in _incident_id int(11) unsigned
)
BEGIN
	-- TODO: should we consider alernative to using '2'. should it be done via 'deployed'? is this possible with joins, etc?
	-- TODO: start date set to today. if resource owner took a long time to deploy, it's possible that start_date is after
	--       expected_return_date. decide how to handle this situation
	-- Deploy Resource
	UPDATE resource_requests 
	SET request_status_id = 2, start_date = SYSDATE()
	WHERE resource_id = _resource_id AND incident_id = _incident_id AND get_resource_status(resource_id)="available";
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spReturnResource//
CREATE PROCEDURE spReturnResource (
	in _resource_id int(11) unsigned,
	in _incident_id int(11) unsigned
)
BEGIN
	-- TODO: should we consider alernative to using '3'. should it be done via 'completed'? is this possible with joins, etc?
	-- TODO: also, this should potentially trigger check for scheduled repairs. if a repair is scheduled, 
	--       repair_status should change to 'in-progress'. if not done in SQL, include in php. 
	-- Return Resource
	UPDATE resource_requests 
	SET request_status_id = 3
	WHERE resource_id = _resource_id AND incident_id = _incident_id;

	-- If a repair is scheduled we update it. 

	UPDATE repairs r
	SET r.repair_status_id = 2, r.start_date = SYSDATE(), r.end_date = DATE_ADD(SYSDATE(),INTERVAL r.duration DAY)
	WHERE resource_id = _resource_id AND repair_status_id = 1
	LIMIT 1;

END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spRequestResource//
CREATE PROCEDURE spRequestResource (
	in _resource_id int(11) unsigned,
	in _incident_id int(11) unsigned,
	in _expected_return_date datetime
)
BEGIN
	-- TODO: reminder that expected return date should be >= today. validate in PHP or add SQL trigger that rejects past dates.
	-- Request Resource
	INSERT INTO resource_requests 
	VALUES (_resource_id, _incident_id, 1, SYSDATE(), _expected_return_date);
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spSchedulePendingRepair//
CREATE PROCEDURE spSchedulePendingRepair (
	in _resource_id int(11) unsigned,
	in _duration int(11) unsigned
)
BEGIN
	-- TODO: Start and End Date may cause tricky problems. ultimately need to decide how to handle them when repair would be pending.
	--       If resource is unavailable for immediate repair, then we won't know end date. if we don't allow null, then a date must be picked. 
	--       The date we pick will have consequences for other functions e.g. 'Scheduled/In-Progress Repairs' view.
	-- Schedule Pending Repair
	INSERT INTO repairs 
	VALUES (NULL, _resource_id, 1, _duration, NULL, NULL);
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spScheduleImmediateRepair//
CREATE PROCEDURE spScheduleImmediateRepair (
	in _resource_id int(11) unsigned,
	in _duration int(11) unsigned
)
BEGIN
	-- Schedule Immediate Repair 
	INSERT INTO repairs 
	VALUES (NULL, _resource_id , 2, _duration, SYSDATE(), DATE_ADD(SYSDATE(),INTERVAL _duration DAY));
END//
-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS spCancelRepair//
CREATE PROCEDURE spCancelRepair (
	in _repair_id int(11) unsigned,
	in _resource_id int(11) unsigned
)
BEGIN
	-- NOTE: technically only need repair ID to delete but using resource_id since repairs is weak entity 
	--       and primary key is defined as (repair_id,resource_id) per lectures.
	-- Cancel Repair
	DELETE FROM repairs
	WHERE repair_id = _repair_id AND resource_id = _resource_id;
END//
-- --------------------------------------------------------------










-- --------------------------------------------------------------
DROP PROCEDURE IF EXISTS template//
CREATE PROCEDURE template ()
BEGIN
	-- SELECT_STATEMENT_HERE;
END//
-- --------------------------------------------------------------






DELIMITER ;
