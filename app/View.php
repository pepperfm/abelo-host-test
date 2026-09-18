<?php

declare(strict_types=1);

namespace Core;

use Core\Support\Format;
use RuntimeException;
use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    /** @param list<array<string, mixed>> $navigation */
    public function __construct(string $root, private readonly array $navigation = [])
    {
        foreach (['compile', 'cache'] as $directory) {
            $path = "$root/storage/smarty/$directory";
            if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
                throw new RuntimeException('Cannot create Smarty directory.');
            }
            if (!is_writable($path)) {
                throw new RuntimeException('Smarty directory is not writable.');
            }
        }
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir("$root/views");
        $this->smarty->setCompileDir("$root/storage/smarty/compile");
        $this->smarty->setCacheDir("$root/storage/smarty/cache");
        $this->smarty->setEscapeHtml(true);
        $this->smarty->setCaching(Smarty::CACHING_OFF);
        $this->smarty->registerPlugin('modifier', 'ru_date', Format::date(...));
        $this->smarty->registerPlugin('modifier', 'iso_date', Format::isoDate(...));
        $this->smarty->registerPlugin('modifier', 'readable_number', Format::number(...));
    }

    /** @param array<string, mixed> $data */
    public function render(string $name, array $data = []): string
    {
        // A separate template object prevents variables leaking between renders.
        $template = $this->smarty->createTemplate($name);
        $template->assign(array_replace([
            'title' => 'Лист — журнал о цифровом',
            'description' => 'Заметки о разработке, дизайне и инструментах. Без лишнего шума.',
            'navigation' => $this->navigation,
            'active_category_id' => null,
            'page_type' => '',
            'robots' => 'index, follow',
            'year' => gmdate('Y'),
        ], $data));
        return $template->fetch();
    }
}
