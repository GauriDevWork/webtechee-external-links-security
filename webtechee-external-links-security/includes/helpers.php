<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get site host safely
 */
function selm_get_site_host() {
    $home_url = home_url();
    $parsed   = wp_parse_url( $home_url );

    return isset( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';
}

/**
 * Check if a URL is external
 */
function selm_is_external_url( $url ) {
    if ( empty( $url ) ) {
        return false;
    }

    // Skip anchors and protocols we don't touch
    if (
        strpos( $url, '#' ) === 0 ||
        strpos( $url, 'mailto:' ) === 0 ||
        strpos( $url, 'tel:' ) === 0
    ) {
        return false;
    }

    $site_host = selm_get_site_host();
    $parsed    = wp_parse_url( $url );

    // Relative URLs are internal
    if ( empty( $parsed['host'] ) ) {
        return false;
    }

    return strtolower( $parsed['host'] ) !== $site_host;
}
