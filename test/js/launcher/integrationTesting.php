<?php

ob_start();

header('Content-Type: text/html; charset=utf-8');

/*** error reporting on ***/
error_reporting(-1);
ini_set('display_errors', 'On');

define('REAL_PATH_ROOT', realpath('../../../') . '/');
require_once REAL_PATH_ROOT . 'autoloader.php';

ob_end_clean();

define('BASE_URL', Config::get('app.url'));

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>QUnit Example</title>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>test/js/lib/qunit/qunit-1.17.1.css">
<script>
    var BASE_URL = "<?php echo BASE_URL; ?>";
</script>
</head>
<body>
<div id="qunit"></div>
<div id="qunit-fixture"></div>
<script src="<?php echo BASE_URL; ?>public/js/jquery-1.11.2.min.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>test/js/lib/qunit/qunit-1.17.1.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>public/js/util.js" charset="UTF-8"></script>

<!--<script src="<?php echo BASE_URL; ?>public/data/lex-fr.min.js" charset="UTF-8"></script>-->
<script src="<?php echo BASE_URL; ?>public/js/JSreal.js" charset="UTF-8"></script>

<!-- Other -->
<script src="<?php echo BASE_URL; ?>test/js/integration_test/date-fr.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>test/js/integration_test/number-fr.js" charset="UTF-8"></script>

<!-- Plural -->
<script src="<?php echo BASE_URL; ?>test/js/integration_test/plural-noun-fr.js" charset="UTF-8"></script>

<!-- Feminine -->
<script src="<?php echo BASE_URL; ?>test/js/integration_test/feminine-noun-fr.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>test/js/integration_test/feminine-adjective-fr.js" charset="UTF-8"></script>

<!-- Verb -->
<script src="<?php echo BASE_URL; ?>test/js/integration_test/verb-indicative-past-fr.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>test/js/integration_test/verb-indicative-present-fr.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>test/js/integration_test/verb-indicative-future-fr.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>test/js/integration_test/verb-participle-fr.js" charset="UTF-8"></script>

<!-- Dat presentation -->
<script src="<?php echo BASE_URL; ?>test/js/integration_test/presentation-fr.js" charset="UTF-8"></script>

<!-- Expression -->
<script src="<?php echo BASE_URL; ?>test/js/integration_test/expression-fr.js" charset="UTF-8"></script>
</body>
</html>