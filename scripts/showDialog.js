    var showDialog = function(title, content, onsubmit)
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
            onSubmit: onsubmit
        });
    }