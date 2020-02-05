<?php
namespace App\Models;

use App\Helpers\Queries;

class DeployResource extends Base
{
    
    public function deployResource($resource_id, $incident_id) {

       $data = Queries::spDeployResource($resource_id, $incident_id); 
       return $data;
              

    } 

}