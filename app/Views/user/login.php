<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Login</title>

    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>

<body>

<div id="login-wrapper">

<h1>Sign In</h1>

<?php if(session()->getFlashdata('flash_msg')): ?>

<div class="alert alert-danger">
    <?= session()->getFlashdata('flash_msg') ?>
</div>

<?php endif; ?>

<form action="" method="post">

<p>
    <input type="email" name="email" placeholder="Email">
</p>

<p>
    <input type="password" name="password" placeholder="Password">
</p>

<p>
    <button type="submit">Login</button>
</p>

</form>

</div>

</body>
</html>