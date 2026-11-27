<x-filament-panels::page>
    <div class="max-w-2xl space-y-6">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Points are virtual entertainment credits only. They have no cash value and cannot be deposited, withdrawn, transferred, or redeemed.
        </p>

        <form wire:submit="submit" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit" color="primary">
                Apply points adjustment
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page>
