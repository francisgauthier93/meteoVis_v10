QUnit.test( "Verb : Participle - Present FR", function( assert ) {
    var tense = "pr";

    assert.equal( V('placer').f(tense).pe(+1), "plaçant", "Plaçant" );
    assert.equal( V('parler').f(tense).pe(+1), "parlant", "Parlant" );
    
    assert.equal( V('finir').f(tense).pe(+1), "finissant", "Finissant" );
    
    assert.equal( V('pouvoir').f(tense).pe(+1), "pouvant", "Pouvant" );
    assert.equal( V('changer').f(tense).pe(+1), "changeant", "Changeant" );
    assert.equal( V('apprendre').f(tense).pe(+1), "apprenant", "Apprenant" );
    assert.equal( V('boire').f(tense).pe(+1), "buvant", "Buvant" );
    assert.equal( V('connaître').f(tense).pe(+1), "connaissant", "Connaissant" );
    assert.equal( V('croire').f(tense).pe(+1), "croyant", "Croyant" );
    assert.equal( V('écrire').f(tense).pe(+1), "écrivant", "Ecrivant" );
    assert.equal( V('peindre').f(tense).pe(+1), "peignant", "Peignant" );
    assert.equal( V('voir').f(tense).pe(+1), "voyant", "Voyant" );
});

QUnit.test( "Verb : Participle - Past FR", function( assert ) {
    var tense = "pp";
    
    assert.equal( V('placer').f(tense).pe(+1), "placé", "Placé" );
    assert.equal( V('parler').f(tense).pe(+1), "parlé", "Parlé" );
    
    assert.equal( V('finir').f(tense).pe(+1), "fini", "Fini" );
    
    assert.equal( V('pouvoir').f(tense).pe(+1), "pu", "Pu" );
    
    assert.equal( V('naître').f(tense).pe(+1), "né", "Né" );
    assert.equal( V('aller').f(tense).pe(+1), "allé", "Allé" );
    assert.equal( V('joindre').f(tense).pe(+1), "joint", "Joint" );
    assert.equal( V('asseoir').f(tense).pe(+1), "assis", "Assis" );
    assert.equal( V('rire').f(tense).pe(+1), "ri", "Ri" );
    assert.equal( V('recevoir').f(tense).pe(+1), "reçu", "Reçu" );
    assert.equal( V('convaincre').f(tense).pe(+1), "convaincu", "Convaincu" );
});