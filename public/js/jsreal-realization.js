var MeteoVisRealizer = function(language) {
    this.language = language;
    this.realizationInstruction = undefined;
    
    this.run = function(element, eventType) {
        var realizer = this;
        if(realizer.realizationInstruction === undefined)
        {
            $.getJSON(BASE_URL + "public/data/jsreal-realization-instruction.json", function(data) {
                realizer.realizationInstruction = data;
            }).always(function () {
                realizer.exec(element, eventType, realizer.realizationInstruction);
            });
        }
        else
        {
            realizer.exec(element, eventType, realizer.realizationInstruction);
        }
    };
    
    this.exec = function(element, eventType, instruction) {
        var language = this.language;
        
        if(element.attr('class') === undefined)
        {
            return false;
        }
        
        var currentClass = undefined;
        var currentInstruction = undefined;
        var dynamicValuesRegex = /\*\|([a-z\-]+)\|\*/gi;
        var dynamicValuesExtraction = undefined;
        var newContent = undefined;
        var newValue = undefined;
        var classList = element.attr('class').toString().split(" ");
        var classListLength = classList.length;
        for(var i = 0; i < classListLength; i++)
        {
            currentClass = classList[i];
            if(instruction[language][currentClass] !== undefined)
            {
                currentInstruction = instruction[language][currentClass];
                
                if(currentInstruction !== undefined)
                {
                    newContent = null;
                    if(currentInstruction.alternative !== undefined)
                    {
                        $.each(currentInstruction.alternative, function(attr, data)
                        {
                            newValue = parseFloat(element.attr("data-jsreal-" + attr));
                            if(newValue !== undefined)
                            {
                                $.each(data, function(idx, info)
                                {
                                    if(newValue >= parseFloat(currentInstruction.alternative[attr][idx]['min'])
                                            && newValue <= parseFloat(currentInstruction.alternative[attr][idx]['max']))
                                    {
                                        newContent = currentInstruction.alternative[attr][idx]['text'];
                                    }
                                });
                            }
                        });
                    }
                    
                    if(newContent === null)
                    {
                        newContent = currentInstruction.default;
                    }
                    
                    while( (dynamicValuesExtraction = dynamicValuesRegex.exec(newContent)) !== null)
                    {
                        newValue = element.attr("data-jsreal-" + dynamicValuesExtraction[1]);
                        if(newValue !== undefined)
                        {
                            newValue = translateText(newValue, language).toLowerCase();
                            newContent = newContent.replace(dynamicValuesExtraction[0], newValue);
                        }
                        else
                        {
                            break;
                        }
                    }
                    
                    if(dynamicValuesExtraction === null)
                    {
                        console.log(newContent);
                        console.log(eval(newContent));
                        element.attr("data-original-title", eval(newContent)).tooltip("fixTitle");
                    
                        if(eventType !== "click")
                        {
                            element.tooltip("show");
                        }
                    }
                }
            }
        }
    };
};


$(document).ready(function() {
    var realizer = new MeteoVisRealizer(currLang);
    $('#meteo-graphic').on("click", "use, text, rect, line", 
            function(event) { realizer.run($(this), event.type) });
    $('#meteo-graphic').on("mouseenter", "use, text, rect, line", 
            function(event) { realizer.run($(this), event.type) });
});