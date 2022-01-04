<?php

/*
* Plugin Name: LMS Project
*/

function book_setup_post_type() {
    $args = array(
        'public'    => true,
        'label'     => __( 'Questions', 'textdomain' ),
        'menu_icon' => 'dashicons-book',
        'supports'  => array('title')
    );
    register_post_type( 'lms-question', $args );


    // Now register the taxonomy
    register_taxonomy('lms-subjects',array('lms-question'), array(
        'hierarchical' => true,
        'labels' => array('name' => 'Subjects'),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'lms-subject' ),
    ));

    // Now register the taxonomy
    register_taxonomy('lms-levels',array('lms-question'), array(
        'hierarchical' => true,
        'labels' => array('name' => 'Levels'),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'lms-level' ),
        
    ));

    // Now register the taxonomy
    register_taxonomy('lms-types',array('lms-question'), array(
        'hierarchical' => true,
        'labels' => array('name' => 'Types'),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'lms-types' ),
        
    ));
}
add_action( 'init', 'book_setup_post_type' );



add_action( 'add_meta_boxes', array('LMS_Meta_Box', 'add') );
add_action( 'save_post', array('LMS_Meta_Box', 'save') );

function print_pre($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

class LMS_Meta_Box
{
    static function add(){
        $screens = array('questions');
        // fore

        add_meta_box( 'lms-question-options', 'Options', array(self :: class, 'html'), 'lms-question' );
    }

    static function save($post_id){
        // print_pre($_POST);


        $options = isset($_POST['lms_options']) ? $_POST['lms_options']  : [];
        $options_hint = isset($_POST['lms_options_hint']) ? $_POST['lms_options_hint']  : [];

        update_post_meta($post_id, '_total_optons', count($options));
        update_post_meta($post_id, '_lms_options', $options);
        update_post_meta($post_id, '_lms_options_hint', $options_hint);

        // die;
    }


    static function html(){ 
        $post_id = isset($_GET['post']) ? absint( sanitize_text_field( $_GET['post'] ) ): 0 ;
        $options = get_post_meta($post_id, '_lms_options', true);
        // $options = get_post_meta($post_id, '_lms_options', true);
        $options_hint = get_post_meta($post_id, '_lms_options_hint', true);
        if($post_id && !empty($options)){

            print_r($options_hint);

            foreach ($options as $key => $option) {
                echo '<div class="lms-options-field">
                    <div class="lms-field-group">
                        <label for="">Option A</label>
                        <input type="text" name="lms_options[]" value="'.$option.'" class="lms-option-input">
                    </div>

                    <div class="lms-field-group">
                        <label for=""><input type="hidden" name="lms_options_hint_enable[]">
                        <input type="checkbox" name="lms_options_hint_enable[]" id="">
                        Hint Enable
                        </label>
                    </div>

                    <div class="lms-field-group">
                        <textarea name="lms_options_hint[]" id="" cols="30" rows="10">'.$options_hint[$key].'</textarea>
                    </div>

                    <div class="lms-field-group">
                        <label for="">
                        <input type="hidden" name="lms_options_correct_option[]">
                        <input type="checkbox" name="lms_options_correct_option[]" id="">
                        Correct Answer?
                        </label>
                    </div>
                </div>';
            }

        }else{
        ?>
        

        
        <div class="lms-options-field">
            <div class="lms-field-group">
                <label for="">Option A</label>
                <input type="text" name="lms_options[]" class="lms-option-input">
            </div>

            <div class="lms-field-group">
                <label for=""><input type="hidden" name="lms_options_hint_enable[]">
                <input type="checkbox" name="lms_options_hint_enable[]" id="">
                Hint Enable
                </label>
            </div>

            <div class="lms-field-group">
                <textarea name="lms_options_hint[]" id="" cols="30" rows="10"></textarea>
            </div>

            <div class="lms-field-group">
                <label for="">
                <input type="hidden" name="lms_options_correct_option[]">
                <input type="checkbox" name="lms_options_correct_option[]" id="">
                Correct Answer?
                </label>
            </div>
        </div>

    <?php 
        }

        echo '<style>
            /* .lms-options-field {
                padding: 1rem;
                border: 1px solid #ccc;
            } */
            .lms-field-group{
                margin-bottom:.5rem
            }
            .lms-options-field input[type="text"],
            .lms-options-field textarea{
                width:100%;
                display:block;
                
            }
            .lms-field-group > label{
                font-weight:bold;
                line-height:2.5
            }
        </style>';
    }
}