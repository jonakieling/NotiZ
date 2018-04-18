$(document).ready(function () {
    /**
     * When a click occurs on a link to create a new notification for a given
     * event, we need to change the parameters of all the links inside the modal
     * that will be shown.
     */
    (function () {
        $('.link-create-notification').on('click', function () {
            var eventIdentifier = $(this).data('event-identifier');

            $('.link-create-notification-event').each(function () {
                var href = $(this).data('href').replace('#EVENT#', eventIdentifier);

                $(this).attr('href', href);
            });
        });
    })();
});
