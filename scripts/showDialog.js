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
        this.close(1);
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

var launchAjaxDialog = function(dialogTitle, contentEl, ajax_url, operator, use_chrome_radio_fix)
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
        this.close(1);
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
           
           var schools = new Array();
            content.getElements('input').each(function(el){
               if(el.checked)
                   schools.push(el.value);
            });
        
            var mJax = new Request.JSON(
            {
                url: ajax_url,
                method: 'post',
                async: true,
                data: {
                    'operator': operator,
                    'schools': schools
                }
            });
            
            mJax.send();
       }
       this.submitted = false;
    });
}

var showHide = function(El)
{
    if(El.getStyle('display') == 'none')
        El.setStyle('display', '');
    else
        El.setStyle('display', 'none');
}