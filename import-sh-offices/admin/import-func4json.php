<?php

$post_type = "workspace";
$taxonomies = get_object_taxonomies( $post_type );
$taxonomy_names = [];
foreach ( $taxonomies as $taxonomy ) {
    $taxonomy_object = get_taxonomy($taxonomy);
    if ($taxonomy_object) {
        $taxonomy_name = $taxonomy_object->name;
        array_push($taxonomy_names, $taxonomy_name);
    } 
}

if ( isset( $_POST['_wpnonce-is-iu-import-sh-offices-page_import_cn'] ) ) {
    check_admin_referer( 'is-iu-import-sh-offices-page_import_cn', '_wpnonce-is-iu-import-sh-offices-page_import_cn' );
    
    ini_set("auto_detect_line_endings", true);
    //start processing the CSV
    if (!empty($_FILES['users_csv_cn']['name'])) {
        // Setup settings variables
        $filename = $_FILES['users_csv_cn']['tmp_name'];
        $jsonString = file_get_contents($filename);
        $csv_content = json_decode($jsonString, true);

        $field_names = [];

        foreach ($csv_content as $index => $row) {
            
            // if($index == 0) {
            //     $field_names = $row;
            //     continue;
            // }

            $post = array(
                'post_title' => $row['title'],
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => $post_type,
            );
        
            // Insert the post into the database
            $post_id = wp_insert_post($post);

            // Check if the post was inserted successfully
            if ($post_id > 0) {
                update_post_meta($post_id, 'address', $row['address']); // Assuming the custom field key is in the third column
                update_post_meta($post_id, 'square_feet', $row['area']); // Assuming the custom field key is in the third column
                update_post_meta($post_id, 'price_package', $row['price']); // Assuming the custom field key is in the third column

                // Set workspace category
                wp_set_object_terms( $post_id, 'Workspaces V4', 'workspace_cat' );

                // set featured image
                {
                    $image_name       = $row['imageName'];
                    $image_url        = get_image_url_by_name($image_name);
                    // echo $image_name;
                    // echo $image_url;
    
                    // $image_url        = $col;
                    // $myFile           = pathinfo($image_url);
                    // $image_name       = $myFile['basename'];
    
                    $upload_dir       = wp_upload_dir(); // Set upload folder
                    $image_data       = file_get_contents($image_url); // Get image data
                    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
                    $filename         = basename( $unique_file_name ); // Create image file name
    
                    // Check folder permission and define file location
                    if( wp_mkdir_p( $upload_dir['path'] ) ) {
                        $file = $upload_dir['path'] . '/' . $filename;
                    } else {
                        $file = $upload_dir['basedir'] . '/' . $filename;
                    }
    
                    // Create the image  file on the server
                    file_put_contents( $file, $image_data );
    
                    // Check image file type
                    $wp_filetype = wp_check_filetype( $filename, null );
    
                    // Set attachment data
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title'     => sanitize_file_name( $filename ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );
    
                    // Create the attachment
                    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    
                    // Include image.php
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
                    // Define attachment metadata
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    
                    // Assign metadata to attachment
                    wp_update_attachment_metadata( $attach_id, $attach_data );
    
                    // And finally assign featured image to post
                    set_post_thumbnail( $post_id, $attach_id );
                }


                /* labeling screen about current loop */
                echo '<div style="color: green">'.$index.'. '.$row['title'].', '.$row['address'].', '.$row['area'].' successed. </div><br>';               

            } else {
                // Post was not inserted, handle the error if needed
                echo '<div style="color: red">'.$index.'. '.$row['title'].', '.$row['address'].', '.$row['area'].' failed. </div><br>';               

            }
            
        }
    
        // echo 'Updated ' . $imported . ' terms';
        echo '<div id="import-sh-offices-result" hidden>Added</div>';

    } else {
        echo '<div id="import-sh-offices-result" hidden>Failed</div>';
    }
}


function get_image_url_by_name($image_name) {
    $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => '_wp_attachment_metadata',
                'value' => $image_name,
                'compare' => 'LIKE'
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $image_url = wp_get_attachment_url(get_the_ID());
        wp_reset_postdata();
        return $image_url;
    } else {
        return '';
    }
}

    
function import_custom_category_data() {
    $csv_file = get_template_directory() . '/custom_category_data.csv'; // Path to the CSV file

    if (($handle = fopen($csv_file, 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $post_id = $data[0]; // Assuming the first column contains the post ID
            $category_slug = $data[1]; // Assuming the second column contains the category slug

            // Check if the post exists
            if (get_post_status($post_id) !== false) {
                // Check if the category exists
                $category = get_category_by_slug($category_slug);
                if ($category !== false) {
                    // Assign the category to the post
                    wp_set_post_categories($post_id, array($category->term_id), true);
                }
            }
        }
        fclose($handle);
    }
}
add_action('init', 'import_custom_category_data');