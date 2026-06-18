<?php
/**
 * ENQUEUE DATA FOR JAVASCRIPT
 * Passes WP user data and API settings to your handleGameHoop script.
 */
add_action('wp_enqueue_scripts', function() {
    $handle = 'jquery'; 
    $user_id = get_current_user_id();
    $is_logged_in = is_user_logged_in();

    if (!$is_logged_in) {
        // Return minimal data if user is not logged in
        wp_localize_script($handle, 'wpUserData', [
            'isLoggedIn' => false
        ]);
    } else {
        // Retrieve ACF fields
        $firebase_doc_id = get_field('field_69a87e04e3a06', 'user_' . $user_id);
        $scouting_id = get_field('field_6969bf0c75671', 'user_' . $user_id);
        
        // Retrieve Ultimate Member / WordPress data
        $user_data = get_userdata($user_id);

        wp_localize_script($handle, 'wpUserData', [
            'isLoggedIn'    => true,
            'firebaseDocId' => $firebase_doc_id ?: '',
            'userId'        => $user_id,
            'firstName'     => um_user('first_name'),
            'lastInitial'   => substr(um_user('last_name'), 0, 1),
            'scoutingId'    => $scouting_id ?: '',
            'email'         => $user_data ? $user_data->user_email : '',
        ]);
    }

    // API settings for both logged-in and guest users
    wp_localize_script($handle, 'wpApiSettings', [
        'root'  => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
    ]);
});

/**
 * REGISTER REST API ROUTE
 * Creates the endpoint: your-site.com/wp-json/my-app/v1/update-firebase-id
 */
add_action('rest_api_init', function () {
    register_rest_route('my-app/v1', '/update-firebase-id', [
        'methods'             => 'POST',
        'callback'            => 'update_user_firebase_doc_id',
        'permission_callback' => function () {
            // Only allow authenticated users to hit this endpoint
            return is_user_logged_in();
        }
    ]);
});

/**
 * REST API CALLBACK
 * Updates the ACF field for the current user.
 */
function update_user_firebase_doc_id($request) {
    $user_id = get_current_user_id();

    // Try both methods of getting params to be safe
    $params = $request->get_json_params();
    $new_id = !empty($params['firebase_id']) ? $params['firebase_id'] : $request->get_param('firebase_id');

    // Sanitize the input
    $new_id = sanitize_text_field($new_id);

    // Update ACF - explicitly use the field KEY
    update_field('field_69a87e04e3a06', $new_id, 'user_' . $user_id);

    return rest_ensure_response([
        'success' => true,
        'received_id' => $new_id,
        'for_user' => $user_id
    ]);
}
