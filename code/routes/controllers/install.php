<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

$app->get('/install', function (Request $request, Response $response) {

	// Should allow the script to run for a longer period without timing out:
	set_time_limit(0);

	$this->logger->info("CS 6400 Application '/install' GET route");

	$sqlPath = APP_ROOT.'/app/Helpers/SQL/';
	$createTablesFilename = 'CreateTables.sql';
	$createProceduresFilename = 'CreateSP.sql';

	if (isset($_GET['testing']))
	{
		$createSampleDataFilename = 'CreateDataTesting.sql';
	}
	else
	{
		$createSampleDataFilename = 'CreateData.sql';
	}

	echo "<pre>";

	// The PHP Side doesn't like the custom delimiters since it seems that's
	// primarily for the MySQL side of things to use (e.g. MySQL Workbench).
	// Even though they work fine in phpMyAdmin, internally it could be doing
	// something similar to what we're doing here in order to allow it to work
	// within that application.
	$cleanSQL = function($sql) {
		$sql = str_replace('DELIMITER //', '', $sql);
		$sql = str_replace('delimiter //', '', $sql);
		$sql = str_replace('DELIMITER ;', '', $sql);
		$sql = str_replace('delimiter ;', '', $sql);
		$sql = str_replace('END //', 'END;', $sql);
		$sql = str_replace("//\n", ";\n", $sql);

		return $sql;
	};

	try
	{
		$time_start = microtime(true);
		echo "Executing Queries Needed to Operate Application:\n";
		echo "\tExecuting Queries in $createTablesFilename...";
		$sql = file_get_contents($sqlPath . $createTablesFilename);
		$formattedCreateTablesQueries = SqlFormatter::format($sql);
	    $result = $this->db->exec($cleanSQL($sql));
	    $time_end = microtime(true);
	    $time = round($time_end - $time_start, 4);
	    if ($result !== false) echo "success ($time seconds)!\n";

	    $time_start = microtime(true);
	    echo "\tExecuting Queries in $createProceduresFilename...";
		$sql = file_get_contents($sqlPath . $createProceduresFilename);
		$formattedCreateProceduresQueries = SqlFormatter::format($sql);
		$result = $this->db->exec($cleanSQL($sql));
		$time_end = microtime(true);
	    $time = round($time_end - $time_start, 4);
		if ($result !== false) echo "success ($time seconds)!\n";

		$time_start = microtime(true);
	    echo "\tExecuting Queries in $createSampleDataFilename...";
		$sql = file_get_contents($sqlPath . $createSampleDataFilename);
		$formattedCreateSampleDataQueries = SqlFormatter::format($sql);
	    $result = $this->db->exec($cleanSQL($sql));
	    $time_end = microtime(true);
	    $time = round($time_end - $time_start, 4);
	    if ($result !== false) echo "success ($time seconds)!\n";

	    echo "Proceed to <a href='login'>Login</a>\n";

		echo "<h3>Full SQL Listing:</h3>";

		echo "<h3>Queries from $createTablesFilename:</h3>";
		echo $formattedCreateTablesQueries;

		echo "<h3>Queries from $createProceduresFilename:</h3>";
		echo $formattedCreateProceduresQueries;

		echo "<h3>Queries from $createSampleDataFilename:</h3>";
		echo $formattedCreateSampleDataQueries;

	}
	catch (PDOException $e)
	{
	    echo $e->getMessage();
	    die();
	}



	echo "</pre>";
});