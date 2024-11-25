<x-filament-panels::page>
    @foreach($this->getCachedForms() as $form)
        <x-filament-panels::form>
            {{ $form }}
        </x-filament-panels::form>
    @endforeach
</x-filament-panels::page>
