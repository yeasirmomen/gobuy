<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お問い合わせ</title>

<style>
body{
    margin:0;
    font-family:"Segoe UI", Meiryo, sans-serif;
    background: linear-gradient(135deg,#667eea,#764ba2);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.contact-box{
    background:#ffffff;
    width:440px;
    padding:45px 40px;
    border-radius:20px;
    box-shadow:0 25px 60px rgba(0,0,0,0.25);
    position:relative;
}

/* TOP DECOR LINE */
.contact-box::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    height:6px;
    width:100%;
    background: linear-gradient(90deg,#667eea,#f7971e,#ffd200);
    border-radius:20px 20px 0 0;
}

.contact-box h2{
    text-align:center;
    margin-bottom:30px;
    color:#1f2937;
    font-size:28px;
    letter-spacing:1px;
}

label{
    font-size:14px;
    color:#374151;
    font-weight:600;
}

input, textarea{
    width:100%;
    padding:13px 15px;
    margin-top:8px;
    margin-bottom:20px;
    border-radius:12px;
    border:1px solid #d1d5db;
    font-size:14px;
    background:#f9fafb;
    transition:0.3s;
}

input:focus, textarea:focus{
    background:#ffffff;
    border-color:#667eea;
    box-shadow:0 0 0 3px rgba(102,126,234,0.25);
    outline:none;
}

textarea{
    resize:none;
    height:130px;
}

button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:14px;
    font-size:16px;
    font-weight:bold;
    color:#ffffff;
    cursor:pointer;
    background: linear-gradient(135deg,#667eea,#764ba2);
    transition:0.3s;
}

button:hover{
    transform:translateY(-3px);
    box-shadow:0 15px 30px rgba(102,126,234,0.5);
}
</style>
</head>

<body>

<div class="contact-box">

    <h2>お問い合わせ</h2>

    <form action="contact_save.php" method="post">

        <label>お名前</label>
        <input type="text" name="name" required>

        <label>メール</label>
        <input type="email" name="email" required>

        <label>メッセージ</label>
        <textarea name="message" required></textarea>

        <button type="submit">送信</button>

    </form>

</div>

</body>
</html>
