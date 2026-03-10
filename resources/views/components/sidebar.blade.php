<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Platform')" class="grid">
        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
            {{ __('Dashboard') }}
        </flux:sidebar.item>
    </flux:sidebar.group>

    <flux:sidebar.group :heading="__('Messages')" class="grid">
        @if(!empty($users) && count($users))
            @foreach($users as $user)
                <flux:sidebar.item icon="home" :href="route('message.recipient', ['id' => $user->id])" :current="request()->routeIs('message.recipient') && (int) request()->route('id') === (int) $user->id" wire:navigate>
                    {{ $user->name }}
                </flux:sidebar.item>
            @endforeach
        @endif
    </flux:sidebar.group>
</flux:sidebar.nav>
