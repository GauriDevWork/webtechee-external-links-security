<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SELM_Link_Processor {

    public function __construct() {
        add_filter( 'the_content', [ $this, 'process_external_links' ], 20 );
    }

    /**
     * Modify external links in post content
     */
    public function process_external_links( $content ) {

        // Apply only to singular content to avoid unintended changes
        // in archives, feeds, and excerpts
        if (
            is_admin() ||
            empty( $content ) ||
            ! is_singular()
        ) {
            return $content;
        }

        libxml_use_internal_errors( true );

        $dom = new DOMDocument( '1.0', 'UTF-8' );

        // Prevent DOMDocument from adding html/body tags
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?>' . $content,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $links = $dom->getElementsByTagName( 'a' );

        foreach ( $links as $link ) {

            if ( ! $link->hasAttribute( 'href' ) ) {
                continue;
            }

            $href = $link->getAttribute( 'href' );

            if ( ! selm_is_external_url( $href ) ) {
                continue;
            }

            // target="_blank"
            if ( ! $link->hasAttribute( 'target' ) ) {
                $link->setAttribute( 'target', '_blank' );
            }

            // rel attribute handling
            $existing_rel = $link->getAttribute( 'rel' );
            $rels         = array_map( 'trim', explode( ' ', $existing_rel ) );

            if ( ! in_array( 'noopener', $rels, true ) ) {
                $rels[] = 'noopener';
            }

            if ( ! in_array( 'noreferrer', $rels, true ) ) {
                $rels[] = 'noreferrer';
            }

            if (
                    class_exists( 'SELM_Settings' ) &&
                    SELM_Settings::get( 'add_nofollow' ) &&
                    ! in_array( 'nofollow', $rels, true )
                ) {
                    $rels[] = 'nofollow';
                }

            $rels = array_filter( array_unique( $rels ) );
            // DOMDocument safely handles HTML attribute encoding
            $link->setAttribute( 'rel', implode( ' ', $rels ) );
        }

        $html = $dom->saveHTML();

        libxml_clear_errors();

        return $html;
    }
}
