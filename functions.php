<?php
// inc/functions.php
// Image helpers (PHP 5.6 compatible)

if (!defined('INC_FUNCTIONS_LOADED')) {
    define('INC_FUNCTIONS_LOADED', true);
}

/**
 * Normalize image path so browser ALWAYS loads from:
 * http://localhost/gobuynow/uploads/filename.ext
 */
function normalized_image_url($storedPath) {

    // If empty → placeholder
    if (empty($storedPath)) {
        return '/gobuynow/placeholder.png';
    }

    // Trim spaces and slashes
    $storedPath = trim($storedPath);
    $storedPath = ltrim($storedPath, '/\\');

    // If already full URL
    if (preg_match('#^https?://#i', $storedPath)) {
        return $storedPath;
    }

    // Force uploads directory
    return '/gobuynow/uploads/' . basename($storedPath);
}

/**
 * Fetch product images (primary first)
 */
function fetch_product_images($conn, $product_id) {
    $out = array();

    if (!($conn instanceof mysqli)) {
        return $out;
    }

    $pid = (int)$product_id;

    // Check table exists
    $chk = $conn->query("SHOW TABLES LIKE 'product_images'");
    if (!$chk || $chk->num_rows === 0) {
        return $out;
    }

    $sql = "
        SELECT image_path, is_primary
        FROM product_images
        WHERE product_id = {$pid}
        ORDER BY is_primary DESC, id ASC
    ";

    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $out[] = $row;
        }
    }

    return $out;
}
