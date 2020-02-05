<?php
namespace App\Models;

use App\Helpers\Queries;

class ResourceReport extends Base
{

    //The following is used to impersonate a user for testing. It overrides session variables.
    public $user_id = 3;
    public $user_names = array(
        1 => "Craig Foster",
        2 => "Keith Adams",
        3 => "Thomas Neuman",
        4 => "Omar Ramos"
    );

    public function populateResourceReport($user) {

        // $data = Queries::getResourceReport($this::getUserId());   // Use this line for testing the hard-coded user_id
        $data = Queries::getResourceReport($user->user_id);

        return $data;

    }

    public function populateResourceReportTotals($user) {

        // $data = Queries::getResourceReport($this::getUserId());   // Use this line for testing the hard-coded user_id
        $data = Queries::getResourceReportTotals($user->user_id);

        return $data;

    }

    //The following is to impersonate a user for testing. It overrides session variables.

    public function getUserId() {
        return $this->user_id;

    }

    public function getUserName() {
        return $this->user_names[$this::getUserId()];

    }



}