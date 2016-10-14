<?php
ob_start();

header('Content-Type: text/html; charset=utf-8');

/*** error reporting on ***/
error_reporting(-1);
ini_set('display_errors', 'On');

define('REAL_PATH_ROOT', realpath('../../../') . '/');
require_once REAL_PATH_ROOT . 'autoloader.php';

ob_end_clean();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- Gabarit de génération avec JSreal -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script>
        var BASE_URL = "<?php echo Config::get('app.url'); ?>";
    </script>
    <script type="text/javascript" charset="utf-8" src="../../../public/js/jquery-1.11.2.min.js"></script>
    <!--<script type="text/javascript" charset="utf-8" src="../../../public/data/lex-fr.js"></script>-->
    <script type="text/javascript" charset="utf-8" src="../../../public/js/JSreal.js"></script>
    <!-- chargement de ACE l'éditeur javascript -->
    <script src="../lib/jsreal-ide/ace-src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <!-- chargement du script spécifique -->    
    <script type="text/javascript" charset="utf-8" src="../lib/jsreal-ide/js/jsTokenizer.js"></script>
    <script type="text/javascript" charset="utf-8" src="../lib/jsreal-ide/js/jsNode.js"></script>
    <script type="text/javascript" charset="utf-8" src="../lib/jsreal-ide/js/JSrealIDE.js"></script>
    <link rel="stylesheet" href="../lib/jsreal-ide/css/JSrealIDE.css" type="text/css" media="screen" charset="utf-8" />
    <title>JSreal-IDE</title>
</head>
<body>
    <h1>Environnement de programmation JSreal</h1>
    <div id="container">
      <div id="centre">
          <input type="button" id="bouton" value="Réaliser" style="width: 200px;" />
      </div>
      <div id="droite">
          Cliquer sur un noeud pour voir la réalisation de son sous-arbre
      </div>
    </div>
    <div id="entree"></div>
    <div id="sep"></div>
    <div id="sortie">
        <div id="realisation"></div>
        <canvas id="canvas" height="1000" width="2000"></canvas>
    </div>
</body>
</html>
