{if $pagination.last_page > 1}
<nav class="pagination" aria-label="Страницы категории">
    {if $pagination.previous_url}<a class="pagination-side" href="{$pagination.previous_url}" rel="prev">{include file="partials/icon.tpl" name="arrow-left"}<span>Назад</span></a>{else}<span class="pagination-side is-disabled" aria-disabled="true">{include file="partials/icon.tpl" name="arrow-left"}<span>Назад</span></span>{/if}
    <div class="pagination-pages">
        {foreach $pagination.links as $link}
            {if $link.gap}<span class="pagination-gap" aria-hidden="true">…</span>
            {elseif $link.current}<span class="pagination-page is-current" aria-current="page" aria-label="Страница {$link.number}">{$link.number}</span>
            {else}<a class="pagination-page" href="{$link.url}" aria-label="Страница {$link.number}">{$link.number}</a>{/if}
        {/foreach}
    </div>
    {if $pagination.next_url}<a class="pagination-side" href="{$pagination.next_url}" rel="next"><span>Дальше</span>{include file="partials/icon.tpl" name="arrow-right"}</a>{else}<span class="pagination-side is-disabled" aria-disabled="true"><span>Дальше</span>{include file="partials/icon.tpl" name="arrow-right"}</span>{/if}
</nav>
{/if}
