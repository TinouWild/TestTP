$('#add-meetingPoints').click(function(){
    const index = +$('#widgets-counter').val();
    const tmpl = $('#event_meeting_Points').data('prototype').replace(/__name__/g, index);
    $('#event_meeting_Points').append(tmpl);
    $('#widgets-counter').val(index + 1);
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#event_meeting_Points div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();

handleDeleteButtons();

$('#add-guest').click(function(){
    const index = +$('#widgets-counter').val();
    const tmpl = $('#event_guest').data('prototype').replace(/__name__/g, index);
    $('#event_guest').append(tmpl);
    $('#widgets-counter').val(index + 1);
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#event_guest div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();

handleDeleteButtons();