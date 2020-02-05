<?php
namespace App\Models;

use App\Helpers\Queries;

class RequestResource extends Base
{
    public function requestResource($resource_id, $incident_id, $expected_return_date)
    {
       $data = Queries::requestResource($resource_id, $incident_id, $expected_return_date);
       return $data;
    }
}