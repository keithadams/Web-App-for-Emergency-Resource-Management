/************************************************************
    Team 072 - Phase III SQL Table Creation Statements

    Craig Foster - cfoster46
    Keith Adams - kadams62
    Thomas Neuman - tneuman6
    Omar Ramos - oramos6

    Designed to run on RackSpace database phpMyAdmin Server

    Note: The Functions do not work on RackSpace Cloud Sites due to
            the need for elevated rights to create them.

************************************************************/

-- Added the utf8mb4 character set (otherwise MySQL by default may use latin1_swedish_ci):
-- CREATE DATABASE IF NOT EXISTS team072_cs6400 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- USE team072_cs6400;

-- Use 1018337_thomas;        -- Set the Thomas DB for testing

-- MySQL Specific Syntax (Primarily Needed During Testing):
-- Once in production DROP TABLE calls would likely want
-- to be avoided in order to prevent accidental deletion
-- of important production data.

-- Need to make use of the non-standard IF EXISTS capability
-- available in MySQL to avoid errors when re-running the
-- contents of this file.

-- Temporarily Disable Foreign Key Checks to make it easier
-- to delete the existing data during our development of the
-- schema and test data.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS capabilities;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS cost_intervals;
DROP TABLE IF EXISTS esfs;
DROP TABLE IF EXISTS government_agencies;
DROP TABLE IF EXISTS incidents;
DROP TABLE IF EXISTS individuals;
DROP TABLE IF EXISTS municipalities;
DROP TABLE IF EXISTS repairs;
DROP TABLE IF EXISTS repair_status;
DROP TABLE IF EXISTS request_status;
DROP TABLE IF EXISTS resources;
DROP TABLE IF EXISTS resource_additional_esfs;
DROP TABLE IF EXISTS resource_capabilities;
DROP TABLE IF EXISTS resource_requests;
DROP TABLE IF EXISTS users;

DROP FUNCTION IF EXISTS get_resource_status;
DROP FUNCTION IF EXISTS get_haversine_distance;
DROP FUNCTION IF EXISTS get_distance_from_incident;

-- Re-enable Foreign Key Checks
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    user_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    username varchar(50) NOT NULL,
    password varchar(50) NOT NULL, -- Clear text password. If we use encryption the password must be longer.
    name varchar(50) NOT NULL,
    PRIMARY KEY (user_id),
    UNIQUE (username)
);

CREATE TABLE individuals (
    user_id int(11) unsigned NOT NULL,
    job_title varchar(50) NOT NULL,
    date_hired datetime NOT NULL, -- Can we add check constraint on MySql
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),

    CHECK (date_hired <= CURDATE()) -- Not defining further constraints. It appears they are only ignored in MySql. (Tom)
                                    --     we defined constraints that worked properly in Microsoft SQL
);

CREATE TABLE municipalities (
    user_id int(11) unsigned NOT NULL,
    population_size int(11) unsigned NOT NULL, -- Constraint?
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TABLE companies (
    user_id int(11) unsigned NOT NULL,
    headquarters varchar(50) NOT NULL,
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TABLE government_agencies (
    user_id int(11) unsigned NOT NULL,
    jurisdiction varchar(50) NOT NULL,    -- constraint? May not be needed
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

-- Longitude/Latitude Reference:
-- http://stackoverflow.com/questions/159255/what-is-the-ideal-data-type-to-use-when-storing-latitude-longitudes-in-a-mysql
CREATE TABLE incidents (
    incident_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    user_id int(11) unsigned NOT NULL,
    description varchar(100) NOT NULL,
    `date` datetime,
    latitude decimal(10, 7) NOT NULL,    -- Constraints?
    longitude decimal(10, 7) NOT NULL,    -- Constraints?
    PRIMARY KEY (incident_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),

    -- MySQL Does not enforce constraints
    CHECK (latitude >= -90.0 AND latitude <= 90.0),
    CHECK (longitude >= -180.0 AND longitude <= 180.0)
);

-- esfs and cost_intervals table must be created before resources:
CREATE TABLE esfs (
    esf_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    description varchar(100) NOT NULL,
    PRIMARY KEY (esf_id),
    UNIQUE (description)
);

CREATE TABLE cost_intervals (
    cost_interval_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    description varchar(100) NOT NULL,
    PRIMARY KEY (cost_interval_id),
    UNIQUE (description)
);

CREATE TABLE resources (
    resource_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    user_id int(11) unsigned NOT NULL,
    name varchar(50) NOT NULL,
    model varchar(50) NULL,
    cost_amount int(11) unsigned NOT NULL,    -- Constraint??
    cost_interval_id int(11) unsigned NOT NULL,
    primary_esf_id int(11) unsigned NOT NULL,
    latitude decimal(10, 7) NOT NULL,    -- Constraint??
    longitude decimal(10, 7) NOT NULL,    -- Constraint??
    PRIMARY KEY (resource_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (cost_interval_id) REFERENCES cost_intervals (cost_interval_id),
    FOREIGN KEY (primary_esf_id) REFERENCES esfs (esf_id),

        -- MySQL Does not enforce constraints
    CHECK (latitude >= -90.0 AND latitude <= 90.0),
    CHECK (longitude >= -180.0 AND longitude <= 180.0)
);

-- capabilities table must be created before resource_capabilities:
CREATE TABLE capabilities (
    capability_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    description varchar(50) NOT NULL,
    PRIMARY KEY (capability_id),
    UNIQUE (description)

);

CREATE TABLE resource_capabilities (
    resource_id int(11) unsigned NOT NULL,
    capability_id int(11) unsigned NOT NULL,
    PRIMARY KEY (resource_id, capability_id),
    FOREIGN KEY (resource_id) REFERENCES resources (resource_id),
    FOREIGN KEY (capability_id) REFERENCES capabilities (capability_id)
);

CREATE TABLE resource_additional_esfs (
    resource_id int(11) unsigned NOT NULL,
    esf_id int(11) unsigned NOT NULL,
    PRIMARY KEY (resource_id, esf_id),
    FOREIGN KEY (resource_id) REFERENCES resources (resource_id),
    FOREIGN KEY (esf_id) REFERENCES esfs (esf_id)
);

CREATE TABLE repair_status (
    repair_status_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    description varchar(100) NOT NULL,
    PRIMARY KEY (repair_status_id),
    UNIQUE (description)
);

-- OMAR: Added the AUTO_INCREMENT part (noticed it was missing):
-- KEITH: from lectures, added resource_id to PK since repairs is weak entity (just in case, even though repair_id is sufficient)
CREATE TABLE repairs (
    repair_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    resource_id int(11) unsigned NOT NULL,
    repair_status_id int(11) unsigned NOT NULL,
    duration int(11) unsigned NOT NULL,
    start_date datetime NULL,
    end_date datetime NULL,
    PRIMARY KEY (repair_id,resource_id),
    FOREIGN KEY (resource_id) REFERENCES resources (resource_id),
    FOREIGN KEY (repair_status_id) REFERENCES repair_status (repair_status_id)
);

CREATE TABLE request_status (
    request_status_id int(11) unsigned NOT NULL AUTO_INCREMENT,
    description varchar(100) NOT NULL,
    PRIMARY KEY (request_status_id),
    UNIQUE (description)
);

CREATE TABLE resource_requests (
    resource_id int(11) unsigned NOT NULL,
    incident_id int(11) unsigned NOT NULL,
    request_status_id int(11) unsigned NOT NULL,
    start_date datetime NOT NULL,
    expected_return_date datetime NOT NULL,
    PRIMARY KEY (resource_id, incident_id),
    FOREIGN KEY (resource_id) REFERENCES resources (resource_id),
    FOREIGN KEY (incident_id) REFERENCES incidents (incident_id),
    FOREIGN KEY (request_status_id) REFERENCES request_status (request_status_id)
);


/***********************************************

    SPECIAL NOTE:
        These custom functions will not work in a shared hosted
            environment such as RackSpace Cloud Sites. We were not
            able to obtain the proper rights to create the Functions
            on our testing servers, but were able to run this file
            locally in XAMPP (under the MySQL root account) and the
            functions were created without issue.

-- Custom Functions:

-- Function to Derive the Resource Status:
-- Examples:
-- get_resource_status(1011); // available
-- get_resource_status(1013); // in-use
-- get_resource_status(1034); // in-repair
--
***************************************************/

delimiter //
CREATE FUNCTION get_resource_status(input_resource_id int) RETURNS varchar(50)
    DETERMINISTIC
BEGIN

    DECLARE is_deployed int;
    DECLARE is_in_repair int;
    DECLARE return_resource_status varchar(50);


        SELECT in_use_resource INTO is_deployed
         FROM
            (SELECT COALESCE(rr.resource_id, input_resource_id) resource_id,
                   COUNT(rr.resource_id) in_use_resource
            FROM resource_requests rr
            INNER JOIN
                request_status rs
            ON
                rr.request_status_id = rs.request_status_id
            WHERE rr.resource_id = input_resource_id
            -- If we do a good job of ensuring resources get returned
            -- then the next filter isn't strictly required:
            -- AND SYSDATE() < rr.expected_return_date
            AND rs.description = 'deployed') request_info;

        SELECT in_progress_repair INTO is_in_repair
         FROM
                (SELECT COALESCE(r.resource_id, input_resource_id) resource_id2,
                       COUNT(r.resource_id) in_progress_repair
                FROM repairs r
                INNER JOIN
                    repair_status rs2
                ON
                    r.repair_status_id = rs2.repair_status_id
                WHERE r.resource_id = input_resource_id
                -- If we do a good job of ensuring resources get returned
                -- then the next filter isn't strictly required:
                -- AND SYSDATE() < rr.end_date
                AND rs2.description = 'in-progress') repair_info;

    IF is_deployed > 0 THEN SET return_resource_status = 'in-use';
    ELSEIF is_in_repair > 0 THEN SET return_resource_status = 'in-repair';
    ELSE SET return_resource_status = 'available';
    END IF;

    RETURN return_resource_status;
END //
delimiter ;

-- Haversine Distance General Function:
-- DONE: Should be working correctly
-- Example: select get_haversine_distance(33.3862486, -82.2310012, 33.7755980, -84.4663251, 'kilometers');
delimiter //
CREATE FUNCTION get_haversine_distance(lat1 DECIMAL(10,7), lon1 DECIMAL(10,7), lat2 DECIMAL(10,7), lon2 DECIMAL(10,7), units varchar(50)) RETURNS INT
BEGIN

    DECLARE return_distance INT;
    DECLARE earth_radius INT;
    DECLARE a DECIMAL(40,20);

    IF (units = 'kilometers' OR units = 'km') THEN SET earth_radius = 6371;
    ELSEIF (units = 'meters' OR units = 'm') THEN SET earth_radius = (6371 * 1000);
    ELSEIF (units = 'centimeters' OR units = 'cm') THEN SET earth_radius = (6371 * 1000 * 100);
    ELSEIF (units = 'miles' OR units = 'mi') THEN SET earth_radius = 3959;
    ELSEIF (units = 'feet' OR units = 'ft') THEN SET earth_radius = (3959 * 5280);
    ELSEIF (units = 'inches' OR units = 'in') THEN SET earth_radius = (3959 * 5280 * 12);
    ELSE SET units = 6371; -- Default to Kilometers
    END IF;

    -- Added a variable for "a" so we could simplify the Haversine formula/equation below:
    SET a = (POW(SIN((RADIANS(lat2) - RADIANS(lat1)) / 2), 2) + COS(RADIANS(lat1)) * COS(RADIANS(lat2)) * POW(SIN((RADIANS(lon2) - RADIANS(lon1)) / 2), 2));

    SET return_distance = ROUND(
        earth_radius *
        -- The Below Portion Represents "c" in the Haversine Function:
        2 *    ATAN2(SQRT(a), SQRT(1 - a))
    );

    RETURN return_distance;
END //

delimiter ;

-- Custom Function to Get the Resource's Distance from an Incident:
-- (Wraps the get_haversine_distance() function above so we don't
-- have to pass in so many parameters.
-- Example: select get_distance_from_incident(4284, 1011, 'kilometers');
delimiter //
CREATE FUNCTION get_distance_from_incident(input_incident_id INT, input_resource_id INT, units varchar(50)) RETURNS INT
BEGIN

    DECLARE incident_latitude DECIMAL(10,7);
    DECLARE incident_longitude DECIMAL(10,7);
    DECLARE resource_latitude DECIMAL(10,7);
    DECLARE resource_longitude DECIMAL(10,7);
    DECLARE return_distance INT;

    SELECT latitude INTO incident_latitude
    FROM incidents
    WHERE incident_id = input_incident_id;

    SELECT longitude INTO incident_longitude
    FROM incidents
    WHERE incident_id = input_incident_id;

    SELECT latitude INTO resource_latitude
    FROM resources
    WHERE resource_id = input_resource_id;

    SELECT longitude INTO resource_longitude
    FROM resources
    WHERE resource_id = input_resource_id;

    SET return_distance = get_haversine_distance(incident_latitude, incident_longitude, resource_latitude, resource_longitude, units);

    RETURN return_distance;
END //

delimiter ;

