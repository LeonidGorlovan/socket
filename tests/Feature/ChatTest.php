<?php

use App\Events\MessageSent;
use App\Livewire\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

test('guests cannot access chat page', function () {
    $recipient = User::factory()->create();

    $response = $this->get(route('chat.recipient', ['id' => $recipient->id]));

    $response->assertRedirect(route('login'));
});

test('authenticated user can send a message', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    Livewire::actingAs($sender)
        ->test(Chat::class, ['recipientId' => $recipient->id])
        ->set('content', 'Hello, world!')
        ->call('sendMessage')
        ->assertSet('content', '');

    expect(Message::query()->count())->toBe(1);

    $message = Message::query()->first();
    expect($message->content)->toBe('Hello, world!')
        ->and($message->sender_id)->toBe($sender->id)
        ->and($message->recipient_id)->toBe($recipient->id);
});

test('cannot send empty message', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    Livewire::actingAs($sender)
        ->test(Chat::class, ['recipientId' => $recipient->id])
        ->set('content', '')
        ->call('sendMessage')
        ->assertHasErrors(['content' => 'required']);

    expect(Message::query()->count())->toBe(0);
});

test('sending a message dispatches MessageSent event', function () {
    Event::fake([MessageSent::class]);

    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    Livewire::actingAs($sender)
        ->test(Chat::class, ['recipientId' => $recipient->id])
        ->set('content', 'Hello!')
        ->call('sendMessage');

    Event::assertDispatched(MessageSent::class, function (MessageSent $event) use ($recipient): bool {
        return $event->message->recipient_id === $recipient->id
            && $event->message->content === 'Hello!';
    });
});

test('MessageSent event broadcasts on recipient private channel', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    $message = Message::query()->create([
        'sender_id' => $sender->id,
        'recipient_id' => $recipient->id,
        'content' => 'Test content',
    ]);

    $event = new MessageSent($message);

    $channels = $event->broadcastOn();

    expect($channels)->toHaveCount(1)
        ->and($channels[0]->name)->toBe('private-App.Models.User.'.$recipient->id);
});
