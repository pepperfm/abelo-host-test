{* All dynamic output is escaped globally in Blog\View. *}
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$description}">
    <meta name="robots" content="{$robots}">
    <meta name="color-scheme" content="light">
    <title>{$title}</title>
    <link rel="icon" href="/assets/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body class="page-{$page_type}" id="top">
<a class="skip-link" href="#main">Перейти к содержимому</a>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="/" aria-label="Лист — главная">
            <svg class="brand-mark" viewBox="0 0 36 36" aria-hidden="true">
                <path d="M8 7h20v6H14v5h12v6H14v5H8z" fill="currentColor"/>
                <path d="M21 29h7v-5h-7z" fill="currentColor"/></svg>лист<span class="brand-dot">.</span>
        </a>
        <nav class="main-nav" aria-label="Категории">
            {foreach $navigation as $item}
                <a href="/categories/{$item.id}"{if $active_category_id == $item.id} class="is-active" aria-current="page"{/if}>{$item.name}</a>
            {/foreach}
        </nav>
        <span class="header-note">О цифровом.<br>По-человечески.</span>
    </div>
</header>
<main id="main" class="container" tabindex="-1">
    {block name="content"}{/block}
</main>
<footer class="site-footer">
    <div class="container footer-inner">
        <div><a class="brand brand-small" href="/">лист<span class="brand-dot">.</span></a><p>Код. Форма. Смысл.</p></div>
        <p class="footer-copyright">© {$year} Лист. Журнал о цифровом.</p>
        <a href="#top" class="text-link">Наверх {include file="partials/icon.tpl" name="arrow-up"}</a>
    </div>
</footer>
</body>
</html>
