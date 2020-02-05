<?php
namespace App\Models;

use App\Helpers\Queries;

class RepairResource extends Base
{
    public function repairInUseResource($resource_id, $duration)
    {
       $data = Queries::schedulePendingRepair($resource_id, $duration);
       return $data;
    }

    public function repairAvailableResource($resource_id, $duration)
    {
       $data = Queries::scheduleImmediateRepair($resource_id, $duration);
       return $data;
    }

}