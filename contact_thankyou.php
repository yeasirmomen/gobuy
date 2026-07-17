<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ありがとうございました</title>

<style>
body{
    margin:0;
    font-family:"Segoe UI", Meiryo, sans-serif;
    background: linear-gradient(135deg,#43cea2,#185a9d);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.thank-container{
    background:#ffffff;
    padding:55px 60px;
    border-radius:22px;
    text-align:center;
    box-shadow:0 30px 70px rgba(0,0,0,0.25);
    max-width:460px;
    width:90%;
    position:relative;
}

/* TOP COLOR BAR */
.thank-container::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:6px;
    background: linear-gradient(90deg,#43cea2,#185a9d,#30cfd0);
    border-radius:22px 22px 0 0;
}

.thank-container h2{
    font-size:30px;
    color:#1f2937;
    margin-bottom:12px;
}

.thank-container p{
    font-size:17px;
    color:#4b5563;
    margin-bottom:35px;
    line-height:1.6;
}

/* BUTTON */
.home-btn{
    display:inline-block;
    padding:14px 36px;
    background: linear-gradient(135deg,#43cea2,#185a9d);
    color:#ffffff;
    font-weight:bold;
    font-size:16px;
    text-decoration:none;
    border-radius:30px;
    transition:all 0.3s ease;
}

.home-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 12px 30px rgba(24,90,157,0.45);
}
</style>
</head>

<body>

<div class="thank-container">

    <h2>ありがとうございました。</h2>
    <p>お問い合わせを受け付けました。</p>

    <a href="menu.php" class="home-btn">ホームへ戻る</a>

</div>

</body>
</html>
