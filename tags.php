<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$msg = "";

/* ===== Add Tag ===== */
if (isset($_POST['add_tag'])) {
    $name = trim($_POST['name']);
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));

    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $slug);
        $stmt->execute();
        $stmt->close();
        $msg = "タグを追加しました";
    }
}

/* ===== Toggle Status ===== */
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $conn->query("UPDATE tags SET is_active = IF(is_active=1,0,1) WHERE id=$id");
    header("Location: tags.php");
    exit;
}

/* ===== Delete ===== */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM tags WHERE id=$id");
    header("Location: tags.php");
    exit;
}

/* ===== Fetch Tags ===== */
$tags = $conn->query("SELECT * FROM tags ORDER BY id DESC");
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>タグ管理</title>
<link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

<h1>タグ管理</h1>

<?php if ($msg): ?>
<p style="color:green"><?= $msg ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="name" placeholder="タグ名（例：人気）" required>
    <button class="btn" name="add_tag">＋ 追加</button>
</form>

<table border="1" cellpadding="8" style="margin-top:20px;width:100%">
<tr>
<th>ID</th>
<th>タグ名</th>
<th>状態</th>
<th>操作</th>
</tr>

<?php while ($t = $tags->fetch_assoc()): ?>
<tr>
<td><?= $t['id'] ?></td>
<td><?= htmlspecialchars($t['name']) ?></td>
<td><?= $t['is_active'] ? '表示' : '非表示' ?></td>
<td>
<a href="?toggle=<?= $t['id'] ?>">切替</a> |
<a href="?delete=<?= $t['id'] ?>" onclick="return confirm('削除しますか？')">削除</a>
</td>
</tr>
<?php endwhile; ?>

</table>
</body>
</html>
