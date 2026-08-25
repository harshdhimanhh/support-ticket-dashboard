<x-app-layout>
    <x-slot name="header"><div class="container py-4"><a href="{{ route('tickets.show', $ticket) }}" class="small text-decoration-none">← Ticket details</a><h1 class="h3 mb-0 mt-1">Edit ticket</h1></div></x-slot>
    <main class="container py-4 py-md-5"><div class="row justify-content-center"><div class="col-lg-8"><div class="card ticket-card"><div class="card-body p-4 p-md-5">
        <form action="{{ route('tickets.update', $ticket) }}" method="POST">@csrf @method('PUT')
            <div class="row g-3"><div class="col-md-6"><label class="form-label">Customer name</label><input name="customer_name" value="{{ old('customer_name', $ticket->customer_name) }}" class="form-control" required></div><div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $ticket->email) }}" class="form-control" required></div><div class="col-12"><label class="form-label">Subject</label><input name="subject" value="{{ old('subject', $ticket->subject) }}" class="form-control" required></div><div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="open" @selected($ticket->status === 'open')>Open</option><option value="in-progress" @selected($ticket->status === 'in-progress')>In progress</option><option value="closed" @selected($ticket->status === 'closed')>Closed</option></select></div></div>
            <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Save changes</button><a href="{{ route('tickets.show', $ticket) }}" class="btn btn-light">Cancel</a></div>
        </form>
    </div></div></div></div></main>
</x-app-layout>
