<?php
/**
 * Rowstyles
 *
 * @author Badabing
 * @package rowstyler
 * @subpackage Customizations
 */

namespace BadabingBreda\rowstyler;

// get the helperfunctions
require_once( 'helperfunctions.php' );

add_action( 'wp_enqueue_scripts',					__NAMESPACE__ . '\bb_rowstyler_scripts_styles' );		// enqueue the scripts and styles
add_action( 'fl_builder_before_render_row', 		__NAMESPACE__ . '\do_before_render_row', 10 , 2 );		// change behavior on render_row, add row top and bottom borders

add_filter( 'fl_builder_render_css',				__NAMESPACE__ .'\add_row_style_css', 10, 3 );			// add callback that runs when rendering css. it loops through the rows and adds row dependant css
add_filter( 'fl_builder_render_js', 				__NAMESPACE__ . '\add_row_style_js', 10, 3 );			// add callback that runs when rendering layout js. it loops through the rows and adds js if needed


$global_settings = \FLBuilderModel::get_global_settings();

// get the default settings
$row_settings = \FLBuilderModel::$settings_forms[ 'row' ];

$new_bg_option = array(
					'unsplashit'	=> _x( 'Unsplash It','Background type.', 'textdomain' ),
				 ) ;

$new_bg_toggle_option = array( 'unsplashit'         => array(
									'sections'      => array('bg_unsplashit'),
								),
						);

$new_bg_section = array(
					'bg_unsplashit' => array(
		  				'title'         => __( 'Unsplash Image' , 'textdomain' ),
		  				'fields'        => array(
				            'unsplashid' => array(
				                'type'          => 'text',
				                'label'         => __( 'Unsplash ID', 'textdomain' ),
				                'default'       => '',
				                'maxlength'     => '5',
				                'size'          => '6',
				                'placeholder'   => __( '', 'textdomain' ),
				                'class'         => 'my-css-class',
				                'description'   => __( '', 'textdomain' ),
				                'help'          => __( 'Enter a Unsplash ID (https://unsplash.it/images)', 'textdomain' ),
				            ),
		  					'sizex' => array(
		  					    'type'          => 'text',
		  					    'label'         => __( 'Image Width', 'textdomain' ),
		  					    'default'       => '1800',
		  					    'maxlength'     => '5',
		  					    'size'          => '5',
		  					    'placeholder'   => __( '', 'textdomain' ),
				                'description'   => __( 'px', 'textdomain' ),
		  					),
		  					'sizey' => array(
		  					    'type'          => 'text',
		  					    'label'         => __( 'Image Height', 'textdomain' ),
		  					    'default'       => '1200',
		  					    'maxlength'     => '5',
		  					    'size'          => '5',
		  					    'placeholder'   => __( '', 'textdomain' ),
				                'description'   => __( 'px', 'textdomain' ),
		  					),
			  				'grayscale' 	=> array(
			  				    'type'          => 'select',
			  				    'label'         => __( 'Grayscale', 'textdomain' ),
			  				    'default'       => 'false',
			  				    'options'       => array(
			  				        'true'      => __( 'Yes', 'textdomain' ),
			  				        'false'      => __( 'No', 'textdomain' ),
			  				    ),
			  				),
			  				'blurred' 	=> array(
			  				    'type'          => 'select',
			  				    'label'         => __( 'Blurred', 'textdomain' ),
			  				    'default'       => 'false',
			  				    'options'       => array(
			  				        'true'      => __( 'Yes', 'textdomain' ),
			  				        'false'      => __( 'No', 'textdomain' ),
			  				    ),
			  				),
			  				'random' 	=> array(
			  				    'type'          => 'select',
			  				    'label'         => __( 'Random', 'textdomain' ),
			  				    'default'       => 'false',
			  				    'options'       => array(
			  				        'true'      => __( 'Yes', 'textdomain' ),
			  				        'false'      => __( 'No', 'textdomain' ),
			  				    ),
			  				),
			            	'halign'   => array(
				                'type'          => 'select',
				                'label'         => __( 'Horizontal Alignment', 'textdomain' ),
				                'default'       => 'left',
				                'options'       => array(
				                    'left'      => __( 'Left', 'textdomain' ),
				                    'center'      => __( 'Center', 'textdomain' ),
				                    'right'      => __( 'Right', 'textdomain' ),
				                )
				            ),
				            'valign'   => array(
				                'type'          => 'select',
				                'label'         => __( 'Vertical Alignment', 'textdomain' ),
				                'default'       => 'top',
				                'options'       => array(
				                    'top'      => __( 'Top', 'textdomain' ),
				                    'center'      => __( 'Center', 'textdomain' ),
				                    'bottom'      => __( 'Bottom', 'textdomain' ),
				                )
			    	        )
                    	)
                	),
                );


// get the current number of options and sections
$current_num_options = count($row_settings['tabs']['style']['sections']['background']['fields']['bg_type']['options']);
$current_num_sections = count($row_settings['tabs']['style']['sections']);

// insert the option to the selectbox as the last item
\BadabingBreda\rowstyler\array_insert( $row_settings['tabs']['style']['sections']['background']['fields']['bg_type']['options'], $new_bg_option, 'unsplashit', $current_num_options   );

// insert the toggle settings to the option bg_type
\BadabingBreda\rowstyler\array_insert( $row_settings['tabs']['style']['sections']['background']['fields']['bg_type']['toggle'], $new_bg_toggle_option, 'unsplashit'   );

// insert the section at the correct location (last)
\BadabingBreda\rowstyler\array_insert( $row_settings['tabs']['style']['sections'], $new_bg_section, 'bg_unsplashit', $current_num_sections - 1 );

// re-register the row form
\FLBuilder::register_settings_form( 'row' , $row_settings );


/**
 * Enqueue scripts
 * @since 0.1
 */
function bb_rowstyler_scripts_styles() {
	wp_enqueue_style( 'rowstylercss', BBROWSTYLER_URL . 'includes/bb-rowstyler.css', null , BBROWSTYLER_VERSION, 'screen' );
}

/**
 * Find out if settings are made to change the template for this row
 * @param  object $row
 * @param  object $groups
 * @since 0.1
 * @return void
 */
function do_before_render_row( $row, $groups ) {

	// only run when row style top OR bottom is set
	if( isset( $row->settings->row_style_top )  || isset( $row->settings->row_style_bottom ) ) {
		// add rowstyle before adding the bg
		add_action( 'fl_builder_before_render_row_bg', __NAMESPACE__ . '\add_row_style' );
	}
}

/**
 * Add Styles to the current row
 * @param object $row
 * @since 0.1
 * @param return void
 */
function add_row_style( $row ) {

	// remove the action or it will run on all subsequent rows
	remove_action( 'fl_builder_before_render_row_bg' , __NAMESPACE__. '\add_row_style' );

}


/**
 * Filter callback that loops through $nodes[rows] and returns css needed for the inline css
 * @param string $css
 * @param object $nodes
 * @param array $global_settings
 * @todo tidy up some more, move fixed css to css-file
 * @since 0.1
 * @return string $css
 */
function add_row_style_css ( $css , $nodes , $global_settings ) {

	// Loop through rows
	foreach( $nodes['rows'] as $row ) {

		// do we need to set the bg_image
		if ( $row->settings->bg_type == 'unsplashit' ) {
			$css .= '.fl-node-' . $row->node . '.fl-row-bg-unsplashit .fl-row-content-wrap {';
			//$css .= sprintf('background-image: url(https://unsplash.it/%s/%s/);', $row->settings->sizex , $row->settings->sizey );
			$css .=		'background-image:url("https://unsplash.it/' . ( $row->settings->grayscale === 'true' ?'g/':'') .
						 $row->settings->sizex . '/' . $row->settings->sizey . '/?' .
						(( $row->settings->unsplashid=='' && $row->settings->random === 'true' ) ? 'random&':'') .
						( $row->settings->unsplashid ? 'image=' . $row->settings->unsplashid . '&' : '' ) .
						( $row->settings->blurred === 'true' ? 'blur&' : '' ) . '");';
			$css .= 'background-position: '.$row->settings->halign.' '.$row->settings->valign.';';
			$css .= 'background-size: cover;';
			$css .= '}';
		}

	}

	return $css;
}

/**
 * Callback for the fl_builder_render_js filter, adds js to the layout-js cache
 * @param string $js              [description]
 * @param object $nodes           [description]
 * @param object $global_settings [description]
 * @since  0.2
 * @return  string $js
 */
function add_row_style_js ( $js , $nodes , $global_settings ) {
	// Loop through rows
	foreach( $nodes['rows'] as $row ) {

		if ( $row->settings->bg_type == 'unsplashit' ) {
			// add some js if needed
			$js .= '';

		}
	}
	return $js;
}
