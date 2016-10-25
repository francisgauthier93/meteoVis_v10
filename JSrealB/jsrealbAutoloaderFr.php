<!DOCTYPE html>
<html lang="fr">
    <head>

        <title>Realisation avec JSrealB pour MeteoVis</title>
        <meta charset="UTF-8">
        <script src="../JSrealB/static/js/JSrealB.js" charset="UTF-8"></script>

<script type="text/javascript">
var url = <?php echo json_encode($_SERVER['REQUEST_URI']);?>;
console.log(url);

//var BASE_URL = <?php
    //echo $_SERVER['REQUEST_URI'];
//?>
    
// var URL = {
//                 lexicon:  {
//                     fr: "data/lex-fr.min.json",
//                     en: "data/lex-en.min.json"
//                 },
//                 rule: {
//                     fr: "data/rule-fr.min.json",
//                     en: "data/rule-en.min.json"
//                 },
//                 feature: "data/feature.min.json"
//             };

//     JSrealLoader({
//                 language: "fr",
//                 lexiconUrl: URL.lexicon.fr,
//                 ruleUrl: URL.rule.fr,
//                 featureUrl: URL.feature
//             }, function() {
//                 console.log("Langue française chargée");
//             });
</script>
<?php
echo $_SERVER['REQUEST_URI'];
?>
</head>
</html>