//// Global variable
var aTranslationTable = undefined;

//// Initialization
$(document).ready(function() {
    $.getJSON("public/data/translation_table.json")
        .done(function(data) {
            
            aTranslationTable = data;
            
            // print page in default language
            setLanguage(currLang, null);
    
        }).fail(function() {
            alert("Failed to translate.");
        });
});

//// Function
function translatePage(sLanguage)
{
    $("[data-original-translation]").each(function() {
        var oElement = $(this);       

        if(oElement.attr("placeholder") !== undefined)
        {
            updateAttributeText(oElement, "placeholder", sLanguage);
        }
        
        if(oElement.attr("data-title") !== undefined)
        {
            updateAttributeText(oElement, "data-title", sLanguage);
        }
        
        updateElementText(oElement, sLanguage);
    });
    
    $("[data-title]").each(function() {
       var newContent = $("<div>").html($(this).attr("data-title"))
            .find("[data-original-translation]").each(function() {
                oElement = $(this);
                updateElementText(oElement, sLanguage);
       }).parent().html();
        
        if(newContent !== undefined)
        {
            $(this).attr("data-title", newContent);
            $(this).tooltip()
                .attr('data-original-title', newContent)
                .tooltip('fixTitle');
        }
    });
}

function updateElementText(oElement, sLanguage)
{
//    var sOriginalText = oElement.data("original-translation");
    var sOriginalText = oElement.attr("data-original-translation");
    
    if(sOriginalText === "") // if it is first time
    {
        sOriginalText = oElement.text();
//        oElement.data("original-translation", sOriginalText);
        oElement.attr("data-original-translation", sOriginalText);
    }
    
    var sTranslation = translateText(sOriginalText, sLanguage);
    
    oElement.text(sTranslation);
    oElement.html(sTranslation);
}

function updateAttributeText(oElement, sAttributeName, sLanguage)
{
    var sOriginalText = oElement.data("original-translation");
    
    if(sOriginalText === "") // if it is first time
    {
        sOriginalText = oElement.attr(sAttributeName);
        oElement.data("original-translation", sOriginalText);
    }
    
    var sTranslation = translateText(sOriginalText, sLanguage);
    
    oElement.attr(sAttributeName, sTranslation);
}

function translateText(sOriginalText, sLanguage)
{
    if(sOriginalText === "")
    {
        return "";
    }
    
    if(sOriginalText.isDate())
    {
    	console.log(sOriginalText.toDate().toLocalDate(sLanguage))
        return sOriginalText.toDate().toLocalDate(sLanguage);
    }
    
    var aDigitList = sOriginalText.match(/\d+/g);
    var sCleanText = $.trim(sOriginalText.replace(/\d+/g, "%d"));

    var sTranslation = translateCleanText(sCleanText, sLanguage);

    if(aDigitList !== null)
    {
        $.each( aDigitList, function( index, value ) {
            sTranslation = sTranslation.replace(/%d/, value);
        });
    }
    
    return sTranslation;
}

function translateCleanText(sText, sLanguage)
{
    if(aTranslationTable !== undefined)
    {
        var aTranslationSection = aTranslationTable[sText.toLowerCase()];

        if(aTranslationSection !== undefined)
        {
            var sTranslation = aTranslationSection[sLanguage];
            
            if(sTranslation !== undefined)
            {
                return sTranslation;
            }
        }
        else
        {
            return translateCity(sText, sLanguage);
        }
    }
    else{
	console.warn('Translation table is undefined')
    }
    
    return sText;
}

function translateCity(sText, sLanguage)
{
    var sTranslation = sText;
    var identifier = null;
    var sourceRegion = regionsEn;
    var targetRegion = regionsFr;
    
    if(sLanguage !== "en")
    {
        $.each(sourceRegion, function(name, info) {
            if(name.toLowerCase() === sText.toLowerCase())
            {
                identifier = info[1];
            }
        });

        if(identifier !== null)
        {
            $.each(targetRegion, function(name, info) {
                if(identifier === info[1])
                {
                    sTranslation = name;
                }
            });
        }
    }

    return sTranslation;
}
