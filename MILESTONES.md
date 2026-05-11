# Milestones & Roadmap — Invoicely Backend

## Overview

- **Total Duration**: 6 Sprints (≈ 6 weeks)
- **Team**: 1 developer (Full-stack)
- **Velocity Estimate**: 4-5 story points/sprint (using Fibonacci)

---

## Sprint 1: Foundation & Infrastructure (Week 1)

**Focus**: Scaffolding, Sanctum, PostgreSQL, Migrations, TenantScope

### Tasks

| Task                                                                                                               | Effort | Dependencies       |
| ------------------------------------------------------------------------------------------------------------------ | ------ | ------------------ |
| Install Sanctum & Filament via Composer                                                                            | 1 SP   | —                  |
| Configure PostgreSQL (`config/database.php`, `.env`)                                                               | 1 SP   | —                  |
| Publish Sanctum config + run migrations                                                                            | 1 SP   | Sanctum installed  |
| Install Filament v4 + create admin user                                                                            | 2 SP   | Filament installed |
| Create `TenantScope` trait (auto `team_id` scoping)                                                                | 3 SP   | —                  |
| Create all migration files (branches, clients, items, invoices, invoice_items, payments, invoice_number_sequences) | 3 SP   | —                  |
| Run migrations + seeders (factories)                                                                               | 1 SP   | Migrations done    |
| Create `InvoiceNumberSequence` model + logic                                                                       | 2 SP   | Migration done     |
| Create `InvoiceCalculator` service class                                                                           | 3 SP   | —                  |
| Create all new Models (Client, Item, Invoice, InvoiceItem, Payment, Branch)                                        | 2 SP   | Migrations done    |
| Create all new Factories                                                                                           | 2 SP   | Models done        |
| Define `api.php` routes (route group with Sanctum)                                                                 | 1 SP   | Sanctum ready      |

### Definition of Done

- [ ] `php artisan migrate` runs cleanly on PostgreSQL
- [ ] Sanctum issues tokens via `POST /api/auth/login`
- [ ] `TenantScope` trait available and testable
- [ ] All models exist with proper relationships
- [ ] Factories create valid model instances

---

## Sprint 2: API Authentication & Authorization (Week 2)

**Focus**: Sanctum auth, Policies, FormRequests, Rate Limiting

### Tasks

| Task                                                           | Effort | Dependencies       |
| -------------------------------------------------------------- | ------ | ------------------ |
| `AuthController` (login, logout, me, register if needed)       | 3 SP   | Sanctum installed  |
| `LoginRequest` + validation                                    | 1 SP   | —                  |
| Create all Policies (Client, Item, Invoice, Payment, Branch)   | 5 SP   | Models ready       |
| Ensure `InvoicePolicy` — Owner can delete, all can view/create | 2 SP   | Policies framework |
| Configure Rate Limiting on `api.php`                           | 1 SP   | —                  |
| Add `X-Team-Id` middleware or header-based team resolution     | 2 SP   | TenantScope ready  |
| Create all FormRequests for each resource                      | 5 SP   | —                  |
| Add UUID trait to Invoice and Client                           | 2 SP   | Models ready       |
| Create `RequestIdMiddleware` for logging context               | 1 SP   | —                  |
| Configure daily logging with request ID                        | 1 SP   | —                  |

### Definition of Done

- [ ] All API endpoints pass auth via Bearer token
- [ ] Policies correctly prevent unauthorized access
- [ ] Rate limiting returns 429 on excess requests
- [ ] Invoices and Clients have UUIDs, ID enumeration impossible
- [ ] Log files contain request_id, user_id, team_id

---

## Sprint 3: Core API — Clients & Items (Week 3)

**Focus**: Full CRUD for Clients and Items, API Resources, Feature Tests

### Tasks

| Task                                                     | Effort | Dependencies             |
| -------------------------------------------------------- | ------ | ------------------------ |
| `ClientController` (index, show, store, update, destroy) | 3 SP   | Policies, Requests ready |
| `ItemController` (index, show, store, update, destroy)   | 3 SP   | Policies, Requests ready |
| `ClientResource` JsonResource                            | 1 SP   | —                        |
| `ItemResource` JsonResource                              | 1 SP   | —                        |
| Feature Tests: ClientTest (CRUD + team isolation)        | 5 SP   | Controllers done         |
| Feature Tests: ItemTest (CRUD + team isolation)          | 5 SP   | Controllers done         |
| Feature Tests: AuthTest (login, logout, token expiry)    | 3 SP   | AuthController done      |

### Definition of Done

- [ ] `GET /api/clients` returns paginated, scoped to team
- [ ] `POST /api/clients` creates with validation
- [ ] Cross-team access returns 403 in tests
- [ ] All endpoint responses use JsonResource format
- [ ] 100% test pass rate

---

## Sprint 4: Core API — Invoices (Week 4)

**Focus**: Invoice CRUD, auto-numbering, server-side calculations, InvoiceItems

### Tasks

| Task                                                                  | Effort | Dependencies             |
| --------------------------------------------------------------------- | ------ | ------------------------ |
| `InvoiceController` (index, show, store, update, destroy)             | 5 SP   | Policies, Requests ready |
| `InvoiceItemController` (nested under invoices)                       | 2 SP   | —                        |
| Integrate `InvoiceCalculator` in store flow                           | 2 SP   | Calculator done          |
| Integrate `InvoiceNumberSequence` for auto-numbering                  | 2 SP   | Sequence model done      |
| `InvoiceResource` + `InvoiceItemResource`                             | 2 SP   | —                        |
| Feature Tests: InvoiceTest (CRUD + server-side calc + team isolation) | 8 SP   | Controller done          |
| Feature Tests: TeamIsolationTest (cross-team access suite)            | 5 SP   | All controllers done     |
| Unit Tests: `InvoiceCalculatorTest`                                   | 3 SP   | Calculator done          |
| Unit Tests: `NumberSequenceTest`                                      | 2 SP   | Sequence model done      |

### Definition of Done

- [ ] Invoice totals calculated server-side, never client-side
- [ ] Invoice number auto-generated per team (e.g., INV-2026-00001)
- [ ] Deleting invoice restricted to Owner role
- [ ] TeamIsolationTest ensures no data leak between teams

---

## Sprint 5: Payments, Branches, Dashboard & Logging (Week 5)

**Focus**: Payments CRUD, Branch management, API dashboard stats, async logging

### Tasks

| Task                                                          | Effort | Dependencies        |
| ------------------------------------------------------------- | ------ | ------------------- |
| `PaymentController` (index, show, store)                      | 3 SP   | Policies ready      |
| `BranchController` (index, show, store, update)               | 2 SP   | Policies ready      |
| `PaymentResource` + `BranchResource`                          | 1 SP   | —                   |
| `DashboardController` (stats: totals, charts data)            | 3 SP   | All models ready    |
| Feature Tests: PaymentTest                                    | 4 SP   | Controller done     |
| Feature Tests: BranchTest                                     | 3 SP   | Controller done     |
| Async logging on critical actions (Invoice deletion, Payment) | 2 SP   | Logging config done |
| Audit logging: format with context for debugging              | 1 SP   | Logging done        |

### Definition of Done

- [ ] Payments can be recorded against invoices
- [ ] Branch filtering works in Invoice index
- [ ] Dashboard returns aggregated team stats
- [ ] Deletion events logged with full context

---

## Sprint 6: Filament Admin Dashboard (Week 6)

**Focus**: Owner admin panel, Polish, Final integration, Documentation

### Tasks

| Task                                                             | Effort | Dependencies       |
| ---------------------------------------------------------------- | ------ | ------------------ |
| Create Filament Admin User (seeder)                              | 1 SP   | Filament installed |
| `UserResource` — Owner manages employees (CRUD, assign to team)  | 5 SP   | —                  |
| `TeamResource` — edit team settings, invoice prefix, tax rate    | 3 SP   | —                  |
| `ClientResource` — read-only view                                | 2 SP   | —                  |
| `ItemResource` — read-only view                                  | 2 SP   | —                  |
| `InvoiceResource` — read-only view with filters                  | 3 SP   | —                  |
| `PaymentResource` — read-only view                               | 2 SP   | —                  |
| `BranchResource` — manage branches                               | 2 SP   | —                  |
| Dashboard Widgets: Revenue chart, stats overview, recent items   | 3 SP   | —                  |
| Feature Test: AdminDashboardTest (access control for non-owners) | 2 SP   | —                  |
| Final `vendor/bin/pint` formatting                               | 1 SP   | —                  |

### Definition of Done

- [ ] Owner can add/remove employees to teams
- [ ] Owner sees all teams' data in read-only views
- [ ] Non-owner users cannot access /admin (403)
- [ ] All tests pass: `php artisan test --compact`
- [ ] Code is Pint-formatted
