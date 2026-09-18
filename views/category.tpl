{extends file="layouts/base.tpl"}
{block name="content"}
<nav class="breadcrumbs" aria-label="Хлебные крошки">
    <a href="/">Главная</a>
    <span aria-hidden="true">/</span>
    <span aria-current="page">{$category.name}</span>
</nav>
<header class="category-intro">
    <h1>{$category.name}<span class="title-dot">.</span></h1>
    <p>{$category.description}</p>
</header>
<div class="category-toolbar">
    <p class="results-count">Материалов: <strong>{$pagination.total}</strong>{if $pagination.total > 0}<span class="results-range"> · {$pagination.from}–{$pagination.to} на странице</span>{/if}</p>
    <nav class="sort-control" aria-label="Сортировка статей">
        <a href="/categories/{$category.id}?sort=date"{if $sort == 'date'} class="is-active" aria-current="true"{/if}>Сначала новые</a>
        <a href="/categories/{$category.id}?sort=views"{if $sort == 'views'} class="is-active" aria-current="true"{/if}>По просмотрам</a>
    </nav>
</div>
{if $articles}
    <div class="article-grid category-articles">
        {foreach $articles as $article}
            {include file="partials/article-card.tpl" article=$article}
        {/foreach}
    </div>
    {include file="partials/pagination.tpl" pagination=$pagination}
{else}
    <section class="empty-state">
        <span class="empty-symbol" aria-hidden="true">✳</span>
        <h2>Пока чистый лист</h2>
        <p>В этой категории ещё нет опубликованных статей.<br>А в других уже есть что почитать.</p>
        <a class="solid-link" href="/">Вернуться в журнал {include file="partials/icon.tpl" name="arrow-right"}</a>
    </section>
{/if}
{/block}
