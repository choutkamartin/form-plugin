<?php
/*
Plugin Name: Kontaktní formulář
Plugin URI: https://github.com/choutkamartin/form-plugin
Description: Jednoduchý plugin pro kontatktní formulář
Version: 1.0
Author: Martin Choutka
Author URI: https://martinchoutka.cz/
*/

function form_code() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'Vaše jméno *<br/>';
	echo '<input type="text" class="form-control" name="fullname" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["fullname"] ) ? esc_attr( $_POST["fullname"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Vaše e-mailová adresa *<br/>';
	echo '<input type="email" class="form-control" name="email" value="' . ( isset( $_POST["email"] ) ? esc_attr( $_POST["email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Předmět *<br/>';
	echo '<input type="text" class="form-control" name="subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["subject"] ) ? esc_attr( $_POST["subject"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Vaše zpráva *<br/>';
	echo '<textarea rows="10" class="form-control" cols="35" name="message">' . ( isset( $_POST["message"] ) ? esc_attr( $_POST["message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<button class="btn btn-primary" type="submit" name="submitted">Odeslat</button>';
	echo '</form>';
}

function deliver_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["fullname"] );
		$email   = sanitize_email( $_POST["email"] );
		$subject = sanitize_text_field( $_POST["subject"] );
		$message = esc_textarea( $_POST["message"] );

		// get the blog administrator's email address
		$to = get_option( 'admin_email' );

		$headers = "From: $name <$email>" . "\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div class="alert alert-success" role="alert">';
			echo 'Děkuji za kontaktování, brzy se vám ozvu';
			echo '</div>';
		} else {
			echo '<div class="alert alert-danger" role="alert">';
			echo 'Nastala neznámá chyba';
			echo '</div >';
		}
	}
}

function form_shortcode() {
	ob_start();
	deliver_mail();
	form_code();

	return ob_get_clean();
}

add_shortcode( 'form-plugin', 'form_shortcode' );

?>
