<!DOCTYPE html>
<html lang="de">
<head>
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Keller & Knilche'; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="google-site-verification" content="SCdSYTNXmmyjRRt0STf3Fpfv749wHQQ0DVs86xIFWQE" />

    <?php // SEO Meta-Tags ?>
    <meta name="description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Keller & Knilche – Gewinne maximieren, Helden minimieren.'; ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?'https':'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription ?? ''); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?'https':'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>">
    <meta property="og:type" content="website">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/font.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg" sizes="any">
    <script src="/assets/js/fontSwitcher.js" defer></script>
</head>
<body>