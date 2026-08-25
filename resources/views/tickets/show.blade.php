<x-app-layout>
    <x-slot name="header">
        <div class="container py-4 d-flex justify-content-between align-items-center gap-3">
            <div><a href="{{ route('tickets.index') }}" class="text-decoration-none small">← All tickets</a>
                <h1 class="h3 mb-0 mt-1">Ticket details</h1>
            </div>
            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-outline-primary">Edit ticket</a>
        </div>
    </x-slot>

    <main class="container py-4 py-md-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button
                    type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="notification" class="toast align-items-center border-0 position-fixed top-0 end-0 m-4 text-bg-primary"
            role="alert">
            <div class="d-flex">
                <div id="notification-text" class="toast-body"></div><button type="button"
                    class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>

        <div class="card ticket-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <p class="text-secondary text-uppercase small fw-semibold mb-1">{{ $ticket->customer_name }}</p>
                        <h2 class="h4 mb-1">{{ $ticket->subject }}</h2><a class="text-secondary"
                            href="mailto:{{ $ticket->email }}">{{ $ticket->email }}</a>
                    </div>
                    <span
                        class="badge {{ $ticket->status === 'open' ? 'text-bg-success' : ($ticket->status === 'in-progress' ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ $ticket->status === 'in-progress' ? 'In progress' : ucfirst($ticket->status) }}</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card conversation-card h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h2 class="h5 mb-0">Conversation</h2>
                    </div>
                    <div id="messages" class="card-body px-4 pb-4">
                        @forelse($ticket->messages as $message)
                            <div id="message-{{ $message->id }}"
                                class="border rounded-3 p-3 mb-3 {{ $message->user_type === 'agent' ? 'border-primary-subtle bg-primary-subtle' : 'border-light bg-light' }}">
                                <div class="d-flex justify-content-between gap-3">
                                    <strong>{{ ucfirst($message->user_type) }}</strong><small
                                        class="text-secondary">{{ $message->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <p class="mb-0 mt-2 text-break">{{ $message->message }}</p>
                            </div>
                        @empty
                            <p id="no-messages" class="text-secondary mb-0">No messages yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card conversation-card">
                    <div class="card-body p-4">
                        <h2 class="h5">Reply as agent</h2>
                        <p class="small text-secondary">Your reply appears instantly for active dashboard viewers.</p>
                        <form action="{{ route('messages.store', $ticket) }}" method="POST">@csrf
                            <input type="hidden" name="user_type" value="agent">
                            <textarea name="message" rows="7" class="form-control @error('message') is-invalid @enderror"
                                placeholder="Write a helpful reply…" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-primary w-100 mt-3">Send message</button>
                        </form>
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
                `border rounded-3 p-3 mb-3 ${message.user_type === 'agent' ? 'border-primary-subtle bg-primary-subtle' : 'border-light bg-light'}`;
            const header = document.createElement('div');
            header.className = 'd-flex justify-content-between gap-3';
            const sender = document.createElement('strong');
            sender.textContent = message.user_type.charAt(0).toUpperCase() + message.user_type.slice(1);
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
            document.getElementById('notification-text').textContent = 'New message received.';
            bootstrap.Toast.getOrCreateInstance(document.getElementById('notification')).show();
        });
    });
</script>
