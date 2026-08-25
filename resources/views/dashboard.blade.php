<x-app-layout>
    <x-slot name="header">
        <div class="container py-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="text-primary text-uppercase fw-semibold small mb-1">Real-time workspace</p>
                <h1 class="h3 mb-0">Live Support Dashboard</h1>
            </div>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">+ Create ticket</a>
        </div>
    </x-slot>

    <main class="container py-4 py-md-5">
        <div id="notification" class="toast align-items-center border-0 position-fixed top-0 end-0 m-4 text-bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex"><div id="notification-text" class="toast-body"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
        </div>

        <div class="card ticket-card">
            <div class="card-body p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <div><h2 class="h5 mb-1">Support tickets</h2><p class="text-secondary mb-0 small">New tickets appear here automatically.</p></div>
                    <span id="ticket-count" class="badge text-bg-primary rounded-pill">Loading</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-4">S.No.</th><th>Customer</th><th>Subject</th><th>Status</th><th class="text-end pe-4">Action</th></tr></thead>
                        <tbody id="ticketsTable"><tr><td colspan="5" class="text-center text-secondary py-5"><div class="spinner-border spinner-border-sm me-2"></div>Loading tickets…</td></tr></tbody>
                    </table>
                </div>
                <div class="p-3 px-4 border-top d-flex justify-content-between align-items-center">
                    <small id="page-summary" class="text-secondary"></small>
                    <div class="btn-group"><button id="previous-page" class="btn btn-outline-secondary btn-sm">Previous</button><button id="next-page" class="btn btn-outline-secondary btn-sm">Next</button></div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('ticketsTable');
    const previous = document.getElementById('previous-page');
    const next = document.getElementById('next-page');
    let currentPage = 1;
    let firstItem = 1;
    let pagination = {};

    const statusClass = status => ({ open: 'text-bg-success', 'in-progress': 'text-bg-warning' , closed: 'text-bg-secondary' }[status] || 'text-bg-secondary');
    const label = status => status === 'in-progress' ? 'In progress' : status.charAt(0).toUpperCase() + status.slice(1);

    function ticketRow(ticket, serialNumber) {
        const row = document.createElement('tr');
        row.id = `ticket-${ticket.id}`;
        row.dataset.ticketRow = 'true';
        const fields = [serialNumber, ticket.customer_name, ticket.subject];
        fields.forEach((value, index) => { const cell = document.createElement('td'); cell.textContent = value; if (index === 0) cell.className = 'ps-4 text-secondary'; row.appendChild(cell); });
        const status = document.createElement('td');
        const badge = document.createElement('span'); badge.className = `badge ${statusClass(ticket.status)}`; badge.textContent = label(ticket.status); status.appendChild(badge); row.appendChild(status);
        const action = document.createElement('td'); action.className = 'text-end pe-4';
        const link = document.createElement('a'); link.href = ticket.url; link.className = 'btn btn-sm btn-outline-primary'; link.textContent = 'Open'; action.appendChild(link); row.appendChild(action);
        return row;
    }

    function refreshSerialNumbers() {
        table.querySelectorAll('tr[data-ticket-row]').forEach((row, index) => {
            row.firstElementChild.textContent = firstItem + index;
        });
    }

    function showNotification(message) { document.getElementById('notification-text').textContent = message; bootstrap.Toast.getOrCreateInstance(document.getElementById('notification')).show(); }

    function render(tickets) {
        table.replaceChildren();
        if (!tickets.length) { table.innerHTML = '<tr><td colspan="5" class="text-center text-secondary py-5">No tickets found.</td></tr>'; return; }
        tickets.forEach((ticket, index) => table.appendChild(ticketRow(ticket, firstItem + index)));
    }

    async function loadTickets(page = 1) {
        table.innerHTML = '<tr><td colspan="5" class="text-center text-secondary py-5">Loading tickets…</td></tr>';
        try {
            const response = await fetch(`/api/tickets?page=${page}`, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
            if (!response.ok) throw new Error('Could not load tickets');
            const result = await response.json();
            currentPage = result.current_page; pagination = result; firstItem = result.from || 1;
            render(result.data);
            document.getElementById('ticket-count').textContent = `${result.total} total`;
            document.getElementById('page-summary').textContent = `Page ${result.current_page} of ${result.last_page}`;
            previous.disabled = !result.prev_page_url; next.disabled = !result.next_page_url;
        } catch (error) { table.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-5">Unable to load tickets. Please refresh the page.</td></tr>'; }
    }

    previous.addEventListener('click', () => loadTickets(currentPage - 1));
    next.addEventListener('click', () => loadTickets(currentPage + 1));
    loadTickets();

    window.Echo.channel('tickets').listen('.NewTicketEvent', event => {
        if (currentPage !== 1 || document.getElementById(`ticket-${event.ticket.id}`)) return;
        table.querySelector('tr td[colspan]')?.parentElement.remove();
        firstItem = 1;
        table.prepend(ticketRow(event.ticket, 1));
        refreshSerialNumbers();
        showNotification('New ticket received.');
    });

    window.Echo.private('agents').listen('.NewMessageEvent', event => {
        if (event.message?.user_type !== 'customer') return;
        showNotification(`New customer message on ticket #${event.message.ticket_id}.`);
    });
});
</script>
