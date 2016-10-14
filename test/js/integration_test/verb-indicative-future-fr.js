QUnit.test( "Verb : Indicative - Future simple FR", function( assert ) {
    var tense = "f";

    assert.equal( V('placer').f(tense).pe(+1), "placerai", "Je placerai" );
    assert.equal( V('placer').f(tense).pe(+2), "placeras", "Tu placeras" );
    assert.equal( V('placer').f(tense).pe(+3), "placera", "Il placera" );
    assert.equal( V('placer').f(tense).pe(+4), "placerons", "Nous placerons" );
    assert.equal( V('placer').f(tense).pe(+5), "placerez", "Vous placerez" );
    assert.equal( V('placer').f(tense).pe(+6), "placeront", "Ils placeront" );
    
    assert.equal( V('aller').f(tense).pe(+1), "irai", "J'irai" );
    assert.equal( V('aller').f(tense).pe(+2), "iras", "Tu iras" );
    assert.equal( V('aller').f(tense).pe(+3), "ira", "Il ira" );
    assert.equal( V('aller').f(tense).pe(+4), "irons", "Nous irons" );
    assert.equal( V('aller').f(tense).pe(+5), "irez", "Vous irez" );
    assert.equal( V('aller').f(tense).pe(+6), "iront", "Ils iront" );
    
    assert.equal( V('apprendre').f(tense).pe(+1), "apprendrai", "J'apprendrai" );
    assert.equal( V('apprendre').f(tense).pe(+2), "apprendras", "Tu apprendras" );
    assert.equal( V('apprendre').f(tense).pe(+3), "apprendra", "Il apprendra" );
    assert.equal( V('apprendre').f(tense).pe(+4), "apprendrons", "Nous apprendrons" );
    assert.equal( V('apprendre').f(tense).pe(+5), "apprendrez", "Vous apprendrez" );
    assert.equal( V('apprendre').f(tense).pe(+6), "apprendront", "Ils apprendront" );
    
    assert.equal( V('mettre').f(tense).pe(+1), "mettrai", "Je mettrai" );
    assert.equal( V('mettre').f(tense).pe(+2), "mettras", "Tu mettras" );
    assert.equal( V('mettre').f(tense).pe(+3), "mettra", "Il mettra" );
    assert.equal( V('mettre').f(tense).pe(+4), "mettrons", "Nous mettrons" );
    assert.equal( V('mettre').f(tense).pe(+5), "mettrez", "Vous mettrez" );
    assert.equal( V('mettre').f(tense).pe(+6), "mettront", "Ils mettront" );
    
    assert.equal( V('faire').f(tense).pe(+1), "ferai", "Je ferai" );
    assert.equal( V('faire').f(tense).pe(+2), "feras", "Tu feras" );
    assert.equal( V('faire').f(tense).pe(+3), "fera", "Il fera" );
    assert.equal( V('faire').f(tense).pe(+4), "ferons", "Nous ferons" );
    assert.equal( V('faire').f(tense).pe(+5), "ferez", "Vous ferez" );
    assert.equal( V('faire').f(tense).pe(+6), "feront", "Ils feront" );
//    
    assert.equal( V('courir').f(tense).pe(+1), "courrai", "Je courrai" );
    assert.equal( V('courir').f(tense).pe(+2), "courras", "Tu courras" );
    assert.equal( V('courir').f(tense).pe(+3), "courra", "Il courra" );
    assert.equal( V('courir').f(tense).pe(+4), "courrons", "Nous courrons" );
    assert.equal( V('courir').f(tense).pe(+5), "courrez", "Vous courrez" );
    assert.equal( V('courir').f(tense).pe(+6), "courront", "Ils courront" );
//    
    assert.equal( V('vivre').f(tense).pe(+1), "vivrai", "Je vivrai" );
    assert.equal( V('vivre').f(tense).pe(+2), "vivras", "Tu vivras" );
    assert.equal( V('vivre').f(tense).pe(+3), "vivra", "Il vivra" );
    assert.equal( V('vivre').f(tense).pe(+4), "vivrons", "Nous vivrons" );
    assert.equal( V('vivre').f(tense).pe(+5), "vivrez", "Vous vivrez" );
    assert.equal( V('vivre').f(tense).pe(+6), "vivront", "Ils vivront" );
    
    assert.equal( V('finir').f(tense).pe(+1), "finirai", "Je finis" );
    assert.equal( V('finir').f(tense).pe(+2), "finiras", "Tu finis" );
    assert.equal( V('finir').f(tense).pe(+3), "finira", "Il finit" );
    assert.equal( V('finir').f(tense).pe(+4), "finirons", "Nous finissons" );
    assert.equal( V('finir').f(tense).pe(+5), "finirez", "Vous finissez" );
    assert.equal( V('finir').f(tense).pe(+6), "finiront", "Ils finissent" );
    
    assert.equal( V('subir').f(tense).pe(+1), "subirai", "Je subirai" );
    assert.equal( V('subir').f(tense).pe(+2), "subiras", "Tu subiras" );
    assert.equal( V('subir').f(tense).pe(+3), "subira", "Il subira" );
    assert.equal( V('subir').f(tense).pe(+4), "subirons", "Nous subirons" );
    assert.equal( V('subir').f(tense).pe(+5), "subirez", "Vous subirez" );
    assert.equal( V('subir').f(tense).pe(+6), "subiront", "Ils subiront" );
    
//    assert.equal( , "", "" );
});