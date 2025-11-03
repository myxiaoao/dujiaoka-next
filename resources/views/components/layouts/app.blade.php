<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $lang ?? app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- 动态标题 --}}
    <title>{{ ($title ?? '') ? $title . ' | ' . dujiaoka_config_get('title', config('app.name')) : dujiaoka_config_get('title', config('app.name')) }}</title>

    {{-- 基础 SEO Meta 标签 --}}
    <meta name="description" content="{{ $description ?? dujiaoka_config_get('description', '') }}">
    <meta name="keywords" content="{{ $keywords ?? dujiaoka_config_get('keywords', '') }}">
    @if(isset($noindex) && $noindex)
    <meta name="robots" content="noindex, nofollow">
    @else
    <meta name="robots" content="index, follow">
    @endif

    {{-- Canonical 链接 --}}
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    {{-- Open Graph 标签 (Facebook/LinkedIn) --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $title ?? dujiaoka_config_get('title', config('app.name')) }}">
    <meta property="og:description" content="{{ $description ?? dujiaoka_config_get('description', '') }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('default-og-image.jpg') }}">
    <meta property="og:site_name" content="{{ dujiaoka_config_get('title', config('app.name')) }}">
    @isset($updated_at)
    <meta property="og:updated_time" content="{{ $updated_at }}">
    @endisset

    {{-- Twitter Card 标签 --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? dujiaoka_config_get('title', config('app.name')) }}">
    <meta name="twitter:description" content="{{ $description ?? dujiaoka_config_get('description', '') }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('default-og-image.jpg') }}">

    {{-- JSON-LD 结构化数据 --}}
    @isset($schema)
    <script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endisset

    @isset($breadcrumb)
    <script type="application/ld+json">
{!! json_encode($breadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endisset

    {{-- HTTPS 强制 --}}
    @if(request()->getScheme() === 'https')
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    {{-- Favicon --}}
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- 暗黑模式初始化脚本 (避免闪烁) --}}
    <script>
        // 在页面加载前立即执行，避免暗黑模式切换时的闪烁
        (function() {
            let theme = localStorage.getItem('theme');

            // 如果没有保存过主题设置，使用系统偏好
            if (!theme) {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                theme = prefersDark ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
            }

            // 根据主题设置添加或移除 dark class
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    {{-- Vite 资产 --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles

    {{-- Flux Appearance (仅免费组件) --}}
    @fluxAppearance
</head>
<body class="min-h-screen flex flex-col bg-white dark:bg-zinc-800">
    {{-- Flux Header with Navigation --}}
    <flux:header class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <div class="max-w-7xl w-full mx-auto px-4 flex items-center h-16">
            {{-- Mobile Sidebar Toggle --}}
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            {{-- Brand / Logo (Left) --}}
            <a href="/" class="max-lg:hidden text-2xl font-bold text-zinc-900 dark:text-white hover:text-zinc-700 dark:hover:text-zinc-300 transition">
                {{ dujiaoka_config_get('title', config('app.name')) }}
            </a>

            <flux:spacer />

            {{-- Right Side: Navigation + Actions --}}
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="home" href="/" :current="request()->is('/')">首页</flux:navbar.item>
                <flux:navbar.item icon="magnifying-glass" href="{{ route('search-order') }}" :current="request()->routeIs('search-order')">订单查询</flux:navbar.item>
            </flux:navbar>

            <flux:separator vertical variant="subtle" class="my-2 mx-4 max-lg:hidden" />

            <flux:navbar>
                {{-- Dark Mode Toggle --}}
                <button
                    x-data="{
                        dark: false,
                        toggle() {
                            this.dark = !this.dark;
                            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                            document.documentElement.classList.toggle('dark', this.dark);
                        }
                    }"
                    x-init="
                        dark = localStorage.getItem('theme') === 'dark';
                        document.documentElement.classList.toggle('dark', dark);
                    "
                    @click="toggle()"
                    class="p-2 text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    title="切换暗黑模式"
                    aria-label="切换暗黑模式">
                    {{-- 亮色模式下显示月亮图标 --}}
                    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    {{-- 暗黑模式下显示太阳图标 --}}
                    <svg x-show="dark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
            </flux:navbar>
        </div>
    </flux:header>

    {{-- Mobile Sidebar --}}
    <flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="/"
                :name="dujiaoka_config_get('title', config('app.name'))"
            />
            <flux:sidebar.collapse />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="/" :current="request()->is('/')">首页</flux:sidebar.item>
            <flux:sidebar.item icon="magnifying-glass" href="{{ route('search-order') }}" :current="request()->routeIs('search-order')">订单查询</flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>

    {{-- Main Content --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="border-t border-neutral-200 dark:border-zinc-700 bg-white dark:bg-zinc-800/50 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-neutral-600 dark:text-zinc-400">
                <div class="text-center md:text-left">
                    <p>&copy; {{ date('Y') }} {{ dujiaoka_config_get('title', config('app.name')) }}. All rights reserved.</p>
                    @if(dujiaoka_config_get('footer'))
                    <p class="mt-1">{!! dujiaoka_config_get('footer') !!}</p>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Flux Scripts (仅免费组件) --}}
    @fluxScripts

    {{-- 强制修正暗黑模式（在所有脚本加载后执行） --}}
    <script>
        // 在 DOM 完全加载并且所有脚本执行后，再次强制应用正确的主题
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const savedTheme = localStorage.getItem('theme');
                const isDark = savedTheme === 'dark';

                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }, 0);
        });
    </script>

    {{-- 回到顶部按钮 --}}
    <div x-data="{ show: false }"
         @scroll.window="show = window.pageYOffset > 300"
         x-show="show"
         x-transition
         class="fixed bottom-8 right-8 z-50">
        <button
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        </button>
    </div>
</body>
</html>
