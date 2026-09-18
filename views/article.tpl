{extends file="layouts/base.tpl"}
{block name="content"}
<nav class="breadcrumbs" aria-label="Хлебные крошки">
    <a href="/">Главная</a>
    <span aria-hidden="true">/</span>
    <span>Статья</span>
</nav>
<article class="article-detail" data-article-id="{$article.id}">
    <header class="article-heading">
        <div class="article-categories">
            {foreach $categories as $category}
                <a class="category-tag" href="/categories/{$category.id}">{$category.name}</a>
            {/foreach}
        </div>
        <h1>{$article.title}</h1>
        <p class="article-lead">{$article.description}</p>
        <div class="article-meta">
            <time datetime="{$article.published_at|iso_date}">{$article.published_at|ru_date}</time>
            <span>{$reading_minutes} мин чтения</span>
            <span class="views">{include file="partials/icon.tpl" name="eye"}<span data-view-count="{$article.views}">{$article.views|readable_number}</span> просмотров</span>
        </div>
    </header>
    <figure class="article-cover">
        <img src="{$article.image_path}" alt="{$article.title}" width="1200" height="750" fetchpriority="high">
    </figure>
    <div class="article-reading-layout">
        <aside class="article-aside">
            <span class="aside-rule"></span>
            <p>Без лишнего шума.<br>С вниманием к деталям.</p>
            <a class="text-link" href="/">{include file="partials/icon.tpl" name="arrow-left"} В журнал</a>
        </aside>
        <div class="article-text">
            {foreach $paragraphs as $paragraph}
            <p>{$paragraph}</p>
            {/foreach}
            <div class="article-end" aria-hidden="true">●</div>
        </div>
    </div>
</article>
{if $similar}
<section class="similar-section" aria-labelledby="similar-title">
    <div class="section-heading"><div>
        <h2 id="similar-title">Продолжить чтение</h2>
        <p>Материалы из близких категорий — возможно, вам будет интересно.</p>
        </div>{include file="partials/icon.tpl" name="arrow-down-right"}</div>
    <div class="article-grid">{foreach $similar as $article}{include file="partials/article-card.tpl" article=$article}{/foreach}</div>
</section>
{/if}
{/block}
