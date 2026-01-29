<?php
$themeScript = "(function(){try{var t=localStorage.getItem('theme')||'light';document.documentElement.setAttribute('data-theme',t);}catch(e){}})();";
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Easy. склад</title>
  <link rel="stylesheet" href="/assets/styles/variables.css" />
  <link rel="stylesheet" href="/assets/styles/base.css" />
  <link rel="stylesheet" href="/assets/styles/layout.css" />
  <link rel="stylesheet" href="/assets/styles/components.css" />
  <link rel="stylesheet" href="/assets/styles/pages.css" />
  <link rel="stylesheet" href="/assets/styles/dark.css" />
  <link rel="stylesheet" href="/assets/styles/choices-theme.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
  <script><?php echo $themeScript; ?></script>
</head>
<body>
<div class="app-shell">
