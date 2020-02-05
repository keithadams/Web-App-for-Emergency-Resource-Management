<?php
namespace App\Models;

use App\Helpers\Queries;

class ReturnResource extends Base
{
    
    public function returnResource($resourceId, $incidentId) {

        $data = Queries::returnResource($resourceId, $incidentId); 

        return $data;       

    } 

}