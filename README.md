# Live Support Ticket Dashboard

A Laravel + Blade real-time support dashboard for authenticated support agents. It uses Pusher Channels and Laravel Echo for live ticket and message notifications.

## Requirements

- PHP 8.3+
- Composer
- Node.js 20+
- SQLite, MySQL, or another Laravel-supported database

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure the database in `.env`. For SQLite, create `database/database.sqlite` and use:

```env
DB_CONNECTION=sqlite
```

Configure Pusher Channels values in `.env`, then migrate and seed:

```bash
php artisan migrate --seed
npm install
npm run build
```

The seeded agent account is:

```text
Email: agent@example.com
Password: password
```

## Run locally

Run the application:

```bash
php artisan serve
npm run dev
```

`BROADCAST_CONNECTION` must be `pusher`. Set `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER`, `VITE_PUSHER_APP_KEY`, and `VITE_PUSHER_APP_CLUSTER` in `.env`. Pusher runs the WebSocket service, so no separate Reverb process is required.

Events use `ShouldBroadcastNow`, so a queue worker is not required for real-time notifications. If the application is changed to queued broadcasting, also run `php artisan queue:work`.

## Features

- Bootstrap-based, responsive agent dashboard.
- Paginated ticket API and dashboard controls (10 tickets per page).
- Live public `tickets` channel for new tickets.
- Live private `ticket.{ticketId}` channel for new messages.
- Agent-only web routes, API routes, and private channel authorization.
- Seed data: 5 open tickets with 10 messages each.

## API

All API routes require an authenticated user with the `agent` role.

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/tickets?page=1` | Paginated tickets |
| POST | `/api/tickets` | Create a ticket |
| GET | `/api/tickets/{ticket}/messages` | Ticket messages |
| POST | `/api/tickets/{ticket}/messages` | Add a message |

Message creation accepts `user_type` (`agent` or `customer`) and `message`. Ticket creation accepts `customer_name`, `email`, `subject`, and optional `status`.

## Security

`routes/channels.php` authorizes `ticket.{ticketId}` only for users holding the `agent` role. Ticket/message input is validated through Laravel Form Requests. The browser creates live ticket and message elements with DOM APIs rather than injecting user-provided content as HTML.
