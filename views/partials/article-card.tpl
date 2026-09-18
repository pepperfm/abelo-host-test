<article class="article-card" data-article-id="{$article.id}">
    <a class="card-image-link" href="/articles/{$article.id}" tabindex="-1" aria-hidden="true">
        <img src="{$article.image_path}" alt="" width="1200" height="750" loading="lazy" decoding="async">
        <span class="card-open">{include file="partials/icon.tpl" name="arrow-up-right"}</span>
    </a>
    <div class="card-meta">
        <time datetime="{$article.published_at|iso_date}">{$article.published_at|ru_date}</time>
        <span class="views">{include file="partials/icon.tpl" name="eye"}<span class="sr-only">Просмотры: </span>{$article.views|readable_number}</span>
    </div>
    <h3><a href="/articles/{$article.id}">{$article.title}</a></h3>
    <p>{$article.description}</p>
</article>
