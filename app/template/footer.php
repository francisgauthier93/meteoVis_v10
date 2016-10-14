    </div>
</body>
</html>
<!-- bootstrap -->
<?php echo Import::css('public/css/bootstrap-3.3.2.min.css'); ?>
<?php echo Import::css('public/css/bootstrap-theme-3.3.2.min.css'); ?>
<!--<link rel="stylesheet" href="public/css/bootstrap-3.3.2.min.css" type="text/css" charset="utf-8" />-->
<!--<link rel="stylesheet" href="public/css/bootstrap-theme-3.3.2.min.css" type="text/css" charset="utf-8" />-->

<!-- spécifique à MeteoVis -->
<?php echo Import::css('public/css/MeteoVis.css'); ?>
<?php echo Import::css('public/css/MeteoVis-typeahead.css'); ?>
<!--<link rel="stylesheet" href="public/css/MeteoVis.css" type="text/css" charset="utf-8">
<link rel="stylesheet" href="public/css/MeteoVis-typeahead.css" type="text/css" charset="utf-8">-->

<?php echo Import::js('public/js/jquery-1.11.2.min.js'); ?>
<?php echo Import::js('public/js/bootstrap-3.3.2.min.js'); ?>
<?php echo Import::js('public/js/typeahead.bundle.min.js'); ?>
<!--<script type="text/javascript" charset="utf-8" src="public/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/bootstrap-3.3.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/typeahead.bundle.min.js"></script>-->
<script language="javascript" type="text/javascript">
    var currNbJours = <?php echo ($param = 7); ?>
</script>

<?php echo Import::js('public/js/date.js'); ?>
<?php echo Import::js('public/data/regionTable.min.js'); ?>
<?php echo Import::js('public/js/MeteoVis.js'); ?>
<?php echo Import::js('public/js/util.js'); ?>
<?php echo Import::js('public/js/translator.js'); ?>
<!--<script type="text/javascript" charset="utf-8" src="public/js/date.js"></script>
<script type="text/javascript" charset="utf-8" src="public/data/regionTable.min.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/MeteoVis.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/util.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/translator.js"></script>-->

<?php echo Import::js('public/js/JSreal.js'); ?>
<?php echo Import::js('public/js/jsreal-realization.js'); ?>
<!--<script type="text/javascript" charset="utf-8" src="public/data/lex-fr.min.js"></script>-->
<!--<script type="text/javascript" charset="utf-8" src="public/js/JSreal.js"></script>
<script type="text/javascript" charset="utf-8" src="public/js/jsreal-realization.js"></script>-->