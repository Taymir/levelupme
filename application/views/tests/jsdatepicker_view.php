<script type="text/javascript" src="/scripts/mootools-core.js"></script> 
<script type="text/javascript" src="/scripts/mootools-more.js"></script> 

<script src="/scripts/datepicker/Locale.ru-RU.DatePicker.js" type="text/javascript"></script>
<script src="/scripts/datepicker/Picker.js" type="text/javascript"></script>
<script src="/scripts/datepicker/Picker.Attach.js" type="text/javascript"></script>
<script src="/scripts/datepicker/Picker.Date.js" type="text/javascript"></script>

<link href="/styles/datepicker_vista/datepicker_vista.css" rel="stylesheet">

<script type="text/javascript">
window.addEvent('domready', function()
{
    Locale.use('ru-RU');
    new Picker.Date($('datepicker'), {
        timePicker: false,
        positionOffset: {x: 0, y: 0},
        pickerClass: 'datepicker_vista',
        useFadeInOut: false,//!Browser.ie,
        toggle: $('datepicklink')
    });
})
</script>
<style type="text/css">
    .datepickerlink {
        background: url(/images/calendar.png) no-repeat right center;
        padding: 0px 6px;
        text-decoration: none;
    }
</style>

<form>
    <input type="text" id="datepicker" value="01.04.2001" size="10" />
    <a id="datepicklink" class="datepickerlink" onclick="return false;" href="#">&nbsp;</a>
</form>