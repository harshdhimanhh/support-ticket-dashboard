<x-app-layout>
    <x-slot name="header"><div class="container py-4 d-flex justify-content-between align-items-center"><div><p class="text-primary text-uppercase fw-semibold small mb-1">Ticket management</p><h1 class="h3 mb-0">All tickets</h1></div><a href="{{ route('tickets.create') }}" class="btn btn-primary">+ Create ticket</a></div></x-slot>
    <main class="container py-4 py-md-5">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card ticket-card overflow-hidden"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th class="ps-4">S.No.</th><th>Customer</th><th>Subject</th><th>Status</th><th class="text-end pe-4">Actions</th></tr></thead><tbody>
            @forelse($tickets as $ticket)<tr><td class="ps-4 text-secondary">{{ $tickets->firstItem() + $loop->index }}</td><td><div class="fw-semibold">{{ $ticket->customer_name }}</div><small class="text-secondary">{{ $ticket->email }}</small></td><td>{{ $ticket->subject }}</td><td><span class="badge {{ $ticket->status === 'open' ? 'text-bg-success' : ($ticket->status === 'in-progress' ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ $ticket->status === 'in-progress' ? 'In progress' : ucfirst($ticket->status) }}</span></td><td class="text-end pe-4"><a class="btn btn-sm btn-outline-primary" href="{{ route('tickets.show', $ticket) }}">Open</a><a class="btn btn-sm btn-outline-secondary" href="{{ route('tickets.edit', $ticket) }}">Edit</a></td></tr>
            @empty<tr><td colspan="5" class="text-center text-secondary py-5">No tickets found.</td></tr>@endforelse
        </tbody></table></div></div>
        <div class="mt-4">{{ $tickets->links() }}</div>
    </main>
</x-app-layout>
