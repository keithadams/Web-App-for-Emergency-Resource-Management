<?php
namespace App\Helpers;

// This class will be able to be autoloaded
class SQLStatements
{
    /*    Setup and testing code for this class
        // TAN: Testing code of readin SQL Statements
        $sqlStatements = new App\Helpers\SQLStatements();
        $sqlObj = $sqlStatements->readStatements();
        
        print "showing GetUsers.sql: " . $sqlObj->GetUsers->sql;
        print "<br>";
        print "showing CheckPassword.sql: " . $sqlObj->CheckPassword->sql;
        print "<br>";
        print "showing GetUserById.sql: " . $sqlObj->GetUserById->sql;    
        // Sample calls available
        // $sqlStatements->readCreateTables();\
        // $sqlStatements->readCreateData();
    
        
    
    */
    
    public function readValue($sqlObj, $itemName, $valueName){
    //public function readValue() {
        print 'itemName: ' . $itemName;
        $newObj = $sqlObj->{$itemName};
        //print 'newObj: ' . $newObj;
        //return $newObj->{$valueName};
        return '';
    }
    public function readCreateTables() {
        // SQL to create the database and tables
        //      all tables will be droped if they exist
        //      If the DB exists an error may be thrown???
        // Return: String containing the SQL Statement

        return file_get_contents(dirname(__FILE__) . "/SQL/CreateTables.sql");
    }
    
    public function readCreateData() {
        // SQL to add data to SQL data tables
        // Return: String containing the SQL Statement
        
        return file_get_contents(dirname(__FILE__) . "/SQL/CreateData.sql");
    }
    
    public function readCreateSP() {
        // SQL to Create Stored Procedures to SQL database
        // Return: String containing the SQL Statement
        
        return file_get_contents(dirname(__FILE__) . "/SQL/CreateSP.sql");
    }
    
    public function runSQL($query) {
        // $sql - SQL String to be executed
        // return - result of exection
        
        $db = $this->container->db;

        $preparedStatement = $db->prepare($query);
        $preparedStatement->bindValue(':user_id', $id);
        $preparedStatement->execute();

        $user = $preparedStatement->fetchObject();
    }
    
    public function getExampleResponse()
    {
        return 'I am being called from ' . __METHOD__;
    }
}

