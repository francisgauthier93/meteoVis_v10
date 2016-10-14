//QUnit.test( "Verb : Coumpound past FR", function( assert ) {
//    var tense = "pc";
//
//    assert.equal( V('placer').f(tense).pe(+1), "placé", "J'ai placé" );
//    assert.equal( V('placer').f(tense).pe(+2), "placé", "Tu as placé" );
//    assert.equal( V('placer').f(tense).pe(+3), "placé", "Il a placé" );
//    assert.equal( V('placer').f(tense).pe(+4), "placé", "Nous avons placé" );
//    assert.equal( V('placer').f(tense).pe(+5), "placé", "Vous avez placé" );
//    assert.equal( V('placer').f(tense).pe(+6), "placé", "Ils ont placé" );
//
//    
////    assert.equal( , "", "" );
//});

QUnit.test( "Verb : Indicative - Imperfect FR", function( assert ) {
    var tense = "i";

    assert.equal( V('placer').f(tense).pe(+1), "plaçais", "Je plaçais" );
    assert.equal( V('placer').f(tense).pe(+2), "plaçais", "Tu plaçais" );
    assert.equal( V('placer').f(tense).pe(+3), "plaçait", "Il plaçait" );
    assert.equal( V('placer').f(tense).pe(+4), "placions", "Nous plaçions" );
    assert.equal( V('placer').f(tense).pe(+5), "placiez", "Vous plaçiez" );
    assert.equal( V('placer').f(tense).pe(+6), "plaçaient", "Ils plaçaient" );
    
    assert.equal( V('aller').f(tense).pe(+1), "allais", "J'allais" );
    assert.equal( V('aller').f(tense).pe(+2), "allais", "Tu allais" );
    assert.equal( V('aller').f(tense).pe(+3), "allait", "Il allait" );
    assert.equal( V('aller').f(tense).pe(+4), "allions", "Nous allions" );
    assert.equal( V('aller').f(tense).pe(+5), "alliez", "Vous alliez" );
    assert.equal( V('aller').f(tense).pe(+6), "allaient", "Ils allaient" );
    
    assert.equal( V('apprendre').f(tense).pe(+1), "apprenais", "J'apprenais" );
    assert.equal( V('apprendre').f(tense).pe(+2), "apprenais", "Tu apprenais" );
    assert.equal( V('apprendre').f(tense).pe(+3), "apprenait", "Il apprenait" );
    assert.equal( V('apprendre').f(tense).pe(+4), "apprenions", "Nous apprenions" );
    assert.equal( V('apprendre').f(tense).pe(+5), "appreniez", "Vous appreniez" );
    assert.equal( V('apprendre').f(tense).pe(+6), "apprenaient", "Ils apprenaient" );
    
    assert.equal( V('mettre').f(tense).pe(+1), "mettais", "Je mettais" );
    assert.equal( V('mettre').f(tense).pe(+2), "mettais", "Tu mettais" );
    assert.equal( V('mettre').f(tense).pe(+3), "mettait", "Il mettait" );
    assert.equal( V('mettre').f(tense).pe(+4), "mettions", "Nous mettions" );
    assert.equal( V('mettre').f(tense).pe(+5), "mettiez", "Vous mettiez" );
    assert.equal( V('mettre').f(tense).pe(+6), "mettaient", "Ils mettaient" );
    
    assert.equal( V('faire').f(tense).pe(+1), "faisais", "Je faisais" );
    assert.equal( V('faire').f(tense).pe(+2), "faisais", "Tu faisais" );
    assert.equal( V('faire').f(tense).pe(+3), "faisait", "Il faisait" );
    assert.equal( V('faire').f(tense).pe(+4), "faisions", "Nous faisions" );
    assert.equal( V('faire').f(tense).pe(+5), "faisiez", "Vous faisiez" );
    assert.equal( V('faire').f(tense).pe(+6), "faisaient", "Ils faisaient" );
    
    assert.equal( V('courir').f(tense).pe(+1), "courais", "Je courais" );
    assert.equal( V('courir').f(tense).pe(+2), "courais", "Tu courais" );
    assert.equal( V('courir').f(tense).pe(+3), "courait", "Il courait" );
    assert.equal( V('courir').f(tense).pe(+4), "courions", "Nous courions" );
    assert.equal( V('courir').f(tense).pe(+5), "couriez", "Vous couriez" );
    assert.equal( V('courir').f(tense).pe(+6), "couraient", "Ils couraient" );
    
    assert.equal( V('vivre').f(tense).pe(+1), "vivais", "Je vivais" );
    assert.equal( V('vivre').f(tense).pe(+2), "vivais", "Tu vivais" );
    assert.equal( V('vivre').f(tense).pe(+3), "vivait", "Il vivait" );
    assert.equal( V('vivre').f(tense).pe(+4), "vivions", "Nous vivions" );
    assert.equal( V('vivre').f(tense).pe(+5), "viviez", "Vous viviez" );
    assert.equal( V('vivre').f(tense).pe(+6), "vivaient", "Ils vivaient" );
    
//    assert.equal( , "", "" );
});