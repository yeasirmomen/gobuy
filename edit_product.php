<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config.php';

/* =======================
   Helpers
======================= */
function image_url($p){
    return '/gobuynow/uploads/' . htmlspecialchars($p, ENT_QUOTES);
}

function fetch_product_images($conn, $pid){
    $out = [];
    $res = $conn->query("SELECT * FROM product_images WHERE product_id=".(int)$pid." ORDER BY is_primary DESC, id ASC");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $out[] = $r;
        }
    }
    return $out;
}

function save_multiple_uploads($files){
    $saved = [];
    if (empty($files['name'][0])) return $saved;

    $dir = __DIR__ . '/../uploads/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

        $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
        $name = time() . '_' . mt_rand(1000,9999) . '.' . $ext;

        if (move_uploaded_file($files['tmp_name'][$i], $dir . $name)) {
            $saved[] = $name;
        }
    }
    return $saved;
}

/* =======================
   CATEGORY CHAIN
======================= */
function getCategoryChain($conn, $category_id){
    $ids = [];
    while ($category_id) {
        $ids[] = (int)$category_id;
        $res = $conn->query("SELECT parent_id FROM categories WHERE id=".(int)$category_id);
        if (!$res) break;
        $row = $res->fetch_assoc();
        $category_id = $row ? (int)$row['parent_id'] : 0;
    }
    return $ids;
}

/* =======================
   Product ID
======================= */
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) die("Invalid product ID");

/* =======================
   Load Product
======================= */
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) die("Product not found");

/* =======================
   Handle POST
======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete_image'])) {
        $img_id = (int)$_POST['delete_image'];

        $res = $conn->query("SELECT image_path,is_primary FROM product_images WHERE id=$img_id AND product_id=$product_id");
        if ($res && $img = $res->fetch_assoc()) {
            @unlink(__DIR__ . '/../uploads/' . $img['image_path']);
            $conn->query("DELETE FROM product_images WHERE id=$img_id");

            if ($img['is_primary']) {
                $conn->query("UPDATE product_images SET is_primary=1 WHERE product_id=$product_id LIMIT 1");
            }
        }
        header("Location: edit_product.php?id=".$product_id);
        exit;
    }

    $is_hidden = isset($_POST['is_hidden']) ? 1 : 0;

    $stmt = $conn->prepare("
        UPDATE products SET
            name=?,
            price=?,
            stock=?,
            category=?,
            description=?,
            is_hidden=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "sdiisii",
        $_POST['name'],
        $_POST['price'],
        $_POST['stock'],
        $_POST['category'],
        $_POST['description'],
        $is_hidden,
        $product_id
    );
    $stmt->execute();
    $stmt->close();

    if (isset($_POST['primary_image'])) {
        $pid = (int)$_POST['primary_image'];
        $conn->query("UPDATE product_images SET is_primary=0 WHERE product_id=$product_id");

        $stmt = $conn->prepare("UPDATE product_images SET is_primary=1 WHERE id=? AND product_id=?");
        $stmt->bind_param("ii", $pid, $product_id);
        $stmt->execute();
        $stmt->close();
    }

    foreach (save_multiple_uploads($_FILES['images']) as $f) {
        $stmt = $conn->prepare("INSERT INTO product_images (product_id,image_path,is_primary) VALUES (?,?,0)");
        $stmt->bind_param("is", $product_id, $f);
        $stmt->execute();
        $stmt->close();
    }

    /* ===== TAG SAVE ===== */
    $conn->query("DELETE FROM product_tags WHERE product_id=$product_id");

    if (!empty($_POST['tags'])) {
        foreach ($_POST['tags'] as $tid) {
            $stmt = $conn->prepare("INSERT INTO product_tags (product_id,tag_id) VALUES (?,?)");
            $stmt->bind_param("ii", $product_id, $tid);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: edit_product.php?id=".$product_id);
    exit;
}

/* =======================
   UI DATA
======================= */
$categories = [];
$res = $conn->query("SELECT id,name FROM categories ORDER BY name");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $categories[] = $r;
    }
}

$images = fetch_product_images($conn, $product_id);

/* ===== TAG LOAD (FIXED) ===== */
$tags = [];
$category_chain = getCategoryChain($conn, (int)$product['category']);

if (!empty($category_chain)) {
    $chain_sql = implode(',', array_map('intval', $category_chain));

    $res = $conn->query("
        SELECT DISTINCT t.id, t.name
        FROM tags t
        JOIN category_tags ct ON ct.tag_id = t.id
        WHERE ct.category_id IN ($chain_sql)
        ORDER BY t.name
    ");

    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $tags[] = $r;
        }
    }
}

$selected = [];
$res = $conn->query("SELECT tag_id FROM product_tags WHERE product_id=$product_id");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $selected[] = $r['tag_id'];
    }
}
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>商品編集</title>
<link rel="stylesheet" href="../assets/styles.css">

<style>
/* ================================
   ADMIN UI POLISH (SAFE)
================================ */
body {
    background:#f4f6f9;
    font-family:"Inter","Segoe UI",system-ui,-apple-system,sans-serif;
    color:#222;
}

.container {
    max-width:1100px;
    margin:20px auto;
    background:#fff;
    padding:20px;
    border-radius:14px;
    border:1px solid #e6e9ee;
    box-shadow:0 20px 40px rgba(0,0,0,.06);
}

h2 {
    font-size:22px;
    margin-bottom:16px;
    border-bottom:1px solid #eee;
    padding-bottom:10px;
}

h3 {
    margin-top:30px;
    font-size:18px;
}

label {
    font-weight:600;
    margin-top:12px;
    display:block;
}

input, select, textarea {
    width:100%;
    background:#fafafa;
    border:1px solid #dcdfe4;
    border-radius:8px;
    padding:8px;
    transition:.2s;
}

input:focus, select:focus, textarea:focus {
    background:#fff;
    border-color:#1f6feb;
    box-shadow:0 0 0 3px rgba(31,111,235,.15);
    outline:none;
}

.tags {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    background:#fafafa;
    padding:14px;
    border-radius:12px;
    border:1px solid #e2e5ea;
}

.tags label {
    background:#f1f3f5;
    padding:6px 12px;
    border-radius:20px;
}

.grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,150px);
    gap:12px;
    margin-top:12px;
}

.card {
    background:#fff;
    border:1px solid #ddd;
    padding:8px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 6px 16px rgba(0,0,0,.06);
    transition:.15s;
}

.card:hover {
    transform:translateY(-3px);
    box-shadow:0 10px 22px rgba(0,0,0,.08);
}

.card img {
    width:100%;
    height:100px;
    object-fit:contain;
}

.save {
    margin-top:20px;
    font-size:15px;
    font-weight:600;
    background:linear-gradient(135deg,#1f6feb,#0d4fd6);
    color:#fff;
    padding:10px 18px;
    border:none;
    border-radius:10px;
    box-shadow:0 10px 20px rgba(31,111,235,.35);
}

.del {
    margin-top:6px;
    background:linear-gradient(135deg,#ff5a5a,#d93636);
    color:#fff;
    border:none;
    padding:4px 10px;
    border-radius:6px;
}
</style>
</head>

<body>
<?php include __DIR__.'/header.php'; ?>

<div class="container">
<h2>📝 商品編集</h2>

<form method="post" enctype="multipart/form-data">

<label>商品名</label>
<input name="name" value="<?=htmlspecialchars($product['name'],ENT_QUOTES)?>">

<label>価格</label>
<input type="number" name="price" value="<?=$product['price']?>">

<label>在庫</label>
<input type="number" name="stock" value="<?=$product['stock']?>">

<label>カテゴリ</label>
<select name="category">
<?php foreach ($categories as $c): ?>
<option value="<?=$c['id']?>" <?=$product['category']==$c['id']?'selected':''?>>
<?=$c['name']?>
</option>
<?php endforeach; ?>
</select>

<label>説明</label>
<textarea name="description"><?=$product['description']?></textarea>

<label>
<input type="checkbox" name="is_hidden" <?=$product['is_hidden']?'checked':''?>> 非表示
</label>

<h3>タグ（カテゴリ連動）</h3>
<div class="tags">
<?php foreach ($tags as $t): ?>
<label>
<input type="checkbox" name="tags[]" value="<?=$t['id']?>" <?=in_array($t['id'],$selected)?'checked':''?>>
<?=$t['name']?>
</label>
<?php endforeach; ?>
</div>

<h3>画像（主画像を1つ選択）</h3>
<div class="grid">
<?php foreach ($images as $img): ?>
<div class="card">
<img src="<?=image_url($img['image_path'])?>">
<label>
<input type="radio" name="primary_image" value="<?=$img['id']?>" <?=$img['is_primary']?'checked':''?>> 主画像
</label>
<button class="del" name="delete_image" value="<?=$img['id']?>">削除</button>
</div>
<?php endforeach; ?>
</div>

<label>画像追加</label>
<input type="file" name="images[]" multiple>

<button class="save">💾 保存</button>
</form>
</div>
</body>
</html>
