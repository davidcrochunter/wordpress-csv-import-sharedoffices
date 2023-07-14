
<div id="q-import-images-wrapper" class="wrap import-csv">
    <h2>Import CSV Custom</h2>
    <p id="q-import-images" class="danger">1. Have you imported the featured image files into media library yet?</p>
    <p class="submit">
        <input type="button" class="button-primary" value="No, I have done." onclick="javascript:imported_images_ok()"/>
    </p>
</div>

<?php
    /**
     * Load main import-func module.
     */
    include 'import-func4json.php';
?>

<form id="import-sh-offices-form" method="post" action="" enctype="multipart/form-data" class="disabled" style="padding: 0px 20px 0px 0px;">
    <p>2. Pick up .csv file and Click Process button to do whatever is below in your run process.</p>

    <input type="file" id="users_csv" name="users_csv_cn" value="" class="all-options" />
    <?php wp_nonce_field( 'is-iu-import-sh-offices-page_import_cn', '_wpnonce-is-iu-import-sh-offices-page_import_cn' ); ?>

    <!-- <div class="light-gray-bar">
        <div class="button-primary" style="height:24px; width:0%"></div>
    </div> -->

    <p class="submit">
        <input type="button" class="button-primary" value="Process Posts" onclick="javascript:import_sh_offices_submit()" />
        <div id="import-sh-offices-loader" style="display: none; align-items: center; flex-flow: column-reverse;" hidden>
            <div class="loader"></div>
        </div>
    </p>
</form>