$('#eqslDefaultQSLMsg').keyup(function(event) {
    calcRemainingChars(event);
});

$('.qso_panel #qslmsg').keyup(function(event) {
    calcRemainingChars(event, '.qso_panel');
});

function calcRemainingChars(event, object = '') {
    var remainingChars = 240 - $(event.target).val().length;
    $(object + ' #charsLeft').text(remainingChars + "/240");

    if (remainingChars < 5) {
        $(object + ' #charsLeft').css('color', 'red');
    } else {
        $(object + ' #charsLeft').css('color', '');
    }
}