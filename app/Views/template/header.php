<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Praktikum CodeIgniter 4'); ?></title>
    <link rel="stylesheet" href="<?= base_url('style.css'); ?>">
</head>
<body>
<div id="container">
    <header class="site-header">
        <p class="eyebrow">Pemrograman Web 2</p>
        <h1>Praktikum CodeIgniter 4</h1>
        <p>Relasi tabel dan Query Builder</p>
    </header>

    <nav class="site-nav" aria-label="Navigasi utama">
        <a href="<?= base_url('/'); ?>">Home</a>
        <a href="<?= base_url('/artikel'); ?>">Artikel</a>
        <a href="<?= base_url('/about'); ?>">About</a>
        <a href="<?= base_url('/contact'); ?>">Contact</a>
        <a href="<?= base_url('/faqs'); ?>">FAQ</a>
        <a href="<?= base_url('/admin/artikel'); ?>">Admin</a>
    </nav>

    <div id="wrapper">
        <main id="main">
