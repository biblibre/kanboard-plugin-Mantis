$(document).ready(function () {
    $('body').on('click', '.mantis-status-button', function (ev) {
        ev.preventDefault();
        var link = $(this);
        var mantisStatus = link.next('.mantis-status');
        mantisStatus.html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
        $.getJSON(link.attr('href'), function (data) {
            if (data.error) {
                mantisStatus.text(data.error);
            } else {
                mantisStatus.text(data.status);
            }
        });
    });
});
