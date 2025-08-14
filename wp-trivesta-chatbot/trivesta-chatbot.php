<?php
/*
Plugin Name: Trivesta Chatbot (n8n)
Description: Embed the n8n chat widget on WordPress via a shortcode and configure it from the admin.
Version: 1.0.0
Author: Trivesta
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TRIVESTA_CHATBOT_VERSION', '1.0.0' );
function trivesta_chatbot_default_settings() {
	return array(
		'webhook_url' => '',
		'mode' => 'window',
		'show_welcome' => true,
		'initial_messages' => array( 'Hi there! 👋', 'How can I assist you today?' ),
		'load_previous' => true,
		'enable_streaming' => false,
	);
}

function trivesta_chatbot_activate() {
	$defaults = trivesta_chatbot_default_settings();
	$existing = get_option( 'trivesta_chatbot_settings' );
	if ( ! is_array( $existing ) ) {
		update_option( 'trivesta_chatbot_settings', $defaults );
	}
}
register_activation_hook( __FILE__, 'trivesta_chatbot_activate' );

function trivesta_chatbot_sanitize_settings( $input ) {
	$defaults = trivesta_chatbot_default_settings();
	$output = array();
	$output['webhook_url'] = isset( $input['webhook_url'] ) ? esc_url_raw( $input['webhook_url'] ) : $defaults['webhook_url'];
	$output['mode'] = ( isset( $input['mode'] ) && in_array( $input['mode'], array( 'window', 'fullscreen' ), true ) ) ? $input['mode'] : $defaults['mode'];
	$output['show_welcome'] = ! empty( $input['show_welcome'] );
	$messages_raw = isset( $input['initial_messages'] ) ? wp_kses_post( $input['initial_messages'] ) : '';
	$messages_list = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $messages_raw ) ) );
	$output['initial_messages'] = ! empty( $messages_list ) ? array_slice( $messages_list, 0, 10 ) : $defaults['initial_messages'];
	$output['load_previous'] = ! empty( $input['load_previous'] );
	$output['enable_streaming'] = ! empty( $input['enable_streaming'] );
	return $output;
}

function trivesta_chatbot_admin_init() {
	register_setting( 'trivesta_chatbot', 'trivesta_chatbot_settings', 'trivesta_chatbot_sanitize_settings' );

	add_settings_section( 'trivesta_chatbot_main', __( 'General', 'trivesta-chatbot' ), function () {
		echo '<p>' . esc_html__( 'Configure the n8n chat widget settings.', 'trivesta-chatbot' ) . '</p>';
	}, 'trivesta_chatbot' );

	add_settings_field( 'webhook_url', __( 'Webhook URL', 'trivesta-chatbot' ), function () {
		$options = get_option( 'trivesta_chatbot_settings', trivesta_chatbot_default_settings() );
		echo '<input type="url" name="trivesta_chatbot_settings[webhook_url]" value="' . esc_attr( $options['webhook_url'] ) . '" class="regular-text" placeholder="https://your-n8n-host/webhook/xxxx/chat" />';
	}, 'trivesta_chatbot', 'trivesta_chatbot_main' );

	add_settings_field( 'mode', __( 'Display Mode', 'trivesta-chatbot' ), function () {
		$options = get_option( 'trivesta_chatbot_settings', trivesta_chatbot_default_settings() );
		$mode = $options['mode'];
		echo '<select name="trivesta_chatbot_settings[mode]">';
		echo '<option value="window"' . selected( $mode, 'window', false ) . '>window</option>';
		echo '<option value="fullscreen"' . selected( $mode, 'fullscreen', false ) . '>fullscreen</option>';
		echo '</select>';
	}, 'trivesta_chatbot', 'trivesta_chatbot_main' );

	add_settings_field( 'show_welcome', __( 'Show Welcome Screen', 'trivesta-chatbot' ), function () {
		$options = get_option( 'trivesta_chatbot_settings', trivesta_chatbot_default_settings() );
		echo '<label><input type="checkbox" name="trivesta_chatbot_settings[show_welcome]" value="1"' . checked( ! empty( $options['show_welcome'] ), true, false ) . ' /> ' . esc_html__( 'Enable', 'trivesta-chatbot' ) . '</label>';
	}, 'trivesta_chatbot', 'trivesta_chatbot_main' );

	add_settings_field( 'initial_messages', __( 'Initial Messages', 'trivesta-chatbot' ), function () {
		$options = get_option( 'trivesta_chatbot_settings', trivesta_chatbot_default_settings() );
		$val = implode( "\n", (array) $options['initial_messages'] );
		echo '<textarea name="trivesta_chatbot_settings[initial_messages]" rows="4" cols="50" class="large-text code">' . esc_textarea( $val ) . '</textarea>';
		echo '<p class="description">' . esc_html__( 'One message per line.', 'trivesta-chatbot' ) . '</p>';
	}, 'trivesta_chatbot', 'trivesta_chatbot_main' );

	add_settings_field( 'load_previous', __( 'Load Previous Session', 'trivesta-chatbot' ), function () {
		$options = get_option( 'trivesta_chatbot_settings', trivesta_chatbot_default_settings() );
		echo '<label><input type="checkbox" name="trivesta_chatbot_settings[load_previous]" value="1"' . checked( ! empty( $options['load_previous'] ), true, false ) . ' /> ' . esc_html__( 'Enable', 'trivesta-chatbot' ) . '</label>';
	}, 'trivesta_chatbot', 'trivesta_chatbot_main' );

	add_settings_field( 'enable_streaming', __( 'Enable Streaming', 'trivesta-chatbot' ), function () {
		$options = get_option( 'trivesta_chatbot_settings', trivesta_chatbot_default_settings() );
		echo '<label><input type="checkbox" name="trivesta_chatbot_settings[enable_streaming]" value="1"' . checked( ! empty( $options['enable_streaming'] ), true, false ) . ' /> ' . esc_html__( 'Enable', 'trivesta-chatbot' ) . '</label>';
	}, 'trivesta_chatbot', 'trivesta_chatbot_main' );
}
add_action( 'admin_init', 'trivesta_chatbot_admin_init' );

function trivesta_chatbot_admin_menu() {
	add_options_page(
		__( 'Trivesta Chatbot', 'trivesta-chatbot' ),
		__( 'Trivesta Chatbot', 'trivesta-chatbot' ),
		'manage_options',
		'trivesta_chatbot',
		'trivesta_chatbot_render_settings_page'
	);
}
add_action( 'admin_menu', 'trivesta_chatbot_admin_menu' );

function trivesta_chatbot_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Trivesta Chatbot (n8n)', 'trivesta-chatbot' ) . '</h1>';
	echo '<form action="options.php" method="post">';
	settings_fields( 'trivesta_chatbot' );
	do_settings_sections( 'trivesta_chatbot' );
	submit_button();
	echo '</form>';
	echo '</div>';
}

function trivesta_chatbot_shortcode( $atts ) {
	$defaults = trivesta_chatbot_default_settings();
	$settings = get_option( 'trivesta_chatbot_settings', $defaults );

	$atts = shortcode_atts( array(
		'webhook_url' => '',
		'mode' => '',
		'show_welcome' => '',
		'initial_messages' => '',
		'load_previous' => '',
		'enable_streaming' => '',
	), $atts, 'trivesta_chatbot' );

	$instance_id = 'trivesta-chatbot-' . wp_generate_uuid4();

	$effective = array(
		'webhookUrl' => $atts['webhook_url'] ? esc_url_raw( $atts['webhook_url'] ) : ( ! empty( $settings['webhook_url'] ) ? esc_url_raw( $settings['webhook_url'] ) : '' ),
		'mode' => in_array( $atts['mode'], array( 'window', 'fullscreen' ), true ) ? $atts['mode'] : $settings['mode'],
		'showWelcomeScreen' => '' !== $atts['show_welcome'] ? (bool) $atts['show_welcome'] : (bool) $settings['show_welcome'],
		'initialMessages' => $settings['initial_messages'],
		'loadPreviousSession' => '' !== $atts['load_previous'] ? (bool) $atts['load_previous'] : (bool) $settings['load_previous'],
		'enableStreaming' => '' !== $atts['enable_streaming'] ? (bool) $atts['enable_streaming'] : (bool) $settings['enable_streaming'],
		'target' => '#' . $instance_id,
	);

	if ( ! empty( $atts['initial_messages'] ) ) {
		$messages_list = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n|\|/', (string) $atts['initial_messages'] ) ) );
		if ( ! empty( $messages_list ) ) {
			$effective['initialMessages'] = array_slice( $messages_list, 0, 10 );
		}
	}

	// Enqueue CSS once
	wp_enqueue_style( 'n8n-chat-css', 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css', array(), TRIVESTA_CHATBOT_VERSION );

	$container = '<div class="trivesta-chatbot" id="' . esc_attr( $instance_id ) . '"></div>';
	$script = '<script type="module">' .
		"import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';" .
		'const settings = ' . wp_json_encode( $effective ) . ';' .
		'if (!settings.webhookUrl) { console.warn("Trivesta Chatbot: Missing webhookUrl"); }' .
		'createChat(settings);' .
		'</script>';

	return $container . $script;
}
add_shortcode( 'trivesta_chatbot', 'trivesta_chatbot_shortcode' );
