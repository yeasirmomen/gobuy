<?php
// admin/categories.php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
require_once __DIR__ . '/../config.php';

// fetch categories and build tree (same logic as header)
$catColumns = array();
$colsRes = $conn->query("SHOW COLUMNS FROM categories");
if ($colsRes) while ($c = $colsRes->fetch_assoc()) $catColumns[] = $c['Field'];
$hasParentField = in_array('parent_id', $catColumns) || in_array('parent', $catColumns);
$parentFieldName = in_array('parent_id', $catColumns) ? 'parent_id' : (in_array('parent', $catColumns) ? 'parent' : null);

$catSql = "SELECT id, name" . ($parentFieldName ? ", `" . $parentFieldName . "` AS parent_id" : "") . " FROM categories ORDER BY name";
$catRes = $conn->query($catSql);
$rawCats = array();
if ($catRes) while ($r = $catRes->fetch_assoc()) $rawCats[] = $r;

$categoryTree = array();
if ($hasParentField && $parentFieldName) {
    $map = array();
    foreach ($rawCats as $c) { if (!isset($c['parent_id'])) $c['parent_id'] = null; $c['children'] = array(); $map[$c['id']] = $c; }
    foreach ($map as $id => $node) {
        $pid = $node['parent_id'];
        if ($pid === null || $pid === '' || !isset($map[$pid]) || (int)$pid === 0) $categoryTree[$id] = &$map[$id];
        else $map[$pid]['children'][] = &$map[$id];
    }
} else {
    $categoryTree = $rawCats;
}

// helper to render for admin page
function render_tree_admin_page($nodes, $level = 0) {
    foreach ($nodes as $n) {
        $id = (int)$n['id'];
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
        echo '<div style="margin:6px 0;">' . $indent . '<strong>' . htmlspecialchars($n['name'], ENT_QUOTES) . '</strong> ';
        echo '<a href="edit_category.php?id=' . $id . '">編集</a> | ';
        echo '<a href="add_category.php?parent_id=' . $id . '">子カテゴリ追加</a> | ';
        echo '<a href="delete_category.php?id=' . $id . '" onclick="return confirm(\'このカテゴリを削除しますか？\')">削除</a>';
        echo '</div>';
        if (!empty($n['children'])) render_tree_admin_page($n['children'], $level+1);
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>カテゴリ管理 - 管理画面</title>
<style>
body{font-family:Meiryo,Arial;background:#f6f7fb;margin:0;padding:0}
.container{max-width:1000px;margin:18px auto;padding:12px}
.card{background:#fff;border:1px solid #eee;border-radius:8px;padding:12px}
.controls{display:flex;gap:8px;margin-bottom:12px}
a.btn{display:inline-block;padding:8px 10px;border-radius:6px;background:#1f6feb;color:#fff;text-decoration:none}
.small{font-size:13px;color:#666}
</style>
</head>
<body>
<div class="container">
  <?php include __DIR__ . '/header.php'; ?>

  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h2>カテゴリ管理</h2>
      <div><a class="btn" href="add_category.php">+ 新規カテゴリを追加</a></div>
    </div>

    <p class="small">以下はカテゴリツリーです。編集・削除・子カテゴリ追加が可能です。</p>

    <div style="margin-top:12px;">
      <?php
        if (empty($categoryTree)) {
            echo '<div class="small">カテゴリがありません。</div>';
        } else {
            render_tree_admin_page($categoryTree);
        }
      ?>
    </div>
  </div>
</div>
</body>
</html>
