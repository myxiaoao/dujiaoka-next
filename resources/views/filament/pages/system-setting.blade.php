<x-filament-panels::page>
    <form wire:submit="saveSettings">
        {{ $this->schema }}

        <div class="flex flex-wrap items-center gap-3" style="margin-top: 1.5rem !important;">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
