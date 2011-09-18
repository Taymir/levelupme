<script type="text/javascript" src="/scripts/mootools-core.js"></script> 
<script type="text/javascript" src="/scripts/mootools-more.js"></script> 
<script type="text/javascript" src="/scripts/MUX.Dialog.js"></script>
<link rel="stylesheet" href="/styles/MUX.Dialog.css"> 

<style type="text/css">
     .link {
	cursor: pointer;
	text-decoration: none;
	color: inherit;
	font-size: inherit;
     }
 </style>
<script type="text/javascript"> 
window.addEvent('domready', function()
{
    var schools = ["Школа 1", "Школа 2", "Школа 3"];
    
    var classes = new Array();
    var classIDs = new Array();
    classes[0] = ["Класс 1", "Класс 2", "Класс 3"];
    classIDs[0] = [0, 3, 5];
    classes[1] = ["Класс 4", "Класс 5", "Класс 6"];
    classIDs[1] = [8, 11, 56];
    classes[2] = ["Класс 7", "Класс 8", "Класс 9"];
    classIDs[2] = [111, 234, 2567];
    
    var content = new Array();
    
    schools.each(function(schoolEl, schoolKey){
       content[schoolKey] = new Element('div'). grab(
       new Element('p', {html: schoolEl}) );
       
       classes[schoolKey].each(function(classEl, classKey){
           content[schoolKey].grab(
           new Element('label', {
              html: classes[schoolKey][classKey]
           }).grab(new Element('input', {
              type:'checkbox',
              name: 'class',
              value: classIDs[schoolKey][classKey]
           }), 'top'). grab(new Element('br'))
           );
       });
    });
    content = new Element('form', {id: 'dialogForm'}).adopt(content);
        
    
    $('basic-dialog-modal').addEvent('click', function(event)
    {
        //content = new Element('textarea', {id: 'commentField', style: 'width: 400px; height: 200px'});
        
        new MUX.Dialog({
            loader: 'none',
            title: 'Выбор класса и школы',
            content: new Element('div', {styles: {maxWidth: 400}}).adopt(content),
            buttons: [{
                title: 'Отмена',
                style: 'link',
                click: 'close'
            },{
                title: 'Сохранить',
                click: 'submit'
            }],
            onSubmit: function()
            {
                var selected = '';
                //var selected = $('commentField').value;
                $$('#dialogForm input').each(function(el)
                {
                    if(el.checked)
                        selected += el.value + ',';
                });
                $('hiddenField').value = selected;
                
                /*var mJax = new Request.JSON(
                {
                   url: 'http://dnevnikam.net/index.php/ajax/saveOpClasses',
                   method: 'post',
                   async: true,
                   data: {
                    'operator' : "11",
                    'classes' : selected
                    }
                });
                mJax.send();*/
                this.close();
            }
        });
    });
})
</script>
<h1>Диалоги</h1>
<em>Кликните по ссылке, чтобы открыть диалог. Такие диалоги будут использоваться для выбора из списка классов и школ.</em>
<input id="hiddenField" value="" />
<p><a href="#" id="basic-dialog-modal">Открыть диалог</a></p>