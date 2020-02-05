<?php
namespace App\Models;

use App\Helpers\Queries;

class RejectRequest extends Base
{
    
    public function rejectRequest($resourceId, $incidentId) {

        $data = Queries::rejectRequest($resourceId, $incidentId);     
        
        return $data;
    } 

}