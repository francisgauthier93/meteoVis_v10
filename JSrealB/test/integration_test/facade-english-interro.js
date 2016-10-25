JSrealLoader({
        language: "en",
        lexiconUrl: URL.lexicon.en,
        ruleUrl: URL.rule.en,
        featureUrl: URL.feature
    }, function() 
    {
        QUnit.test( "English Interrogative", function( assert ) {
        	assert.equal(S(NP(D("the"),N("cat")),VP(V("eat"),NP(D("the"),N("mouse")))), "The cat eats the mouse.", "Simple sentence, no option");  
        	assert.equal(S(NP(D("the"),N("cat")),VP(V("eat"),NP(D("the"),N("mouse")))).typ({int:true}), "Does the cat eat the mouse?", "Interrogative sentence"); 
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat"),NP(D("the"),N("mouse")))).typ({int:'wos'}), "Who does eat the mouse?", "Interrogative sentence (subject-who)");
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat"),NP(D("the"),N("mouse")))).typ({int:'wod'}), "Who does the cat eat?", "Interrogative sentence (direct object-who)");
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat"),NP(D("the"),N("mouse")))).typ({int:'wad'}), "What does the cat eat?", "Interrogative sentence (direct object-what)");               
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat"),NP(D("the"),N("mouse")),PP(P("to"),NP(D("my"),N("family"))))).typ({int:'woi'}), "To who does the cat eat the mouse?", "Interrogative sentence (indirect object-who)");
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat"),NP(D("the"),N("mouse")))).typ({int:'whe'}), "Where does the cat eat the mouse?", "Interrogative sentence (where)");               
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat"),NP(D("the"),N("mouse")))).typ({int:'how'}), "How does the cat eat the mouse?", "Interrogative sentence (how)");// 8
            //Different tense
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:true}), "Did the cat eat the mouse?", "Interrogative sentence(past)");//9
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:true}), "Will the cat eat the mouse?", "Interrogative sentence(future)");//10
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'wad'}), "What will the cat eat?", "Interrogative sentence(wad,future)");//11
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'wos'}), "Who will eat the mouse?", "Interrogative sentence (subject-who)");//12
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'wod'}), "Who will the cat eat?", "Interrogative sentence (direct object-who)");//13
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'whe'}), "Where will the cat eat the mouse?", "Interrogative sentence(whe,future)");//14
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'how'}), "How will the cat eat the mouse?", "Interrogative sentence (how)"); //15
            //With other options
            //Perfect
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:true}), "Had the cat eaten the mouse?", "Interrogative/Perfect sentence(past)");//16
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:true}), "Will the cat have eaten the mouse?", "Interrogative/Perfect sentence(future)");//17
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:'wad'}), "What had the cat eaten?", "Interrogative/Perfect sentence(wad,past)");//18
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:'wad'}), "What will the cat have eaten?", "Interrogative/Perfect sentence(wad,future)");//19
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:'wos'}), "Who had eaten the mouse?", "Interrogative/Perfect sentence (wos,past)");//20
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:'wos'}), "Who will have eaten the mouse?", "Interrogative/Perfect sentence (wos, future)");//21
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:'wod'}), "Who had the cat eaten?", "Interrogative/Perfect sentence (wod, past)");//22
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:'wod'}), "Who will the cat have eaten?", "Interrogative/Perfect sentence (wod, future)");//23
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:'whe'}), "Where had the cat eaten the mouse?", "Interrogative/Perfect sentence(whe,past)");//24
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:'whe'}), "Where will the cat have eaten the mouse?", "Interrogative/Perfect sentence(whe,future)");//25
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:'how'}), "How had the cat eaten the mouse?", "Interrogative/Perfect sentence (how, past)");//26
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:'how'}), "How will the cat have eaten the mouse?", "Interrogative/Perfect sentence (how, future)");//27
            //Negation
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:true,neg:true}), "Did the cat not eat the mouse?", "Interrogative/Negative sentence(past)");//16
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:true,neg:true}), "Will the cat not eat the mouse?", "Interrogative/Negative sentence(future)");//17
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'wad',neg:true}), "What did the cat not eat?", "Interrogative/Negative sentence(wad,past)");//18
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'wad',neg:true}), "What will the cat not eat?", "Interrogative/Negative sentence(wad,future)");//19
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'wos',neg:true}), "Who did not eat the mouse?", "Interrogative/Negative sentence (wos,past)");//20
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'wos',neg:true}), "Who will not eat the mouse?", "Interrogative/Negative sentence (wos, future)");//21
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'wod',neg:true}), "Who did the cat not eat?", "Interrogative/Negative sentence (wod, past)");//22
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'wod',neg:true}), "Who will the cat not eat?", "Interrogative/Negative sentence (wod, future)");//23
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'whe',neg:true}), "Where did the cat not eat the mouse?", "Interrogative/Negative sentence(whe,past)");//24
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'whe',neg:true}), "Where will the cat not eat the mouse?", "Interrogative/Negative sentence(whe,future)");//25
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'how',neg:true}), "How did the cat not eat the mouse?", "Interrogative/Negative sentence (how, past)");//26
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'how',neg:true}), "How will the cat not eat the mouse?", "Interrogative/Negative sentence (how, future)");//27
            //Negation + perfect
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:true,neg:true}), "Had the cat not eaten the mouse?", "Interrogative/Negative/Perfect sentence(past)");//16
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:true,neg:true}), "Will the cat not have eaten the mouse?", "Interrogative/Negative/Perfect sentence(future)");//17
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:'wad',neg:true}), "What had the cat not eaten?", "Interrogative/Negative/Perfect sentence(wad,past)");//18
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:'wad',neg:true}), "What will the cat not have eaten?", "Interrogative/Negative/Perfect sentence (wad, future)");//23
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:'whe',neg:true}), "Where had the cat not eaten the mouse?", "Interrogative/Negative/Perfect sentence(whe,past)");//24
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:'whe',neg:true}), "Where will the cat not have eaten the mouse?", "Interrogative/Negative/Perfect sentence(whe,future)");//25
            //Progressive
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:true,prog:true}), "Was the cat eating the mouse?", "Interrogative/Negative/Perfect sentence(past)");//16
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:true,prog:true}), "Will the cat be eating the mouse?", "Interrogative/Negative/Perfect sentence(future)");//17
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'wad',prog:true}), "What was the cat eating?", "Interrogative/Negative/Perfect sentence(wad,past)");//18
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'wad',prog:true}), "What will the cat be eating?", "Interrogative/Negative/Perfect sentence (wad, future)");//23
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'whe',prog:true}), "Where was the cat eating the mouse?", "Interrogative/Negative/Perfect sentence(whe,past)");//24
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'whe',prog:true}), "Where will the cat be eating the mouse?", "Interrogative/Negative/Perfect sentence(whe,future)");//25
            //Progressive + negation
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:true,neg:true,prog:true}), "Was the cat not eating the mouse?", "Interrogative/Negative/Perfect sentence(past)");//16
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:true,neg:true,prog:true}), "Will the cat not be eating the mouse?", "Interrogative/Negative/Perfect sentence(future)");//17
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'wad',neg:true,prog:true}), "What was the cat not eating?", "Interrogative/Negative/Perfect sentence(wad,past)");//18
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'wad',neg:true,prog:true}), "What will the cat not be eating?", "Interrogative/Negative/Perfect sentence (wad, future)");//23
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:'whe',neg:true,prog:true}), "Where was the cat not eating the mouse?", "Interrogative/Negative/Perfect sentence(whe,past)");//24
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:'whe',neg:true,prog:true}), "Where will the cat not be eating the mouse?", "Interrogative/Negative/Perfect sentence(whe,future)");//25
            //Progressive,perfect and negation
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps").perf(true),NP(D("the"),N("mouse")))).typ({int:true,neg:true,prog:true}), "Had the cat not been eating the mouse?", "Interrogative/Negative/Perfect sentence(past)");//16
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f").perf(true),NP(D("the"),N("mouse")))).typ({int:true,neg:true,prog:true}), "Will the cat not have been eating the mouse?", "Interrogative/Negative/Perfect sentence(future)");//17
            //Passive
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("ps"),NP(D("the"),N("mouse")))).typ({int:true,pas:true}), "Was the mouse eaten by the cat?", "Interrogative/Negative/Perfect sentence(past)");//16
            assert.equal(S(NP(D("the"),N("cat")),VP(V("eat").t("f"),NP(D("the"),N("mouse")))).typ({int:true,pas:true}), "Will the mouse be eaten by the cat?", "Interrogative/Negative/Perfect sentence(future)");//17
            
            

        });
    }
);