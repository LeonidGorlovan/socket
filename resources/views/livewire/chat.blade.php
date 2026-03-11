<div class="relative flex flex-col rounded-xl h-[calc(100vh-4rem)]"
     x-data
     x-init="
        const userId = @js(auth()->id());
        if (typeof window.Echo !== 'undefined' && userId) {
            window.Echo.private('App.Models.User.' + userId)
                .listen('MessageSent', () => { $wire.$refresh(); });
        }
     ">
    <div class="relative flex-1 min-h-0 flex flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden mb-4">
        <div class="absolute inset-0 -z-10 rounded-xl opacity-[0.06]" style="background-image: url('{{ asset('images/chat-bg.jpg') }}');" aria-hidden="true"></div>
        <div class="flex-1 min-h-0 overflow-y-auto p-4">
            @forelse($messages as $message)
                <div wire:key="message-{{ $message->id }}" class="mb-4">
                    <div class="max-w-[30%] rounded-2xl px-4 py-2 bg-neutral-200 dark:bg-neutral-600">
                        <p class="text-sm">
                            <span class="text-gray-500">{{ $message->sender_id === auth()->id() ? 'Me' : $message->sender->name }}:</span>
                            {{ $message->content }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $message->created_at->toDayDateTimeString() }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center pt-14 text-2xl font-bold text-neutral-500">{{ __('No messages') }}</div>
            @endforelse
        </div>
    </div>

    <div class="rounded-xl border mb-4"
         x-data
         @keydown.enter.ctrl.prevent="$wire.sendMessage()"
         @keydown.enter.meta.prevent="$wire.sendMessage()">
        <flux:textarea wire:model="content" class="w-full" rows="2" placeholder="{{ __('Type a message...') }}" />
    </div>

    @error('content') <div class="text-red-600 text-sm mb-4">{{ $message }}</div> @enderror

    <flux:button wire:click="sendMessage" icon="paper-airplane" variant="primary" class="w-full">{{ __('Send') }}</flux:button>
</div>
