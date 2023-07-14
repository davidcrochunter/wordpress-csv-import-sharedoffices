
function imported_images_ok() {
    jQuery('#q-import-images').removeClass('danger');

    jQuery('#import-sh-offices-form').removeClass('disabled');

    jQuery('#q-import-images-wrapper').addClass('disabled');
}
function import_sh_offices_submit() {
    jQuery('#import-sh-offices-loader').css('display', 'flex');
    jQuery('#import-sh-offices-form').submit();
}

jQuery(document).ready(function($) {

    if(jQuery('#import-sh-offices-result').text() == "Added") {

        jQuery('#import-sh-offices-result-label').remove();

        success  = '<div id="import-sh-offices-result-label" class="tribe-dismiss-notice notice notice-success tribe-notice-events-utc-timezone-2023-02-26 is-dismissible" data-ref="events-utc-timezone-2023-02-26">';
        success +=     '<p>Congratulation!<br />CSV and it\'s meta data have been imported Successfuly. </p>';
        success += '</div>'
        jQuery('#q-import-images-wrapper').before(success);
    }
});



