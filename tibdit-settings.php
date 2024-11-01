<?PHP

// tibit plugin settings
// Version: 1.6.5
// License: GPL3


/*  Copyright (C) 2014 tibdit limited

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    See <http://www.gnu.org/licenses/> for the full text of the
    GNU General Public License.
*/
if ( ! defined( 'TIBDIT_DIR' ) ) {
	define( 'TIBDIT_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'TIBDIT_URL' ) ) {
	define( 'TIBDIT_URL', plugin_dir_url( __FILE__ ) );
}
include_once( 'helper-functions.php' );
// use LinusU\Bitcoin\AddressValidator;
// use AddressValidator;
// include 'AddressValidator.php';

bd_log( "admin page" );

if ( ! function_exists( 'is_admin' ) ) {
	bd_log( "admin but not admin" );
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( "tibdit_settings" ) ) {
	class tibdit_settings {
		private $options;
		private $default_settings;
		private $help_hook;

		function __construct() {

			bd_log( "||ADM __construct" );
			$this->default_settings = $GLOBALS['bd_default_settings'];

			bd_log( 'ADM' . var_export( $this->default_settings, true ) );

			$this->page_id = 'tibdit_options';
			// This is the get_options slug used in the database to store our plugin option values.
			$this->settings_field = 'tibdit_options';
			$this->options        = get_option( $this->settings_field );
			bd_log( "ADM __construct checking options on load" . var_export( $this->options, true ) );
			$this->page_title = "tibit plugin settings";
			$this->section    = "tibdit_main_section";
			$this->list       = "tibdit_tibs_list";
			$this->blockchain = "tibdit_blockchain";

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'init_admin_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'tibdit_settings_enqueue' ) );
			// add_action( 'admin_enqueue_scripts', array($this, 'mw_enqueue_color_picker') );
			add_filter( 'contextual_help', array( $this, 'admin_help' ), 10, 3 );
			$plugin = plugin_basename( __FILE__ );
			bd_log( "plugin_action_links_$plugin" );
			add_filter( "plugin_action_links_tibdit/tibdit.php", array( $this, 'bd_plugins_page' ) );

			// Setting up the names for the creation of the buttons
			$this->svg_button_names = array(
				"bubble",
				"chevron",
				"coin",
				"hex",
				"horiz",
				"poster",
				"shadow",
				"default",
				"vert"
			);

			$this->svg_button_heights = $GLOBALS['svg_button_heights'];
		}


		// Add settings link on plugin page
		function bd_plugins_page( $links ) {
			bd_log( "bd_plugins_page() " . var_export( $links, true ) );
			$settings_link = '<a href="options-general.php?page=tibdit_options#help">Settings &amp; Help</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}

		function init_admin_page() {
			add_option( $this->settings_field, $this->default_settings );

			$this->options = get_option( $this->settings_field );
//          $this->options = wp_parse_args($this->options, tibdit_settings::$default_settings);
//          This shouldn't need setting here, we should be setting this from the sanitiser callback

			bd_log( "||ADM init: " . var_export( $this->options, true ) );

			register_setting( $this->settings_field, $this->settings_field, array( $this, 'sanitise' ) );

			add_settings_section( $this->section, '', array( $this, 'main_section' ), $this->page_id );

			// add_settings_field('title', 'Widget Heading', array($this, 'title_field'), $this->page_id, $this->section);
			// add_settings_field('intro', 'Widget Intro', array($this, 'intro_field'), $this->page_id, $this->section);
//      add_settings_field('payaddr', 'Bitcoin Address', array($this, 'payaddr_field'), $this->page_id, $this->section);
//      add_settings_field('DUR', 'Acknowledge tib for', array($this, 'DUR_field'), $this->page_id, $this->section);
//      add_settings_field('bd_button', 'Select button', array($this, 'tib_button_field'), $this->page_id, $this->section);
			// add_settings_field('widget_colour', 'Widget background shading', array($this, 'widget_colour'), $this->page_id, $this->section);

			if ( get_option( 'tib_list' ) ) {
				add_settings_section( $this->list, "list", array( $this, 'list_section' ), $this->page_id );
				update_option( 'tib_list', false );
			}
		}

		function admin_help( $contextual_help, $screen_id, $screen ) {
			bd_log( "admin_help() " );
			include( 'tibdit-settings-help.php' );

			if ( $screen_id == $this->help_hook ) {
				// $contextual_help = 'This is where I would provide help to the user on how everything in my admin panel works. Formatted HTML works fine in here too.';
				$screen->add_help_tab( array(
					'id'      => "bd_help_overview",            //unique id for the tab
					'title'   => "overview",      //unique visible title for the tab
					'content' => $bd_help_overview,  //actual help text
				) );
				$screen->add_help_tab( array(
					'id'      => "bd_help_settings",            //unique id for the tab
					'title'   => "settings",      //unique visible title for the tab
					'content' => $bd_help_settings,  //actual help text
				) );
				$screen->add_help_tab( array(
					'id'      => "bd_help_bitcoin",            //unique id for the tab
					'title'   => "bitcoin",      //unique visible title for the tab
					'content' => $bd_help_bitcoin,  //actual help text
				) );
				$screen->add_help_tab( array(
					'id'      => "bd_help_shortcodes",            //unique id for the tab
					'title'   => "shortcodes",      //unique visible title for the tab
					'content' => $bd_help_shortcodes,  //actual help text
				) );
				$screen->add_help_tab( array(
					'id'      => "bd_help_widgets",            //unique id for the tab
					'title'   => "widgets",      //unique visible title for the tab
					'content' => $bd_help_widgets,  //actual help text
				) );
				$screen->add_help_tab( array(
					'id'      => "bd_help_demomode",            //unique id for the tab
					'title'   => "demo mode",      //unique visible title for the tab
					'content' => $bd_help_demomode,  //actual help text
				) );
			}

			return $contextual_help;
		}

		function tibdit_settings_enqueue() {
			$plugurl = plugin_dir_url( __FILE__ );
			bd_log( "||ADM enqueue" );


			wp_register_style( 'tib-lib-css', 'https://widget.tibit.com/assets/css/tib.css' );
			wp_enqueue_style( 'tib-lib-css' );


			bd_register_script( 'jsbn' );
			bd_register_script( 'jsbn2' );
			bd_register_script( 'crypto-sha256' );
			bd_register_script( 'btcaddr_validator', false, array( 'bd-jsbn', 'bd-jsbn2', 'bd-crypto-sha256' ) );

			bd_register_script( 'tibdit-settings', false, array( 'bd-tibdit-settings-bottom' ) );
			bd_register_script( 'tibdit-settings-bottom', true );

			bd_register_script( 'tib-functions', false, array( 'tib-functions-bottom' ) );
			bd_register_script( 'tib-functions-bottom', true );


			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker-scripts', plugins_url( '/resources/javascripts/wp-color-picker-scripts.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_script( 'bd-btcaddr_validator' );
			wp_enqueue_script( 'bd-tibdit-settings' );
			wp_enqueue_script( 'bd-tib-functions' );

			wp_enqueue_script( 'jquery' );

			bd_register_style( 'tibbee', array( 'wp-color-picker' ) );
			wp_enqueue_style( 'bd-tibbee' );

			wp_register_style( 'jquery-ui-slider-sheet', "https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" );
			wp_enqueue_style( 'jquery-ui-slider-sheet' );

			wp_register_script( 'jquery-ui-slider-script', "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" );
			wp_enqueue_script( 'jquery-ui-slider-script' );

		}


		function main_section() {
			$plugurl = plugin_dir_url( __FILE__ );

			bd_log( "||ADM form section" );

			echo '<br>Please refer to the <a class="bd-admin-link" onclick="jQuery(\'#contextual-help-link\').trigger(\'click\');"> plugin help</a> for information and instructions.<br>';

			if ( isset ( $_GET['tab'] ) ) {
				$this->bd_tibit_tabs( $_GET['tab'] );
			} else {
				$this->bd_tibit_tabs();
			}
		}

		/* Creation of tabs for tibdit plugin settings page */
		function bd_tibit_tabs( $bd_current = 'settings' ) {
			$tabs = array( "settings" => "tibit settings", "list" => "List tib counts", "balance" => "Show balance" );
			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $tabs as $tab => $name ) {
				$class = ( $tab == $bd_current ) ? ' nav-tab-active' : '';
				echo "<a class='nav-tab$class' href='?page=tibdit_options&tab=$tab'>$name</a>";

			}
			echo '</h2>';
			?>

			<?php
			if ( ( isset( $_GET['tab'] ) && $_GET['tab'] == 'settings' ) || ! isset( $_GET['tab'] ) ) {
				if ( substr( $this->options['PAD'], 0, 1 ) == 'm' or substr( $this->options['PAD'], 0, 1 ) == 'n' ) {
					$mode = "/testnet";
				}
				$plugurl = plugin_dir_url( __FILE__ );
				bd_log( "admin render" );
				echo "<div class='wrap ";
				if(isset($this->options['advanced_settings']) && $this->options['advanced_settings'] === 'true'){
					echo 'show-advanced';
				}
				else{
					echo 'hide-advanced';
				}

				echo "'>";
				echo "<h2>$this->page_title</h2>";
					?>
					<!------------------------------------------------------------------------------->
					<!--------------------------- ADVANCED SETTINGS MARKUP -------------------------->
					<!------------------------------------------------------------------------------->
					<hr>
					<h3>Site Settings</h3>
					<table class="form-table bd">
						<tr class="PAD tib-mode-row" style='<?php
						if ( isset( $this->options['tib_mode'] ) ) {
							if ( $this->options['tib_mode'] != 'PAD' ) {
								echo 'display: none;';
							}
						} else {
							echo 'display:none';
						} ?>'>
							<th scope="row">
								Bitcoin Address
								<br>
								<a href=# onclick='openHelpTab("bd_help_bitcoin")'>How do I get a Bitcoin Address?</a>
							</th>

							<td><?php $this->PAD_field( "" ) ?></td>
						</tr>

						<tr class="ASN tib-mode-row" style='<?php
						if ( isset( $this->options['tib_mode'] ) ) {
							if ( $this->options['tib_mode'] != 'ASN' ) {
								echo 'display: none;';
							}
						} else {
							echo 'display: none';
						}
						?>'>
							<th scope="row">Assignee URL</th>
							<td><?php $this->ASN_field( "" ) ?></td>
						</tr>

						<tr class="tib-mode advanced">
							<th scope="row">Use Assignee? <span class="use-asn-tip">(If this looks unfamiliar,
                                leave it set to 'No')</span></th>
							<td><?php $this->tib_mode_field( "" ) ?></td>

						</tr>


						<?php $this->DUR_field( "" ); ?>


								<?php echo $this->bd_display_colour_picker(); ?>


					</table>
					<hr>
					<h3>Posts</h3>

					<table class="form-table bd">

						<?php $this->append_to_content_field(); ?>

						<tr class="post-footer-styling">
							<th scope="row">Button Style</th>
							<td>
								<?php $this->tib_button_field( array( 'horiz', 'vert', 'poster'), 'post'  ); ?>
							</td>
						</tr>

						<?php echo $this->bd_display_scale_picker( 'post' ); ?>

						<tr class="post-footer-styling">
							<th scope="row">Caption</th>
							<td>
								<?php echo $this->caption_field(); ?>
							</td>
						</tr>

						<tr class="post-footer-styling">
							<th scope="row">Preview</th>
							<td>
								<?php echo $this->button_preview('post'); ?>
							</td>
						</tr>


					</table>

					<hr>

					<h3>Widgets</h3>

					<table class="form-table bd">

						<tr>
							<th scope="row">Button Style</th>
							<td>
								<?php $this->tib_button_field( array(
									'shadow',
									'default',
									'poster',
									'bubble')
								, 'widget' ); ?>
							</td>
						</tr>

					</table>

					<hr>

					<h3>Shortcodes</h3>

					<table class="form-table bd">

						<tr>
							<th scope="row">Button Style</th>
							<td>
								<?php $this->tib_button_field( array(
									'shadow',
									'default',
									'horiz',
									'vert'), 'shortcode' ); ?>
							</td>
						</tr>


						<?php echo $this->bd_display_scale_picker( 'shortcode' ); ?>




					</table>

					<hr>
				<?php
				submit_button( 'Save Changes', 'primary', 'submit', false );
				echo( "&emsp;" );
				echo $this->show_hide_advanced_button();


				echo "<script>PAD.onchange();</script>";
				echo "</form></div>";
			} elseif ( $_GET['tab'] == 'customise_button' ) {

				echo "<form method='post' action='options.php' class='bd';>";

				echo "<div class='wrap'>";
				echo "<h2>" . $tabs['customise_button'] . "</h2>";

				echo "<form method='post' action='options.php' class='bd'>";

				?>


				<table class="form-table">


				</table>
				<?php
				submit_button( 'Save Changes', 'primary', 'submit', false );
				echo "</form></div>";
			} elseif ( $_GET['tab'] == 'list' ) {
				$this->list_section();
			} elseif ( $_GET['tab'] == "balance" ) {
				$PAD = $this->options['PAD'];
				if ( $this->is_test_net( $this->options['PAD'] ) ) {
					$testmode = 'testnet';
				} else {
					$testmode = 'blockchain';
				}
				echo '<iframe width="1000" height="700" src="https://www.biteasy.com/' . $testmode . '/addresses/' . $PAD . '"> </iframe>';
			}
			?>

			<?php
		}

		// If $option matches $value then output display: none, otherwise, return empty string
		function option_conditional_display( $option, $value ) {
			if ( $option === $value ) {
				return 'display: none;';
			} else {
				return '';
			}
		}

		function list_section() {
			bd_log( "||ADM list section" );
			// $qargs = array()

			echo( "<table class='widefat'><tr><th>title</th><th>id</th><th>tibs received</th></tr>" );

			$alloptions = wp_load_alloptions();
			$options    = get_option( 'tibdit_options' );

			if ( isset( $options['TIB_QTYs'] ) && $options['TIB_QTYs'] ) {
				foreach ( $options['TIB_QTYs'] as $SUB => $QTY ) {
					echo( "<tr><td>" );

					if ( strstr( $SUB, "WP_ID_" ) ) {
						$ID = $SUB;
						$ID = str_replace( 'WP_ID_', '', $ID );
						echo get_the_title( $ID );
					} else {
						echo 'n/a';
					}
					echo( "</td><td>" );
					echo $SUB;
					echo( "</td><td>" );
					echo $QTY;
				}
			}
			echo( "</table><br><br>" );
		}

		// Output script to
		function show_advanced($class){
			echo '<script>';

			echo '
				jQuery(document).ready(function($){
					targetClass = $("' . $class .'");
					targetClass.show();
				});
			';

			echo '</script>';
		}

		function show_hide_advanced_button() {
			$tibdit_options = get_option( 'tibdit_options' );
			$value          = $tibdit_options['advanced_settings'];
			echo "
            <script>
                function advancedTrigger(val){

                    document.getElementById('advanced_settings').value = val;
                    if(val === false){
                    	jQuery('.wrap.show-advanced').removeClass('show-advanced').addClass('hide-advanced');
                    }
                    if(val === true){
                    	jQuery('.wrap.hide-advanced').removeClass('hide-advanced').addClass('show-advanced');
                    }
                }
            </script>
            ";

			$advanced_settings_button = '<div class="button-secondary simple show-advanced" onclick="advancedTrigger(true)">';
			$advanced_settings_button .= 'Show Advanced Settings';
			$advanced_settings_button .= '</div>';



			$advanced_settings_hidden_field .= "<input type='hidden' id='advanced_settings' name='$this->settings_field[advanced_settings]' ";
			$advanced_settings_hidden_field .= " value='" . $this->options['advanced_settings'] . "'  />";

			echo '<div class="advanced-' . $this->options['advanced_settings'] . ' advanced-button-container">';

			echo $advanced_settings_button;
			echo $advanced_settings_hidden_field;

			echo '</div>';
		}

		function PAD_field( $args ) {
			$slug  = "PAD";
			$value = $this->options[ $slug ];

			$plugurl = plugin_dir_url( __FILE__ );

			echo "<input id='$slug' name='$this->settings_field[$slug]' value='$value'
                class='bd' type='text' size=36 maxlength=36 onchange='bd_PAD_change(this, \"$plugurl\");'
                onkeypress='this.onchange();' onpaste='this.onchange();' oninput='this.onchange();'  >";

			echo "<span class='bd status' id='PAD_field_status'>&emsp;?</span>";
			?> <?php

		}

		function ASN_field( $args ) {
			$slug = "ASN";
			if ( isset( $this->options[ $slug ] ) && $this->options[ $slug ] ) {
				$value = $this->options[ $slug ];
			} else {
				$value = '';
			}

			echo "<input id='$slug' name='$this->settings_field[$slug]' value='$value' size=36 class='bd'
type='text'>";
		}

		function tib_mode_field() {
			$slug = "tib_mode";
			if ( isset( $this->options[ $slug ] ) && $this->options[ $slug ] ) {
				$value = $this->options[ $slug ];
			} else {
				$value = 'PAD';
			}

			echo '<div class="tib-mode">';

			echo "<input id='ASN-radio' onchange='tibModeSelector(this)' name='$this->settings_field[$slug]' value='ASN'";
			if ( $value == 'ASN' ) {
				echo 'checked="checked"';
			}
			echo "class='bd' type='radio'>";
			echo "<label for='ASN-radio'  class='bd-radio-label'>Yes</label>";

			echo "<input id='PAD-radio' onchange='tibModeSelector(this)' name='$this->settings_field[$slug]' value='PAD' ";
			if ( $value == 'PAD' ) {
				echo 'checked="checked"';
			}
			echo " class='bd' type='radio'>";
			echo "<label for='PAD-radio' class='bd-radio-label'>No</label>";

			echo "
                <script>
                function tibModeSelector(element){
                    selected = element.getAttribute('value');
                    selectedColumn = '.' + selected + '.tib-mode-row';
                    jQuery('.tib-mode-row').hide();
                    jQuery(selectedColumn).show();
                }
                </script>
            ";

			echo '</div>';
			if($value === 'ASN'){
				$this->show_advanced('.tib-mode');
			}

		}

		function DUR_field( $args ) {
			$slug  = "DUR";
			$value = $this->options[ $slug ];

			echo '<tr class="advanced';
			if($value != '1'){
				echo 'override-show-advanced-in-simple';
			}
			echo '">';
			echo '<th scope="row">Acknowledge tib for</th>';
			echo '<td>';

			echo "<input id='$slug' name='$this->settings_field[$slug]' value='$value'
                class='bd' type='number' min='1' max='30' step='1'  >";

			echo "&emsp;days &emsp;(or minutes if a testmode / testnet address)";

			echo '</td>';
			echo '</tr>';
		}

		function append_to_content_field( $before = true, $after = true ) {
			$tibdit_options = get_option( 'tibdit_options' );
			$before_slug    = "append_before_content";
			$before_value   = $tibdit_options[ $before_slug ];
			$after_slug     = "append_after_content";
			$after_value    = $tibdit_options[ $after_slug ];
			$single_only_slug = "append_only_on_single";
			$single_only_value = $tibdit_options[$single_only_slug];

			echo '<tr>';
			echo '<th scope="row">Enable</th>';
			echo '<td>';

			echo "<script>

				function appendOnSingleOnChange(checkbox){
					jQuery( 'input[type=hidden].' + checkbox.className).val(checkbox.checked);
				}

				function appendFieldOnChange(checkbox){
					jQuery( 'input[type=hidden].' + checkbox.className).val(checkbox.checked);
					var appendParentTable = jQuery('#append-after-radio').parents('.form-table.bd');

					// If either post footer or header is enabled #23282d
					if(jQuery('#append-before-radio:checked')[0] || jQuery('#append-after-radio:checked')[0]){

						appendParentTable.addClass('append-enabled');
						appendParentTable.removeClass('append-disabled');

						// enable inputs + text area
						appendParentTable.find('input, textarea').not('.bd-append-before, .bd-append-after').prop('disabled',
						false);
						// return preview-container to original color
						appendParentTable.find('.preview-container .bd-flex').css('background', '#fff').css('color', '#000');
						appendParentTable.find('.preview-container .bd-flex button .bd-btn-backdrop').css('fill',
						jQuery('#base_colour').val());
						appendParentTable.find('.button-container .bd-btn-backdrop').css('fill', '#3cdd72');
						appendParentTable.find('.post-footer-styling th').css('color', '#23282d');
					}
					// else - neither is checked
					else{
						appendParentTable.addClass('append-disabled');
						appendParentTable.removeClass('append-enabled');

						// disable inputs + text area
						appendParentTable.find('input, textarea').not('.bd-append-before, .bd-append-after').prop('disabled', true);
						// grey out preview box to match inputs
						appendParentTable.find('.preview-container .bd-flex').css('background', 'rgba(255,255,255,.5)').css('color', '#999');
						appendParentTable.find('.bd-btn-backdrop').css('fill',
						'#999');
						appendParentTable.find('.post-footer-styling th').css('color', '#999');
					}
				}

				jQuery(document).ready(function($){
					// trigger the change event on page load to check if enabled or not
					$('#append-after-radio').trigger('change');

				});
				</script>
			";


				echo '<span class="append-before-container advanced';
				if($before_value === "true"){
					echo ' override-show-advanced-in-simple';
				}
				echo '">';
				echo "<input id='append-before-radio' value='before'  " . checked( $before_value, 'true', false );
				echo "class='bd-append-before";
				echo "' type='checkbox' onchange='appendFieldOnChange(this)'>";
				echo "<label for='append-before-radio' class='bd-radio-label'>Show in Post Header</label>";
				echo "<input type='hidden' class='bd-append-before' name='$this->settings_field[$before_slug]'
				value='$before_value'>";
				echo '</span>';

				echo '<span class="append-after-container';
				if($after_value === "true"){
					echo ' override-advanced';
				}
				echo '">';
				echo "<input id='append-after-radio' value='after'  " . checked( $after_value, 'true', false );
				echo "class='bd-append-after";
				echo "' type='checkbox' onchange='appendFieldOnChange(this)'>";
				echo "<label for='append-after-radio' class='bd-radio-label'>Show in Post Footer</label>";
				echo "<input type='hidden' class='bd-append-after' name='$this->settings_field[$after_slug]'
				value='$after_value'>";
				echo '</span>';




				echo '<span class="append-on-single-only-container advanced';
				if($single_only_value === "true"){
					echo ' override-show-advanced-in-simple';
				}
				echo '">';
				echo "<input id='append-on-single-only-check' value='after'  " . checked( $single_only_value, 'true', false );
				echo "class='bd-append-on-single-only";
				echo "' type='checkbox' onchange='appendOnSingleOnChange(this)'>";
				echo "<label for='append-on-single-only-check' class='bd-radio-label'>Show On Single Posts Only</label>";
				echo "<input type='hidden' class='bd-append-on-single-only' name='$this->settings_field[$single_only_slug]'
					value='$single_only_value'>";
				echo '</span>';

			echo '</td>';
			echo '</tr>';

		}

		function caption_field() {
			$slug  = "caption";
			$value = $this->options[ $slug ];

			$caption_input = "<textarea id='$slug' name='$this->settings_field[$slug]'
                class='bd' type='text' cols='36' rows='5' style='resize: none'>";
			$caption_input .= $value;
			$caption_input .= "</textarea>";
			$caption_input .= '
			<script>
			var slug = "' . $slug . '";
			jQuery("#" + slug).bind("input propertychange", function() {
			      if(this.value.length){
			        jQuery(".post.preview-container .bd-side-text").html(this.value);
			      }
			});
			</script>
			';
			echo $caption_input;
		}

		function widget_colour( $args ) {
			$slug  = "widget_colour";
			$value = $this->options[ $slug ];

			echo "<input id='$slug' name='$this->settings_field[$slug]' value='$value'
                class='bd bd-colourp' type='text' data-default-color='$value' >";
		}

		/**
		 * Displays images and radio buttons
		 * $buttons is an array containing the names of the buttons to be added
		 */
		function tib_button_field( $buttons = null, $target = null ) {
			$buttons = ( $buttons ? $buttons : $this->svg_button_names );
			// This section is for the setup
			include_once( "button-factory/ButtonFactory.php" );
			$slug  = "BTN";
			$value = $this->options[ $slug ];

			// This would be used to decide on how many buttons should be shown in a row (<br> tag would be added to the images)

			$bd_output = ""; // This would display the radio buttons and images

			// Gets the name of the stored button
			$options     = get_option( "tibdit_options" );  // get_option( $this->settings_field );
			$bd_selected = $options['BTN'];
			if ( isset( $target ) ) {
				$bd_selected = $options[ $target ]['BTN'];
			}
			// Checks that the named button is in the svg array
			$bd_button_exists = in_array( $bd_selected, $this->svg_button_names );

			for ( $i = 0; $i < count( $buttons ); $i ++ ) {
				$instance = array();
//              TODO Add logic to automatically default values + block overwrite unset $instance params
				// Find index of current button name in class array
				$index = array_search( $buttons[ $i ], $this->svg_button_names );
				// Set BTN based on index within svg_button_names determined above
				$instance['BTN'] = $buttons[ $i ];
				$instance['BTC'] = '#3cdd72'; // tibit green for consistency
				$instance['BTH'] = $this->svg_button_heights[$instance['BTN']];

				$mybutton = ButtonFactory::make_button( $instance );

				$bd_output .= "<li>";
				$bd_output .= "<input id='" . $target . $buttons[ $i ] . "'";
				$bd_output .= "class='button-radio'";
				$bd_output .= "type='radio'";
				if ( $target ) {
					$bd_output .= "name='$this->settings_field[$target][$slug]'";
				} else {
					$bd_output .= "name='$this->settings_field[$slug]'";
				}
				$bd_output .= "value='" . $this->svg_button_names[ $index ] . "'";

				if ( $bd_button_exists ) {
					if ( $this->svg_button_names[ $index ] == $bd_selected ) {
						$bd_output .= " checked ";
					}
				}

				$bd_output .= "id='{$slug}{$i}' >";
				$bd_output .= $mybutton->render();
				$bd_output .= "<label for='" . $target . $buttons[ $i ] . "' ><div
                class='label-background-div'></div></label>";
				$bd_output .= "</li>";

				$bd_output .= "<script>
				jQuery(document).ready(function($){

					var target = '" . $target ."';
					var targetSuffix;
					if(target){
						targetSuffix = '-' + target;
					}
					else{
						targetSuffix = '';
					}

					// Set change event on current input
					$('.button-radio#' + '" . $target . "' + '" . $this->svg_button_names[ $index ] . "').change(function(){
						// Execute if the current radio input has been checked
						if($(this)[0].checked){
							// Find parent table
							var parentTable = $(this).parents('.form-table.bd');
							// Find previewContainer within this table
							var previewContainer = parentTable.find('.preview-container');
							// Clone the button that has been selected
							var buttonClone = $(this).parent().find('button').clone();
							// Change button clones colour to match the current value of our colour picker
							buttonClone.find('.bd-btn-backdrop').css('fill', $('#base_colour').val());
							var scaleContainer = parentTable.find('.scale-container');
							// Replace button in previewContainer with the newly selected button
							previewContainer.find('button').replaceWith(buttonClone);

							// Reset height selector to default height for the selected button
							if(scaleContainer.length){
								scaleContainer.find('input[type=radio]').val(buttonClone.height());
								scaleContainer.find('#custom-button-scale-number' + targetSuffix).val(buttonClone.height());

								scaleContainer.find('#standard-button-scale-radio' + targetSuffix).prop('checked', true).trigger('change');
							}

						}
					})

				});
				</script>";
			}

			echo "<div class='button-container'>";
			echo $bd_output;
			echo "</div>";
			echo "<script>
                jQuery(document).ready(function($){
                    // Removing onclick events coming from button factory
                   $('.bd-tib-btn svg').off('click');

                });
			</script>";
		}

		function button_preview( $location = null ) {
			$options = get_option( $this->settings_field );

			$instance = get_button_params( $options );

			if ( $location !== null ) {
				$instance = wp_parse_args( get_button_params( $options[ $location ] ), $instance );
			}

			$button = tib_button( $instance );

			$caption = '';
			if ( $location === 'post' ) {
				$caption = $options['caption'];
			}

			$button        = '<div class="bd-flex-item">' . $button . '</div>';
			$caption       = '<div class="bd-flex-item bd-side-text" style="">' . $caption . '</div>';
			$button_output = '<div class="bd-flex postbox">' . $button . $caption . '</div>';
			echo '<div class="preview-container' . ($location ? ' ' . $location : '') . '">';
			echo $button_output;
			echo '</div>';
		}

		private function rgb_to_hex( $rgbstring ) {
			// function to convert RGB string to Hex

			$rgb = sscanf( $rgbstring, "rgb(%d, %d, %d)" );
			$hex = "#";
			$hex .= str_pad( dechex( $rgb[0] ), 2, "0", STR_PAD_LEFT );
			$hex .= str_pad( dechex( $rgb[1] ), 2, "0", STR_PAD_LEFT );
			$hex .= str_pad( dechex( $rgb[2] ), 2, "0", STR_PAD_LEFT );

			return $hex; // returns the hex value including the number sign (#)
		}

		// This would display the colour picker and hooks for changing the svg buttons
		function bd_display_colour_picker() {
			$bd_options      = get_option( 'tibdit_options' );
			$slug            = 'BTC';
			$value           = $bd_options[ $slug ];
			if($value === '#3cdd72' || $value === '#2e71a8' || $value === '#000000'){
				$simple = true;
			}
			else{
				$simple = false;
			}
			$html = '';
			$html .= '<tr><th scope="row"><span class="advanced">Colour</span><span class="simple">Button Face Colour</span></th><td>';



				$html .= "<div class='simple colour-picker-container";
				// If a value outside of the 3 simple values is saved, add override class to hide simple picker
				$html .= ($simple === false ? ' override-hide-simple-in-simple' : '');
				$html .= "'>";
				$html .= "<input id='btc-green'";
				$html .= "name='btc-simple'";
				$html .= "value='#3cdd72'";
				$html .= checked( '#3cdd72', $value, false );
				$html .= "class='bd colour-radio colour' type='radio'>";
				$html .= "<label for='btc-green' class='bd-radio-label'>";
				$html .= "<div class='palette-simple'";
				$html .= "style='background-color: #3cdd72'";
				$html .= "></div>";
				$html .= "</label>";

				$html .= "<input id='btc-blue'";
				$html .= "name='btc-simple'";
				$html .= "value='#2e71a8'";
				$html .= checked( '#2e71a8', $value, false );
				$html .= "class='bd colour-radio' type='radio'>";
				$html .= "<label for='btc-blue' class='bd-radio-label'>";
				$html .= "<div class='palette-simple'";
				$html .= "style='background-color: #2e71a8'";
				$html .= "></div>";
				$html .= "</label>";

				$html .= "<input id='btc-black'";
				$html .= "name='btc-simple'";
				$html .= "value='#000000'";
				$html .= checked( '#000000', $value, false );
				$html .= "class='bd colour-radio' type='radio'>";
				$html .= "<label for='btc-black' class='bd-radio-label'>";
				$html .= "<div class='palette-simple'";
				$html .= "style='background-color: #000'";
				$html .= "></div>";
				$html .= "</label>";

				$html .= "</div>";
				?>

				<script>
					jQuery(document).ready(function ($) {
						$('.bd.colour-radio').change(function () {
							// Changing the colour of the button elements on the page
							$(".preview-container .bd-btn-backdrop").css("fill", $(this).val());
							// Setting the value of the hidden colour field to be submitted to wordpress options
							$('#base_colour').val($(this).val());
							// Setting the advanced picker in case the user switches to advanced view
							$('#spectrum_1').wpColorPicker('color', $(this).val())
						})
					});
				</script>

				<?php



				$html .= '<div class="outerwrapper-colour-picker advanced';
				$html .= ($simple === false ? ' override-show-advanced-in-simple' : '');
				$html .= '">';
				$html .=  '<div class="new_spectrum_1 innerwrapper-colour-picker">';
				$html .= '<input type="text" id="spectrum_1" name="spectrum_1" value="' . $value . '"';
				$html .= 'data-default-color="' . $value . '"/>';
				$html .= '</div>';
				$html .= '</div>';

				$hidden_field = "";

				$hidden_field .= "<input type='hidden' id='base_colour' name='$this->settings_field[BTC]' ";
				$hidden_field .= " value='" . bd_set_colour_value( 'BTC' ) . "'  />";
				echo $hidden_field;
			

			$html .= '</td></tr>';
			echo $html;
		}

		function bd_display_scale_picker( $target = null ) {
			$options = get_option( "tibdit_options" );
			$value   = ( $options[ $target ]['BTH'] ? $options[ $target ]['BTH'] : $options['BTH'] );
			if ( $target ) {
				$field_name = $this->settings_field . '[' . $target . ']' . '[BTH]';
				$container_id = 'scale-container-' . $target;
			} else {
				$field_name = $this->settings_field . '[BTH]';
				$container_id = 'scale-container';
			}
			$default_heights        = $GLOBALS['svg_button_heights'];
			$current_btn            = ( $options[ $target ]['BTN'] ? $options[ $target ]['BTN'] : $options['BTN'] );
			$current_default_height = $default_heights[ $current_btn ];
			$using_custom = ($value != $current_default_height ? true : false);


			?>


			<?php

			$default_number_field = '<span class="scale-input-container standard">';
			$default_number_field .= '<input type="radio"';
			$default_number_field .= 'id="standard-button-scale-radio' . ($target ? '-' . $target : '') . '"';
			$default_number_field .= 'name="height-radio' . ($target ? '-' . $target : '') . '"';
			$default_number_field .= 'value="' . $current_default_height . '"';
			$default_number_field .= checked($value, $current_default_height, false);
			$default_number_field .= '>';
			$default_number_field .= '<label ';
			$default_number_field .= 'for="standard-button-scale-radio' . ($target ? '-' . $target : '') . '"';
			$default_number_field .= '>Standard</label>';
			$default_number_field .= '</span>';

			$custom_number_field = '<span class="scale-input-container custom">';
			$custom_number_field .= '<input type="radio"';
			$custom_number_field .= 'name="height-radio' . ($target ? '-' . $target : '') . '"';
			$custom_number_field .= 'id="custom-button-scale-radio' . ($target ? '-' . $target : '') . '"';
			$custom_number_field .= checked($using_custom, true, false);
			$custom_number_field .= '>';
			$custom_number_field .= '<label ';
			$custom_number_field .= 'for="custom-button-scale-radio' . ($target ? '-' . $target : '') . '"';
			$custom_number_field .= '>Custom</label>';
			$custom_number_field .= '<input type="number" ';
			$custom_number_field .= 'min="15" max="80" step="1"';
			$custom_number_field .= 'name="' . $field_name . '"';
			$custom_number_field .= 'id="custom-button-scale-number' . ($target ? '-' . $target : '') . '"';
			$custom_number_field .= 'value="' . $value . '">';
			$custom_number_field .= '<span>px</span>';
			$custom_number_field .= '</span>';

			$hidden_field = "<input type='hidden'  value='" . $options['BTH'] . "'";
			$hidden_field .= "name='";

			$hidden_field .= "'";
			$hidden_field .= "/>";

			$scale_script = '
				<script>

				jQuery(document).ready(function($){

					var scaleContainer = $("#'. $container_id .'");
					var target = "'. $target .'";
					var targetSuffix;
					if(target){
						targetSuffix = "-" + target;
					}
					else{
						targetSuffix = "";
					}

					if(scaleContainer.find("#standard-button-scale-radio" + targetSuffix).prop("checked")  === true){
							scaleContainer.find("#custom-button-scale-number"  + targetSuffix).prop("readonly", true);
					}

					scaleContainer.find("#standard-button-scale-radio"  + targetSuffix).change(function(){
						if($(this).prop("checked") === true){

							scaleContainer.find("#custom-button-scale-number"  + targetSuffix).prop("readonly", true)
							.val($(this).val());

							// Find containing table in parents
							var parentTable = $(this).parents(".form-table.bd");
							// Find previewContainer within containing table
							var previewContainer = parentTable.find(".preview-container");
							// if previewContainer is found, amend the height to match the custom height specified
							if(previewContainer.length){
								previewContainer.find("button").height($(this).val());
							}
						}
					});

					scaleContainer.find("#custom-button-scale-radio"  + targetSuffix).change(function(){
						if($(this).prop("checked") === true){
							scaleContainer.find("#custom-button-scale-number"  + targetSuffix).prop("readonly", false);
						}

					});
					scaleContainer.find("#custom-button-scale-number"  + targetSuffix).click(function(){
						scaleContainer.find("#custom-button-scale-radio" + targetSuffix).prop("checked", "true").trigger("change");
					});
					scaleContainer.find("#custom-button-scale-number" + targetSuffix).on( "change keyup",  function(){
						// Match radio buttons value to value of number field - the radio value is what is submitted to the options array
						$("#custom-button-scale-radio" + targetSuffix).val($(this).val());
						// Find containing table in parents
						var parentTable = $(this).parents(".form-table.bd");
						// Find previewContainer within containing table
						var previewContainer = parentTable.find(".preview-container");
						// if previewContainer is found, amend the height to match the custom height specified
						if(previewContainer.length){
							previewContainer.find("button").height($(this).val());
						}
					});
				});

				</script>
				';

				echo '<tr class="advanced ' . ($using_custom === true ? 'override-show-advanced-in-simple' :
						'');
				echo '"><th scope="row">Height</th><td>';

				echo '<div class="scale-container';
				echo '" id="'. $container_id .'">';
				echo $scale_script;
				echo $default_number_field;
				echo $custom_number_field;
				echo $hidden_field;
				echo '</div>';

				echo '</td></tr>';

		}

		function add_admin_menu() {
			bd_log( "add admin menu" );
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// $this->pagehook = $page =  add_options_page( $this->page_title, 'tibdit', 'manage_options', $this->page_id, array($this,'render') );
			$this->help_hook = add_options_page( $this->page_title, 'tibit', 'manage_options', $this->page_id, array(
				$this,
				'render'
			) );
		}

		private
		function sanitiser_setter(
			$key1, $opts_in, $default, $key2 = null
		) {
// checks the incoming array for the specified option key. If it is set, the value is returned. If it isn't, the
//    corresponding value is retrieved from the existing options and this is returned instead

			if ( ! isset( $key2 ) ) {
				if ( isset( $opts_in[ $key1 ] ) ) {
					bd_log( "||ADM sanitiser_setter " . var_export( $opts_in[ $key1 ], true ) );

					return $opts_in[ $key1 ];
				} elseif ( isset( $this->options[ $key1 ] ) ) {
					bd_log( "||ADM sanitiser_setter " . var_export( $this->options[ $key1 ], true ) );

					return $this->options[ $key1 ];
				} else {
					bd_log( "||ADM sanitiser_setter " . var_export( $default[ $key1 ], true ) );

					return $default[ $key1 ];

				}
			} // If $key2 is set, this means that $key1 is an array, and $key2 is an element within this array
			else {
				if ( isset( $opts_in[ $key1 ][ $key2 ] ) ) {
					bd_log( "||ADM sanitiser_setter " . var_export( $opts_in[ $key1 ][ $key2 ], true ) );

					return $opts_in[ $key1 ][ $key2 ];
				} elseif ( isset( $this->options[ $key1 ][ $key2 ] ) ) {
					bd_log( "||ADM sanitiser_setter " . var_export( $this->options[ $key1 ][ $key2 ], true ) );

					return $this->options[ $key1 ][ $key2 ];
				} else {
					bd_log( "||ADM sanitiser_setter " . var_export( $default[ $key1 ][ $key2 ], true ) );

					return $default[ $key1 ][ $key2 ];

				}
			}

		}

		function sanitise( $opts_in ) // Sanitize our plugin settings array as needed.
		{
			// TODO Cleanup to use PHP array defaulting.
			bd_log( "Options in " . var_export( $opts_in, true ) );

			bd_log( "||ADM sanitise: POST " . var_export( $_POST, true ) );

			static $new_options = array();

			if ( isset( $_POST['list'] ) ) {
				bd_log( "||ADM sanitise: list !!!!" );
				update_option( 'tib_list', true );   //persist request for list of tibs through page multiple refreshes
			}


			$new_options['BTC']              = $this->sanitiser_setter( 'BTC', $opts_in, $this->default_settings );
			$new_options['BTH']              = $this->sanitiser_setter( 'BTH', $opts_in, $this->default_settings );
			$new_options['shortcode']['BTH'] = $this->sanitiser_setter( 'shortcode', $opts_in,
				$this->default_settings, 'BTH' );
			$new_options['post']['BTH']      = $this->sanitiser_setter( 'post', $opts_in,
				$this->default_settings, 'BTH' );

			$new_options['ASN'] = $this->sanitiser_setter( 'ASN', $opts_in, $this->default_settings );

			$new_options['BTN']              = $this->sanitiser_setter( 'BTN', $opts_in, $this->default_settings );
			$new_options['shortcode']['BTN'] = $this->sanitiser_setter( 'shortcode', $opts_in,
				$this->default_settings, 'BTN' );
			$new_options['post']['BTN']      = $this->sanitiser_setter( 'post', $opts_in,
				$this->default_settings, 'BTN' );
			$new_options['widget']['BTN']      = $this->sanitiser_setter( 'widget', $opts_in,
				$this->default_settings, 'BTN' );

			$new_options['advanced_settings'] = 'false';
			$new_options['tib_mode']          = $this->sanitiser_setter( 'tib_mode', $opts_in, $this->default_settings );
			$new_options['append_before_content'] = $this->sanitiser_setter( 'append_before_content', $opts_in,
				$this->default_settings );
			$new_options['append_after_content']  = $this->sanitiser_setter( 'append_after_content', $opts_in,
				$this->default_settings );

			$new_options['append_only_on_single']  = $this->sanitiser_setter( 'append_only_on_single', $opts_in,
				$this->default_settings );

			$new_options['last_known_version']    = $this->options['last_known_version'];
			if ( isset( $opts_in['last_known_version'] ) ) {
				$new_options['last_known_version'] = $opts_in['last_known_version'];
			}

			$new_options['TIB_QTYs'] = $this->options['TIB_QTYs'];

			/* If the ASN, PAD, or tib_mode has changed, we want to wipe the TIB_QTYs array so that counters
				don't carry over between different PADs or ASNs */
			if($opts_in['ASN'] != $this->options['ASN']){
				$new_options['TIB_QTYs'] = $this->default_settings['TIB_QTYs'];
			}
			if($opts_in['PAD'] != $this->options['PAD']){
				$new_options['TIB_QTYs'] = $this->default_settings['TIB_QTYs'];
			}
			if($opts_in['tib_mode'] != $this->options['tib_mode']){
				$new_options['TIB_QTYs'] = $this->default_settings['TIB_QTYs'];
			}


			if ( isset( $opts_in['caption'] ) ) {
				$new_options['caption'] = wp_kses_post( $opts_in['caption'] );
			} else if ( isset( $this->options['caption'] ) ) {
				$new_options['caption'] = $this->options['caption'];
			} else {
				$new_options['caption'] = $this->default_settings['caption'];
			}

			$new_options['title'] = $this->default_settings['title'];
			$new_options['intro'] = $this->default_settings['intro'];
//$new_options['BTC']= tibdit_settings::$default_settings['BTC'];
//$new_options['bd_colour_two']= tibdit_settings::$default_settings['bd_colour_two'];
//$new_options['bd_button'] = tibdit_settings::$default_settings['bd_button'];
//$new_options['BTH'] = tibdit_settings::$default_settings['BTH'];

			bd_log( "|ADM sanitise: default scale:  " . var_export( $this->default_settings, true ) );

			bd_log( "||ADM sanitise: current options dump" . var_export( $this->options, true ) );
//$new_options['BTH']= $opts_in['bd_colour_two'];

			if ( isset( $opts_in['PAD'] ) && strlen( $opts_in['PAD'] ) > 2 ) {
				if ( AddressValidator::typeOf( $opts_in['PAD'] ) ) {
					$new_options['PAD'] = $opts_in['PAD'];
				} else {
					$new_options['PAD'] = "";
				}
			} elseif ( $opts_in['PAD'] = "" ) {
				$new_options['PAD'] = "";
			} else {
				$new_options['PAD'] = $this->options['PAD'];
			}

			if ( isset( $opts_in['DUR'] ) ) {
				if ( intval( $opts_in['DUR'] ) > 0 && intval( $opts_in['DUR'] ) < 31 ) {
					$new_options['DUR'] = intval( $opts_in['DUR'] );
				} else {
					$new_options['DUR'] = $this->default_settings['DUR'];
				}
			} else {
				$new_options['DUR'] = $this->default_settings['DUR'];
			}


// if( isset($opts_in['widget_colour']))
//   $new_options['widget_colour'] = ($opts_in['widget_colour']);
// else
//   $new_options['widget_colour']= tibdit_settings::$default_settings['widget_colour'];

			bd_log( "||ADM sanitise: options " . var_export( $opts_in, true ) );
			bd_log( "||ADM sanitise: new_options " . var_export( $new_options, true ) );

			return $new_options;
		}


		/**
		 * @param $PAD
		 *
		 * @return bool
		 */
		function is_test_net( $PAD ) {
			if ( substr( $PAD, 0, 1 ) == 'm' or substr( $PAD, 0, 1 ) == 'n' ) {
				return true;
			} else {
				return false;
			}
		}


		function render() {
			// if (! current_user_can('manage_options'))
			//   wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			// $mode="";
			if ( $this->is_test_net( $this->options['PAD'] ) ) {
				$mode = "/testnet";
			}
			$plugurl = plugin_dir_url( __FILE__ );
			bd_log( "admin render" );
//      echo "<div class='wrap'>";
//      echo "<h2>$this->page_title</h2>";
			echo "<form method='post' action='options.php' class='bd' id='main-settings-form'>";
			settings_fields( $this->settings_field );
			do_settings_sections( $this->page_id );
//      submit_button( 'Save Changes', 'primary', 'submit', false);
//      echo("&emsp;");
//      submit_button( 'list tib counts', 'secondary', 'list', false, array( 'onclick' => "{}" ));
//      submit_button( 'balance', 'secondary', 'blockchain', false, array( 'onclick' => "{biteasy_blockchain();}"));
//
//      echo "<script>payaddr.onchange();</script>";
//      echo "</form></div>";


		}
	} // end class
} // end if