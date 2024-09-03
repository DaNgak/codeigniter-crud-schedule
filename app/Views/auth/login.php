<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <?php if(session()->getFlashdata('message')):?>
    <div><?= session()->getFlashdata('message') ?></div>
    <?php endif;?>
    <form action="/loginPost" method="post">
        <label>Email</label>
        <input type="email" name="email">
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>

</html>