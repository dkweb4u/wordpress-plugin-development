<?php
/**
 * Plugin Name: Auto WebP Converter & Compressor
 * Plugin URI:  https://getmysite.in
 * Description: Automatically converts uploaded JPEG and PNG images to compressed WebP format and replaces the original upload.
 * Version:     1.0.0
 * Author:      DK
 * License:     GPL-2.0+
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Auto_WebP_Converter {

    /**
     * WebP output quality (1 - 100).
     */
    const WEBP_QUALITY = 82;

    public function __construct() {
        // Intercept right after the file is saved to wp-content/uploads/
        add_filter( 'wp_handle_upload', [ $this, 'convert_upload_to_webp' ] );
    }

    /**
     * Intercept uploaded files, convert image to WebP, and update upload metadata.
     *
     * @param array $file Array containing 'file', 'url', and 'type'.
     * @return array
     */
    public function convert_upload_to_webp( array $file ): array {
        // Only target JPEG and PNG
        $allowed_mimes = [ 'image/jpeg', 'image/png' ];
        if ( ! isset( $file['type'] ) || ! in_array( $file['type'], $allowed_mimes, true ) ) {
            return $file;
        }

        $source_path = $file['file'];
        if ( ! file_exists( $source_path ) ) {
            return $file;
        }

        // Determine destination file path (.webp)
        $path_info = pathinfo( $source_path );
        $webp_filename = $path_info['filename'] . '.webp';
        $destination_path = $path_info['dirname'] . '/' . $webp_filename;

        // Ensure unique filename if a webp with the same base name exists
        $destination_path = wp_unique_filename( $path_info['dirname'], $webp_filename );
        $destination_path = $path_info['dirname'] . '/' . $destination_path;

        // Perform conversion via Imagick or GD
        $converted = false;
        if ( extension_loaded( 'imagick' ) && class_exists( 'Imagick' ) ) {
            $converted = $this->convert_via_imagick( $source_path, $destination_path );
        } elseif ( extension_loaded( 'gd' ) && function_exists( 'imagewebp' ) ) {
            $converted = $this->convert_via_gd( $source_path, $destination_path, $file['type'] );
        }

        // If conversion succeeded, delete original and rewrite array parameters
        if ( $converted && file_exists( $destination_path ) ) {
            // Delete original file (.jpg/.png)
            if ( $source_path !== $destination_path ) {
                @unlink( $source_path );
            }

            // Update upload array so WordPress registers the .webp file
            $file['file'] = $destination_path;
            $file['url']  = dirname( $file['url'] ) . '/' . basename( $destination_path );
            $file['type'] = 'image/webp';
        }

        return $file;
    }

    /**
     * Conversion using ImageMagick (preserves colorspace and alpha channels cleanly).
     */
    private function convert_via_imagick( string $source, string $destination ): bool {
        try {
            $imagick = new Imagick( $source );

            // Strip metadata profiles (EXIF, IPTC) to save bytes
            $imagick->stripImage();

            $imagick->setImageFormat( 'webp' );
            $imagick->setImageCompressionQuality( self::WEBP_QUALITY );

            // Set lossless compression for images requiring absolute fidelity if needed
            $imagick->setOption( 'webp:method', '6' ); // 6 = highest compression effort

            $success = $imagick->writeImage( $destination );
            $imagick->clear();
            $imagick->destroy();

            return (bool) $success;
        } catch ( Exception $e ) {
            error_log( 'WebP Imagick conversion failed: ' . $e->getMessage() );
            return false;
        }
    }

    /**
     * Conversion fallback using standard PHP GD library.
     */
    private function convert_via_gd( string $source, string $destination, string $mime_type ): bool {
        $image = null;

        if ( $mime_type === 'image/jpeg' ) {
            $image = @imagecreatefromjpeg( $source );
        } elseif ( $mime_type === 'image/png' ) {
            $image = @imagecreatefrompng( $source );
            if ( $image ) {
                // Preserve transparency
                imagepalettetotruecolor( $image );
                imagealphablending( $image, true );
                imagesavealpha( $image, true );
            }
        }

        if ( ! $image ) {
            return false;
        }

        $result = imagewebp( $image, $destination, self::WEBP_QUALITY );
        imagedestroy( $image );

        return (bool) $result;
    }
}

// Initialize
new Auto_WebP_Converter();