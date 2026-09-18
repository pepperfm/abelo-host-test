{extends file="layouts/base.tpl"}
{block name="content"}
<section class="home-intro" aria-labelledby="intro-title">
    <h1 id="intro-title">Меньше шума.<br>Больше <em>смысла.</em></h1>
    <div class="intro-copy"><p>Заметки о разработке, дизайне<br class="desktop-break"> и инструментах, с которыми<br class="desktop-break"> приятно работать.</p>
        <a class="text-link" href="#journal">Листать журнал {include file="partials/icon.tpl" name="arrow-down"}</a>
    </div>
</section>
<div id="journal" class="journal-sections">
    {foreach $sections as $section}
        <section class="category-section" aria-labelledby="category-{$section.category.id}" data-category-id="{$section.category.id}">
            <div class="section-heading">
                <div>
                    <div class="section-title-line">
                        <span class="section-index">0{$section@iteration}</span>
                        <h2 id="category-{$section.category.id}">{$section.category.name}</h2>
                    </div>
                    <p>{$section.category.description}</p>
                </div>
                <a class="outline-link" href="/categories/{$section.category.id}">Все статьи {include file="partials/icon.tpl" name="arrow-right"}</a>
            </div>
            <div class="article-grid">
                {foreach $section.articles as $article}
                    {include file="partials/article-card.tpl" article=$article}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <section class="empty-state">
            <span class="empty-symbol" aria-hidden="true">✳</span>
            <h2>Первый лист ещё впереди</h2>
            <p>Здесь появятся статьи, когда журнал будет наполнен.</p>
        </section>
    {/foreach}
</div>
{/block}
