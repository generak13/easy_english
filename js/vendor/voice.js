$('#exercises').on('click', '.sound-icon', function(event) {
    var audio = new Audio();
    audio.src = $(this).data('sound');
    audio.play();

    event.preventDefault();
    return false;
});