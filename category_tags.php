<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../config.php';

/* Fetch */
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
$tags = $conn->query("SELECT * FROM tags WHERE is_active=1");

if (isset($_POST['save'])) {
    $cat = (int)$_POST['category_id'];
    $conn->query("DELETE FROM category_tags WHERE category_id=$cat");

    if (!empty($_POST['tags'])) {
        $stmt = $conn->prepare("INSERT INTO category_tags (category_id, tag_id) VALUES (?,?)");
        foreach ($_POST['tags'] as $tagId) {
            $stmt->bind_param("ii", $cat, $tagId);
            $stmt->execute();
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>カテゴリタグ管理</title>
</head>
<body>

<h1>カテゴリ別タグ管理</h1>

<form method="post">
<select name="category_id" required>
<?php while ($c=$categories->fetch_assoc()): ?>
<option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
<?php endwhile; ?>
</select>

<h3>使用可能タグ</h3>
<?php while ($t=$tags->fetch_assoc()): ?>
<label>
<input type="checkbox" name="tags[]" value="<?= $t['id'] ?>">
<?= htmlspecialchars($t['name']) ?>
</label><br>
<?php endwhile; ?>

<button class="btn" name="save">保存</button>
</form>

</body>
</html>
