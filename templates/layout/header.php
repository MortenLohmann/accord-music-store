<?php
use App\Helpers\FormattingHelper;
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= FormattingHelper::sanitizeOutput($pageTitle ?? 'Accord Music Store') ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">ACCORD</div>
            <nav class="main-nav">
                <ul>
                    <li><a href="/products">KATALOG</a></li>
                    <li><a href="#">BUTIKKER ▼</a></li>
                    <li><a href="#">SÆLG & BYT</a></li>
                    <li><a href="#">GRADUERING</a></li>
                    <li><a href="#">ACCORD</a></li>
                </ul>
            </nav>
        </div>
        <div class="header-bottom">
            <div class="search-cart">
                <span>SØG 🔍</span>
                <span>KASSE(0) 📦</span>
            </div>
        </div>
    </header>

    <div class="breadcrumb">
        • <?= FormattingHelper::sanitizeOutput($breadcrumb ?? 'Hjem') ?>
    </div>

    <main class="<?= FormattingHelper::sanitizeOutput($mainClass ?? '') ?>"> 