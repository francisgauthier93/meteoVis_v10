QUnit.test( "Feminine of nouns FR", function( assert ) {
    assert.equal( N("chat").g('f'), "chatte", "chat	chatte" );
    assert.equal( N("époux").g('f'), "épouse", "époux	épouse" );
    assert.equal( N("blateur").g('f'), "blatrice", "blateur	blatrice" );
    assert.equal( N("chien").g('f'), "chienne", "chien      chienne" );
//    assert.equal( N("père").g('f'), "mère", "père       mère" );
    assert.equal( N("vendeur").g('f'), "vendeuse", "vendeur       vendeuse" );
//    assert.equal( N("").n('p'), "", "" );
});