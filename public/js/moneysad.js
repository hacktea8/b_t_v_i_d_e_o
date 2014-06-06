$('#adlink_button').height(document.body.offsetHeight - 312);
$('#adlink_button').click(function(){
cookie.set('ahref_click','1',1/24);
$(this).hide();
});

