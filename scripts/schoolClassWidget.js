var updateClassList = function (classes, schoolSelectorEl, classSelectorEl)
{
    classSelectorEl.set('html', '');
    var schoolID = schoolSelectorEl.value;
    for(classID in classes[schoolID])
    {
        new Element('option',
        {
            'value' : classID,
            'text'  : classes[schoolID][classID]
        }).inject(classSelectorEl);
    }
}