<?php
// manage_categories.php
// Simple admin UI for managing categories (create / rename / delete / set parent).
session_start();
require_once __DIR__ . '/../config.php'; // adjust path as needed

// TODO: Replace with your real admin auth
if (empty($_SESSION['is_admin'])) {
    die("Admin only.");
}

// helper: fetch all categories
function fetch_all_categories($conn) {
    $rows = [];
    $res = $conn->query("SELECT id, name, parent_id FROM categories ORDER BY parent_id, name");
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    return $rows;
}

// actions: add, edit, delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $parent = isset($_POST['parent']) && $_POST['parent'] !== '' ? intval($_POST['parent']) : null;
        if ($name !== '') {
            $stmt = $conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
            $stmt->bind_param("si", $name, $parent);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $parent = isset($_POST['parent']) && $_POST['parent'] !== '' ? intval($_POST['parent']) : null;
        if ($id && $name !== '') {
            $stmt = $conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
            $stmt->bind_param("sii", $name, $parent, $id);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            // Option A: delete category and all its children (cascade)
            // If your table has FK ON DELETE CASCADE, this will remove children; otherwise, do manual delete.
            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // Option B (alternative): reassign children to parent_id = NULL (uncomment to use)
            // $conn->query("UPDATE categories SET parent_id = NULL WHERE parent_id = $id");
        }
    }
    // redirect to avoid resubmit
    header("Location: manage_categories.php");
    exit;
}

// fetch categories for display
$all = fetch_all_categories($conn);

// build nested tree for display
$map = [];
foreach ($all as $c) {
    $c['children'] = [];
    $map[$c['id']] = $c;
}
$tree = [];
foreach ($map as $id => $c) {
    if (empty($c['parent_id']) || !isset($map[$c['parent_id']])) {
        $tree[$id] = &$map[$id];
    } else {
        $map[$c['parent_id']]['children'][] = &$map[$id];
    }
}

function render_tree_ui($nodes, $depth = 0) {
    foreach ($nodes as $node) {
        echo '<div style="margin-left:' . ($depth*18) . 'px;padding:6px;border-bottom:1px solid #eee;">';
        echo '<strong>' . htmlspecialchars($node["name"], ENT_QUOTES) . '</strong> ';
        echo '<form style="display:inline" method="post" action="">';
        echo '<input type="hidden" name="action" value="delete">';
        echo '<input type="hidden" name="id" value="'. (int)$node['id'] .'">';
        echo '<button type="submit" onclick="return confirm(\'Delete this category?\')">Delete</button>';
        echo '</form> ';
        echo '<button onclick="showEdit('.(int)$node['id'].',\''.htmlspecialchars(addslashes($node['name']), ENT_QUOTES).'\','.(isset($node['parent_id'])? (int)$node['parent_id'] : 0).')">Edit</button>';
        echo '</div>';
        if (!empty($node['children'])) render_tree_ui($node['children'], $depth+1);
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Categories</title></head>
<body>
<h1>Category Management</h1>

<h2>Add Category</h2>
<form method="post">
    <input type="hidden" name="action" value="add">
    <label> Name: <input name="name" required> </label>
    <label> Parent: 
        <select name="parent">
            <option value="">(root)</option>
            <?php foreach ($all as $c): ?>
                <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name'], ENT_QUOTES) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Add</button>
</form>

<h2>Existing Categories</h2>
<div><?php render_tree_ui($tree); ?></div>

<!-- Edit form (hidden) -->
<div id="editForm" style="display:none;padding:12px;border:1px solid #ccc;margin-top:12px;">
    <h3>Edit Category</h3>
    <form method="post">
        <input type="hidden" name="action" value="edit">
        <input id="edit_id" name="id" type="hidden">
        <label> Name: <input id="edit_name" name="name" required> </label>
        <label> Parent:
            <select id="edit_parent" name="parent">
                <option value="">(root)</option>
                <?php foreach ($all as $c): ?>
                    <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name'], ENT_QUOTES) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Save</button>
        <button type="button" onclick="document.getElementById('editForm').style.display='none'">Cancel</button>
    </form>
</div>

<script>
function showEdit(id, name, parent){
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_parent').value = parent || '';
    document.getElementById('editForm').style.display = 'block';
}
</script>

</body>
</html>
