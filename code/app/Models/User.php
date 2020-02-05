<?php
namespace App\Models;

use App\Helpers\Queries;

class User extends Base
{
	public function getUserById($id)
	{
        $user = Queries::getUserByUserId($id);
        return $user;

        /*
		$db = $this->container->db;

		$query = "SELECT u.user_id,
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
					WHERE u.user_id = :user_id";

		$preparedStatement = $db->prepare($query);
		$preparedStatement->bindValue(':user_id', $id);
		$preparedStatement->execute();

		$user = $preparedStatement->fetchObject();

		return $user;
        */
	}
}
