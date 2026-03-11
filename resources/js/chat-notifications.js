/**
 * Global chat message notifications: Echo subscription, toast event dispatch, and sound.
 * Call init(userId) from the layout when the user is authenticated.
 */

const TOAST_EVENT = 'show-chat-toast';

function playNewMessageSound() {
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.frequency.value = 800;
    osc.type = 'sine';
    gain.gain.setValueAtTime(0.25, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
    osc.start(ctx.currentTime);
    osc.stop(ctx.currentTime + 0.15);
  } catch (_) {}
}

function showMessageToast(senderName, content, senderId) {
  try {
    let container = document.getElementById('chat-toast-container');

    if (!container) {
      container = document.createElement('div');
      container.id = 'chat-toast-container';
      container.className =
        'fixed top-4 right-4 z-[100] flex flex-col gap-2 max-w-sm pointer-events-none';
      container.setAttribute('aria-live', 'polite');
      document.body.appendChild(container);
    }

    const toast = document.createElement('a');
    toast.href = '/chat/recipient/' + String(senderId);
    toast.className =
      'pointer-events-auto flex flex-col gap-0.5 rounded-xl border border-zinc-200 dark:border-zinc-600 bg-white dark:bg-zinc-800 shadow-lg p-3 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors';

    const title = document.createElement('span');
    title.className =
      'text-sm font-medium text-zinc-900 dark:text-zinc-100';
    title.textContent = senderName || 'New message';

    const body = document.createElement('p');
    body.className =
      'text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2';
    body.textContent = content || '';

    toast.appendChild(title);
    toast.appendChild(body);
    container.appendChild(toast);

    setTimeout(() => {
      toast.remove();
      if (!container.children.length) {
        container.remove();
      }
    }, 5000);
  } catch (_) {}
}

export function initChatNotifications(userId) {
  if (!userId || typeof window.Echo === 'undefined') return;

  window.Echo.private('App.Models.User.' + userId).listen('MessageSent', (e) => {
    const senderId = String(e.sender_id);
    const match = window.location.pathname.match(/\/chat\/recipient\/(\d+)$/);
    const inChatWithSender = match && match[1] === senderId;

    if (inChatWithSender) {
      playNewMessageSound();
    } else {
      showMessageToast(e.sender_name ?? null, e.content ?? '', senderId);
      playNewMessageSound();
    }
  });
}

export { TOAST_EVENT };
