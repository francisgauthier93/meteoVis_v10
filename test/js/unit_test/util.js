QUnit.test( "Util functions", function( assert ) {
    // removeEnd
    assert.equal( "placer".removeEnd("cer") , "pla", "Récupération du radical du verbe 'placer'");
    assert.raises( function() { "manger".removeEnd("ir"); }, "Erreur attendue : mauvaise terminaison");
    assert.raises( function() { "manger".removeEnd(""); }, "Erreur attendue : terminaison vide");
    assert.raises( function() { "manger".removeEnd("amanger"); }, "Erreur attendue : terminaison plus grande que le mot (par l'avant)");
    assert.raises( function() { "manger".removeEnd("mangera"); }, "Erreur attendue : terminaison plus grande que le mot (par l'arrière)");


});