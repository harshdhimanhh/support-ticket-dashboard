<x-app-layout>
    <x-slot name="header"><div class="container py-4"><a href="{{ route('tickets.index') }}" class="small text-decoration-none">← All tickets</a><h1 class="h3 mb-0 mt-1">Create ticket</h1></div></x-slot>
    <main class="container py-4 py-md-5"><div class="row justify-content-center"><div class="col-lg-8"><div class="card ticket-card"><div class="card-body p-4 p-md-5">
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form action="{{ route('tickets.store') }}" method="POST">@csrf
            <div class="row g-3"><div class="col-md-6"><label class="form-label">Customer name</label><input name="customer_name" value="{{ old('customer_name') }}" class="form-control" required></div><div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div><div class="col-12"><label class="form-label">Subject</label><input name="subject" value="{{ old('subject') }}" class="form-control" required></div><div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="open">Open</option><option value="in-progress">In progress</option><option value="closed">Closed</option></select></div></div>
            <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Create ticket</button><a href="{{ route('tickets.index') }}" class="btn btn-light">Cancel</a></div>
        </form>
    </div></div></div></div></main>
</x-app-layout>
