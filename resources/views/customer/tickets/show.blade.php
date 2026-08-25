<x-app-layout>
    <x-slot name="header">
        <div class="container py-4 d-flex justify-content-between align-items-center gap-3">
            <div><a href="{{ route('customer.dashboard') }}" class="small text-decoration-none">← My tickets</a>
                <h1 class="h3 mb-0 mt-1">Conversation</h1>
            </div><span
                class="badge {{ $ticket->status === 'open' ? 'text-bg-success' : ($ticket->status === 'in-progress' ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ $ticket->status === 'in-progress' ? 'In progress' : ucfirst($ticket->status) }}</span>
        </div>
    </x-slot>
    <main class="container py-4 py-md-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="card ticket-card mb-4">
                <div class="card-body p-4">
                    <p class="text-primary text-uppercase fw-semibold small mb-1">Support request</p>
                    <h2 class="h4 mb-1">{{ $ticket->subject }}</h2>
                    <p class="text-secondary mb-0">Our support team will reply in this conversation.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card conversation-card">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h2 class="h5 mb-0">Message thread</h2>
                        </div>
                        <div id="messages" class="card-body px-4 pb-4">
                            @forelse($ticket->messages as $message)
                                <div id="message-{{ $message->id }}"
                                    class="border rounded-3 p-3 mb-3 {{ $message->user_type === 'customer' ? 'border-primary-subtle bg-primary-subtle' : 'border-light bg-light' }}">
                                    <div class="d-flex justify-content-between gap-3">
                                        <strong>{{ $message->user_type === 'customer' ? 'You' : 'Support agent' }}</strong><small
                                            class="text-secondary">{{ $message->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="mb-0 mt-2 text-break">{{ $message->message }}</p>
                            </div>@empty<p id="no-messages" class="text-secondary mb-0">No messages yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card conversation-card">
                        <div class="card-body p-4">
                            <h2 class="h5">Send a reply</h2>
                            <p class="small text-secondary">Keep all updates in one place.</p>
                            @if ($ticket->status !== 'closed')
                                <form method="POST" action="{{ route('customer.tickets.messages.store', $ticket) }}">
                                    @csrf<input type="hidden" name="user_type" value="customer">
                                    <textarea name="message" rows="7" class="form-control" placeholder="Write your message…" required>{{ old('message') }}</textarea><button class="btn btn-primary w-100 mt-3">Send
                                        reply</button>
                            </form>@else<div class="alert alert-secondary mb-0">This ticket is closed and cannot
                                    receive replies.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </main>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const messages = document.getElementById('messages');

        function appendMessage(message) {
            if (!message || document.getElementById(`message-${message.id}`)) return;
            document.getElementById('no-messages')?.remove();

            const card = document.createElement('div');
            card.id = `message-${message.id}`;
            card.className =
                `border rounded-3 p-3 mb-3 ${message.user_type === 'customer' ? 'border-primary-subtle bg-primary-subtle' : 'border-light bg-light'}`;
            const header = document.createElement('div');
            header.className = 'd-flex justify-content-between gap-3';
            const sender = document.createElement('strong');
            sender.textContent = message.user_type === 'customer' ? 'You' : 'Support agent';
            const time = document.createElement('small');
            time.className = 'text-secondary';
            time.textContent = new Date(message.created_at).toLocaleString();
            const body = document.createElement('p');
            body.className = 'mb-0 mt-2 text-break';
            body.textContent = message.message;
            header.append(sender, time);
            card.append(header, body);
            messages.appendChild(card);
            card.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        window.Echo.private('ticket.{{ $ticket->id }}').listen('.NewMessageEvent', event => {
            appendMessage(event.message);
        });
    });
</script>
