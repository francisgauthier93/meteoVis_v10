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
                language: "en",
                lexiconUrl: URL.lexicon.enr,
                ruleUrl: URL.rule.en,
                featureUrl: URL.feature
            }, function() {
                console.log("English language loaded");
            });