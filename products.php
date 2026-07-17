<?php
require_once 'header.php';
require_once '../config.php';

/* =============================
   Fetch products grouped by category
============================= */
$sql = "
SELECT 
    p.id,
    p.name,
    p.price,
    p.stock,
    p.is_hidden,
    c.id AS category_id,
    c.name AS category_name
FROM products p
LEFT JOIN categories c ON p.category = c.id
ORDER BY c.name, p.name
";
$res = $conn->query($sql);

/* =============================
   Build category grouped array
============================= */
$groups = [];
while($row = $res->fetch_assoc()){
    $cat = $row['category_name'] ?: '未分類';
    $groups[$cat][] = $row;
}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>商品管理 | カテゴリ別</title>

<style>
body{background:#f1f5f9;font-family:system-ui}

.admin-container{
    max-width:1200px;
    margin:24px auto;
    padding:0 20px;
}
.page-title{
    font-size:24px;
    font-weight:800;
    margin-bottom:18px;
}

.category-tab{
    background:#fff;
    border-radius:12px;
    margin-bottom:14px;
    box-shadow:0 6px 16px rgba(0,0,0,.06);
    overflow:hidden;
    transition:.3s;
}
.category-header{
    padding:14px 18px;
    font-weight:700;
    font-size:15px;
    background:#e2e8f0;
    cursor:pointer;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.category-header:hover{
    background:#cbd5e1;
}
.category-content{
    max-height:0;
    overflow:hidden;
    transition:max-height .4s ease;
}
.category-tab.active .category-content{
    max-height:1500px;
}

.product-table{
    width:100%;
    border-collapse:collapse;
}
.product-table th,
.product-table td{
    padding:10px 12px;
    font-size:14px;
}
.product-table thead{
    background:#f8fafc;
}
.product-table tr:not(:last-child){
    border-bottom:1px solid #e5e7eb;
}

.badge{
    padding:4px 10px;
    border-radius:6px;
    font-size:12px;
}
.badge-show{background:#dcfce7;color:#166534;}
.badge-hide{background:#fee2e2;color:#991b1b;}
.actions a{
    margin-right:6px;
    text-decoration:none;
    padding:5px 9px;
    border-radius:6px;
    font-size:13px;
}
.btn-edit{background:#2563eb;color:#fff;}
.btn-hide{background:#f59e0b;color:#fff;}
.btn-delete{background:#dc2626;color:#fff;}
.btn-edit:hover{background:#1d4ed8;}
.btn-hide:hover{background:#d97706;}
.btn-delete:hover{background:#b91c1c;}
</style>
</head>

<body>

<div class="admin-container">
    <div class="page-title">📦 商品管理（カテゴリ別タブ）</div>

<?php foreach($groups as $catName => $products): ?>
<div class="category-tab">
    <div class="category-header">
        <?= htmlspecialchars($catName) ?>
        <span>▼</span>
    </div>
    <div class="category-content">

        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫</th>
                    <th>表示</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td>¥<?= number_format($p['price']) ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td>
                        <?= $p['is_hidden'] 
                            ? '<span class="badge badge-hide">非表示</span>' 
                            : '<span class="badge badge-show">表示</span>' ?>
                    </td>
                    <td class="actions">
                        <a class="btn-edit" href="edit_product.php?id=<?= $p['id'] ?>">編集</a>
                        <a class="btn-hide" href="toggle_product.php?id=<?= $p['id'] ?>">
                            <?= $p['is_hidden']?'表示':'非表示' ?>
                        </a>
                        <a class="btn-delete" href="delete_product.php?id=<?= $p['id'] ?>" 
                           onclick="return confirm('削除しますか？')">削除</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

    </div>
</div>
<?php endforeach; ?>

</div>

<script>
document.querySelectorAll('.category-header').forEach(header=>{
    header.addEventListener('click', ()=>{
        header.parentElement.classList.toggle('active');
    });

    header.addEventListener('mouseenter', ()=>{
        header.parentElement.classList.add('active');
    });
});
</script>

</body>
</html>
