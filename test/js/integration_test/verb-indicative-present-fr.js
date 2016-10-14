QUnit.test( "Verb : Indicative - Present FR", function( assert ) {
    var tense = "p";

    assert.equal( V('placer').f(tense).pe(+1), "place", "Je place" );
    assert.equal( V('placer').f(tense).pe(+2), "places", "Tu places" );
    assert.equal( V('placer').f(tense).pe(+3), "place", "Il place" );
    assert.equal( V('placer').f(tense).pe(+4), "plaçons", "Nous plaçons" );
    assert.equal( V('placer').f(tense).pe(+5), "placez", "Vous placez" );
    assert.equal( V('placer').f(tense).pe(+6), "placent", "Ils placent" );
    
    assert.equal( V('aller').f(tense).pe(+1), "vais", "Je vais" );
    assert.equal( V('aller').f(tense).pe(+2), "vas", "Tu vas" );
    assert.equal( V('aller').f(tense).pe(+3), "va", "Il va" );
    assert.equal( V('aller').f(tense).pe(+4), "allons", "Nous allons" );
    assert.equal( V('aller').f(tense).pe(+5), "allez", "Vous allez" );
    assert.equal( V('aller').f(tense).pe(+6), "vont", "Ils vont" );
    
    assert.equal( V('finir').f(tense).pe(+1), "finis", "Je finis" );
    assert.equal( V('finir').f(tense).pe(+2), "finis", "Tu finis" );
    assert.equal( V('finir').f(tense).pe(+3), "finit", "Il finit" );
    assert.equal( V('finir').f(tense).pe(+4), "finissons", "Nous finissons" );
    assert.equal( V('finir').f(tense).pe(+5), "finissez", "Vous finissez" );
    assert.equal( V('finir').f(tense).pe(+6), "finissent", "Ils finissent" );
    
    assert.equal( V('subir').f(tense).pe(+1), "subis", "Je subis" );
    assert.equal( V('subir').f(tense).pe(+2), "subis", "Tu subis" );
    assert.equal( V('subir').f(tense).pe(+3), "subit", "Il subit" );
    assert.equal( V('subir').f(tense).pe(+4), "subissons", "Nous subissons" );
    assert.equal( V('subir').f(tense).pe(+5), "subissez", "Vous subissez" );
    assert.equal( V('subir').f(tense).pe(+6), "subissent", "Ils subissent" );
    
    assert.equal( V('apprendre').f(tense).pe(+1), "apprends", "J'apprends" );
    assert.equal( V('apprendre').f(tense).pe(+2), "apprends", "Tu apprends" );
    assert.equal( V('apprendre').f(tense).pe(+3), "apprend", "Il apprend" );
    assert.equal( V('apprendre').f(tense).pe(+4), "apprenons", "Nous apprenons" );
    assert.equal( V('apprendre').f(tense).pe(+5), "apprenez", "Vous apprenez" );
    assert.equal( V('apprendre').f(tense).pe(+6), "apprennent", "Ils apprennent" );

    assert.equal( V('mettre').f(tense).pe(+1), "mets", "Je mets" );
    assert.equal( V('mettre').f(tense).pe(+2), "mets", "Tu mets" );
    assert.equal( V('mettre').f(tense).pe(+3), "met", "Il met" );
    assert.equal( V('mettre').f(tense).pe(+4), "mettons", "Nous mettons" );
    assert.equal( V('mettre').f(tense).pe(+5), "mettez", "Vous mettez" );
    assert.equal( V('mettre').f(tense).pe(+6), "mettent", "Ils mettent" );
    
    assert.equal( V('faire').f(tense).pe(+1), "fais", "Je fais" );
    assert.equal( V('faire').f(tense).pe(+2), "fais", "Tu fais" );
    assert.equal( V('faire').f(tense).pe(+3), "fait", "Il fait" );
    assert.equal( V('faire').f(tense).pe(+4), "faisons", "Nous faisons" );
    assert.equal( V('faire').f(tense).pe(+5), "faites", "Vous faites" );
    assert.equal( V('faire').f(tense).pe(+6), "font", "Ils font" );
    
    assert.equal( V('courir').f(tense).pe(+1), "cours", "Je cours" );
    assert.equal( V('courir').f(tense).pe(+2), "cours", "Tu cours" );
    assert.equal( V('courir').f(tense).pe(+3), "court", "Il court" );
    assert.equal( V('courir').f(tense).pe(+4), "courons", "Nous courons" );
    assert.equal( V('courir').f(tense).pe(+5), "courez", "Vous courez" );
    assert.equal( V('courir').f(tense).pe(+6), "courent", "Ils courent" );
    
    assert.equal( V('vivre').f(tense).pe(+1), "vis", "Je vis" );
    assert.equal( V('vivre').f(tense).pe(+2), "vis", "Tu vis" );
    assert.equal( V('vivre').f(tense).pe(+3), "vit", "Il vit" );
    assert.equal( V('vivre').f(tense).pe(+4), "vivons", "Nous vivons" );
    assert.equal( V('vivre').f(tense).pe(+5), "vivez", "Vous vivez" );
    assert.equal( V('vivre').f(tense).pe(+6), "vivent", "Ils vivent" );
    
    assert.equal( V('vouloir').f(tense).pe(+1), "veux", "Je veux" );
    assert.equal( V('vouloir').f(tense).pe(+2), "veux", "Tu veux" );
    assert.equal( V('vouloir').f(tense).pe(+3), "veut", "Il veut" );
    assert.equal( V('vouloir').f(tense).pe(+4), "voulons", "Nous voulons" );
    assert.equal( V('vouloir').f(tense).pe(+5), "voulez", "Vous voulez" );
    assert.equal( V('vouloir').f(tense).pe(+6), "veulent", "Ils veulent" );
    
//    assert.equal( , "", "" );
});