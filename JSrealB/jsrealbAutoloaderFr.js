var URL = {
                lexicon:  {
                    fr: "data/lex-fr.min.json",
                    en: "data/lex-en.min.json"
                },
                rule: {
                    fr: "data/rule-fr.min.json",
                    en: "data/rule-en.min.json"
                },
                feature: "data/feature.min.json"
            };

    JSrealLoader({
                language: "fr",
                lexiconUrl: URL.lexicon.fr,
                ruleUrl: URL.rule.fr,
                featureUrl: URL.feature
            }, function() {
                console.log("Langue française chargée");
            });