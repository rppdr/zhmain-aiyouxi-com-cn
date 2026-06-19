<?php

namespace App\Render;

class LinkCard
{
    private string $siteUrl;
    private string $siteName;
    private int $maxTitleLength;

    public function __construct(
        string $siteUrl = 'https://zhmain-aiyouxi.com.cn',
        string $siteName = '爱游戏',
        int $maxTitleLength = 50
    ) {
        $this->siteUrl = $siteUrl;
        $this->siteName = $siteName;
        $this->maxTitleLength = $maxTitleLength;
    }

    public function renderCard(string $title, string $description = '', string $imageUrl = ''): string
    {
        $safeTitle = htmlspecialchars($this->truncateTitle($title), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeDescription = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeImageUrl = htmlspecialchars($imageUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeSiteUrl = htmlspecialchars($this->siteUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeSiteName = htmlspecialchars($this->siteName, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $imageHtml = '';
        if ($safeImageUrl !== '') {
            $imageHtml = sprintf(
                '<img src="%s" alt="%s" class="link-card-image" loading="lazy" />',
                $safeImageUrl,
                $safeTitle
            );
        }

        return sprintf(
            '<div class="link-card">' .
            '<a href="%s" target="_blank" rel="noopener noreferrer" class="link-card-anchor">' .
            '%s' .
            '<div class="link-card-body">' .
            '<h3 class="link-card-title">%s</h3>' .
            '<p class="link-card-description">%s</p>' .
            '<span class="link-card-site">%s</span>' .
            '</div>' .
            '</a>' .
            '</div>',
            $safeSiteUrl,
            $imageHtml,
            $safeTitle,
            $safeDescription,
            $safeSiteName
        );
    }

    public function renderDefaultCard(): string
    {
        $defaultTitle = '欢迎来到 ' . $this->siteName;
        $defaultDescription = '探索更多精彩内容，尽在' . $this->siteName;
        
        return $this->renderCard(
            $defaultTitle,
            $defaultDescription
        );
    }

    public function renderMultipleCards(array $items): string
    {
        $html = '';
        foreach ($items as $item) {
            $title = $item['title'] ?? '';
            $desc = $item['description'] ?? '';
            $img = $item['image'] ?? '';
            $html .= $this->renderCard($title, $desc, $img);
        }
        
        if ($html === '') {
            return $this->renderDefaultCard();
        }
        
        return sprintf('<div class="link-card-list">%s</div>', $html);
    }

    private function truncateTitle(string $title): string
    {
        if (mb_strlen($title) <= $this->maxTitleLength) {
            return $title;
        }
        return mb_substr($title, 0, $this->maxTitleLength - 3) . '...';
    }

    public static function createConfig(): array
    {
        return [
            'site_url' => 'https://zhmain-aiyouxi.com.cn',
            'site_name' => '爱游戏',
            'max_title_length' => 50,
            'default_image' => '',
        ];
    }
}