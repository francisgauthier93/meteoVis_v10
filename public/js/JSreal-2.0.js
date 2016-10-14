/*
 * JSreal Version 2
 */
var JSrealV2 = (function() {
    
    //// Default this.configuration
//    this.config = {
//        language: 'en',
//        lexicon: {},
//        rule: {},
//        isInit: false,
//        isDevEnv: false
//    };
    
    return {
        init: function(language, lexicon, rule) {
            this.Config.set({
                language: language,
                lexicon: lexicon,
                rule: rule,
                isDevEnv: true
            });
        },
        destroy: function() {
            
        },
        conjugate: function(verb, tense, person) {
            return this.Module.Conjugation.conjugate(verb, tense, person);
        }
    };
})();

/**
 * Modules
 */
JSrealV2.Module = {};

//// Conjugation Module
JSrealV2.Module.Conjugation = (function() {
    var applyEnding = function(unit, tense, person, conjugationTable) {
        // TODO : IF NULL
        // TODO : throw exception
        JSrealV2.Logger.print(person);
        JSrealV2.Logger.print(conjugationTable.tense[tense]);
        return unit.removeEnd(conjugationTable.ending) 
                            + conjugationTable.tense[tense][person-1];
    };

    var conjugate = function(unit, tense, person) {
console.log(JSrealV2.Config);
        var verbInfo = this.Config.get("lexicon")[unit];
        if(verbInfo !== undefined)
        {
            if(verbInfo.V !== undefined)
            {
                var conjugationTable = this.Config.get("rule").conjugation[verbInfo.V.ct];

                if(conjugationTable !== undefined)
                {
                    return applyEnding(unit, tense, person, conjugationTable);
                }
                else
                {
                    throw JSrealV2.Exception.conjugationTableNotExists("Table Id = " + verbInfo.V.ct);
                }
            }
            else
            {
                throw JSrealV2.Exception.isNotAVerb("Verb = " + unit);
            }
        }
        else
        {
            throw JSrealV2.Exception.verbNotExists("Unit = " + unit);
        }
    };

    return {
        conjugate: function(verb, tense, person) {
            var conjugatedVerb = null;

            try
            {
                conjugatedVerb = conjugate(verb, tense, person);
            }
            catch(err)
            {
                JSrealV2.Logger.warning(err);
            }

            return conjugatedVerb;
        }
    };
})();

/*
 * Util
 */
String.prototype.removeEnd = function(ending) {
    var endingPosition = this.lastIndexOf(ending);
    if(ending.length > 0
            && endingPosition >= 0 
            && this.length === (endingPosition + ending.length))
    {
        return this.substring(0, endingPosition);
    }
    else
    {
        throw JSrealV2.Exception.wrongEnding("Word : " + this + ", Ending : " + ending);
    }
};

/*
 * Configuration
 */
JSrealV2.Config = (function() {
    
    var config = {
        language: 'en',
        lexicon: {},
        rule: {},
        isDevEnv: false // TODO : Automation
    };
    
    return {
        set: function(args) {
            for(var key in args)
            {
                if(config[key] !== undefined)
                {
                    config[key] = args[key];
                    
                    return config[key];
                }
            }
            
            return null;
        },
        get: function(key) {
            if(config[key] !== undefined)
            {
                return config[key];
            }
            
            return null;
        }
    };
})();

/*
 * Exception
 */
JSrealV2.Exception = (function() {
    
    var exceptionConfig = {
        exception: {
            4501: {
                "en": "This word does not exist in lexicon",
                "fr": "Ce mot n'est pas présent dans le lexique"
            },
            4502: {
                "en": "This word is not a verb",
                "fr": "Ce mot n'est pas un verbe"
            },
            4503: {
                "en": "Conjugation does not exist for this verb",
                "fr": "La conjugaison n'existe pas pour ce verbe"
            },
            4504: {
                "en": "It is not the ending of this word",
                "fr": "Ce n'est pas la terminaison du mot"
            },
            0000: {
                "en": "",
                "fr": ""
            }
        }
    };
    
    var exception = function(id, debugMessage) {
        JSrealV2.Logger.debug(debugMessage);
        
        return exceptionConfig.exception[id][this.Config.get("language")];
    };
    
    return {
        verbNotExists: function(m) {
            return exception(4501, m);
        },
        isNotAVerb: function(m) {
            return exception(4502, m);
        },
        conjugationTableNotExists: function(m) {
            return exception(4503, m);
        },
        wrongEnding: function(m) {
            return exception(4504, m);
        }
    };
})();

/*
 * Logger
 */
JSrealV2.Logger = (function() {
    var print = function(object) {
        console.log(object);
    };
    
    var debug = function(message) {
        if(this.Config.get("isDevEnv"))
        {
            console.log('%cDebug : ' + message, 'background: #CEE3F6; color: black');
        }
    };
    
    var info = function(message) {
        console.log('%cInfo : ' + message, 'background: #A9D0F5; color: black');
    };
    
    var warning = function(message) {
        console.log('%cWarning : ' + message, 'background: orange; color: black');
        console.trace();
        if(this.Config.get("isDevEnv"))
        {
            console.trace();
        }
    };
    
    var alert = function(message) {
        console.log('%cAlert : ' + message, 'background: red; color: black');
        
        if(this.Config.get("isDevEnv"))
        {
            console.trace();
        }
    };
    
    return {
        print: function(object) {
            print(object);
        },
        debug: function(message) {
            debug(message);
        },
        info: function(message) {
            info(message);
        },
        warning: function(message) {
            warning(message);
        },
        alert: function(message) {
            alert(message);
        }
    };
})();