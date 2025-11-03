<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Pages;

use App\Service\SeoService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Error extends Component
{
    public string $title = '出错了';

    public string $message = '抱歉，系统遇到了一些问题。';

    public string $type = 'error'; // error, warning, info

    public function mount(): void
    {
        // 从 session 中获取错误信息
        $this->title = session('error_title', $this->title);
        $this->message = session('error_message', $this->message);
        $this->type = session('error_type', $this->type);

        // 如果没有错误信息，使用通用错误消息
        if (! session()->has('error_message') && ! session()->has('error')) {
            $this->message = '抱歉，系统遇到了一些问题。';
        } elseif (session()->has('error')) {
            $this->message = session('error');
        }
    }

    public function getIconClass(): string
    {
        return match ($this->type) {
            'warning' => 'text-amber-600 dark:text-amber-400',
            'info' => 'text-blue-600 dark:text-blue-400',
            default => 'text-red-600 dark:text-red-400',
        };
    }

    public function getBackgroundClass(): string
    {
        return match ($this->type) {
            'warning' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
            'info' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
            default => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
        };
    }

    public function getTextClass(): string
    {
        return match ($this->type) {
            'warning' => 'text-amber-900 dark:text-amber-100',
            'info' => 'text-blue-900 dark:text-blue-100',
            default => 'text-red-900 dark:text-red-100',
        };
    }

    public function getDescriptionClass(): string
    {
        return match ($this->type) {
            'warning' => 'text-amber-800 dark:text-amber-200',
            'info' => 'text-blue-800 dark:text-blue-200',
            default => 'text-red-800 dark:text-red-200',
        };
    }

    public function render(SeoService $seoService)
    {
        $seoData = $seoService->getDefaultSeoData($this->title, true);

        return view('livewire.pages.error')->with($seoData);
    }
}
