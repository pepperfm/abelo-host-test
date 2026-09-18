{extends file="layouts/base.tpl"}
{block name="content"}
<section class="error-state">
    <span class="error-code">{$status}</span>
    <h1>{if $status == 404}Лист не найден{elseif $status == 400}Проверим адрес?{else}Не тот способ обращения{/if}</h1>
    <p>{$message}</p>
    <a class="solid-link" href="/">На главную {include file="partials/icon.tpl" name="arrow-right"}</a>
</section>
{/block}
