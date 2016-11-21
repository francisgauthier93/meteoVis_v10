/* 
 * Date prototype
 * 
 */

String.prototype.isDate = function()
{
    return (this.toDate() !== null);
};

function extractDate(date)
{
    var patternList = ["^([0-9]{4})[-]([0-9]{2})[-]([0-9]{2})[ ]([0-9]{2})[:]([0-9]{2})$", // Format YYYY-MM-DD HH:ii
            "^([0-9]{4})[-]([0-9]{2})[-]([0-9]{2})$"]; // Format YYYY-MM-DD
    
    var i = 0;
    var pattern = null;
    var extraction = null;
    while(i < patternList.length && extraction === null)
    {
        pattern = new RegExp(patternList[i]);
        extraction = pattern.exec(date);
        
        i++;
    }
    
    return extraction;
}

String.prototype.toDate = function()
{
    var date = $.trim(this.toString());
    
    var extraction = extractDate(date);
    
    if (extraction === null)
    {
        return null;
    }

    var year = parseInt(extraction[1]);
    var month = parseInt(extraction[2])-1;
    var day = parseInt(extraction[3]);
    var hour = (extraction.length >= 5) ? parseInt(extraction[4]) : parseInt(0);
    var minute = (extraction.length >= 6) ? parseInt(extraction[5]) : parseInt(0);
    var second = (extraction.length >= 7) ? parseInt(extraction[6]) : parseInt(0);
    var millisecond = (extraction.length >= 8) ? parseInt(extraction[7]) : parseInt(0);
    
    var composedDate = new Date(year, month, day, hour, minute, second, millisecond);
    if(composedDate.getFullYear() === year
            && composedDate.getMonth() === month
            && composedDate.getDate() === day
            && composedDate.getHours() === hour
            && composedDate.getMinutes() === minute
            && composedDate.getSeconds() === second
            && composedDate.getMilliseconds() === millisecond)
    {
        return composedDate;
    }
    
    return null;
};

Date.prototype.toLocalDate = function(sLanguage)
{
    return ((sLanguage === "fr") ? this.toDateFr() : this.toDateEn());
};

Date.prototype.toDateFr = function()
{
    //var noYear = (this.getHours() === 0);
	var noYear=true;
    var sDateFr = DT(this).dOpt({det:false,hour:false,year:!(noYear)}).cap(true);
    return sDateFr.toString();
};

Date.prototype.toDateEn = function()
{
	//var noYear = (this.getHours() === 0);
	//console.log("hours:"+this.getHours())
	var noYear=true;
	var sDateEn = DT(this).dOpt({det:false,hour:false,year:!(noYear)}).cap(true);
	return sDateEn.toString();
    //return this.toLocaleString();
};