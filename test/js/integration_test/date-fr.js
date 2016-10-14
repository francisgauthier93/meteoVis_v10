QUnit.test( "Date FR", function( assert ) {
    function assertEqual(e1, e2, comment)
    {
        assert.equal(e1.toString().replace(/\xA0/g," "), e2, comment);
    }

    assertEqual( DT().y(2015).m(10).d(21).h(16).min(29).noDay(true), "le 21 octobre 2015 à 16 h 29", 'date et heure, pas de jour de la semaine' );
    assertEqual( DT().y(2015).m(10).d(21).h(16).min(29).only('date'), "le mercredi 21 octobre 2015", 'date seulement, avec jour de la semaine' );
    assertEqual( DT().y(2015).m(10).d(21).h(16).min(29).only('time'), "16 h 29", 'heure seulement' );
    assertEqual( DT().h(2).min(3).s(38), "2 h 3 min 38 s", 'heures, minutes et secondes' );
    assertEqual( DT().y(-480).m(8).d(11).noDay(true), "le 11 août -480", 'dates négatives' );
    assertEqual( DT().y(2000).m(10), "en octobre 2000", 'juste mois et année' );
    assertEqual( DT().y(2000), "en 2000", 'juste année' );
    assertEqual( DT(new Date().getTime()+24*60*60*1000).t('nat').only('date'), "demain", '"demain" naturellement généré' );
    assertEqual( DTR(DT().y(2014).m(10).d(14),DT().y(2015).m(10).d(17)).noDay(true), "du 14 octobre 2014 au 17 octobre 2015", 'intervalle de dates distantes, contraction automatique en "du" et "au"' );
    assertEqual( DTR(DT().y(2015).m(10).d(14), DT().y(2015).m(10).d(17)).noDay(true), "du 14 au 17 octobre 2015", 'intervalle de dates rapprochées' );
    assertEqual( DTR(DT(new Date().getTime()-24*60*60*1000),DT(new Date().getTime()+24*60*60*1000)).t('nat').only('date'), "d'hier à demain", 'intervalle de dates en termes naturels' );
    assertEqual( DTR(DT(new Date().getTime()),DT(new Date().getTime()+24*60*60*1000)).t('nat').only('date'), "aujourd'hui et demain", 'liste de dates en termes naturels' );
    assertEqual( DTR(DT().y(2000),DT(new Date().getTime())).t('nat').only('date'), "de 2000 à aujourd'hui", 'intervalle de dates avec juste année' );
    assertEqual( DTR(DT().y(2000).m(10).d(10),DT().y(2000).m(10).d(11)), "les mardi 10 et mercredi 11 octobre 2000", 'liste de dates des mêmes mois et année' );
    assertEqual( DT('2010-10-21T21:16:29'), "le jeudi 21 octobre 2010 à 21 h 16 min 29 s", 'expression temporelle à partir d\'une chaîne' );
    //    assertEqual( , "", '' );
});