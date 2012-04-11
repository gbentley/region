<?php
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 
    'debug-message' => '' , 
    'use-session' => true , 
    'use-modules' => true , 
    'use-extensions' => true 
) );
$script->startup();
$options = $script->getOptions( "", "", array() );
$script->initialize();

$db = eZDB::instance();
#$db->begin();


$db->query( "DROP TABLE IF EXISTS ip2country" );

$db->query( "CREATE TABLE ip2country (
  start_ip CHAR(15) NOT NULL,
  end_ip CHAR(15) NOT NULL,
  start INT UNSIGNED NOT NULL,
  end INT UNSIGNED NOT NULL,
  cc CHAR(2) NOT NULL,
  cn VARCHAR(50) NOT NULL
)" );

$row = 1;
$handle = fopen( "extension/region/GeoIPCountryWhois.csv", "r" );
while ( ( $data = fgetcsv( $handle, 0, ",", '"' ) ) !== false )
{
    $query = "INSERT INTO ip2country ( start_ip, end_ip, start, end, cc,cn ) VALUES ( '" . $db->escapeString( $data[0] ) . "', '" . $db->escapeString( $data[1] ) . "' ,'" . $db->escapeString( $data[2] ) . "', '" . $db->escapeString( $data[3] ) . "' , '" . $db->escapeString( $data[4] ) . "', '" . $db->escapeString( $data[5] ) . "' );";
    
    if ( $row >= 540000 )
    {
        echo $query . ";";
    }
    $result = $db->query( $query );
    if ( $result == false )
    {
        echo "FAILD: " . $query;
    }
    if ( $row % 1000 == 0 )
    {
        echo $row . " records inserted\n";
    }
    $row ++;
}
fclose( $handle );
$db->query( "DROP TABLE IF EXISTS ezx_i2c_cc" );
$db->query( "CREATE TABLE ezx_i2c_cc (
  ci TINYINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  cc CHAR(2) NOT NULL,
  cn VARCHAR(50) NOT NULL
)" );
$db->query( "DROP TABLE IF EXISTS ezx_i2c_ip" );
$db->query( "CREATE TABLE ezx_i2c_ip (
  start INT UNSIGNED NOT NULL,
  end INT UNSIGNED NOT NULL,
  ci TINYINT UNSIGNED NOT NULL
);" );
$db->query( "INSERT INTO ezx_i2c_cc SELECT DISTINCT NULL,cc,cn FROM ip2country;" );
$db->query( "INSERT INTO ezx_i2c_ip SELECT start,end,ci FROM ip2country NATURAL JOIN ezx_i2c_cc;" );
$db->query( "DROP TABLE IF EXISTS ip2country" );
#$db->commit();
$cli->output( "Done." );
$cli->output( "Dump results with mysqldump --add-drop-table databasename ezx_i2c_cc ezx_i2c_ip > region.sql" );
$script->shutdown();

?>