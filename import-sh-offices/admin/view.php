<div class="wrap import-csv">
    <h2>Import CSV Custom</h2>
    <p>Click Process button to do whatever is below in your run process.</p>
<?php
    if ( isset( $_POST['_wpnonce-is-iu-import-users-users-page_import_cn'] ) ) {
    check_admin_referer( 'is-iu-import-users-users-page_import_cn', '_wpnonce-is-iu-import-users-users-page_import_cn' );
         
        ini_set("auto_detect_line_endings", true);
        //start processing the CSV
        if (!empty($_FILES['users_csv_cn']['name'])) {
            // Setup settings variables
            $filename = $_FILES['users_csv_cn']['tmp_name'];
            $file_handle = fopen($filename,"r");
            $i=0;
            $imported = 0;
            $failedusers = array();
            $successusers = array();
            while (!feof($file_handle) ) {
                $block = 0;
                $check = 0;
                $line_of_text = fgetcsv($file_handle, 1024);
                // Let's make sure fields have data and it is not line 1
                if(!empty($line_of_text[0])) {
                    $i++;
                    if($i > 1 && $i < 302) {
                    $imported++;    
                        //start new import
                         
                        $locationID = sanitize_text_field($line_of_text[0]);
                        $locationLat = sanitize_text_field($line_of_text[1]);
                        $locationLng = sanitize_text_field($line_of_text[2]);
                        $refpost = 'location_' . $locationID;
                        update_field( 'latitude', $locationLat, $refpost );
                        update_field( 'longitude', $locationLng, $refpost );
                        echo 'Updated Term ID ' . $locationID . ' - ' . $locationLat . ', ' . $locationLng . '<br />'; 
                         
                         
         
         
                    }
                }
            }
        fclose($file_handle);
        echo 'Updated ' . $imported . ' terms';
        } else {
            echo 'Fail';
        }
    }
    ?>
  
    <form method="post" action="" enctype="multipart/form-data">
        <input type="file" id="users_csv" name="users_csv_cn" value="" class="all-options" />
        <?php wp_nonce_field( 'is-iu-import-users-users-page_import_cn', '_wpnonce-is-iu-import-users-users-page_import_cn' ); ?>
        <p class="submit">
            <input type="submit" class="button-primary" value="Process Posts" />
        </p>
    </form>

