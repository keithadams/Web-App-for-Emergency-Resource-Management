/************************************************************
    Team 072 - Phase II SQL Insert Sample Data Statements

    Craig Foster - cfoster46
    Keith Adams - kadams62
    Thomas Neuman - tneuman6
    Omar Ramos - oramos6

    Designed to run on RackSpace database phpMyAdmin Server

************************************************************/

-- Sample Data:

INSERT INTO esfs (
    esf_id,
    description
) VALUES (
    1,
    'Transportation'
),(
    2,
    'Communications'
),(
    3,
    'Public Works and Engineering'
),(
    4,
    'Firefighting'
),(
    5,
    'Information and Planning'
),(
    6,
    'Mass Care, Emergency Assistance, Temporary Housing and Human Services'
),(
    7,
    'Logistics Management and Resource Support'
),(
    8,
    'Public Health and Medical Services'
),(
    9,
    'Search and Rescue'
),(
    10,
    'Oil and Hazardous Materials Response'
),(
    11,
    'Agriculture and Natural Resources'
),(
    12,
    'Energy'
),(
    13,
    'Public Safety and Security'
),(
    14,
    'Long-Term Community Recovery'
),(
    15,
    'External Affairs'
);

INSERT INTO cost_intervals (
    cost_interval_id,
    description
) VALUES (
    1,
    'hour'
),(
    2,
    'day'
),(
    3,
    'week'
),(
    4,
    'each'
);


-- Add more sample data in here:

-- Indviduals:
INSERT INTO users (
    user_id,
    username,
    password,
    name
)
 VALUES (
    1,
    'cfoster46',
    'password',
    'Craig Foster'
), (
    2,
    'kadams62',
    'password',
    'Keith Adams'
), (
    3,
    'tneuman6',
    'password',
    'Thomas Neuman'
), (
    4,
    'oramos6',
    'password',
    'Omar Ramos'
);

INSERT INTO individuals (
    user_id,
    job_title,
    date_hired
)
 VALUES (
    1,
    'OMSCS Student',
    '2016-08-22 00:00:00'
), (
    2,
    'OMSCS Student',
    '2016-08-22 00:00:00'
), (
    3,
    'OMSCS Student',
    '2016-08-22 00:00:00'
), (
    4,
    'OMSCS Student',
    '2016-08-22 00:00:00'
);

 -- Municipalities:
 INSERT INTO users (
    user_id,
    username,
    password,
    name
)
 VALUES (
    5,
    'city_atlanta_georgia',
    'password',
    'City of Atlanta, Georgia'
), (
    6,
    'city_macon_georgia',
    'password',
    'City of Macon, Georgia'
), (
    7,
    'city_athens_georgia',
    'password',
    'City of Athens, Georgia'
), (
    8,
    'city_marietta_georgia',
    'password',
    'City of Marietta, Georgia'
), (
    9,
    'city_savannah_georgia',
    'password',
    'City of Savannah, Georgia'
), (
    10,
    'city_sandy_springs_georgia',
    'password',
    'City of Sandy Springs, Georgia'
), (
    11,
    'city_columbus_georgia',
    'password',
    'City of Columbus, Georgia'
), (
    26,
    'city_augusta_georgia',
    'password',
    'City of Augusta, Georgia'
);

-- More municipalities:
INSERT INTO municipalities (
    user_id,
    population_size
)
 VALUES (
    5,
    463878
), (
    6,
    153691
), (
    7,
    115452
), (
    8,
    59089
), (
    9,
    145674
), (
    10,
    93853
), (
    11,
    189885
), (
    26,
    195844
);

INSERT INTO users (
    user_id,
    username,
    password,
    name
)
 VALUES (
    12,
    'city_denver_colorado',
    'password',
    'City of Denver, Colorado'
), (
    13,
    'city_boulder_colorado',
    'password',
    'City of Boulder, Colorado'
), (
    14,
    'city_durango_colorado',
    'password',
    'City of Durango, Colorado'
), (
    15,
    'city_fort_collins_colorado',
    'password',
    'City of Fort Collins, Colorado'
), (
    16,
    'city_pueblo_colorado',
    'password',
    'City of Pueblo, Colorado'
);

INSERT INTO municipalities (
    user_id,
    population_size
)
 VALUES (
    12,
    600158
), (
    13,
    105112
), (
    14,
    17557
), (
    15,
    143986
), (
    16,
    106595
);

-- Companies:
INSERT INTO users (
    user_id,
    username,
    password,
    name
)
 VALUES (
    17,
    'company_facebook_atlanta',
    'password',
    'Facebook (Atlanta)'
), (
    18,
    'company_facebook_boston',
    'password',
    'Facebook (Boston)'
), (
    19,
    'company_facebook_austin',
    'password',
    'Facebook (Austin)'
), (
    20,
    'company_facebook_redmond',
    'password',
    'Facebook (Redmond)'
), (
    21,
    'company_facebook_menlo_park',
    'password',
    'Facebook (Menlo Park)'
), (
    22,
    'company_facebook_new_york',
    'password',
    'Facebook (New York)'
);

INSERT INTO companies (
    user_id,
    headquarters
) VALUES (
    17,
    'Menlo Park, CA'
), (
    18,
    'Menlo Park, CA'
), (
    19,
    'Menlo Park, CA'
), (
    20,
    'Menlo Park, CA'
), (
    21,
    'Menlo Park, CA'
), (
    22,
    'Menlo Park, CA'
);

-- Government Agencies:
INSERT INTO users (
    user_id,
    username,
    password,
    name
)
 VALUES (
    23,
    'agency_georgia_state_patrol_atlanta',
    'password',
    'Georgia State Patrol (Atlanta)'
), (
    24,
    'agency_georgia_state_patrol_milledgeville',
    'password',
    'Georgia State Patrol (Milledgeville)'
), (
    25,
    'agency_georgia_state_patrol_forest_park',
    'password',
    'Georgia State Patrol (Forest Park)'
);

INSERT INTO government_agencies (
    user_id,
    jurisdiction
) VALUES (
    23,
    'Atlanta, Georgia'
), (
    24,
    'Milledgeville, Georgia'
), (
    25,
    'Forest Park, Georgia'
);

-- Capabilities Sample Data:
INSERT INTO capabilities (
    capability_id,
    description
)
 VALUES (
    1,
    'light ground transport'
), (
    2,
    'medium ground transport'
), (
    3,
    'heavy ground transport'
), (
    4,
    'electricity generator'
), (
    5,
    'firefighting vehicle'
), (
    6,
    'air firefighting'
), (
    7,
    'air medical'
), (
    8,
    'medical vehicle'
), (
    9,
    'earth moving (excavator)'
), (
    10,
    'earth moving (backhoe loader)'
), (
    11,
    'earth moving (bulldozer)'
), (
    12,
    'earth moving (skid steer loader)'
), (
    13,
    'earth moving (motor grader)'
), (
    14,
    'earth moving (trencher)'
), (
    15,
    'water transport'
), (
    16,
    'short-range radio'
), (
    17,
    'clean water generation'
), (
    18,
    'air rescue'
), (
    19,
    'shelter'
), (
    20,
    'rope rescue line'
), (
    21,
    'rope rescue harness'
);

-- https://www.fema.gov/disasters/grid/year/2016?field_disaster_type_term_tid_1=All
-- Took the 2016 List from the Above Link and formatted it for our incidents table
-- Unfortunately it does not include latitude and longitude info so we'll need
-- to update the below ones with our best guesses for each (NOTE: They've been updated now):

INSERT INTO incidents (
    incident_id,
    user_id,
    description,
    date,
    latitude,
    longitude
) VALUES (
    3377,
    4,
    'Florida - Hurricane Matthew - Emergency Declaration',
    '2016-10-06 00:00:00',
    30.3452117,
    -81.8605438
), (
    3378,
    4,
    'South Carolina - Hurricane Matthew - Emergency Declaration',
    '2016-10-06 00:00:00',
    32.821256,
    -80.1105618
), (
    3379,
    1,
    'Atlanta, Georgia - Hurricane Matthew - Emergency Declaration',
    '2016-10-06 00:00:00',
    33.7678359,
    -84.4906438
), (
    4249,
    4,
    'Washington - Severe Storms, Straight-line Winds, Flooding, Landslides, and Mudslides - Major Disaste',
    '2016-01-15 00:00:00',
    33.7678359,
    -84.4906438
), (
    4251,
    2,
    'Alabama - Severe Storms, Tornadoes, Straight-line Winds, and Flooding - Major Disaster Declaration',
    '2016-01-21 00:00:00',
    33.7678359,
    -84.4906438
), (
    4253,
    2,
    'Washington - Severe Winter Storm, Straight-Line Winds, Flooding, Landslides, Mudslides, and a Tornad',
    '2016-02-02 00:00:00',
    33.7678359,
    -84.4906438
), (
    4259,
    2,
    'Savannah, Georgia - Severe Storms and Flooding - Major Disaster Declaration',
    '2016-02-26 00:00:00',
    32.0407509,
    -81.3404599
), (
    4280,
    3,
    'Florida - Hurricane Hermine - Major Disaster Declaration',
    '2016-09-28 00:00:00',
    30.3452117,
    -81.8605438
), (
    4283,
    2,
    'Florida - Hurricane Matthew - Major Disaster Declaration',
    '2016-10-08 00:00:00',
    30.3452117,
    -81.8605438
), (
    4284,
    4,
    'Augusta, Georgia - Hurricane Matthew - Major Disaster Declaration',
    '2016-10-08 00:00:00',
    33.3862486,
    -82.2310012
), (
    4286,
    3,
    'South Carolina - Hurricane Matthew - Major Disaster Declaration',
    '2016-10-11 00:00:00',
    32.821256,
    -80.1105618
), (
    5124,
    1,
    'California - Old Fire - Fire Management Assistance Declaration',
    '2016-06-05 00:00:00',
    33.4367435,
    -117.653413
), (
    5128,
    1,
    'California - Border 3 Fire - Fire Management Assistance Declaration',
    '2016-06-19 00:00:00',
    32.6126704,
    -116.7106152
), (
    5129,
    2,
    'California - Fish Fire - Fire Management Assistance Declaration',
    '2016-06-21 00:00:00',
    33.2977332,
    -115.9939955
), (
    5131,
    4,
    'California - Erskine Fire - Fire Management Assistance Declaration',
    '2016-06-24 00:00:00',
    37.8759797,
    -122.2981316
), (
    5132,
    3,
    'California - Sage Fire - Fire Management Assistance Declaration',
    '2016-07-09 00:00:00',
    40.7832891,
    -124.1812396
), (
    5133,
    4,
    'Colorado - Cold Spring Fire - Fire Management Assistance Declaration',
    '2016-07-10 00:00:00',
    33.7718388,
    -116.7040308
), (
    5135,
    3,
    'California - Sand Fire - Fire Management Assistance Declaration',
    '2016-07-23 00:00:00',
    32.7503172,
    -114.7740548
), (
    5137,
    2,
    'California - Soberanes Fire - Fire Management Assistance Declaration',
    '2016-07-28 00:00:00',
    36.5832644,
    -118.1485498
), (
    5140,
    1,
    'California - Goose Fire - Fire Management Assistance Declaration',
    '2016-07-31 00:00:00',
    34.4282596,
    -119.7370865
), (
    5144,
    4,
    'California - Pilot Fire - Fire Management Assistance Declaration',
    '2016-08-08 00:00:00',
    38.5617256,
    -121.5829958
), (
    5145,
    1,
    'California - Clayton Fire - Fire Management Assistance Declaration',
    '2016-08-14 00:00:00',
    38.5617256,
    -121.5829962
), (
    5146,
    2,
    'California - Chimney Fire  - Fire Management Assistance Declaration',
    '2016-08-14 00:00:00',
    38.5568493,
    -121.7524533
), (
    5147,
    2,
    'California - Blue Cut Fire - Fire Management Assistance Declaration',
    '2016-08-16 00:00:00',
    32.8440673,
    -116.7918859
), (
    5148,
    2,
    'Washington - Wellesley Fire - Fire Management Assistance Declaration',
    '2016-08-22 00:00:00',
    47.4282527,
    -120.3672652
), (
    5149,
    3,
    'Washington - Yale Fire - Fire Management Assistance Declaration',
    '2016-08-22 00:00:00',
    46.9981468,
    -120.5872933
), (
    5150,
    3,
    'California - Cedar Fire - Fire Management Assistance Declaration',
    '2016-08-22 00:00:00',
    35.2725611,
    -120.7054055
), (
    5152,
    1,
    'Washington - Suncrest Fire - Fire Management Assistance Declaration',
    '2016-08-28 00:00:00',
    47.6697765,
    -122.1565973
), (
    5155,
    2,
    'Colorado - Beulah Hill Fire - Fire Management Assistance Declaration',
    '2016-10-04 00:00:00',
    39.7178536,
    -105.7132886
), (
    6000,
    4,
    'Georgia Tech - College of Computing Zombie Takeover',
    '2016-11-17 00:00:00',
    33.7756222,
    -84.3984737
), (
    6001,
    1,
    'Atlanta Zoo - Tortoise Stampede',
    '2016-11-14 00:00:00',
    33.7334158,
    -84.3728826
), (
    6002,
    2,
    'Atlanta Waffle House - Maple Syrup Explosion',
    '2016-11-15 00:00:00',
    33.7756222,
    -84.3984737
), (
    6003,
    3,
    'Atlanta Botanical Garden - Zika Virus Outbreak',
    '2016-11-16 00:00:00',
    33.7862505,
    -84.3688274
);

-- Resources:
-- Georgia Tech Lat/Long: 33.775598,-84.4663251
INSERT INTO resources (
    resource_id,
    user_id,
    name,
    model,
    cost_amount,
    cost_interval_id,
    primary_esf_id,
    latitude,
    longitude
) VALUES (
    1001,
    1,
    'CASE Backhoe Loader',
    '590 Super N',
    200,
    2,
    3,
    33.775598,
    -84.4663251
), (
    1002,
    2,
    'CASE Backhoe Loader',
    '590 Super N',
    200,
    2,
    3,
    33.775598,
    -84.4663251
), (
    1003,
    3,
    'CASE Backhoe Loader',
    '590 Super N',
    200,
    2,
    3,
    33.775598,
    -84.4663251
), (
    1004,
    4,
    'CASE Backhoe Loader',
    '590 Super N',
    200,
    2,
    3,
    33.775598,
    -84.4663251
), (
    -- http://www.fire.ca.gov/communications/downloads/AviationGuide_FINAL_web.pdf
    1011,
    1,
    'Rescue Helicopter',
    'UH-1H Super Huey Type II Helicopter',
    200,
    2,
    9,
    33.775598,
    -84.4663251
), (
    1012,
    2,
    'Rescue Helicopter',
    'UH-1H Super Huey Type II Helicopter',
    200,
    2,
    9,
    33.775598,
    -84.4663251
), (
    1013,
    3,
    'Rescue Helicopter',
    'UH-1H Super Huey Type II Helicopter',
    200,
    2,
    9,
    33.775598,
    -84.4663251
), (
    1014,
    4,
    'Rescue Helicopter',
    'UH-1H Super Huey Type II Helicopter',
    200,
    2,
    9,
    33.775598,
    -84.4663251
), (
    -- http://www.fire.ca.gov/communications/downloads/AviationGuide_FINAL_web.pdf
    1021,
    1,
    'Firefighting Helicopter',
    'Bell 205 A++ Type II Helicopter',
    200,
    2,
    4,
    33.775598,
    -84.4663251
), (
    1022,
    2,
    'Firefighting Helicopter',
    'Bell 205 A++ Type II Helicopter',
    200,
    2,
    4,
    33.775598,
    -84.4663251
), (
    1023,
    3,
    'Firefighting Helicopter',
    'Bell 205 A++ Type II Helicopter',
    200,
    2,
    4,
    33.775598,
    -84.4663251
), (
    1024,
    4,
    'Firefighting Helicopter',
    'Bell 205 A++ Type II Helicopter',
    200,
    2,
    4,
    33.775598,
    -84.4663251
), (
    -- http://www.aev.com/3-ambulance-models/
    1031,
    1,
    'EMS Vehicle',
    'GM 4500 172" TraumaHawk',
    200,
    2,
    8,
    33.775598,
    -84.4663251
), (
    1032,
    2,
    'EMS Vehicle',
    'GM 4500 172" TraumaHawk',
    200,
    2,
    8,
    33.775598,
    -84.4663251
), (
    1033,
    3,
    'EMS Vehicle',
    'GM 4500 172" TraumaHawk',
    200,
    2,
    8,
    33.775598,
    -84.4663251
), (
    1034,
    4,
    'EMS Vehicle',
    'GM 4500 172" TraumaHawk',
    200,
    2,
    8,
    33.775598,
    -84.4663251
), (
    1040,
    4,
    'Utility Vehicle',
    'Workman MDX (07235)',
    50,
    2,
    1,
    33.7611478,
    -84.4835772
);


INSERT INTO resource_additional_esfs (
    resource_id,
    esf_id
) VALUES (
    1011,
    1
), (
    1012,
    1
), (
    1013,
    1
), (
    1014,
    1
), (
    1021,
    9
), (
    1022,
    9
), (
    1023,
    9
), (
    1024,
    9
), (
    1031,
    6
), (
    1032,
    6
), (
    1033,
    6
), (
    1034,
    6
);

INSERT INTO resource_capabilities (
    resource_id,
    capability_id
) VALUES (
    1001,
    10
), (
    1002,
    10
), (
    1003,
    10
), (
    1004,
    10
), (
    1011,
    18
), (
    1012,
    18
), (
    1013,
    18
), (
    1014,
    18
), (
    1021,
    6
), (
    1022,
    6
), (
    1023,
    6
), (
    1024,
    6
), (
    1021,
    18
), (
    1022,
    18
), (
    1023,
    18
), (
    1024,
    18
), (
    1031,
    8
), (
    1032,
    8
), (
    1033,
    8
), (
    1034,
    8
), (
    1031,
    1
), (
    1032,
    1
), (
    1033,
    1
), (
    1034,
    1
);

INSERT INTO request_status (
    request_status_id,
    description
) VALUES (
    1,
    'pending'
), (
    2,
    'deployed'
), (
    3,
    'returned'
);

INSERT INTO repair_status (
    repair_status_id,
    description
) VALUES (
    1,
    'pending'
), (
    2,
    'in-progress'
), (
    3,
    'complete'
);

-- Resource Requests:
INSERT INTO resource_requests (
    resource_id,
    incident_id,
    request_status_id,
    start_date,
    expected_return_date
) VALUES (
    1011,
    4259,
    1,
    '2016-10-22 00:00:00',
    '2016-10-31 00:00:00'
), (
    1012,
    4259,
    1,
    '2016-10-22 00:00:00',
    '2016-10-31 00:00:00'
), (
    1013,
    4259,
    2,
    '2016-10-22 00:00:00',
    '2016-11-22 00:00:00'
), (
    1014,
    4259,
    1,
    '2016-10-22 00:00:00',
    '2016-10-31 00:00:00'
), (
    1011,
    4284,
    1,
    '2016-10-22 00:00:00',
    '2016-10-31 00:00:00'
), (
    1012,
    4284,
    2,
    '2016-10-22 00:00:00',
    '2016-11-22 00:00:00'
), (
    1013,
    4284,
    1,
    '2016-10-22 00:00:00',
    '2016-10-31 00:00:00'
), (
    1014,
    4284,
    1,
    '2016-10-22 00:00:00',
    '2016-10-31 00:00:00'
), (
    1011,
    4251,
    3,
    '2016-02-08 00:00:00',
    '2016-02-15 00:00:00'
), (
    1012,
    4251,
    3,
    '2016-02-08 00:00:00',
    '2016-02-15 00:00:00'
), (
    1013,
    4251,
    3,
    '2016-02-08 00:00:00',
    '2016-02-15 00:00:00'
), (
    1014,
    4251,
    3,
    '2016-02-08 00:00:00',
    '2016-02-15 00:00:00'
);

-- Repairs:

INSERT INTO repairs (
    repair_id,
    resource_id,
    repair_status_id,
    duration,
    start_date,
    end_date
) VALUES (
    1,
    1031,
    3,
    14,
    '2016-07-15 00:00:00',
    '2016-07-29 00:00:00'
), (
    2,
    1032,
    3,
    14,
    '2016-07-15 00:00:00',
    '2016-07-29 00:00:00'
), (
    3,
    1033,
    3,
    14,
    '2016-07-15 00:00:00',
    '2016-07-29 00:00:00'
), (
    4,
    1034,
    3,
    14,
    '2016-07-15 00:00:00',
    '2016-07-29 00:00:00'
), (
    11,
    1031,
    2,
    14,
    '2016-10-15 00:00:00',
    '2016-10-29 00:00:00'
), (
    12,
    1032,
    2,
    14,
    '2016-10-15 00:00:00',
    '2016-10-29 00:00:00'
), (
    13,
    1033,
    2,
    14,
    '2016-10-15 00:00:00',
    '2016-10-29 00:00:00'
), (
    14,
    1034,
    2,
    14,
    '2016-10-15 00:00:00',
    '2016-10-29 00:00:00'
), (
    21,
    1031,
    1,
    14,
    '2017-01-15 00:00:00',
    '2017-01-29 00:00:00'
), (
    22,
    1032,
    1,
    14,
    '2017-01-15 00:00:00',
    '2017-01-29 00:00:00'
), (
    23,
    1033,
    1,
    14,
    '2017-01-15 00:00:00',
    '2017-01-29 00:00:00'
), (
    24,
    1034,
    1,
    14,
    '2017-01-15 00:00:00',
    '2017-01-29 00:00:00'
), (
    25,
    1012,
    1,
    14,
    '2016-11-23 00:00:00',
    '2016-12-15 00:00:00'
);
