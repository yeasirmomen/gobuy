<?php
/* ===============================
   CATEGORY + TAG HELPERS
================================ */

/* Get parent chain */
function get_category_chain($conn, $category_id) {
    $ids = [];
    while ($category_id) {
        $ids[] = $category_id;
        $r = $conn->query("SELECT parent_id FROM categories WHERE id=".(int)$category_id);
        if (!$r || !$row = $r->fetch_assoc()) break;
        $category_id = $row['parent_id'];
    }
    return $ids;
}

/* Get tags for category + parents */
function get_category_tags($conn, $category_id) {
    $chain = get_category_chain($conn, $category_id);
    if (empty($chain)) return [];

    $ids = implode(',', array_map('intval', $chain));

    $sql = "
        SELECT DISTINCT t.id, t.name
        FROM category_tags ct
        JOIN tags t ON ct.tag_id = t.id
        WHERE ct.category_id IN ($ids)
        ORDER BY t.name
    ";

    $res = $conn->query($sql);
    $out = [];
    while ($row = $res->fetch_assoc()) $out[] = $row;
    return $out;
}
