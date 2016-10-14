QUnit.test( "Feminine of adjectives FR", function( assert ) {
    
    assert.equal( A("calme").g('f'), "calme", "calme	calme" );
    assert.equal( A("continuel").g('f'), "continuelle", "continuel	continuelle" );
    assert.equal( A("pareil").g('f'), "pareille", "pareil	pareille" );
    assert.equal( A("ancien").g('f'), "ancienne", "ancien	ancienne" );
    assert.equal( A("bon").g('f'), "bonne", "bon	bonne" );
    assert.equal( A("cadet").g('f'), "cadette", "cadet	cadette" );
    assert.equal( A("creux").g('f'), "creuse", "creux	creuse" );
    assert.equal( A("léger").g('f'), "légère", "léger	légère" );
    assert.equal( A("beau").g('f'), "belle", "beau	belle" );
    assert.equal( A("aigu").g('f'), "aiguë", "aigu	aiguë" );
    assert.equal( A("long").g('f'), "longue", "long	longue" );
    assert.equal( A("moqueur").g('f'), "moqueuse", "moqueur	moqueuse" );
    assert.equal( A("meilleur").g('f'), "meilleure", "meilleur	meilleure" );
    assert.equal( A("migrateur").g('f'), "migratrice", "migrateur	migratrice" );
    assert.equal( A("attentif").g('f'), "attentive", "attentif	attentive" );
    
//    assert.equal( , "", "" );
});