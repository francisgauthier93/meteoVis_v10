//
//   Environnement de programmation JSreal
//     intègre l'éditeur Javascript ACE
//

// transformer une structure d'élements JSreal en Node
function jsReal2Node(jsNode){
    var n=new Node(jsNode.constructor,jsNode.prop);
    if(jsNode.elements){
        n.realisation=jsNode.real();
        for(var i=0;i<jsNode.elements.length;i++){
            n.addChild(jsReal2Node(jsNode.elements[i]))
        }
    } else
        if(jsNode.real)
            n.addChild(new Node(n.realisation=jsNode.real()));
    return n;
}

////////////
//   affichage de l'arbre dans le canvas
//
var tree,realisation,sortie,entree,sep;

function dessiner(expr){
    realisation.style.display="none";
    clearCanvas();
    // tree=parse(expr);
    // console.log(pprint(tree));
    jsTree=null;
    try {
        jsTree=eval(expr)
        if(jsTree){
            tree=jsReal2Node(jsTree);
            // console.log(pprint(tree));
            layout(tree);
            Node.ctx.fillStyle="black"; // dessiner le texte de la réalisation complète
            Node.ctx.fillText(strip(jsTree.real()),10,20);
        }
    } catch (e) {
        Node.ctx.fillStyle="#000000";
        Node.ctx.fillText(e.toString(),10,30);
    }
}

// distance au carré entre deux points
function dist2(x1,y1,x2,y2){    
    var d2=(x1-x2)*(x1-x2)+(y1-y2)*(y1-y2);
    return d2;
}

// trouver un noeud "proche" dans le sous-arbre ayant jsNode pour racine
// retourne null si aucun n'est assez proche
function noeudProche(jsNode,xC,yC){
    if(dist2(jsNode.x,jsNode.y,xC,yC)<400)return jsNode;
    if(jsNode.children){
        for(var i=0;i<jsNode.children.length;i++){
            var n=noeudProche(jsNode.children[i],xC,yC);
            if(n!=null)return n;
        }
    }
    return null;
}

// fonctions associées au clic de souris
function afficherRealisation(e){
    var xC=e.clientX-sortie.offsetLeft+sortie.scrollLeft;
    var yC=e.clientY-sortie.offsetTop+sortie.scrollTop;
    var n=noeudProche(tree,xC,yC);
    if(n!=null && n.realisation){
        realisation.innerHTML=n.realisation;
        realisation.style.left=xC+"px";
        realisation.style.top=(yC-25)+"px";
        realisation.style.display="block";
    }
}

function cacherRealisation(e){
    realisation.style.display="none";
}

var deplacement=false;
function debutDeplacerSep(e){
    deplacement=true;
}
function finDeplacerSep(e){
    deplacement=false;
}

function deplacerSep(e){
    if(!deplacement)return;
    var largeur=entree.clientWidth+sortie.clientWidth;    
    var xC=e.clientX;
    var prop=Math.round(xC*100/largeur);
    entree.style.width=prop+"%";
    sep.style.left=prop+"%";
    sortie.style.width=(100-prop)+"%";
    editor.resize();
}

// Feature test
var hasStorage = (function() {
    var mod = "jsreal_storage_feature_test";
  try {
    localStorage.setItem(mod, mod);
    localStorage.removeItem(mod);
    return true;
  } catch (exception) {
    return false;
  }
}());

////
function storeCurrentData() {
    if(hasStorage && editor !== undefined)
    {
        localStorage.setItem("jsreal_source", editor.getValue());
    }
}

window.addEventListener("unload", storeCurrentData);

////
var editor;
function initEventHandlers(){
    JSreal.config.textTags=false;
    var bouton = document.getElementById("bouton");
    entree=document.getElementById("entree");
    sep=document.getElementById("sep");
    sortie=document.getElementById("sortie");
    realisation=document.getElementById("realisation");
    
    var canvas=document.getElementById("canvas");
    Node.ctx = canvas.getContext('2d');
    Node.ctx.fillStyle="#FF0000";
    Node.ctx.font=Node.labelHeight+"px "+Node.labelFont;

    editor = ace.edit("entree");
    editor.setTheme("ace/theme/textmate");
    // editor.getSession().setMode("ace/mode/JSreal");
    editor.getSession().setMode("ace/mode/javascript");
    editor.setShowPrintMargin(false);
    if(localStorage.getItem("jsreal_source") !== undefined
            && localStorage.getItem("jsreal_source") != "")
    {
        editor.setValue(localStorage.getItem("jsreal_source"));
        dessiner(editor.getValue());
    }
//    editor.setValue(
//        "S(\n"+
//        "  NP(D('le'),\n"+
//        "     N('souris'),\n"+
//        "     SP(C('que'),\n"+
//        "        S(NP(D('le'),\n"+
//        "             N('chat')),\n"+
//        "          VP(V('manger').f('pc'))))),\n"+
//        "  VP(V('être').f('i'),\n"+
//        "     A('gris'))\n"+
//        ")\n"
//    );

    bouton.addEventListener("click",function(){
        dessiner(editor.getValue());
    });
    canvas.addEventListener("mousedown",afficherRealisation,false);
    canvas.addEventListener("mouseup",cacherRealisation,false);
    sep.addEventListener("mousedown",debutDeplacerSep,false);
    window.addEventListener("mouseup",finDeplacerSep,false);
    window.addEventListener("mousemove",deplacerSep,false);
}

window.addEventListener("load",initEventHandlers,false);