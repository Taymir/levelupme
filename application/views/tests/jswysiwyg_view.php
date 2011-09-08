<style type="text/css"> 
        body{
                font-family: sans-serif;
                font-size: .9em;
        }
        #textarea-1{
                width: 720px;
                height: 200px;
                padding: 10px;
                border: 2px solid #ddd;
        }
</style> 

<link rel="stylesheet" type="text/css" href="/styles/MooEditable/MooEditable.css"> 
<link rel="stylesheet" type="text/css" href="/styles/MooEditable/MooEditable.Extras.css"> 
<link rel="stylesheet" type="text/css" href="/styles/MooEditable/MooEditable.SilkTheme.css"> 

<script type="text/javascript" src="/scripts/mootools-core.js"></script> 
<script type="text/javascript" src="/scripts/mootools-more.js"></script> 

<script type="text/javascript" src="/scripts/Locale/Locale.ru-RU.MooEditable.js"></script>
<script type="text/javascript" src="/scripts/MooEditable/MooEditable.js"></script>
<script type="text/javascript" src="/scripts/MooEditable/MooEditable.UI.MenuList.js"></script> 
<script type="text/javascript" src="/scripts/MooEditable/MooEditable.Extras.js"></script> 
<script type="text/javascript"> 
        window.addEvent('domready', function(){
            
                
                
                $('textarea-1').mooEditable({
                        actions: 'bold italic underline strikethrough | formatBlock justifyleft justifyright justifycenter justifyfull | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | urlimage | toggleview'
                });

                // Post submit
                $('theForm').addEvent('submit', function(e){
                        alert($('textarea-1').value);
                        return true;
                });

        });
</script> 

<h1>MooEditable example with extra toolbar items</h1> 

<form id="theForm" method="post" action="http://form-data.appspot.com/"> 

        <label for="textarea-1">Textarea 1</label> 
        <textarea id="textarea-1" name="editable1"> 
        &lt;p&gt;&lt;strong&gt;This&lt;/strong&gt; is cool!&lt;/p&gt;
        </textarea> 

        <input type="submit"> 

</form> 