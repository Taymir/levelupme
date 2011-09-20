<script type="text/javascript" src="/scripts/mootools-core.js"></script> 
<script type="text/javascript" src="/scripts/mootools-more.js"></script> 

<script type="text/javascript" src="/scripts/Meio.Autocomplete.js"></script> 
<script type="text/javascript">
window.addEvent('domready', function()
{
    var meioList = new Meio.Element.List();
    
    $$('#scheduleForm input.subjectField').each(function(field)
    {
        new Meio.Autocomplete.Select(field, 'http://dnevnikam.net/index.php/ajax/livesearch', {
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
})
</script>
<style type="text/css">
/*
---

description: A plugin for enabling autocomplete of a text input or textarea.

authors:
 - Fábio Miranda Costa

license: MIT-style license

...
*/
.ma-container{
    color: #000;
    border: 1px solid #333;
    background-color: #fff;
    position: absolute;
    visibility: hidden;
    overflow-y: auto;
    overflow-x: hidden;
}
.ma-container ul{
    list-style: none;
    overflow: hidden;
    width: 100%;
    margin: 0;
    padding: 0;
}
.ma-container li{
    padding: 2px 5px;
    line-height: 16px;
    cursor: pointer;
    float: left;
    width: 100%;
    overflow: hidden;
}
.ma-container .ma-hover{
    color: #fff;
    background-color: #921506 !important;
}
.ma-container .ma-odd{
}
.ma-container .ma-even{
    background-color: #efefef;
}
</style>

<h1>Проверка скрипта автодополнения</h1>
<em>Начните вводить название школьных предметов в поля ниже, чтобы появилась подсказка-автодополнение:</em>
<form id="scheduleForm">
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>
    <input type="text" class="subjectField"  /><br/>    
</form>

<?php 
$this->load->helper('widgets');
$ci = & get_instance();
$ci->load->model('classes_model');
$schoolClassData = $ci->classes_model->get_schools_and_classes();
?>
<?= school_class_widget($schoolClassData, ''); ?>