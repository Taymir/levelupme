var addAutocompletion = function (subjectFields, submitEl, ajaxTarget)
{
    var meioList = new Meio.Element.List();
    
    subjectFields.each(function(field, i)
    {
        new Meio.Autocomplete.Select(field, ajaxTarget,
        {
            delay: 200,
            minChars: 0,
            cacheLenght: 20,
            cacheType: 'shared',
            selectOnTab: true,
            maxVisibleItems: 10,
            requestOptions: { 
            formatResponse: function(jsonResponse){ // this function should return the array of autocomplete data from your jsonResponse 
                return jsonResponse; 
            }, 
            noCache: true  // nocache is setted by default to avoid cache problem on ie 
            // you can pass any of the Request.JSON options here -> http://mootools.net/docs/core/Request/Request.JSON 
            },
            urlOptions: {
                queryVarName: 'q',
                max:20
            }
        }, meioList );
    });
}