<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Service;

use App\Models\Goods;
use Illuminate\Support\Str;

/**
 * SEO 服务层
 *
 * 负责生成页面的 SEO 数据，包括 meta 标签、Open Graph、Twitter Card 和 Schema.org 结构化数据
 *
 * Class SeoService
 */
class SeoService
{
    /**
     * 获取商品页的 SEO 数据
     */
    public function getGoodsSeoData(Goods $goods): array
    {
        $description = $this->sanitizeDescription($goods->gd_description);

        return [
            'title' => $goods->gd_name,
            'description' => Str::limit($description, 155),
            'keywords' => $goods->gd_keywords,
            'ogImage' => picture_url($goods->picture),
            'ogType' => 'product',
            'canonical' => url('buy/'.$goods->id),
            'updated_at' => $goods->updated_at?->toIso8601String(),
            'schema' => $this->getProductSchema($goods),
            'breadcrumb' => $this->getBreadcrumbSchema($goods),
        ];
    }

    /**
     * 获取首页的 SEO 数据
     */
    public function getHomeSeoData(): array
    {
        return [
            'title' => dujiaoka_config_get('title', config('app.name')),
            'description' => dujiaoka_config_get('description', ''),
            'keywords' => dujiaoka_config_get('keywords', ''),
            'ogImage' => asset('default-og-image.jpg'),
            'ogType' => 'website',
            'canonical' => url('/'),
            'schema' => $this->getOrganizationSchema(),
        ];
    }

    /**
     * 获取默认 SEO 数据（用于没有特定 SEO 数据的页面）
     */
    public function getDefaultSeoData(string $pageTitle = '', bool $noindex = false): array
    {
        $title = $pageTitle ?: dujiaoka_config_get('title', config('app.name'));

        return [
            'title' => $title,
            'description' => dujiaoka_config_get('description', ''),
            'keywords' => dujiaoka_config_get('keywords', ''),
            'ogImage' => asset('default-og-image.jpg'),
            'ogType' => 'website',
            'canonical' => url()->current(),
            'noindex' => $noindex,
        ];
    }

    /**
     * 生成商品的 Schema.org Product 结构化数据
     */
    public function getProductSchema(Goods $goods): array
    {
        $description = $this->sanitizeDescription($goods->gd_description);

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $goods->gd_name,
            'description' => $description,
            'image' => picture_url($goods->picture),
            'sku' => (string) $goods->id,
            'offers' => [
                '@type' => 'Offer',
                'price' => (string) $goods->actual_price,
                'priceCurrency' => 'CNY',
                'availability' => $goods->in_stock > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'url' => url('buy/'.$goods->id),
            ],
        ];
    }

    /**
     * 生成组织的 Schema.org Organization 结构化数据
     */
    public function getOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => dujiaoka_config_get('title', config('app.name')),
            'url' => url('/'),
            'logo' => asset('logo.png'),
        ];
    }

    /**
     * 生成面包屑的 Schema.org BreadcrumbList 结构化数据
     */
    public function getBreadcrumbSchema(Goods $goods): array
    {
        $items = [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => '首页',
                'item' => url('/'),
            ],
        ];

        // 如果商品有分类，添加分类到面包屑
        if ($goods->group) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $goods->group->gp_name,
                'item' => url('/?group='.$goods->group->id),
            ];
            $items[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $goods->gd_name,
                'item' => url('buy/'.$goods->id),
            ];
        } else {
            $items[] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $goods->gd_name,
                'item' => url('buy/'.$goods->id),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * 清理描述文本，移除 HTML 标签和多余空白
     */
    private function sanitizeDescription(string $description): string
    {
        // 移除 HTML 标签
        $description = strip_tags($description);

        // 移除多余空白
        $description = preg_replace('/\s+/', ' ', $description);

        // Trim 并返回
        return trim($description);
    }
}
