var showDialog = function(title, content, onsubmit, onclose)
{
    new MUX.Dialog({
        loader: 'none',
        title: title,
        content: new Element('div', {styles: {maxWidth: 400, maxHeight: 600, overflow: 'auto'}}).adopt(content),
        buttons: [{
            title: 'Отмена',
            style: 'link',
            click: 'close'
        },{
            title: 'Сохранить',
            click: 'submit'
        }],
        submitted: false,
        onSubmit: onsubmit,
        onClose: onclose
    });
}

var launchTypicalDialog = function(dialogTitle, contentEl, use_chrome_radio_fix)
{   
    var oldParent = contentEl.getParent();
    var content = contentEl;
    var contentBackup = contentEl.clone(true, true);
    //@BUGFIX: в google chrome не копируются значение checked:
    if(use_chrome_radio_fix === true)
    {
        var holder = content.innerHTML; 
        var inputs = content.getElementsByTagName('input');
        for(var i in inputs)
        {
           if(inputs[i].checked === true)
           {
               contentBackup.innerHTML = holder;
               contentBackup.getElementsByTagName('input')[i].checked = true;
           }
        }
    }
    //@END: chrome bugfix;
    
    showDialog(dialogTitle, content, 
    function()
    {
        this.submitted = true;
        this.close();
    },
    function()
    {
       if(!this.submitted)
       {   
            oldParent.adopt(contentBackup);
            content.dispose();
       } else {
           oldParent.adopt(content);
           contentBackup.dispose();
       }
       this.submitted = false;
    });
}