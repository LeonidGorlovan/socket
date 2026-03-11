<x-layouts::app :title="__('Dashboard')">
    <livewire:chat :recipient-id="request()->route('id')" />
</x-layouts::app>
