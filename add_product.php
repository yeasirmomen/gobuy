<?php
session_start();
require_once '../config.php';
include 'header.php';
/* =======================
   HELPERS
======================= */
function save_multiple_uploads($files){
    $saved = array();
    if (!isset($files['name'][0]) || $files['name'][0] === '') return $saved;

    $dir = __DIR__ . '/../uploads/';
    if (!is_dir($dir)) mkdir($dir,0755,true);

    for ($i=0;$i<count($files['name']);$i++){
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
        $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
        $name = time().'_'.mt_rand(1000,9999).'.'.$ext;
        if (move_uploaded_file($files['tmp_name'][$i], $dir.$name)) {
            $saved[] = $name;
        }
    }
    return $saved;
}

function getCategoryChain($conn, $category_id){
    $ids = array();
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
   LOAD CATEGORIES
======================= */
$categories = array();
$res = $conn->query("SELECT id,name FROM categories ORDER BY name");
if ($res) {
    while($r = $res->fetch_assoc()) {
        $categories[] = $r;
    }
}

/* =======================
   HANDLE POST
======================= */
$error = '';

$name = '';
$price = '';
$stock = 0;
$category = 0;
$description = '';
$is_hidden = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['name'])) $name = trim($_POST['name']);
    if (isset($_POST['price'])) $price = $_POST['price'];
    if (isset($_POST['stock'])) $stock = (int)$_POST['stock'];
    if (isset($_POST['category'])) $category = (int)$_POST['category'];
    if (isset($_POST['description'])) $description = trim($_POST['description']);
    if (isset($_POST['is_hidden'])) $is_hidden = 1;

    if ($name === '') {
        $error = "商品名を入力してください。";
    } elseif ($price === '' || !is_numeric($price)) {
        $error = "価格が正しくありません。";
    } elseif ($category <= 0) {
        $error = "カテゴリを選択してください。";
    }

    if ($error === '') {

        $stmt = $conn->prepare("
            INSERT INTO products
            (name,price,stock,category,description,is_hidden,created_at)
            VALUES (?,?,?,?,?,?,NOW())
        ");
        $stmt->bind_param(
            "sdiisi",
            $name,$price,$stock,$category,$description,$is_hidden
        );
        $stmt->execute();
        $product_id = $conn->insert_id;
        $stmt->close();

        /* IMAGES */
        $imgs = save_multiple_uploads($_FILES['images']);
        for ($i=0;$i<count($imgs);$i++) {
            $is_primary = ($i === 0) ? 1 : 0;
            $stmt = $conn->prepare("
                INSERT INTO product_images
                (product_id,image_path,is_primary)
                VALUES (?,?,?)
            ");
            $stmt->bind_param("isi",$product_id,$imgs[$i],$is_primary);
            $stmt->execute();
            $stmt->close();
        }

        /* TAG SAVE */
        if (isset($_POST['tags']) && is_array($_POST['tags'])) {
            foreach ($_POST['tags'] as $tid) {
                $stmt = $conn->prepare("
                    INSERT INTO product_tags
                    (product_id,tag_id)
                    VALUES (?,?)
                ");
                $stmt->bind_param("ii",$product_id,$tid);
                $stmt->execute();
                $stmt->close();
            }
        }

        header("Location: dashboard.php?added=1");
        exit;
    }
}

/* =======================
   TAG LOAD
======================= */
$tags = array();
if ($category > 0) {
    $chain = getCategoryChain($conn,$category);
    if (!empty($chain)) {
        $chain_sql = implode(',', $chain);
        $res = $conn->query("
            SELECT DISTINCT t.id,t.name
            FROM tags t
            JOIN category_tags ct ON ct.tag_id=t.id
            WHERE ct.category_id IN ($chain_sql)
            ORDER BY t.name
        ");
        if ($res) {
            while($r = $res->fetch_assoc()) {
                $tags[] = $r;
            }
        }
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>商品追加</title>
<style>
body{font-family:Meiryo,Arial;background:#f6f7fb;padding:18px}
.wrap{max-width:900px;margin:auto;background:#fff;padding:18px;border-radius:8px}
label{display:block;margin-top:10px;font-weight:700}
input,textarea,select{width:100%;padding:8px;border:1px solid #ddd;border-radius:6px}
.tags{display:flex;flex-wrap:wrap;gap:10px;background:#fafafa;padding:12px;border-radius:8px}
.tags label{background:#eee;padding:6px 12px;border-radius:20px}
.row{display:flex;gap:12px}
.col{flex:1}
.btn{margin-top:14px;padding:10px 16px;border-radius:8px;background:#1f6feb;color:#fff;border:none}
.error{background:#fee;color:#900;padding:10px;border-radius:6px}
</style>
</head>

<body>
<div class="wrap">
<h2><center>商品追加</center></h2>

<?php if ($error !== ''): ?>
<div class="error"><?php echo htmlspecialchars($error,ENT_QUOTES); ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<label>商品名</label>
<input name="name" value="<?php echo htmlspecialchars($name,ENT_QUOTES); ?>">

<div class="row">
<div class="col"><label>価格</label><input name="price" value="<?php echo htmlspecialchars($price,ENT_QUOTES); ?>"></div>
<div class="col"><label>在庫</label><input type="number" name="stock" value="<?php echo (int)$stock; ?>"></div>
</div>

<label>カテゴリ</label>
<select name="category" onchange="this.form.submit()">
<option value="">-- 選択 --</option>
<?php foreach($categories as $c): ?>
<option value="<?php echo $c['id']; ?>" <?php if($category==$c['id']) echo 'selected'; ?>>
<?php echo htmlspecialchars($c['name'],ENT_QUOTES); ?>
</option>
<?php endforeach; ?>
</select>

<?php if (!empty($tags)): ?>
<h3>タグ</h3>
<div class="tags">
<?php foreach($tags as $t): ?>
<label>
<input type="checkbox" name="tags[]" value="<?php echo $t['id']; ?>">
<?php echo htmlspecialchars($t['name'],ENT_QUOTES); ?>
</label>
<?php endforeach; ?>
</div>
<?php endif; ?>

<label>画像</label>
<input type="file" name="images[]" multiple>

<label>説明</label>
<textarea name="description"><?php echo htmlspecialchars($description,ENT_QUOTES); ?></textarea>

<label><input type="checkbox" name="is_hidden" <?php if($is_hidden) echo 'checked'; ?>> 非表示</label>

<button class="btn">商品を追加</button>
</form>
</div>
</body>
</html>
