<?php

namespace App\Livewire;

use App\Models\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Chat extends Component
{
    #[Validate('required|string|max:512')]
    public string $content = '';

    public int|string $recipientId;

    public function mount(int|string $recipientId): void
    {
        $this->recipientId = $recipientId;
    }

    public function sendMessage(): void
    {
        $this->validate();
        $validated = $this->only('content');

        Message::query()->create([
            'sender_id' => auth()->id(),
            'recipient_id' => $this->recipientId,
            'content' => trim($validated['content']),
        ]);

        $this->reset('content');
    }

    public function getConversationMessages(): Collection
    {
        $currentUserId = auth()->id();

        return Message::query()
            ->where(function ($q) use ($currentUserId) {
                $q->where('sender_id', $currentUserId)->where('recipient_id', $this->recipientId)
                    ->orWhere('sender_id', $this->recipientId)->where('recipient_id', $currentUserId);
            })
            ->with(['sender', 'recipient'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.chat', [
            'messages' => $this->getConversationMessages(),
        ]);
    }
}
