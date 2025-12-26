<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Handles plugin settings.
 *
 * Settings are registered for future extensibility.
 * No settings UI is exposed in v1 by design to keep the plugin lightweight.
 */
class SELM_Settings {

    const OPTION_KEY = 'selm_settings';

    /**
     * Default settings
     */
    public static function get_defaults() {
        return [
            'add_nofollow' => false,
        ];
    }

    /**
     * Register settings
     */
    public static function register() {
        register_setting(
            'selm_settings_group',
            self::OPTION_KEY,
            [
                'type'              => 'array',
                'sanitize_callback' => [ __CLASS__, 'sanitize' ],
                'default'           => self::get_defaults(),
            ]
        );
    }

    /**
     * Sanitize options
     */
    public static function sanitize( $input ) {
        $defaults = self::get_defaults();

        return [
            'add_nofollow' => ! empty( $input['add_nofollow'] ),
        ] + $defaults;
    }

    /**
     * Get single option value
     */
    public static function get( $key ) {
        $options = get_option( self::OPTION_KEY, self::get_defaults() );

        return $options[ $key ] ?? self::get_defaults()[ $key ] ?? null;
    }
}
