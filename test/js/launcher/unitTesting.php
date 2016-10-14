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
//    var BASE_URL = "<?php echo BASE_URL; ?>";
    var URL = {
        "lexicon": {
            "en": "<?php echo BASE_URL; ?>public/data/lex-en.min.json",
            "fr": "<?php echo BASE_URL; ?>public/data/lex-fr.min.json"
        },
        "rule": {
            "en": "<?php echo BASE_URL; ?>public/data/rule-en.min.json",
            "fr": "<?php echo BASE_URL; ?>public/data/rule-fr.min.json"
        }
    };
</script>
</head>
<body>
<div id="qunit"></div>
<div id="qunit-fixture"></div>
<script src="<?php echo BASE_URL; ?>public/js/jquery-1.11.2.min.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>test/js/lib/qunit/qunit-1.17.1.js" charset="UTF-8"></script>
<script src="<?php echo BASE_URL; ?>public/js/util.js" charset="UTF-8"></script>

<script src="<?php echo BASE_URL; ?>public/js/JSreal-2.0.js" charset="UTF-8"></script>

<!-- Util -->
<script src="<?php echo BASE_URL; ?>test/js/unit_test/util.js" charset="UTF-8"></script>

<!-- Verb Conjugation -->
<script src="<?php echo BASE_URL; ?>test/js/unit_test/verb-indicative-present-fr.js" charset="UTF-8"></script>

</body>
</html>