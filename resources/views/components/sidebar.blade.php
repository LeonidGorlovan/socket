<flux:sidebar.nav>
    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
        {{ __('Dashboard') }}
    </flux:sidebar.item>

    <flux:sidebar.group icon="chat-bubble-bottom-center-text" expandable :heading="__('Chat with:')" class="grid">
        @if(!empty($users) && count($users))
            @foreach($users as $user)
                <flux:sidebar.item :href="route('chat.recipient', ['id' => $user->id])" :current="request()->routeIs('chat.recipient') && (int) request()->route('id') === (int) $user->id" wire:navigate>
                    {{ $user->name }}
                </flux:sidebar.item>
            @endforeach
        @endif
    </flux:sidebar.group>
</flux:sidebar.nav>
