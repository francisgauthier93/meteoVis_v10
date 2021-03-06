QUnit.test( "Number FR", function( assert ) {
    console.log(N('rangée').d(1).num('l'));
    console.log(N('rangée').d(1).num('l').toString());
    assert.equal( N(221212221121/100000).num('d'), "2 212 122,211 21", 'Écriture décimale française' );
    assert.equal( N('rangée').d(25).num('l'), "vingt-cinq rangées", 'En toutes lettres' );
    assert.equal( N(2080218071.45718).num('l'), "deux milliards quatre-vingt millions deux cent dix-huit mille soixante-et-onze et quarante-cinq mille sept cent dix-huit cent-millièmes", 'En toutes lettres' );
    assert.equal( A(18071.718).num('ln'), "dix-huit-mille-soixante-et-onze et sept-cent-dix-huit millièmes", 'En toutes lettres' );
    assert.equal( N(1).d('d').num('l'), "le un", 'En toutes lettres' );
    assert.equal( N('un').d('d'), "le un", 'En toutes lettres' );
    assert.equal( A(1001001).num('on'), "un-million-mille-unième", 'Ordinaux' );
    assert.equal( N('rangée').d('d').add(A(1).num('o')), "la première rangée", 'Ordinaux' );
    assert.equal( S(A(11).num('o').d('d'), V('gagner') ).t('exc'), "Le onzième gagne!", 'Ordinaux' );
    assert.equal( S(N(2080218071.45718), VP(V('être'), N('nombre').d('i'))), "2080218071.45718 est un nombre.", 'nombre sans modification' );
    assert.equal( N(2080218071.45718).num('d'), "2 080 218 071,457 18", 'écriture décimale française' );
    assert.equal( N(2080218071.45718).num('l'), "deux milliards quatre-vingt millions deux cent dix-huit mille soixante-et-onze et quarante-cinq mille sept cent dix-huit cent-millièmes", 'écriture en toutes lettres, fraction décimale' );
    assert.equal( N(1001001001).num('l'), "un milliard un million mille un", 'problème réglé avec les 1' );
    assert.equal( N('rangée').d('d').add(A(25).num('l')), "la rangée vingt-cinq", 'nombre adjectif' );
    assert.equal( N('rangée').d('d').add(A(25)), "la rangée 25", 'nombre adjectif sans trait "num"' );
    assert.equal( N('rangée').d(3), "3 rangées", 'nombre déterminant' );
    assert.equal( N('rangée').d(3).num('l'), "trois rangées", 'nombre déterminant modifié par trait du NP' );
    assert.equal( N('rangée').d(1).num('l'), "une rangée", 'nombre déterminant 1 s\'accorde en genre' );
    assert.equal( N('rangée').d(2).num('d'), "2 rangées", 'nombre déterminant impose pluriel à partir de 2' );
    assert.equal( N('rangée').d(1.99).num('d'), "1,99 rangée", 'mais pas avant' );
    assert.equal( N('rangée').d(-2).num('d'), "-2 rangées", 'aussi dans le négatif' );
    assert.equal( S(N('un').d('d'),V('gagner').f('pc')).t('exc'), "Le un a gagné!", 'nombre nom n\'entraîne pas élision ou liaison' );
    assert.equal( S(N(11).d('d').num('l'),V('gagner').f('pc')).t('exc'), "Le onze a gagné!", 'pareil pour nombre en lettres généré par JSreal' );
    assert.equal( J(A(24),A(105),A(9),A(1001)).c(',').num('o'), "vingt-quatrième, cent cinquième, neuvième, mille unième", 'terminaisons de nombres ordinaux' );
    assert.equal( N('rangée').d('d').add(A(1).num('o')), "la première rangée", 'nombre ordinal comme adjectif' );
    assert.equal( N('conte').d('d').add(A(1001).num('o')), "le mille unième conte", 'nombre ordinal complexe' );
    assert.equal( A(11).num('o').d('d'), "le onzième", 'pas d\'élision ou liaison avec nombre ordinal' );
//    assert.equal( , "", '' );
});