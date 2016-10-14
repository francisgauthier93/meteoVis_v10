/*
 * Bibliothèque JavaScript JSreal
 * http://daou.st/JSreal
 * 
 * Par Nicolas Daoust, sous la direction de Guy Lapalme
 * Université de Montréal
 * 2013
 * 
 * Par Paul Molins, sous la direction de Guy Lapalme
 * Correction, Amélioration et portabilité vers la version anglaise
 * Université de Montréal
 * 2015
 */

var JSreal;
$.getJSON(BASE_URL + "public/data/lex-fr.json", function(data) {
    JSreal = JSrealFct(data);
});
 
var JSrealFct = function (data) {

    /* Paramètres */
    var config = {
        textTags: 0, // balises HTML: chevrons remplacés par '&lt;' (pratique pour tester)
        num: 0, // forme de nombre par défaut: false, l (lettres), d (décimal), t (téléphone)
        dateT: 'false', // ton de date: false, nat (naturel)
        CPcoo: 'et', // coordonnant de CP (recommandé: 'et')
        Jcoo: ' ', // coordonnant de J (recommandé: ' ')
        ScompPos: 'beg', // position de complément de phrase: beg, mid, end
        Hcap: 1, // majuscule automatique pour <h1> à <h6>   
        guilFR: 1 // guillemets français
    } // éventuellement déterminant devant nom


    /* Traitement du lexique */

//var lex // si retiré par commentaire, devient global
//    if(lexFR)
//    {
//        lex = lexFR;
//    }
//    else
//    {
//        lex = {};
//        warn('pas de lexique détecté');
//    }

    lex = data;

// Attention: seuls les traits de traitList seront ajoutés
//    lex.add = function (key, properties) {
//        lex[key] = lex[key] || [];
//        lex[key].push(properties);
//    };
//    lex.tr = function (key, traitName, traitValue, entryNum) {
//        entryNum = entryNum || 0;
//        lex[key][entryNum][traitName] = traitValue;
//    };
// quelques "mots" additionnels
//    var lexNewEntries = [
//        ["et/ou", {c: "C", n: 'p', nc: 1}],
//        ["Nicolas", {c: "N", g: 'm', i: 1, prp: 1}],
//        ["puisque", {c: "C", ve: 1}],
//        ["parce que", {c: "C", ve: 1}],
//        ["orange", {c: "N", g: 'f'}],
//        ["suspect", {c: "N", g: 'm'}],
//        ["moto", {c: "N", g: 'f'}],
//        ["sauce", {c: "N", g: 'f'}],
//        ["rangée", {c: "N", g: 'f'}],
//        ["un", {c: "N", nel: 1}],
//        ["onze", {c: "N", nel: 1}],
//        ["neuf", {c: "A", fs: 'neuve', fp: 'neuves'}],
//        ["promotion", {c: "N", g: 'f'}],
//        ["métis", {c: "A", fs: 'métisse', fp: 'métisses'}],
//        ["concession", {c: "N", g: 'f'}],
//        ["hibou", {c: "N", g: 'm', i: 1, h: 1}],
//        ["parenthèse", {c: "N", g: 'f'}],
//        ["génération", {c: "N", g: 'f'}],
//        ["tuque", {c: "N", g: 'f'}],
//        ["maximum", {c: "N", g: "m"}]
//    ];
//    for (var i = 0, imax = lexNewEntries.length; i < imax; i++) {
//        lex.add(lexNewEntries[i][0], lexNewEntries[i][1]);
//    }
//    var lexNewTraits = {
//        "à": [{ve: 1}],
//        "jusque": [{ve: 1}],
//        "puisque": [{ve: 1}],
//        "ce": [{}, {ve: 1}],
//        "avoir": [{ir: 'av'}],
//        "aller": [{ip2: 'va'}],
//        "finir": [{ir: 'finiss'}],
//        "pouvoir": [{pp: 'pu'}],
//        "gens": [{n: 'p'}],
//        "il": [{og: 'elle'}],
//        "lui": [{og: 'elle'}],
//        "ils": [{og: 'elles'}],
//        "eux": [{og: 'elles'}],
//        "celui": [{og: 'celle', p: 'ceux'}],
//        "celui-ci": [{og: 'celle-ci', p: 'ceux-ci'}],
//        "celui-là": [{og: 'celle-là', p: 'ceux-là'}],
//        "celle-ci": [{og: 'celui-ci', p: 'celles-ci'}],
//        "celle-là": [{og: 'celui-là', p: 'celles-là'}],
//        "ceux": [{og: 'celles'}],
//        "ceux-ci": [{og: 'celles-ci'}],
//        "ceux-là": [{og: 'celles-là'}],
//        "être": [{ps1: 'fus', ps2: 'fus', ps3: 'fut', ps4: 'fûmes', ps5: 'fûtes', ps6: 'furent'}],
//        "revoir": [{ps1: 'revis', ps2: 'revis', ps3: 'revit', ps4: 'revîmes', ps5: 'revîtes', ps6: 'revirent'}],
//        "rire": [{pp: "ri"}],
//        "recevoir": [{pp: "reçu"}]
//    }
//    for (var l in lexNewTraits)
//        for (var i = 0, imax = lexNewTraits[l].length; i < imax; i++) {
//            for (var t in lexNewTraits[l][i])
//            {
//                lex[l][i][t] = lexNewTraits[l][i][t];
//            }
//        }


    function lexE(key, properties) {
// juste utilisé pour gérer le lexique, pas ailleurs
        this.key = key
        for (var i = 0, imax = traitList.length; i < imax; i++) {
            var p = traitList[i]
            if(properties[p])
                this[p] = properties[p]
        }
    }
    lexE.prototype.toString = function () {
        var props = ''
        for (var i = 0, imax = traitList.length; i < imax; i++) {
            var p = traitList[i]
            if(this[p])
                props += p + ':' + this[p] + ' '
        }
        return this.key + ' ' + props.substring(0, props.length - 1)
    }

    function lexEntryCopy(key) {
        var entryCopy = []
        for (var i = 0, imax = lex[key].length; i < imax; i++) {
            entryCopy.push(lexElemCopy(lex[key][i]))
        }
        return entryCopy
    }
    function lexElemCopy(e) {
        var elemCopy = {}
        for (var i = 0, imax = traitList.length; i < imax; i++) {
            var p = traitList[i]
            if(e[p])
                elemCopy[p] = e[p]
        }
        return elemCopy
    }

    lex.get = function (key, props) {
        //if(key == 'être') pr2(key)
        if(lex[key]) { // si dans le lexique
            var l = lexEntryCopy(key), i
            if(props.c) { // requiert catégorie spécifique
                if(l) { // élimine tout mot de la mauvaise catégorie
                    i = 0
                    while (i < l.length) {
                        if(l[i].c != props.c)
                            l.splice(i, 1)
                        else
                            i++
                    }
                }
            }
            if(props.g) { // requiert genre spécifique
                if(l) { // cherche élément du bon genre
                    var gFound = false
                    for (var i = 0, imax = l.length; i < imax; i++) {
                        if(l[i].g == props.g)
                            gFound = true;
                        break
                    }
                    if(gFound) { // si tel élément trouvé, élimine les autres
                        i = 0
                        while (i < l.length) {
                            if(l[i].g != props.g)
                                l.splice(i, 1)
                            else
                                i++
                        }
                    }
                }
            }
            if(props.df) { // distingue certains pronoms
                if(l) {
                    // df:"i" / df:"s" versus d:1
                }
            }
            // si élément de lexique valide, le retourne
            if(l[0]) {
                l[0].found = true
                return l[0]
            }
        } // if(lex[key])
        return {} // pas trouvé dans lexique
    }




    /* Outils */

    Array.prototype.isArray = true
    Array.prototype.has = function (e) {
// renvoie true si tableau contient e
        for (i = 0; i < this.length; i++)
            if(e == this[i])
                return true
        return false
    }
    String.prototype.firstUpperCase = function () {
        var i = this.slice(0, 1) == '<' ? this.indexOf('>') + 1 || 0 : 0
        return this.slice(0, i) + this.slice(i, i + 1).toUpperCase() + this.slice(i + 1)
    }
    String.prototype.endReplace = function (t) {
// remplace t[0] par t[1]
        for (var i = 0, imax = t.length; i < imax; i++) {
            var ts = t[i].split(' > ')
            var a = ts[0], n = ts[1]
            if(this.end(a))
                return this.bs(a.length) + n
            if(a == '_')
                return this + n
        }
    }
    String.prototype.has = function (c) {
        if(typeof c == 'string')
            return this.indexOf(c) > -1 ? true : false;
        if(!c.isArray)
            c = arguments;
        if(c.isArray)
            for (var i = 0, imax = c.length; i < imax; i++)
                if(this.indexOf(c[i]) > -1)
                    return true
        return false
    }
    String.prototype.end = function (list) {
        if(typeof list == 'string')
            return this.checkEnd(list)
        else
            for (var i = 0; i < list.length; i++)
                if(this.checkEnd(list[i]))
                    return true
        return false
    }
    String.prototype.checkEnd = function (l) {
        if(this.substring(this.length - l.length) == l)
            return true
        else
            return false
    }
    String.prototype.beg = function (list) {
        if(typeof list == 'string')
            return this.checkBeg(list)
        else
            for (var i = 0; i < list.length; i++)
                if(this.checkBeg(list[i]))
                    return true
        return false
    }
    String.prototype.checkBeg = function (l) {
        if(this.slice(0, l.length) == l)
            return true
        else
            return false
    }
    String.prototype.bs = function (del) {
        if(del === 0)
            return this
        del = del || 1
        return this.slice(0, -del)
    }
    String.prototype.insertAt = function (i, str) {
        return this.slice(0, i) + str + this.slice(i)
    }
    if(!String.prototype.trim)
        String.prototype.trim = function () {
            return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '')
        }
    function isNumeric(n) {
        return (!isNaN(parseFloat(n)) && isFinite(n))
    }


    /* Utilisées avec eval */
    function makeCont() {
        var toEval = 'cont = cont || {}\n', t;
        toEval += 'for (p in cont)  if(cont[p]) this.cont[p] = cont[p]\n';
        for (var i = 0, imax = arguments.length; i < imax; i++) {
            t = arguments[i];
            toEval += 'var ' + t + ' = cont.' + t + ' || this.' + t + '()\n';
        }
        return toEval;
    }
    function manageElements() {
        return 'if(arguments[1]) { for (var i=0, imax=arguments.length; i<imax; i++) { this.add(arguments[i]) } } else if(!elements.length) elements = [elements]';
    }
    function treeCheck(e) {
// vérifie un élément avant qu'il ne soit candidat à l'ajout
        var code = ''
        code += 'var skip = false\n'
        code += 'if(!e) skip = true\n'
        code += 'else {'
        code += 'if(typeof e == "string") { this.ro(e); skip = true }\n'
        code += 'else this.ro(false)\n'
        code += 'if(!e.c) skip = true\n'
        if(e && e.fct) {
            var fct = e.fct()
            if(fct)
                code += 'if(this.e.' + fct + ' != undefined) { if(this.e.' + fct + '.isArray) this.e.' + fct + '.push(e); else this.e.' + fct + ' = e; skip = true }\n'
        }
        code += '}'
        return code
    }
    
    /* Avertissement vers console */

    function warn(message) {
        var err = new Error()
        err.name = 'JSreal'
        err.message = message
        throw(err);
        console.log(err);
    }

    /* Éléments */

    JSrealE = function (key, c) {
// élément de base à partir duquel les N, V et al. sont créés
        if(key != undefined)
            this.key = key + '' // lemme (clé du lexique)
        else
            this.key = false
        this.prop = {} // ensemble de propriétés explicites
        this.prop.c = c
        this.constructor = c
        this.cont = {} // ensemble de propriétés contextuelles
        if(isNumeric(key))
            this.prop.num = true
        //if(config.num == 'l') alert(this.key+': '+(this.prop.num == true))
    }

    JSrealE.prototype.clone = function () {
        var e, c = this.constructor || 'E'
        if(c == 'DT') {
            e = DT()
        } else if(this.isWord()) {
            eval('e = ' + c + '("' + this.key + '")')
        } else {
            eval('e = ' + c + '()')
            e.elements = []
            for (var i = 0, imax = this.elements.length; i < imax; i++) {
                e.elements.push(this.elements[i])
            }
        }
        for (var p in this.prop)
            e.prop[p] = this.prop[p]
        return e
    }

    JSrealE.prototype.getKey = function () {
        if(this.key)
            return this.key
        var subKey
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            subKey = this.elements[i].getKey()
            if(subKey)
                return subKey
        }
    }

    JSrealE.prototype.getP = function (p, skip_pe) {
        if(p == 'pe' && !skip_pe) {
            var pe = this.getP('pe', true)
            if(pe && pe < 4)
                pe += this.n() == 'p' ? 3 : 0
            return pe
        }
        if(this.prop.num) {
            if(p == 'n' && parseFloat(Math.abs(this.key)) >= 2)
                return true
            else if(p == 'pos' && this.num() == 'o')
                return 'pre'
        }
        if(this.prop[p] != undefined) // propriété spécifiée manuellement
            return this.prop[p]
        if(this.cont[p] != undefined) // propriété contextuelle
            return this.cont[p]
        if(this.isWord()) { // mot isolé: vérifie le lexique
            var e = this.lex()
            if(e && e[p])
                return e[p]
            if(p == 'pe' && ['N', 'NP', 'Adv'].has(this.c())) // personne
                return 3
        }
        return '' // pas d'autre source, alors rien de retournable
    }
    JSrealE.prototype.setP = function (p, val) {
        if(p == 'sub')
            return SP(C(val), this)
        if(p == 'p')
            return PP(P(val), this)
        this.prop[p] = val
        if(p == 'pe' && !this.key && this.c() == 'Pro') {
            var i = 0
            while (val != equivPro[i].pe)
                i++
            this.key = equivPro[i].subj
        }
        return this
    }
    JSrealE.prototype.delP = function (p, val) {
        delete this.prop[p]
        return this
    }

// Affichage d'un élément et de ses propriétés, pour test'
    JSrealE.prototype.propPr = function () {
        pr('&nbsp;' + this.key)
        var propPr = ''
        for (p in this.prop) {
            propPr += ', ' + p + ':' + this.prop[p]
        }
        if(propPr)
            pr('&nbsp;&nbsp;prop = { ' + propPr.substring(2) + ' }')
        var contPr = ''
        for (p in this.cont) {
            contPr += ', ' + p + ':' + this.cont[p]
        }
        if(contPr)
            pr('&nbsp;&nbsp;cont = { ' + contPr.substring(2) + ' }')
    }


// Ajout d'éléments à la liste actuelle
    JSrealE.prototype.add = function (elements) {
        if(!this.e) {
            var e
            eval('e = ' + this.c().replace('Pro', 'N') + 'P(this).add(arguments)')
            return e
        }
        if(arguments[1]) {
            for (var i = 0, imax = arguments.length; i < imax; i++) {
                this.add(arguments[i])
            }
        } else if(!elements.length)
            elements = [elements]
        this.elements = this.elements || []
        for (var i = 0, imax = elements.length; i < imax; i++) {
            this.elements.push(elements[i])
        }
        return this
    }
    JSrealE.prototype.addIf = function (condition, elements) {
        if(condition)
            return this.add(elements)
        else
            return this
    }


    var fctList = ['Head', 'Subj', 'Det', 'Mod', 'Sub', 'Comp', 'DirO', 'IndO', 'Post', 'Coo', 'Elem']
    var methodList = ['add']
    for (var i = 0, imax = fctList.length; i < imax; i++) {
        var r = fctList[i]
        methodList.push('add' + r)
        JSrealE.prototype['add' + r] = (function (r) {
            return function (e) {
                e.fct(r.toLowerCase())
                this.add(e)
                return this
            }
        })(r)
    }




    /* Liste de propriétés */
    var traitList = ['b', 'c', 'g', 'n', 'p', 'f', 'l', 'h', 'd', 'r', 'og', 'nc', 
        'rc', 'df', 'ne', 'pe', 'po', 'pa', 'pt', 'fs', 'fp', 'co', 'p1', 'p2', 
        'p3', 'p4', 'p5', 'p6', 'ip2', 'ip4', 'ip5', 'fr', 'ir', 'pp', 'pf', 'pr', 
        's1', 's2', 's3', 's4', 's5', 's6', 'ps1', 'ps2', 'ps3', 'ps4', 'ps5', 
        'ps6', 'cp', 've', 'ae', 'cr', 'ps', 'i', 'prp', 'pos', 't', 'sub', 'y', 
        'm', 'd', 'h', 'min', 's', 'only', 'noDay', 'noDate', 'noMonth', 'noYear', 
        'noDet', 'a', 'cap', 'ro', 'num', 'fct', 'pro', 'imp', 'ns', 'ell', 'en', 
        'a2', 'cs', 'nel', 'ppi', 'ppg', 'ppn'];
    var attrList = ['tag', 'id', 'class', 'title', 'style', 'onclick', 'href', 'src', 'alt'];
    traitList = traitList.concat(attrList)

// chaque propriété devient une méthode de JSrealE
    for (var i = 0, imax = traitList.length; i < imax; i++) {
        var p = traitList[i]
        JSrealE.prototype[p] = (function (p) {
            return function (val) {
                if(val === undefined)
                    return this.getP(p)
                if(val === null)
                    return this.delP(p)
                else
                    return this.setP(p, val)
            }
        })(p)
    }
    JSrealE.prototype.d = function (val) {
        if(val === undefined)
            return this.getP('d')
        if(val === null)
            return this.delP('d')
        if(['N', 'A'].has(this.c()))
            return NP(this).d(val)
        this.prop.d = val
        return this
    }

    JSrealE.prototype.href = function (val) {
        if(val === undefined)
            return this.getP('href')
        if(val === null)
            return this.delP('href')
        else
            return this.tag('a').setP('href', val)
    }

    JSrealE.prototype.getEn = function (after) {
        var en = this.getP('en')
        if(en == '"' && config.guilFR) {
            if(after)
                return '&#8239;&raquo;'
            else
                return '&laquo;&#8239;'
        } else if(['(', ')'].has(en)) {
            if(after)
                return ')'
            else
                return '('
        } else
            return en
    }

    JSrealE.prototype.isWord = function () {
// vérifie si un élément est un mot unique
        return this.e ? false : true
    }

    JSrealE.prototype.isInfinitive = function () {
// vérifie si un verbe ou groupe-verbe sera à l'infinitif
        if(this.f() == 'npr')
            return true
        if(this.f() || this.pe())
            return false
        return true
    }

    JSrealE.prototype.lex = function () {
// renvoie l'entrée lexicale correspondante
        return lex.get(this.key, this.prop)
    }

// Flexion des noms, adjectifs et déterminants
    JSrealE.prototype.flex = function (cont) {
        eval(makeCont('c', 'g', 'n', 'num', 'i'))
        //alert(this.key+': '+(this.prop.num == true)+' || '+(this.key === '0'))
        if(this.prop.num || this.key === '0')
            return this.flexNum()
        var thisLex = this.lex() || {}
        if((!g && !n) || i) // aucun accord demandé (ou mot invariable)
            return this.key
        switch (c) {
            case 'Pro':
            case 'N':
                // genre opposé dans lexique?
                if((g == 'm' && thisLex.g == 'f') ||
                        (g == 'f' && thisLex.g == 'm')) {
                    if(thisLex.og) {
                        this.key = thisLex.og;
                        return this.flex(cont);
                    }
                }
                // pluriel dans lexique?
                if(n == 'p' && thisLex.p)
                    return thisLex.p;
                // sinon, passe à l'accord sans lexique
                break
            case 'D':
                // juste un cas particulier
                if(g == 'f' && n == 'p')
                    if(!thisLex.fp && thisLex.p)
                        return thisLex.p
                // pas de break; la suite est comme pour les adjectifs
            case 'A': // adjectifs et déterminants
                if(g == 'f') {
                    if(n == 'p') {
                        if(thisLex.fp)
                            return thisLex.fp
                    } else {
                        if(thisLex.fs)
                            return thisLex.fs
                        if(thisLex.f)
                            return thisLex.f
                    }
                } else {
                    if(n == 'p') {
                        if(thisLex.p)
                            return thisLex.p
                    } else
                        return this.key
                }
                break
        } // switch (c)
        // pas encore trouvé? accord par règles
        var w = this.key // mot final
        if(g == 'f' && thisLex.g != 'f') {
            if(c == 'N') // féminin des noms
                w = w.endReplace(flexEnds.nf)
            else { // autres féminins
                if(c == 'A' && w.end('eur')) { // adjectifs en "eur"
                    if(lex.get(w.bs(3) + 'er', {c: "V"}).found ||
                            lex.get(w.bs(3) + 'ir', {c: "V"}).found) {
                        w = w.bs(3) + 'euse'
                    }
                }
                if(c != 'Pro')
                    w = w.endReplace(flexEnds.f)
            }
        }
        if(n == 'p') // pluriels
            // juste si pas déjà pluriel
            if(thisLex.n != 'p')
                w = w.endReplace(flexEnds.p)
        return w
    } // JSrealE.flex

    var flexEnds = {
        nf: [
            'p > ve',
            'f > ve',
            'ier > ière',
            'er > ière',
            'n > nne',
            't > tte',
            'el > elle',
            'eau > elle',
            'x > se',
            'ateur > atrice',
            'eur > euse',
            'e > e',
            '_ > e'
        ],
        f: [
            'e > e',
            'el > elle',
            'eil > eille',
            'en > enne',
            'on > onne',
            'et > ette',
            'x > se',
            'er > ère',
            'eau > elle',
            'gu > guë',
            'g > gue',
            'teur > trice',
            'if > ive',
            'c > que',
            '_ > e'
        ],
        p: [
            'au > aux',
            'eu > eux',
            'al > aux',
            's > s',
            'x > x',
            'z > z',
            '_ > s'
        ]
    }

// Conjugaison de verbes
    JSrealE.prototype.conjug = function (cont) {
        eval(makeCont('f', 'pe', 'n'))
        var thisLex = this.lex();
        if(!f)
            f = pe ? 'p' : 'npr' // aucune forme demandée
        if(f == 'npr')
            return this.key // infinitif
        if(!pe)
            pe = (f == 'ip') ? 2 : 3
        // on modifie pe pour 1 à 6
        if(n == 'p' && pe < 4)
            pe += 3
        // si forme dans lexique, renvoyée tout de suite
        if(f != 'pp' && thisLex[f + pe])
            return thisLex[f + pe]
        // sinon
        w = this.key;
        if(compoList.has(f))
        {
            var aux = this.ae() ? 'être' : 'avoir'
            var newHead = V(aux).f(compoAuxF[f])
            var newComp = V(this.key).f('pp')
            var g = this.g()
            var ppg, ppn
            if(!this.ppi())
                if(!this.ae()) {
                    ppg = this.ppg()
                    if(ppg)
                        newComp.g(ppg)
                    ppn = this.ppn()
                    if(ppn)
                        newComp.n(ppn)
                } else {
                    newComp.g(g)
                    if(this.ppg())
                        newComp.g(this.ppg())
                    newComp.n(n)
                }
            return VP(newHead.n(n).pe(pe), newComp).real();
        }
        else if(f == 'pp')
        { // participe passé
            var pp
            if(thisLex.pp)
                pp = thisLex.pp
            else if(w.end('er'))
                pp = w.bs(2) + 'é'
            else if(w.end('enir'))
                pp = w.bs(2) + 'u'
            else if(w.end('r') && !w.end('oir'))
                pp = w.bs(1)
            if(pp) {
                var e = A(pp)
                for (p in this.prop)
                    e.prop[p] = this.prop[p]
                for (p in this.cont)
                    e.cont[p] = this.cont[p]
                return e.flex()
            }
        }
        
        // irregulars
        if(f == 'i' && thisLex.ir)
            return joinRadDes(thisLex.ir, conjugDes.er.i[pe - 1])
        if(f == 'f' && thisLex.fr)
            return joinRadDes(thisLex.fr, conjugDes.er.f[pe - 1])
        if(f == 'c' && thisLex.fr)
            return joinRadDes(thisLex.fr, conjugDes.er.i[pe - 1])
        if(f == 'pr' && thisLex.pr)
        {
            return thisLex.pr;
        }
        else if(f == 'pr')
        {
            var w2 = V(w).f('p').pe(+4).toString();
            return joinRadDes(w2.bs(3), 'ant');
        }
        // Paul : 2015.03.11 : Verbes reguliers traites en dernier
        if(w.end('er'))
        { // premier groupe
            if(f != 'f' && f != 'c')
                w = w.bs(2); // trouve radical
            
            if(f == 'c')
            {
                return joinRadDes(w, conjugDes.er.i[pe - 1]);
            }
            else if(conjugDes.er[f])
            {
                if(typeof conjugDes.er[f] === 'string')
                {
                    return joinRadDes(w, conjugDes.er[f]);
                }
                else
                {
                    return joinRadDes(w, conjugDes.er[f][pe - 1]);
                }
            }
        }
        else if(w.end('oir'))
        { // deuxieme groupe
            if(f != 'f' && f != 'c')
                w = w.bs(3); // trouve radical
            if(f == 'c')
            {
                return joinRadDes(w, conjugDes.ir.i[pe - 1]);
            }
            else if(conjugDes.er[f])
            {
                if(typeof conjugDes.er[f] === 'string')
                {
                    return joinRadDes(w, conjugDes.ir[f]);
                }
                else
                {
                    return joinRadDes(w, conjugDes.ir[f][pe - 1]);
                }
            }
        }
        else if(w.end('ir'))
        { // deuxieme groupe
            if(f != 'f' && f != 'c')
                w = w.bs(2); // trouve radical
            if(f == 'c')
            {
                return joinRadDes(w, conjugDes.ir.i[pe - 1]);
            }
            else if(conjugDes.er[f])
            {
                if(typeof conjugDes.er[f] === 'string')
                {
                    return joinRadDes(w, conjugDes.ir[f]);
                }
                else
                {
                    return joinRadDes(w, conjugDes.ir[f][pe - 1]);
                }
            }
        }
        
        return '[' + w + ']'; // default
    }; // JSrealE.conjug

    var conjugDes = {
        er: {
            p: ['e', 'es', 'e', 'ons', 'ez', 'ent'], // present
            f: ['ai', 'as', 'a', 'ons', 'ez', 'ont'], // futur simple
            i: ['ais', 'ais', 'ait', 'ions', 'iez', 'aient'], // imparfait
            ps: ['ai', 'as', 'a', 'âmes', 'âtes', 'èrent'], // passe simple
            s: ['e', 'es', 'e', 'ions', 'iez', 'ent'], // subjonctif present
            ip: ['', 'e', '', 'ons', 'ez', ''], // imperatif present
            pr: 'ant', //participe present
            pp: 'é' // participe passe
        },
        ir: {
            p: ['is', 'is', 'it', 'issons', 'issez', 'issent'], // present
            f: ['ai', 'as', 'a', 'ons', 'ez', 'ont'], // futur simple
            i: ['issais', 'issais', 'issait', 'issions', 'issiez', 'issaient'], // imparfait
            ps: ['is', 'is', 'it', 'îmes', 'îtes', 'irent'], // passe simple
            s: ['isse', 'isses', 'isse', 'issions', 'issiez', 'issent'], // subjonctif present
            ip: ['', 'is', '', 'issons', 'issez', ''], // imperatif present
            pr: 'ant', //participe present
            pp: 'i' // participe passe
        }
    };

    function joinRadDes(rad, des) {
        if(rad.end('c') && des.beg(['a', 'â', 'o']))
            return rad.bs(1) + 'ç' + des;
        if(rad.end('g') && des.beg(['a', 'â', 'o']))
            return rad + 'e' + des;
        return rad + des;
    }

    JSrealE.prototype.flexNum = function () {
        var num = this.prop.num
        if(typeof num != 'string' || (!num && num !== undefined))
            num = this.cont.num || config.num || ''
        var n = this.key
        if(num.has('l')) { // en toutes lettres
            return flexNumLet(n, this.g(), num.has('n'))
        }
        if(num.has('o') && this.c() == 'A') { // ordinal en lettres
            return flexNumOrd(flexNumLet(n, '', num.has('n')), this.g(), this.n())
        }
        if(num == 'd') { // chiffres décimaux
            var nat = splitThou(natPart(n)).join(' ')
            var dec = splitThou(decPart(n), true).join(' ')
            return dec ? nat + ',' + dec : nat
        }
        if(num == 't') { // téléphone
            var len = n.length
            if([7, 10, 11].has(len))
                n = n.insertAt(-4, '-')
            if([10, 11].has(len))
                n = n.insertAt(-8, '-')
            if([11].has(len))
                n = n.insertAt(-12, '-')
            return n
        }
        return n
    }
    function natPart(n) {
        if(!n)
            return false
        var i = n.indexOf('.')
        return i > -1 ? n.slice(0, i) : n
    }
    function decPart(n) {
        if(!n)
            return false
        var i = n.indexOf('.')
        return i > -1 ? n.slice(i + 1) : false
    }
    function splitThou(n, dec) {
        var s = []
        if(!n)
            return s
        var i = dec ? 3 : (n.length - 1) % 3 + 1
        s.push(n.slice(0, i))
        n = n.slice(i)
        while (n) {
            s.push(n.slice(0, 3))
            n = n.slice(3)
        }
        return s
    }
    function flexNumLet(n, g, newO) {
        var nat = splitThou(natPart(n))
        var p = 0, cur, r = '', s, one
        while (nat.length) {
            cur = nat.pop()
            if(cur == '000') {
                p++;
                continue
            }
            if(r)
                r = ' ' + r
            one = ['001', '01', '1'].has(cur)
            s = (one || p == 1) ? '' : 's'
            if(p) {
                r = thouPower[p] + s + r
                if(!one || p != 1)
                    r = ' ' + r
            }
            if(!one || p != 1)
                r = subThouLet(cur) + r
            p++
        }
        var dec = decPart(n)
        if(newO)
            r = r.replace(/\s/g, '-')
        if(dec.length)
            if(dec - 0) {
                r += ' et ' + flexNumLet(dec, '', newO)
                        + ' ' + tensPowerDec[dec.length - 1]
                if((dec - 0) != 1)
                    r += 's'
            }
        if(r == 'un' && g == 'f')
            return 'une'
        if(r == 'zéro')
            if(g == 'f')
                return 'aucune'
            else
                return 'aucun'
        return r
    }
    var tensPowerDec = ['dixième', 'centième', 'millième', 'dix-millième', 'cent-millième', 'millionnième', 'dix-millionnième', 'cent-millionnième', 'milliardième', 'dix-milliardième', 'cent-milliardième']
    var thouPower = ['', 'mille', 'million', 'milliard', 'billion', 'billiard', 'trillion', 'trilliard', 'quadrillion', 'quadrilliard']
    function subThouLet(n, s100omit) {
        if(n.length == 1)
            return sub17Let(n)
        if(n.length == 2)
            return subHunLet(n)
        var s100 = true ? '' : 's'
        var h = n[0], du = n.slice(1), r
        if(h == '0')
            return subHunLet(n.slice(1))
        if(h == '1')
            r = 'cent'
        else {
            r = sub17Let(h) + ' cent' + (du == '00' ? s100 : '')
        }
        if(du != '00')
            r += ' ' + subHunLet(du)
        return r
    }
    var onesList = ['zéro', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize']
    function sub17Let(n) {
        return onesList[n - 0]
    }
    var tensList = ['vingt', 'trente', 'quarante', 'cinquante', 'soixante']
    function tensLet(d) {
        return tensList[d - 2]
    }
    function subHunLet(n, s80omit) {
        var u = n[1], d = n[0], r
        var s80 = true ? '' : 's' // si déterminant?
        if(['0', '1'].has(d))
            if(d == '0' || u < '7')
                return sub17Let(n)
            else
                return 'dix-' + sub17Let(u)
        if(['2', '3', '4', '5', '6'].has(d)) {
            r = tensLet(d)
            r += u == '0' ? '' : u == '1' ? ' et un' : '-' + sub17Let(u)
            return r
        }
        if(d == '7')
            return 'soixante-' + (u == '1' ? 'et-onze' : subHunLet('1' + u))
        if(d == '8') {
            return 'quatre-vingt' + (u == '0' ? s80 : '-' + sub17Let(u))
        }
        if(d == '9')
            return 'quatre-vingt-' + subHunLet('1' + u)
    }
    function flexNumOrd(r, g, n) {
        if(r == 'un')
            return A('premier').g(g).n(n).nel(1).realClean()
        if(r.end('e'))
            r = r.bs(1)
        else if(r.end('q'))
            r = r + 'u'
        else if(r.end('f'))
            r = r.bs(1) + 'v'
        return A(r + 'ième').n(n).nel(1).realClean()
    }



    function getAux(ae) {
        return ae ? 'être' : 'avoir'
    }

    var compoList = ['pc', 'pq', 'fa', 'cp']
    var compoAuxF = {pc: 'p', pq: 'i', fa: 'f', cp: 'c'}




// Liste de catégories
    var categoryList = ['E', 'N', 'V', 'D', 'Pro', 'A', 'Adv', 'P', 'C']
    var phraseList = ['NP', 'VP', 'PP', 'SP', 'S', 'CP', 'J', 'AP', 'DP', 'AdvP', 'DT', 'DTR']
// chaque catégorie/élément devient un constructeur basé sur JSrealE
    for (var i = 0, imax = categoryList.length; i < imax; i++) {
        var c = categoryList[i]
        if(c != 'A' && c != 'P')
            window[c] = (function (c) {
                return function (key) {
                    return new JSrealE(key, c)
                }
            })(c)
    }
    A = function (key) {
        if(key && ['string', 'number'].has(typeof key))
            return new JSrealE(key, 'A')
        else
            return new DOM(arguments).tag('a')
    }
    P = function (key) {
        if(key && ['string', 'number'].has(typeof key))
            return new JSrealE(key, 'P')
        else
            return new DOM(arguments).tag('p')
    }


    JSrealE.prototype.flexEliLia = function (r) {
        // on élimine les cas où aucune telle flexion n'est à faire
        var a = this.a()
        if((a && a != ' ') || this.ns())
            return // suivi de ponctuation
        var nextLeaf = this.nextLeaf ? this.nextLeaf() : false
        if(!nextLeaf)
            return // pas de mot après
        var nLen = nextLeaf.en()
        if(nextLeaf.b() || (nLen && !['"', "'"].has(nLen)))
            return // caractères entre
        if(r == 'des' && this.c() == 'D') { // cas particulier: "d'autres", "d'immenses"
            if(!nextLeaf.hasVocalicInitial() || !['A', 'AP'].has(nextLeaf.c()))
                return
            this.ro("d").a("'").ns(1)
            return
        }
        var ve = this.ve(), l = this.l()
        if(!ve && !l)
            return // N/A avec ce mot
        if(ve && ['de', 'à'].has(r)) { // cas particulier
            var r2 = nextLeaf.real(false)
            nextLeaf.flexEliLia(r2)
            if(r2 == 'le' && r2 == nextLeaf.realClean()) {
                nextLeaf.ell(true)
                if(r == 'de')
                    this.ro('du')
                else
                    this.ro('au')
            }
            if(r == 'de' && r2 == 'des' && r2 == nextLeaf.realClean()) {
                nextLeaf.ell(true)
            }
        }
        if(!nextLeaf.hasVocalicInitial())
            return // pas suivi de vocalique

        if(ve) { // élision
            if(['le', 'la', 'je', 'me', 'ne', 'se', 'te', 'que', 'jusque', 'de'].has(r)
                    || (['lorsque', 'puisque'].has(r) &&
                            ['il', 'elle', 'on', 'un', 'une', 'en'].has(
                            nextLeaf.realClean()))
                    || (r == 'si' && nextLeaf.realClean() == 'il')) {
                this.ro(r.bs() + "'")
                this.ns(true)
                return
            }
            if(['ce', 'ça'].has(r)) { // 'ça' pas utilisé 
                this.ro("c'")
                this.ns(true)
                return
            }
        } else { // liaison
            if(['ma', 'ta', 'sa'].has(r)
                    || (this.g() != 'f') && (this.n() != 'p')) {
                this.ro(this.l())
            }
        }
    }

    JSrealE.prototype.hasVocalicInitial = function () {
        if(this.nel() || this.num())
            return false
        var c = this.realClean().charAt(0).toLowerCase()
        if(['a', 'e', 'i', 'o', 'u', 'y', 'à', 'â', 'é', 'è', 'ê', 'ë', 'î', 'ô'].has(c) ||
                (c == 'h' && !this.h()))
            return true
        else
            return false
    }


// Réalisation: méthode maîtresse
    JSrealE.prototype.real = function (clean, node, toProAsks) {

        // Chaîne contenant la réalisation cumulative
        var r = ''

        // Chaîne imposée
        if(this.ro())
            r = this.ro()

        // Pronominalisation
        else if(this.pro() && !toProAsks)
            r = this.toPro()

        // Mot isolé
        else if(this.isWord())
            r = this.realWord()

        // Syntagme ou élément DOM
        else {
            if(!this.children)
                this.tree()
            if(this.children) {
                for (var i = 0, imax = this.children.length; i < imax; i++) {
                    var e = this.children[i]
                    if(e.ell)
                        if(!e.ell()) {
                            r += e
                            if(i + 1 < imax && !e.ns()
                                    && (e.inPhrase() || this.tag() == 'p'))
                                r += ' '
                        } else
                            r = r.bs(1)
                }
            }
        }

        // Dans tous les cas
        if(r == '#')
            r = ''
        if(!clean) {
            var tag = this.tag()
            if(tag && domCapList.has(tag))
                this.cont.cap = true
            if(r) // alors on vérifie pour majuscule 
                if(this.cap())
                    r = r.firstUpperCase()
                else if(!this.children && this.childN === 0) {
                    var e = this.parent
                    while (e && (!e.parent || e.childN === 0)) {
                        if(e.cap()) {
                            r = r.firstUpperCase();
                            break
                        }
                        if(e.parent)
                            e = e.parent
                        else
                            break
                    }
                }
            r = this.getEn(0) + this.b() + r + this.a() + this.getEn(1)
            if(tag && !node)
                r = this.openTag() + r + this.closeTag()
            if(this.a2())
                r += this.a2()
        }
        return r
    }


    JSrealE.prototype.toString = function () {
        return this.real()
    }

    JSrealE.prototype.realClean = function (cont) {
        return this.real(true)
    }

    JSrealE.prototype.toID = function (id) {
        document.getElementById(id).innerHTML = this.real()
        return this
    }
    JSrealE.prototype.addToID = function (id) {
        document.getElementById(id).innerHTML += this.real()
        return this
    }
    JSrealE.prototype.node = function () {
        var tag = this.tag(), e
        if(tag) {
            e = document.createElement(tag)
            var i = 0, a
            if(this.prop.attr)
                while (a = this.prop.attr[i++])
                    e.setAttribute(a.attr, a.val)
            var i = 0, val
            while (a = attrList[i++]) {
                val = this[a]()
                if(val && a != 'tag')
                    e.setAttribute(a, val)
            }
            e.innerHTML = this.real(false, true)
        } else
            e = document.createTextNode(this.real(false, true))
        return e
    }

    JSrealE.prototype.realWord = function (cont, clean) {
        var r, c = this.c()
        if(['N', 'A', 'D', 'Pro'].has(c))
            r = this.flex(cont)
        else if(c == 'V')
            r = this.conjug(cont)
        else
            r = this.key
        if(!clean)
            this.flexEliLia(r)
        if(this.ro())
            r = this.ro()
        return r
    }


// Navigation de la structure en arbre
    JSrealE.prototype.tree = function (cont) {
        // à la base, ajoute le contexte à l'élément
        for (p in cont)
            this.cont[p] = cont[p]
        return this
    }
    JSrealE.prototype.addChild = function (e, cont, pass) {
        cont = cont || {}
        if(!(this instanceof DOM) || this.inPhrase())
            cont.isSub = true
        if(pass) {
            if(pass.cap)
                cont.sub_cap = true
            if(pass.a)
                cont.sub_a = true
        }
        if(e) {
            this.children = this.children || []
            if(e.real) {
                if(['CP', 'J'].has(e.constructor) && !e.hasElem())
                    return false
                e = e.clone()
                if(cont.detSpec)
                    e = e.d(cont.detSpec)
                e.childN = this.children.length
                e.parent = this
                e.tree(cont)
            } else if(typeof e == 'string') {
                e = E(e)
                e.childN = this.children.length
                e.parent = this
                e.tree(cont)
            }
            this.children.push(e)
        }
    }
    JSrealE.prototype.inPhrase = function () {
        var e = this.parent
        while (e) {
            if(!(e instanceof DOM))
                return true
            e = e.parent
        }
        return false
    }
    JSrealE.prototype.isLastChild = function () {
        var parent = this.parent
        if(parent)
            if(this.childN == parent.children.length)
                return true
        return false
    }

    JSrealE.prototype.clearChildren = function () {
        this.children = []
    }
    JSrealE.prototype.prevLeaf = function () {
        if(this.parent) {
            var children = this.parent.children
            var n = this.childN
            if(children.length > 0)
                return children[n - 1].lastLeafChild()
            else
                return this.parent.prevLeaf()
        } else
            return false
    }
    JSrealE.prototype.nextLeaf = function () {
        var parent = this.parent
        var next = false
        if(parent) {
            var children = parent.children
            var n = this.childN
            if(n + 1 < children.length) { // reste enfants après
                next = children[n + 1].firstLeafChild(r)
            } else { // dernier enfant
                next = parent.nextLeaf(r)
            }
        }
        return next
    }
    JSrealE.prototype.firstLeafChild = function () {
        if(this.children && this.children.length)
            return this.children[0].firstLeafChild(r)
        else
            return this
    }
    JSrealE.prototype.lastLeafChild = function () {
        if(this.children && this.children.length)
            return this.children[this.children.length - 1].lastLeafChild()
        else
            return this
    }




    /*************/
    /* Syntagmes */
    /*************/


// Syntagme nominal
    NP = function (elements) {
        if(!(this instanceof NP))
            return new NP(arguments)
        this.prop = {}
        this.prop.c = 'NP'
        this.constructor = 'NP'
        this.cont = {}
        this.e = {}
        this.add(elements)
    }
    NP.prototype = new JSrealE()
    NP.prototype.getP = function (p) {
        if(this.prop[p] != undefined)
            return this.prop[p]
        if(this.cont[p] != undefined)
            return this.cont[p]
        this.sort()
        if(['g', 'n', 'pe'].has(p)) {
            var val = ''
            if(this.e.head && this.e.head[p])
                val = val || this.e.head[p]()
            if(this.e.det && this.e.det[p])
                val = val || this.e.det[p]()
            if(this.e.mod.length)
                for (var i = 0, imax = this.e.mod.length; i < imax; i++) {
                    if(this.e.mod[i][p])
                        val = val || this.e.mod[i][p]()
                }
            return val;
        }
        return '';
    }
    NP.prototype.sort = function () {
        this.e.pre = false, this.e.det = false, this.e.head = false
        this.e.mod = [], this.e.comp = [], this.e.sub = []
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            eval(treeCheck(e));
            if(skip)
                continue
            var c = e.c()
            switch (c) {
                case 'V':
                    if(!this.e.head && e.isInfinitive())
                        this.e.head = e;
                    break
                case 'D':
                case 'DP':
                    if(!this.e.det) {
                        this.prop.noDetHead = true
                        this.e.det = e;
                        break
                    }
                case 'Adv':
                case 'AdvP':
                    if(!this.e.pre)
                        this.e.pre = e;
                    break
                case 'A':
                case 'AP':
                    this.e.mod.push(e);
                    break
                case 'P':
                case 'PP':
                    this.e.comp.push(e);
                    break
                case 'SP':
                    this.e.sub.push(e);
                    break
                default:
                    if(!this.e.head)
                        this.e.head = e
                    else
                        this.e.comp.push(e)
            } // switch (c)
            if(!this.e.det && this.e.head
                    && this.e.head.c() == 'NP') {
                this.e.head.sort()
                this.e.det = this.e.head.e.det
                this.d(this.e.head.d())
                this.prop.noDetHead = true
            }
        } // for e in elements
        return this
    }
    var detList = {d: 'le', i: 'un', dem: 'ce'}
    var possDetList = [false, 'mon', 'ton', 'son', 'notre', 'votre', 'leur']
    NP.prototype.tree = function (cont) {
        this.sort()
        cont = cont || {}
        this.cont.a = cont.a
        if(this.ro())
            return this
        //if(!this.e.head) warn('NP sans noyau')

        // Contexte compatible avec nom?
        var n, g
        if(cont && this.e.head) {
            var e = this.e.head
            if(e) {
                if(cont.g)
                    g = e.sameWith('g', cont.g) ? '' : cont.g
                if(cont.n)
                    n = e.sameWith('n', cont.n) ? '' : cont.n
            }
        } else {
            g = cont.g
            n = cont.n
        }
        n = n || this.n()
        g = g || this.g()
        var pe = cont.pe || this.pe()
        var noDet = cont.noDet || this.noDet()
        if(this.e.head && this.e.head.c() == 'Pro')
            noDet = true
        var adj = {pre: [], post: [], end: []}

        // Adjectifs avant et après
        for (var i = 0, imax = this.e.mod.length; i < imax; i++) {
            var e = this.e.mod[i]
            if(e.ps() || e.pos() == 'pre')
                adj.pre.push(e)
            else if(e.pos() == 'post')
                adj.post.push(e)
            else
                adj.end.push(e)
        }

        // Arbre syntaxique
        this.clearChildren()
        var i
        var num = this.num()
        if(this.e.pre)
            this.addChild(this.e.pre)
        if(!noDet) {
            var d = this.d()
            if(d) {
                if(detList[d])
                    this.e.det = D(detList[d])
                else {
                    if(!isNumeric(d) && d.charAt(0) == 'p') {
                        var detPe = d.charAt(1) || 3
                        if(detPe < 4 && d.charAt(2))
                            detPe += 3
                        this.e.det = D(possDetList[detPe])
                    }
                    else
                        this.e.det = D(d)
                }
            } else if(d != undefined && d === 0) {
                this.e.det = D(0).num(num)
            }
            var detCont = {g: g, n: n}
            if(this.e.det && this.e.det.num())
                detCont.num = num
            this.addChild(this.e.det, detCont)
            if(this.e.det && this.e.det.n())
                n = 'p'
        }
        i = 0;
        while (e = adj.pre[i++])
            this.addChild(e, {g: g, n: n})
        if(this.e.head) {
            var headCont = {}
            if(g != this.e.head.g())
                headCont.g = g
            if(n != this.e.head.n())
                headCont.n = n
            if(pe != this.e.head.pe())
                headCont.pe = pe
            if(this.prop.noDetHead)
                headCont.noDet = true
            if(this.e.head.num)
                headCont.num = num
            this.addChild(this.e.head, headCont)
        }
        i = 0;
        while (e = adj.post[i++])
            this.addChild(e, {g: g, n: n})
        i = 0;
        while (e = this.e.comp[i++])
            this.addChild(e)
        i = 0;
        while (e = adj.end[i++])
            this.addChild(e, {g: g, n: n})
        i = 0;
        while (e = this.e.sub[i++])
            this.addChild(e, {sub_g: g, sub_n: n, sub_pe: this.pe()})
    } // NP.tree
    JSrealE.prototype.sameWith = function (pName, pValue) {
        if(pName == 'g') {
            var thisLex = this.lex()
            if(pValue == 'f')
                if(thisLex.g == 'm')
                    if(thisLex.fs || thisLex.og)
                        return false
                    else
                        return true
            if(pValue == 'm')
                if(thisLex.g == 'f')
                    if(thisLex.og)
                        return false
                    else
                        return true
        }
        this.cont = {}
        var r1 = this.realClean()
        this.cont[pName] = pValue
        var r2 = this.realClean()
        this.cont = {}
        return r1 == r2
    }


// Syntagme prépositionnel
    PP = function (elements) {
        if(!(this instanceof PP))
            return new PP(arguments)
        this.prop = {}
        this.prop.c = 'PP'
        this.constructor = 'PP'
        this.cont = {}
        this.e = {}
        this.add(elements)
    }
    PP.prototype = new JSrealE()
    PP.prototype.sort = function () {
        this.e.head = false, this.e.comp = false
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            eval(treeCheck(e));
            if(skip)
                continue
            var c = e.c()
            switch (c) {
                case 'E':
                case 'P':
                    if(!this.e.head)
                        this.e.head = e;
                    break
                case 'V':
                case 'VP':
                    if(!this.e.comp && e.isInfinitive())
                        this.e.comp = e;
                    break
                default:
                    if(!this.e.comp)
                        this.e.comp = e
            }
        } // for e in elements
    }
    PP.prototype.tree = function (cont) {
        this.sort()
        eval(makeCont())
        if(this.ro())
            return this
        if(!this.e.head || !this.e.comp)
            warn('PP requiert préposition et complément')

        // Arbre syntaxique
        this.clearChildren()
        this.addChild(this.e.head)
        this.addChild(this.e.comp)
    }


// Syntagme adjectival
    AP = function (elements) {
        if(!(this instanceof AP))
            return new AP(arguments)
        this.prop = {}
        this.prop.c = 'AP'
        this.constructor = 'AP'
        this.cont = {}
        this.e = {}
        this.add(elements)
    }
    AP.prototype = new JSrealE()
    AP.prototype.getP = function (p) {
        if(this.prop[p] != undefined)
            return this.prop[p]
        if(this.cont[p] != undefined)
            return this.cont[p]
        this.sort()
        if(['g', 'n', 'pos'].has(p) && this.e.head && this.e.head[p]())
            return this.e.head[p]()
        return ''
    }
    AP.prototype.sort = function () {
        this.e.mod = [], this.e.head = false, this.e.comp = [], this.e.sub = []
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            eval(treeCheck(e));
            if(skip)
                continue
            var c = e.c()
            switch (c) {
                case 'Adv':
                case 'AdvP':
                    this.e.mod.push(e);
                    break
                case 'P':
                case 'PP':
                    this.e.comp.push(e);
                    break
                case 'SP':
                    this.e.sub.push(e);
                    break
                default:
                    if(!this.e.head)
                        this.e.head = e
            }
        } // for e in elements
    }
    AP.prototype.tree = function (cont) {
        this.sort()
        eval(makeCont())
        if(this.ro())
            return this
        if(!this.e.head)
            warn('AP requiert adjectif')

        // Arbre syntaxique
        this.clearChildren()
        for (var i = 0, imax = this.e.mod.length; i < imax; i++) {
            this.addChild(this.e.mod[i])
        }
        this.addChild(this.e.head, cont)
        for (var i = 0, imax = this.e.comp.length; i < imax; i++) {
            this.addChild(this.e.comp[i])
        }
        for (var i = 0, imax = this.e.sub.length; i < imax; i++) {
            this.addChild(this.e.sub[i])
        }
    }


// Syntagme verbal
    VP = function (elements) {
        if(!(this instanceof VP))
            return new VP(arguments)
        this.prop = {}
        this.prop.c = 'VP'
        this.constructor = 'VP'
        this.cont = {}
        this.e = {}
        this.add(elements)
    }
    VP.prototype = new JSrealE()
    VP.prototype.getP = function (p) {
        if(this.prop[p] != undefined)
            return this.prop[p]
        if(this.cont[p] != undefined)
            return this.cont[p]
        if(['f', 'cp', 'pe'].has(p) && this.e.head && !this.imp())
            if(this.e.head[p])
                return this.e.head[p]()
        return ''
    }
    VP.prototype.sort = function () {
        this.e.head = false, this.e.post = []
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            eval(treeCheck(e));
            if(skip)
                continue
            if(e.c() == 'V' && !this.e.head)
                this.e.head = e
            else
                this.e.post.push(e)
        } // for e in elements
    }
    VP.prototype.tree = function (cont) {
        this.sort()
        if(this.ro())
            return this
        eval(makeCont('f', 'pe', 'g', 'n'))
        var head = this.e.head
        if(!head) { // pas de tête? VP défectueux
            warn('VP requiert verbe');
            this.ro('[VP]');
            return
        }

        // Pour formes composées
        var cp = head.cp() || false // verbe d'abord noté copule
        var head_imp = head.imp() // pareil pour impersonnel

        // Répartition des compléments et attributs
        // On le fait ici (au lieu de dans .add) car dépend du verbe
        var mod = [], attr = [], diro = [], indo = []
        var pre = {diro: [], indo: []}, ip = {diro: [], indo: []}
        var cPro
        var i = 0, e, c, fct, a, a2, ns

        while (e = this.e.post[i++]) {
            fct = e.fct() || e.realClean() == 'en' ? 'indo' : false
            c = !fct ? e.c() : undefined
            if(['A', 'AP'].has(c) || (c == 'V' && e.f() == 'pp')) {
                attr.push(e)
            } else if(['Adv', 'AdvP'].has(c)) {
                mod.push(e)
            } else if(['V', 'VP', 'Pro', 'N', 'NP'].has(c) || fct == 'diro') {
                if(cp && ['N', 'NP'].has(c) && fct != 'diro')
                    attr.push(e) // verbe copule, requiert attribut
                else {
                    cPro = e.cPro('diro', (f == 'ip')) // pronom modifié, comme "elle" > "la"
                    if(cPro) {
                        if(f == 'ip')
                            ip.diro.push(cPro.ve(false)) // impératif
                        else
                            pre.diro.push(cPro) // avant le verbe, comme "je le porte"
                    } else
                        diro.push(e) // normal
                }
            } else if(['P', 'PP'].has(c) || fct == 'indo') {
                if(f == 'ip') {
                    cPro = e.cPro('indo', true) // true: spécial impératif
                    if(cPro)
                        ip.indo.push(cPro)
                    else
                        indo.push(e)
                } else {
                    if(true)
                        cPro = e.cPro('indo')
                    else
                        cPro = false
                    if(cPro)
                        pre.indo.push(cPro)
                    else
                        indo.push(e)
                }
            } else if(c == 'SP')
                diro.push(e)
        } // for (e in elements)

        // Pour arbre syntaxique
        this.clearChildren()
        var cont_attr = (cp && !head_imp) ? {g: g, n: n} : {}
        if(pre.diro.length) {
            cont_attr.g = cont_attr.g || pre.diro[0].g()
            cont_attr.n = cont_attr.n || pre.diro[0].n()
        } else if(cont.sub_que) {
            cont_attr.g = cont.sub_g
            cont_attr.n = cont.sub_n
        }

        // Formes composées
        if(compoList.has(f)) {
            var sub_que = cont.sub_que
            var ae = head.ae()
            var aux = ae ? 'être' : 'avoir'
            var newHead = VP(V(aux).f(compoAuxF[f]))
            for (p in head.prop)
                if(!['c', 'f'].has(p))
                    newHead.prop[p] = head.prop[p]
            for (p in head.cont)
                if(!['c', 'f'].has(p))
                    newHead.cont[p] = head.cont[p]
            var newComp = V(head.key).f('pp')
            var hasPPcomp = false
            var i = 0, e, c
            while (e = this.e.post[i++]) {
                if(e.c() == 'PP') {
                    e.sort()
                    var headCheck = e.e.head && e.e.head.key == 'de'
                    var compCheck = e.e.comp
                            && ['V', 'VP'].has(e.e.comp.c())
                            && (!e.e.comp.f() || e.e.comp.f() == 'npr')
                    if(headCheck && compCheck)
                        hasPPcomp = true
                }
            }
            head.ppg(cont_attr.g)
            head.ppn(cont_attr.n)
            if(sub_que && !ae && !hasPPcomp) {
                //head.g(cont.sub_g)
                //head.n(cont.sub_n)
            } else if(hasPPcomp)
                head.ppi(1)
            else if(newComp.key == 'être')
                head.ppi(1)
            //this.e.post.unshift(newComp)
            //head = newHead
            /*if(sub_que && !ae && !hasPPcomp) {
             newComp.g(cont.sub_g)
             newComp.n(cont.sub_n)
             } else if(hasPPcomp) newComp.i(1)
             else if(newComp.key == 'être') newComp.i(1)
             this.e.post.unshift(newComp)
             head = newHead*/
        }

        // Arbre syntaxique
        // (pre.diro pre.indo) head (ip.diro ip.indo) mod attr diro indo
        i = 0;
        while (e = pre.diro[i++])
            this.addChild(e)
        i = 0;
        while (e = pre.indo[i++])
            this.addChild(e)
        a2 = (ip.diro.length || ip.indo.length) ? '-' : ''
        ns = a2 ? true : false
        this.addChild(head, {f: f, pe: pe, a2: a2, ns: ns, g: g, n: n})
        i = 0;
        while (e = ip.diro[i++]) {
            a2 = (ip.diro[i] || ip.indo.length) ? '-' : ''
            ns = a2 ? true : false
            this.addChild(e, {g: g, n: n, a2: a2, ns: ns})
        }
        i = 0;
        while (e = ip.indo[i++]) {
            a2 = ip.indo[i] ? '-' : ''
            ns = a2 ? true : false
            this.addChild(e, {g: g, n: n, a2: a2, ns: ns})
        }

        // pos: post
        i = 0;
        while (e = mod[i++])
            if(e.pos() == 'post')
                this.addChild(e)
        i = 0;
        while (e = attr[i++])
            if(e.pos() == 'post')
                this.addChild(e, cont_attr)
        i = 0;
        while (e = diro[i++])
            if(e.pos() == 'post')
                this.addChild(e)
        i = 0;
        while (e = indo[i++])
            if(e.pos() == 'post')
                this.addChild(e)

        // pos: undefined
        i = 0;
        while (e = mod[i++])
            if(!['post', 'end'].has(e.pos()))
                this.addChild(e)
        i = 0;
        while (e = attr[i++])
            if(!['post', 'end'].has(e.pos()))
                this.addChild(e, cont_attr)
        i = 0;
        while (e = diro[i++])
            if(!['post', 'end'].has(e.pos()))
                this.addChild(e)
        i = 0;
        while (e = indo[i++])
            if(!['post', 'end'].has(e.pos()))
                this.addChild(e)

        // pos: end
        i = 0;
        while (e = mod[i++])
            if(e.pos() == 'end')
                this.addChild(e)
        i = 0;
        while (e = attr[i++])
            if(e.pos() == 'end')
                this.addChild(e, cont_attr)
        i = 0;
        while (e = diro[i++])
            if(e.pos() == 'end')
                this.addChild(e)
        i = 0;
        while (e = indo[i++])
            if(e.pos() == 'end')
                this.addChild(e)
    }

    var equivPro = [
        {pe: 1, subj: 'je', ref: 'me', diro: 'me', indo: 'me', disj: 'moi'},
        {pe: 2, subj: 'tu', ref: 'te', diro: 'te', indo: 'te', disj: 'toi'},
        {pe: 3, subj: 'il', ref: 'se', diro: 'le', indo: 'lui', disj: 'lui'},
        {pe: 3, subj: 'elle', ref: 'se', diro: 'la', indo: 'lui', disj: 'elle'},
        {pe: 4, subj: 'nous', ref: 'nous', diro: 'nous', indo: 'nous', disj: 'nous'},
        {pe: 5, subj: 'vous', ref: 'vous', diro: 'vous', indo: 'vous', disj: 'vous'},
        {pe: 6, subj: 'ils', ref: 'se', diro: 'les', indo: 'leur', disj: 'eux'},
        {pe: 6, subj: 'elles', ref: 'se', diro: 'les', indo: 'leur', disj: 'elles'}
    ] // attention: possibles 'en' comme diro et 'y' comme indo
    var equivProFct = ['subj', 'ref', 'diro', 'indo', 'disj']
    JSrealE.prototype.get_equivPro = function (r, pe, fct) {
        r = r || this.real(1, 0, 1)
        var num = 0
        pe = pe || this.pe()
        var n = this.n()
        if(pe < 4 && n == 'p')
            pe += 3
        if(!pe || [3, 6].has(pe)) {
            r = this.g() == 'f' ? 'elle' : 'il'
            if(n == 'p')
                r += 's'
        }
        for (var i = 0, imax = equivPro.length; i < imax; i++) {
            var l = equivPro[i]
            if(pe == l.pe)
                for (var j = 0, jmax = equivProFct.length; j < jmax; j++) {
                    if(r == l[equivProFct[j]])
                        return l
                }
        }
        for (var i = 0, imax = equivPro.length; i < imax; i++) {
            var l = equivPro[i]
            if(pe == l.pe)
                return l
        }
        return false
    }
    JSrealE.prototype.proCP = function () {
        var num = 0, l, r = this.realClean()
        while (l = equivPro[num++])
            if(r == l.subj)
                return Pro(equivPro[num - 1].disj)
        return this
    }
    JSrealE.prototype.cPro = function (fct, ip) {
// change pronom selon son emplacement
// "je donne à elle" -> "je lui donne"
// "je donne elle" -> "je la donne"
// "vous me donnez", "donnez-moi"
        var c = this.c()

        // Syntagmes
        if(['PP', 'NP', 'CP', 'J'].has(this.constructor)) {
            this.tree()
            if(c == 'PP') {
                if(this.e.comp && this.e.comp.c() == 'Pro'
                        && this.e.head && this.e.head.key == 'à')
                    return this.e.comp.cPro(fct, ip)
                else
                    return false
            } else if(c == 'NP') {
                if(this.e.head && this.e.head.c() == 'Pro')
                    return this.e.head.cPro(fct, ip)
            } else if(this.children.length == 1)
                return this.children[0].cPro('subj')
        }

        // Pronom
        var makePro = this.pro()
        if(c == 'Pro' || makePro) {
            var r = this.real(1, 0, 1), pe = this.pe()
            if(fct == 'diro' && r == 'y')
                return this
            if(fct == 'indo' && r == 'en')
                return this
            if(this.pt() == 'p' || makePro) {
                var ePro = this.get_equivPro(r, pe, fct)
                if(ePro) {
                    if(ip && [1, 2].has(pe)) {
                        this.key = ePro.disj
                        return this
                    } else {
                        var newPro = Pro(ePro[fct]).g(this.g())
                        if(this.c() != 'NP')
                            for (var i = 0, imax = traitList.length; i < imax; i++) {
                                var p = traitList[i]
                                var this_p = this[p]()
                                if(this_p && p != 's')
                                    newPro[p](this_p)
                            }
                        return newPro
                    }
                }
            }
        }
        return false
    }
    JSrealE.prototype.toPro = function (fct) {
        fct = fct || this.fct() || 'disj'
        var ePro = this.get_equivPro(fct)
        if(ePro) {
            var pe = this.pe()
            if(this.f() == 'ip' && [1, 2].has(pe))
                return Pro(ePro.disj).real(1, 0, 1)
            else
                return Pro(ePro[fct]).g(this.g()).real(1, 0, 1)
        }
        return '#'
    }


// Proposition/phrase (clause/sentence)
    S = function (elements) {
        if(!(this instanceof S))
            return new S(arguments)
        this.prop = {}
        this.prop.c = 'S'
        this.constructor = 'S'
        this.cont = {}
        this.e = {}
        this.add(elements)
    }
    S.prototype = new JSrealE()
    S.prototype.getP = function (p) {
        if(this.prop[p] != undefined)
            return this.prop[p]
        if(this.cont[p])
            return this.cont[p]
        this.sort()
        if(['f', 'cp'].has(p) && this.e.head)
            if(this.e.head[p]())
                return this.e.head[p]()
        if(['g', 'n', 'pe'].has(p) && this.e.subj)
            if(this.e.subj[p]())
                return this.e.subj[p]()
        if(p == 'cap' && (!this.cont.isSub || this.cont.sub_cap))
            return true
        if(p == 'a' && (!this.cont.isSub || this.cont.sub_a))
            switch (this.t()) {
                case 'int':
                    return '?'
                case 'exc':
                    return '!'
                default:
                    return '.'
            }
        return ''
    }
    S.prototype.sort = function () {
        this.e.head = false, this.e.subj = false, this.e.comp = []
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            eval(treeCheck(e));
            if(skip)
                continue
            if(e.pos()) {
                this.e.comp.push(e);
                continue
            }
            var c = e.c()
            switch (c) {
                case 'V':
                case 'VP':
                    if(!this.e.head) {
                        this.e.head = e
                    } else {
                        warn('déjà un groupe verbal: "' + this.e.head.realClean() + '"')
                    }
                    break
                case 'Adv':
                case 'AdvP':
                case 'P':
                case 'PP':
                case 'SP':
                    this.e.comp.push(e);
                    break
                case 'S': // pour entourer coordination
                    if(!this.e.head && !this.e.subj)
                        this.e.head = e
                    break
                default:
                    if(!this.e.subj) {
                        this.e.subj = e
                    } else {
                        warn('déjà un groupe sujet: "' + this.e.subj.realClean() + '"')
                    }
            } // switch (c)
        } // for e in elements
    }
    S.prototype.tree = function (cont) {
        this.sort()
        eval(makeCont('f', 'pe', 'g', 'n', 't', 'a'))
        if(this.ro())
            return this
        if(this.e.subj)
            pe = pe || 3 // si sujet, sûrement 3e personne...

        // Verbe au passé composé
        var sub_que = cont.sub_que
        if(this.e.head) {
            if(this.e.head.c() == 'V' && compoList.has(f)) {
                var ae = this.e.head.ae()
                var aux = ae ? 'être' : 'avoir'
                var headVerb = V(aux).f(compoAuxF[f])
                var headComp = V(this.e.head.getKey()).f('pp')
                if(sub_que && !ae) {
                    headComp.g(cont.sub_g)
                    headComp.n(cont.sub_n)
                }
                var newHead = VP(headVerb, headComp)
                for (p in this.e.head.prop)
                    if(!['c', 'f'].has(p))
                        newHead.prop[p] = this.e.head.prop[p]
                for (p in this.e.head.cont)
                    if(!['c', 'f'].has(p))
                        newHead.cont[p] = this.e.head.cont[p]
                this.e.head = newHead
                f = false
            } else if(sub_que) {
                this.e.head.cont.g = cont.sub_g
                this.e.head.cont.n = cont.sub_n
            }
        }

        // Distribution des compléments
        var comp = {}, e, pos, con
        var SposList = ['beg', 'mid', 'end']
        for (var i = 0, imax = SposList.length; i < imax; i++) {
            pos = SposList[i]
            if(pos == 'end')
                comp[pos] = J().c(' ')
            else
                comp[pos] = J().c(',').a(',')
        }
        for (var i = 0, imax = this.e.comp.length; i < imax; i++) {
            e = this.e.comp[i]
            pos = e.pos() || config.ScompPos // par défaut
            comp[pos].add(e)
        }

        // Sujet pronom
        if(this.e.subj && this.e.subj.c() == 'Pro') {
            var cPro = this.e.subj.cPro('subj')
            if(cPro)
                this.e.subj = cPro
        }

        // Arbre syntaxique
        this.clearChildren()
        this.addChild(comp.beg)
        var comp_a = comp.mid.hasElem() ? ',' : ''
        if(f != 'ip') // pas de sujet si impératif
            this.addChild(this.e.subj.clone().fct('subj'),
                    {g: cont.g, n: cont.n, pe: cont.pe, a: comp_a, fct: 'subj'})
        this.addChild(comp.mid)
        var headCont = {f: f, pe: pe, g: g, n: n,
            sub_que: sub_que, sub_g: cont.sub_g, sub_n: cont.sub_n}
        this.addChild(this.e.head, headCont)
        this.addChild(comp.end)
    } // S.tree


// Syntagme propositionnel (subrdinated phrase)
    SP = function (elements) {
        if(!(this instanceof SP))
            return new SP(arguments)
        this.prop = {}
        this.prop.c = 'SP'
        this.constructor = 'SP'
        this.cont = {}
        this.e = {}
        this.add(elements)
    }
    SP.prototype = new JSrealE()
    SP.prototype.getP = function (p) {
        if(this.prop[p] != undefined)
            return this.prop[p]
        if(this.cont[p] != undefined)
            return this.cont[p]
        if(['f', 'cp', 'pe'].has(p) && this.e.head)
            if(this.e.head[p]())
                return this.e.head[p]()
        return ''
    }
    SP.prototype.sort = function () {
        this.e.mo = false, this.e.head = false
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            eval(treeCheck(e));
            if(skip)
                continue
            var c = e.c()
            if(c == 'Pro') {
                if(e.pt() == 'r') {
                    if(!this.e.mo)
                        this.e.mo = e
                } else {
                    if(!this.e.head)
                        this.e.head = e
                }
            } else
                switch (c) {
                    case 'E':
                    case 'C':
                        if(!this.e.mo)
                            this.e.mo = e;
                        break
                    default:
                        if(!this.e.head)
                            this.e.head = e;
                        break
                }
        } // for e in elements
    }
    SP.prototype.tree = function (cont) {
        this.sort()
        eval(makeCont('g', 'n', 'f', 'pe'))
        if(this.ro())
            return this
        if(!this.e.head)
            warn('SP requiert proposition')
        if(!this.e.mo)
            warn('SP requiert mot-outil')

        // Arbre syntaxique
        this.clearChildren()
        this.addChild(this.e.mo)
        var headCont = {g: g, n: n, f: f, pe: pe}
        if(this.e.mo.key == 'que') {
            headCont.sub_que = true
            headCont.sub_g = cont.sub_g
            headCont.sub_n = cont.sub_n
        } else if(this.e.mo.key == 'qui') {
            headCont.pe = cont.sub_pe
        }
        this.addChild(this.e.head, headCont)
    }


// Syntagme coordonné
    CP = function (elements) {
        if(!(this instanceof CP))
            return new CP(arguments)
        this.constructor = 'CP'
        this.prop = {}
        this.cont = {}
        this.e = {}
        this.cont.ro = '#' // pas de réalisation avant d'avoir des éléments à coordonner
        this.prop.defaultCoo = config.CPcoo
        this.add(elements)
    }
    J = function () { // alternative à CP, pour bénéfice visuel 
        var e = new CP(arguments)
        e.constructor = 'J'
        e.prop.defaultCoo = config.Jcoo // coordonnant par défaut différente
        return e
    }
    CP.prototype = new JSrealE()
    CP.prototype.hasElem = function () {
        this.sort()
        return this.e.elem.length ? true : false
    }
    CP.prototype.c = function (val) {
        if(val === undefined)
            return this.getP('c')
        if(val === null)
            return this.delP('c')
        this.elements.push(C(val))
        return this
    }
    CP.prototype.getP = function (p, CPasks) {
        if(this.prop[p] != undefined)
            return this.prop[p]
        this.sort()
        if(this.cont[p] != undefined)
            return this.cont[p]
        if(this.e.elem.len)
            if(['nc', 'zc', 'rc', 'ne', 'ns', 'nrs'].has(p) && this.e.coo)
                return this.e.coo[p]()
        var numElem = this.e.elem.length
        if(numElem)
            switch (p) {
                case 'c': // catégorie: celle du premier suffit en pratique
                    return this.e.elem[0].c();
                    break
                case 'g': // genre: tous féminins?
                    var allF = true, oneM = false
                    for (var i = 0; i < numElem; i++) {
                        var e = this.e.elem[i]
                        if(e.g() != 'f')
                            allF = false
                        if(e.g() == 'm')
                            oneM = true
                    }
                    return allF ? 'f' : oneM ? 'm' : '';
                    break
                case 'n': // nombre: quel coordonnant?
                    var oneP = false
                    if(this.e.coo) {
                        if(this.e.coo.n() == 'p' && numElem > 1)
                            return 'p'
                    }
                    for (var i = 0; i < numElem; i++) {
                        var e = this.e.elem[i]
                        if(e.n() == 'p')
                            oneP = true
                    }
                    return oneP ? 'p' : 's';
                    break
                case 'pe': // personne: plus complexe
                    if(numElem == 1)
                        return this.e.elem[0].pe()
                    if(this.e.coo.n() != 'p')
                        return 3
                    var lowest_pe = 3
                    for (var i = 0; i < numElem; i++) {
                        var e_pe = this.e.elem[i].pe()
                        if(e_pe && e_pe < lowest_pe)
                            lowest_pe = e_pe
                    }
                    return lowest_pe;
                    break
                case 'ns':
                    var lastElem = this.e.elem[this.e.elem.length - 1]
                    return lastElem.ns();
                    break
            } // switch (p)
        return ''
    }
    CP.prototype.sort = function () {
        this.e.coo = false, this.e.elem = []
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            eval(treeCheck(e));
            if(skip)
                continue
            var c = e.c()
            if(c == 'C') {
                if(!this.e.coo)
                    this.e.coo = e
                else
                    warn('CP a déjà un coordonnant (' + this.e.coo.realClean() + ')')
            } else {
                delete this.cont.ro
                this.e.elem.push(e)
            }
        } // for e in elements
        if(!this.e.coo)
            this.e.coo = C(this.prop.defaultCoo)
    }
    CP.prototype.tree = function (cont) {
        this.sort()
        eval(makeCont('f', 'ps', 'pos', 'cs'))
        var g = this.prop.g || cont.g || ''
        var n = this.prop.n || cont.n || ''
        var pe = this.prop.pe || cont.pe || ''
        var sub_cap = cont.sub_cap, sub_a = cont.sub_a, isSub = cont.isSub
        var num = this.num()

        // Cas particuliers
        var elem = this.e.elem
        if(!elem.length)
            return // rien à 'coordonner'

        // Dans une liste HTML
        var childTag, childNS
        if(['ul', 'ol'].has(this.tag())/* ||
         (this.parent && ['ul','ol'].has(this.parent.tag()))*/) {
            childTag = 'li', childNS = true
        }

        // Arbre syntaxique
        this.clearChildren()
        if(elem.length == 1)
            this.addChild(elem[0], cont)
        else {
            var coo = this.e.coo || cont.conSpec
            var coo_real = coo.key.trim()
            var rc = coo.rc(), nc = coo.nc()
            var symb = [',', '/', '-', ';', ':', '|'].has(coo_real)
            for (var i = 0, imax = elem.length; i < imax; i++) {
                var e = elem[i], c = e.c()
                if(['N', 'NP', 'A', 'AP', 'Pro'].has(c))
                    cont = {g: g, n: n, num: num}
                else if(['V', 'VP', 'S'].has(c))
                    cont = {f: f, pe: pe, n: n}
                else
                    cont = {}
                cont.tag = childTag, cont.ns = childNS
                if((i == 0 && (sub_cap || !isSub)) || !coo_real)
                    cont.sub_cap = true
                if((i == imax - 1 && (sub_a || !isSub)) || !coo_real)
                    cont.sub_a = true
                if(c == 'Pro')
                    e = e.proCP()
                if(!coo_real) // espace
                    this.addChild(e, cont)
                else if(symb) { // virgule, slash, tiret...
                    if(i < imax - 1) {
                        cont.a = coo_real
                        if(![',', ';', ':'].has(coo_real))
                            e.ns(true)
                    }
                    this.addChild(e, cont)
                } else { // mot, comme "et", "ou"
                    if(i < imax - 1 && !nc)
                        cont.a = ','
                    if(rc || i == imax - 1)
                        this.addChild(coo)
                    cont.a2 = i < imax - 2 ? ',' : ''
                    this.addChild(e, cont)
                }
            }
        }
    }


/******************
 ** 
 *** Date and hour DT
 ** 
 ******************/
    DT = function (date) {
        if(!(this instanceof DT))
            return new DT(date)
        this.prop = {}
        this.constructor = 'DT'
        this.cont = {}
        this.e = {}
        if(date)
            if(date instanceof Date)
                this.fromDateObj(date)
            else {
                if(typeof date == 'string') {
                    var test = date.match(/-/g)
                    if(test && test.length == 2 && !date.has('T'))
                        date = date.replace(/-/g, '/')
                }
                this.fromDateObj(new Date(date))
            }
    }
    DT.prototype = new JSrealE()
    DT.prototype.fromDateObj = function (dateObj) {
        var d = dateObj, l = dateMethodList, m, p
        for (var i = 0, imax = l.length; i < imax; i++) {
            m = 'get' + l[i][1], p = l[i][0]
            if(d[m]())
                this[p](d[m]())
        }
    }
    var dateMethodList = [
        ['y', 'FullYear'],
        ['m', 'RealMonth'],
        ['d', 'Date'],
        ['h', 'Hours'],
        ['min', 'Minutes'],
        ['s', 'Seconds']
    ]
    DT.prototype.toDateObj = function () {
        if(this instanceof Date)
            return this
        var d = new Date(), l = dateMethodList, m, p
        for (var i = 0, imax = l.length; i < imax; i++) {
            if(i != 3) {
                m = 'set' + l[i][1], p = l[i][0]
                d[m](this[p]())
            }
        }
        return d
    }
    Date.prototype.getRealMonth = function () {
        return this.getMonth() + 1
    }
    Date.prototype.setRealMonth = function (m) {
        this.setMonth(m - 1)
    }
    DT.prototype.now = function () {
        // utilise l'objet Date()
        this.fromDateObj(new Date())
        return this
    }
    function daysFrom(d1, d2) {
        if(typeof d1 == 'string')
            d1 = Date(d1)
        else if(d1 instanceof DT)
            d1 = d1.toDateObj()
        if(typeof d2 == 'string')
            d2 = Date(d2)
        else if(d2 instanceof DT)
            d2 = d2.toDateObj()

        var timeDiff = d2.getTime() - d1.getTime();
        var diffDays = timeDiff / (1000 * 3600 * 24);

        return Math.round(diffDays);
        // Paul : 2015.02.10 : unused
//        days += d2.getHours() * 60 * 60 * 1000
//        days += d2.getMinutes() * 60 * 1000
//        days += d2.getSeconds() * 1000
//        days += d2.getMilliseconds()
//        days -= d1.getHours() * 60 * 60 * 1000
//        days -= d1.getMinutes() * 60 * 1000
//        days -= d1.getSeconds() * 1000
//        days -= d1.getMilliseconds()
//        days += 250 // erreurs d'arrondi
//        days /= (24 * 60 * 60 * 1000)
//        return Math.floor(days)
    }
    DT.prototype.findDay = function () {
        var y = this.y(), m = this.m(), d = this.d()
        if(y && m && d) {
            return dayList[this.toDateObj().getDay()]
        } else
            return '';
    }
    DT.prototype.hasTime = function () {
        if(this.only() == 'date')
            return false
        if(this.h() || this.min() || this.s())
            return true
        return false
    }
    DT.prototype.naturalExpression = function (t, fromNow) {
        fromNow = fromNow || daysFrom(DT().now(), this); // for call naturalExpression(t)
        return ([t, config.dateT].has('nat') && fromNow >= -6 && fromNow <= 7)
    }
    var dayList = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi']
    var monthList = [0, 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre']
    var makeContDT = makeCont('t', 'only', 'noDay', 'noDate', 'noMonth', 'noYear', 'noDet')
    DT.prototype.tree = function (cont) {
        eval(makeContDT);
        var y = this.y(), m = this.m(), d = this.d(), h = this.h(), min = this.min(), s = this.s()
        var day = this.findDay();
        var now = DT().now();
        var rd, rt, j, hasDate, hasTime;

        // Date
        var fromNow = daysFrom(now, this)
        if(this.naturalExpression(t, fromNow)) {
            hasDate = true
            if(fromNow == 0)
                rd = NP(N("aujourd'hui"))
            else if(fromNow == 1)
                rd = NP(N('demain'))
            else if(fromNow == 2)
                rd = NP(N('après-demain'))
            else if(fromNow == -1)
                rd = NP(N('hier'))
            else if(fromNow == -2)
                rd = NP(N('avant-hier'))
            else if(fromNow > 2 && fromNow < 7)
                rd = noDet ? NP(N(day)) : NP(D('ce'), N(day))
            else if(fromNow == 7)
                rd = NP(N(day), A('prochain'))
            else if(fromNow < -2 && fromNow >= -6)
                rd = NP(N(day), A('passé'))
        } else {
            j = J().c(' ')
            if(d) {
                rd = noDet ? NP() : NP(D('le'))
                if(!noDay && day)
                    j.add(N(day))
                j.add(N(d).i(1))
            } else
                rd = noDet ? J().c(' ') : PP(P('en'))
            if(m)
                if((t != 'nat' || y != now.y() || m != now.m()) && !noMonth)
                    j.add(N(monthList[m]).i(1))
                else if(!rd && t == 'nat' && m == now.m() && y == now.y())
                    rd = NP('ce mois-ci')
            if(y)
                if((t != 'nat' || y != now.y()) && !noYear)
                    j.add(N(y).i(1))
            if(j.hasElem()) {
                hasDate = true
                rd.add(j)
            }
        }

        // Heure
        rt = J().c(' ')
        var hHas = h ? true : h === 0 ? true : false
        if(hHas)
        {
            rt.add(N(h + '\xA0h')) // paul 2015.03.11 out
//            rt.add(N(h + ' h'))
        }
        if(min)
            if(s || !hHas)
            {
//                rt.add(N(min + '\xA0min')) // paul 2015.03.11 out
                rt.add(N(min + ' min'))
            }
            else
            {
                rt.add(N(min))
            }
        if(s)
        {
//            rt.add(N(s + '\xA0s')) // paul 2015.03.11 out
            rt.add(N(s + ' s'))
        }
        if(rt.hasElem())
        {
            hasTime = true
        }
        
        // Arbre syntaxique
        this.clearChildren()
        if(hasDate && (only == 'date' || !hasTime))
            this.addChild(rd) // juste la date
        else if(hasTime && (only == 'time' || !hasDate))
            this.addChild(rt) // juste l'heure
        else if(hasDate && hasTime)
            this.addChild(rd.add(PP(P('à'), rt))) // date et heure
        else
            warn('DT sans date ou heure précisée')
    }; // DT.tree

/******************
 ** 
 *** Date Range DTR
 ** 
 ******************/
    DTR = function (elements) {
        if(!(this instanceof DTR))
        {
            return new DTR(arguments);
        }
        this.prop = {};
        this.constructor = 'DTR';
        this.cont = {};
        this.e = {};
        this.add(elements);
    };
    DTR.prototype = new JSrealE();
    DTR.prototype.sort = function () {
        this.e.list = [];
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            eval(treeCheck(e));
            if(skip)
            {
                continue
            }
            if(e instanceof DT) // utilise directement
                this.e.list.push(e)
            else if(e instanceof Date
                    || typeof e == 'string') // convertit d'abord
                this.e.list.push(DT(e))
            else
                warn('non-date ajouté à DTR')
        } // for e in elements
    } // DT.add
    DTR.prototype.tree = function (cont) {
        this.sort();
        eval(makeContDT)
        var list = this.e.list;

        // Cas particuliers
        if(list.length === 0) {
            warn('DTR vide');
            this.addChild(J().c(' '));
            return;
        }
        if(list.length === 1) {
            this.addChild(list[0]);
            return;
        }

        // Quelques variables
        var e1 = list[0], e2 = list[1];
        var d1 = e1.toDateObj(), d2 = e2.toDateObj()
        if(d1.getTime() > d2.getTime()) {
            var e0 = e2, d0 = d2;
            e2 = e1, d2 = d1, e1 = e0, d1 = d0
        }
        var daysBetween = daysFrom(d1, d2)
        if(daysBetween == 0) {
            this.addChild(list[0]);
            return
        }
        var now = DT().now();

        // Élimination des redondances
        if(e1.y() == e2.y())
        {
            e1.noYear(true)
            if(e1.m() == e2.m())
            {
                e1.noMonth(true)
                if(e1.d() == e2.d())
                {    
                    e1.noDate(true) // interprété plus bas
                }
            }
        }

        // Transfert du contexte
        for (var p in cont) {
            e1.cont[p] = cont[p]
            e2.cont[p] = cont[p]
        }
        for (var p in this.prop) {
            e1.prop[p] = this.prop[p]
            e2.prop[p] = this.prop[p]
        }

        // Arbre syntaxique
        this.clearChildren();
        if(daysBetween > 1)
        {
            // de le [d1] à [t1] à le [d2] à [t2]
            if(!e1.d())
                e1.noDet(true) // sinon, "d'en 2000"
            if(!e2.d())
                e2.noDet(true) //   au lieu de "de 2000"
            this.addChild(J(C(' '), PP(P('de'), e1), PP(P('à'), e2)))
        }
        else if(daysBetween == 1)
        {
            if(e1.naturalExpression(t) || e2.naturalExpression(t))
            {
                this.addChild(CP(e1, e2, C('et')));
            }
            else
            {
                e1.noDet(true);
                e2.noDet(true);
                this.addChild(NP(D('le'), CP(e1, e2, C('et'))));
            }
        }
        // else same day = anothing to do
    } // DTR.tree



    /*************/
    /* HTML  DOM */
    /*************/

// Universel
    DOM = function (elements, constructor) {
        if(!(this instanceof DOM))
        {
            return new DOM(arguments);
        }
        this.constructor = constructor || 'DOM';
        this.prop = {};
        this.prop.attr = [];
        this.cont = {};
        this.e = {};
        this.add(elements);
    };
    DOM.prototype = new JSrealE();
    DOM.prototype.textTags = function (show) {
        config.textTags = show;
        return this;
    };
    var childTraitList = ['g', 'n', 'pe', 'f', 'noDet', 'ps', 'pos', 'i', 'c']
    DOM.prototype.setP = function (p, val) {
        if(p == 'c')
            this.prop.conSpec = val
        else if(p == 'd')
            this.prop.detSpec = val
        else if(p == 'sub')
            return SP(C(val), this)
        else
            this.prop[p] = val
        return this
    }
    DOM.prototype.getP = function (p) {
        if(this.prop[p] != undefined)
            return this.prop[p]
        if(this.cont[p] != undefined)
            return this.cont[p]
        if(childTraitList.has(p) && this.elements.length && this.elements[0].getP)
            return this.elements[0].getP(p)
        return ''
    }
    JSrealE.prototype.attr = function (attr, val) {
        this.prop.attr = this.prop.attr || []
        if(Object.prototype.toString.call(attr) == '[object Object]')
            for (var p in attr) {
                this.attr(p, attr[p])
            }
        if(attr && val)
            this.prop.attr.push({attr: attr, val: val})
        return this
    }
    var noCloseList = ['img', 'br', 'hr', 'meta', 'col', 'input', 'link']
    JSrealE.prototype.noClose = function () {
        return noCloseList.has(this.tag())
    }
    JSrealE.prototype.openTag = function () {
        var tag = this.tag()
        if(!tag)
            return ''
        var r = '<' + tag.toLowerCase()
        var i, a, val
        i = 0;
        while (a = attrList[i++]) {
            val = this[a]()
            if(val && a != 'tag')
                r += ' ' + a + '="' + val + '"'
        }
        if(this.prop.attr) {
            i = 0;
            while (a = this.prop.attr[i++])
                r += ' ' + a.attr + '="' + a.val + '"'
        }
        r += '>'
        if(config.textTags)
            return r.convertTags()
        else
            return r
    }
    JSrealE.prototype.closeTag = function () {
        var tag = this.tag()
        if(!tag || this.noClose())
            return ''
        var r = '</' + tag.toLowerCase() + '>'
        if(config.textTags)
            return r.convertTags()
        else
            return r
    }
    String.prototype.convertTags = function () {
        return this.replace(/\</g, "&lt;").replace(/\>/g, "&gt;")
    }
    var domCapList = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']
    DOM.prototype.tree = function (cont) {
        eval(makeCont())
        if(!this.elements || !this.elements.length
                || this.elements == '[object Arguments]') {
            this.cont.ro = '#'
            return
        }
        for (var i = 0, imax = childTraitList.length; i < imax; i++) {
            var p = childTraitList[i]
            if(p != 'c')
                cont[p] = this.prop[p] || this.cont[p]
        }
        cont.conSpec = this.prop.conSpec || cont.conSpec
        cont.detSpec = this.prop.detSpec || cont.detSpec
        if(config.Hcap && domCapList.has(this.tag()))
            cont.cap = cont.cap || this.cap() || true

        // Arbre
        this.clearChildren()
        if(['ol', 'ul'].has(this.tag()))
            cont.tag = 'li'
        for (var i = 0, imax = this.elements.length; i < imax; i++) {
            var e = this.elements[i]
            delete cont.a2
            this.addChild(e, cont)
        }
    }

    var domList = ['A', 'P', 'Html', 'Head', 'Body', 'Span', 'Title', 'Div', 'H1', 
        'H2', 'H3', 'H4', 'H5', 'H6', 'Strong', 'Em', 'Br', 'Link', 'Img', 'Li', 
        'Table', 'TR', 'TD', 'TH', 'Form', 'Input', 'Textarea', 'B', 'I', 'TT', 
        'HR', 'UL', 'OL', 'HL', 'Email'];
    for (var i = 0, imax = domList.length; i < imax; i++) {
        var d = domList[i]
        var d2 = d.toLowerCase()
        if(d != 'A' && d != 'P')
            window[d] = (function (d, d2) {
                return function () {
                    return new DOM(arguments, d).tag(d2)
                }
            })(d, d2);
    }
    HL = function (address) {
        var fullAddress
        if(!address.beg('http')) {
            if(address.beg('!')) {
                address = address.substring(1)
                fullAddress = address
            }
            else
                fullAddress = 'http://' + address
        }
        return new N(address).href(fullAddress)
    };
    Email = function (email) {
        return new N(email).href('mailto:' + email);
    };

    /* JSreal[] à l'extérieur */
    var JSrealPort = ['JSrealPort', // garder celui-ci!
        'lex',
        'lexE',
        'JSrealE',
        'config',
        'methodList',
        'traitList',
        'categoryList',
        'phraseList',
        'domList',
        'daysFrom'
    ];
    
    var JSrealReturn = {};
    for (var i = 0, imax = JSrealPort.length; i < imax; i++) {
        var p = JSrealPort[i];
        JSrealReturn[p] = eval(p);
    }
    
    return JSrealReturn;
};