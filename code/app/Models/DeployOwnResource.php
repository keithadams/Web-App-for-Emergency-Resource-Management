<?php
namespace App\Models;

use App\Helpers\Queries;

class DeployOwnResource extends Base
{
    public function deployOwnResource($resource_id, $incident_id, $expected_return_date)
    {
       $data = Queries::deployOwnResource($resource_id, $incident_id, $expected_return_date);
       return $data;
    }
}