<?php

error_reporting(-1);
ini_set('display_errors', 'On');

require_once '../../../lib/geoip/src/geoipcity.inc';
require_once '../../../lib/geoip/src/geoipregionvars.php';

$gi = geoip_open("../../../tmp/geoip/GeoLiteCity.dat",GEOIP_STANDARD);

$record = geoip_record_by_addr($gi, "132.204.24.20");
print $record->country_code . " " . $record->country_code3 . " " . $record->country_name . "\n";
print $record->region . " " . $GEOIP_REGION_NAME[$record->country_code][$record->region] . "\n";
print $record->city . "\n";
print $record->postal_code . "\n";
print $record->latitude . "\n";
print $record->longitude . "\n";
print $record->metro_code . "\n";
print $record->area_code . "\n";
print $record->continent_code . "\n";

geoip_close($gi);