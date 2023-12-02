$('#eqslDefaultQSLMsg').keyup(function(event) {
    calcRemainingChars(event);
});

$('#qslmsg').keyup(function(event) {
    calcRemainingChars(event);
});

function calcRemainingChars(event) {
    var remainingChars = 240 - $(event.target).val().length;
    $('#charsLeft').text(remainingChars + "/240");

    if (remainingChars < 5) {
        $('#charsLeft').css('color', 'red');
    } else {
        $('#charsLeft').css('color', '');
    }
}