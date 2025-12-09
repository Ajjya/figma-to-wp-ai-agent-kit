<?php
/**
 * Theme Helper Functions
 *
 * @package Awesome_Theme
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Debug helper
 */
if (!function_exists('pre')) {
    function pre(...$data) {
        echo '<pre>';
        var_dump(...$data);
        echo '</pre>';
    }
}

/**
 * Shrink text to a certain length
 */
if (!function_exists('awesome_shrink_text')) {
    function awesome_shrink_text(string $text, int $limit, string $suffix = '...'): string {
        $text = trim(strip_tags($text));
        
        if (empty($text) || mb_strlen($text) <= $limit) {
            return $text;
        }
        
        $text = mb_substr($text, 0, $limit);
        $last_space = mb_strrpos($text, ' ');
        
        if ($last_space !== false) {
            $text = mb_substr($text, 0, $last_space);
        }
        
        return $text . $suffix;
    }
}

/**
 * Get excerpt with custom length
 */
if (!function_exists('awesome_get_excerpt')) {
    function awesome_get_excerpt($post_id = null, int $length = 150): string {
        $post = get_post($post_id);
        
        if (!$post) {
            return '';
        }
        
        if (!empty($post->post_excerpt)) {
            return awesome_shrink_text($post->post_excerpt, $length);
        }
        
        return awesome_shrink_text($post->post_content, $length);
    }
}

/**
 * Check if string is valid index/number
 */
if (!function_exists('is_index')) {
    function is_index($value): bool {
        return is_numeric($value) && $value >= 0;
    }
}

/**
 * Get the first key of an array
 */
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach ($arr as $key => $unused) {
            return $key;
        }
        return null;
    }
}

/**
 * Sanitize SVG content
 */
if (!function_exists('awesome_sanitize_svg')) {
    function awesome_sanitize_svg(string $svg): string {
        // Basic SVG sanitization
        $allowed_tags = array(
            'svg', 'g', 'path', 'circle', 'rect', 'line', 'polyline', 
            'polygon', 'ellipse', 'defs', 'use', 'symbol', 'title', 'desc'
        );
        
        $allowed_attrs = array(
            'viewBox', 'fill', 'stroke', 'stroke-width', 'd', 'cx', 'cy', 
            'r', 'x', 'y', 'width', 'height', 'xmlns', 'class', 'id',
            'transform', 'style', 'points', 'rx', 'ry', 'x1', 'y1', 'x2', 'y2'
        );
        
        // Remove script tags and event handlers
        $svg = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $svg);
        $svg = preg_replace('/on\w+="[^"]*"/i', '', $svg);
        $svg = preg_replace('/on\w+=\'[^\']*\'/i', '', $svg);
        
        return $svg;
    }
}

/**
 * Get inline SVG from theme assets
 */
if (!function_exists('awesome_get_svg')) {
    function awesome_get_svg(string $filename): string {
        $path = get_template_directory() . '/assets/icons/' . $filename;
        
        if (!file_exists($path)) {
            return '';
        }
        
        $svg = file_get_contents($path);
        return awesome_sanitize_svg($svg);
    }
}

/**
 * Output inline SVG
 */
if (!function_exists('awesome_svg')) {
    function awesome_svg(string $filename): void {
        echo awesome_get_svg($filename);
    }
}

/**
 * Get image URL with fallback
 */
if (!function_exists('awesome_get_image_url')) {
    function awesome_get_image_url($post_id = null, string $size = 'full', string $fallback = ''): string {
        $image_url = get_the_post_thumbnail_url($post_id, $size);
        
        if (!$image_url && !empty($fallback)) {
            return get_template_directory_uri() . '/assets/images/' . $fallback;
        }
        
        return $image_url ?: '';
    }
}

/**
 * Format phone number for tel: links
 */
if (!function_exists('awesome_format_phone')) {
    function awesome_format_phone(string $phone): string {
        return preg_replace('/[^0-9+]/', '', $phone);
    }
}

/**
 * Check if current page is front page
 */
if (!function_exists('awesome_is_front_page')) {
    function awesome_is_front_page(): bool {
        return is_front_page() || is_home();
    }
}
