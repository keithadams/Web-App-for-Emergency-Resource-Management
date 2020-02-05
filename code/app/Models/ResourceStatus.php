<?php
namespace App\Models;

use App\Helpers\Queries;

class ResourceStatus extends Base
{
    
    //The following is used to impersonate a user for testing. It overrides session variables.
    /*public $user_id = 3;
    public $user_names = array(
        1 => "Craig Foster",
        2 => "Keith Adams",
        3 => "Thomas Neuman",
        4 => "Omar Ramos"
    );*/

    public function populateResourcesInUse() {

        $data = Queries::getResourcesDeployedToUser(); 
        

        return $data;       

    } 

    public function populateResourcesRequestedByMe() {
        
        $data = Queries::getResourceRequestsSentFromUser(); 

        return $data;

    }            


    public function populateResourceRequestsReceivedByMe() {

        $data = Queries::getResourceRequestsSentToUser();
        
        return $data;

    }


    public function populateResourcesScheduledorInProgress() {

        $data = Queries::getRepairsScheduledOrInProgress();    
        
        return $data;

    }


    /*The following is to impersonate a user for testing. It overrides session variables.

    public function getUserId() {
        return $this->user_id;

    }

    public function getUserName() {
        return $this->user_names[$this::getUserId()];

    }*/

}