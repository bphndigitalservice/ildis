# Blueprint Architecture Review — Deepening Opportunities

> Review of `/docs/ARCHITECTURE-BLUEPRINT.md` using the vocabulary from `LANGUAGE.md`: module, interface, implementation, depth, seam, adapter, leverage, locality.

---

## Assessment: The DocumentLifecycle Module is Deep

The `DocumentLifecycle` module (Section 5.2) and the `DocumentTypeExtensionRegistry` (Section 5.1) are genuinely deep: small interfaces hide large implementations (validation, transactions, file streaming, audit, relational upserts). The **deletion test** passes — remove `DocumentLifecycle` and the complexity of "how to create a document" reappears across every caller.

**But the rest of the blueprint is shallow or missing.** The document domain is ~30% of the legacy system. The blueprint treats it as the whole system.

---

## Category A: Shallow Modules in the Blueprint (Present but No Depth)

These sections exist in the document but the **interface is nearly as complex as reading the implementation**. Applying the **deletion test**: remove the section and nothing is lost — the agent knows the same thing from reading framework documentation.

### 1. Frontend Presentation Layer — `apps/web` and `apps/admin`

**Files (in blueprint)**:
- Section 9 (`SvelteKit Frontend`, lines 951–990)
- Section 3 monorepo structure: `apps/web/`, `apps/admin/`, `packages/ui/`

**Problem**: The interface to building the frontend is "use SvelteKit `load` functions, `fetch` the API, install Tailwind." No discussion of:
- How type-specific document forms render dynamically (Peraturan form has `nomorPeraturan`; Monografi form has `isbn`). The backend has `DocumentTypeExtensionRegistry`, but there is no frontend equivalent.
- How the document search/filter UI is built (the legacy has `PeraturanSearch` with 60+ filterable fields, plus the `dokumen_data_subyek` denormalized view for the public portal).
- How `@ildis/ui` components are structured (form inputs, tables, upload widgets).
- How auth state (Better Auth session) propagates from API → frontend → SSR hydration boundaries.
- How file upload works in the browser (multipart form construction, progress bars, pre-signed URLs for S3 direct upload).

**Shallowness**: The section tells an agent to do exactly what the SvelteKit documentation already says. Zero leverage. Zero locality for frontend-specific bugs.

**Solution**: Define a `DocumentFormRenderer` module in `@ildis/ui` that accepts a `DocumentTypeProcessor` and renders the correct fields. Define a `DocumentSearch` module for the public portal that accepts filter parameters and produces a query. Specify how `@ildis/ui` shares types with `@ildis/domain` (via `@ildis/types` or by re-exporting).

**Benefits**:
- **Locality**: A bug in "how to render a Peraturan form" concentrates in one component, not in every SvelteKit route.
- **Leverage**: One form renderer serves all 4+ document type creation pages.

---

### 2. Adapter Strategy — Section 7

**Files (in blueprint)**:
- Section 7 (`Adapter Strategy`, lines 790–871)
- Monorepo structure: `packages/storage-adapters/`, `packages/audit-adapters/`, `packages/db-adapters/`

**Problem**: This section is an adapter **catalogue**, not an adapter **design**. It lists `S3StorageAdapter`, `LocalFileSystemAdapter`, `InMemoryStorageAdapter` in a table. But:
- The `StoragePort` interface is defined in `@ildis/domain`, yet the section doesn't explain how the port interface migrates from `@ildis/domain` into concrete packages without circular dependencies.
- No discussion of `StoragePort` → `LocalFileSystemAdapter` wiring. Does the adapter import the port? Yes — `@ildis/storage-adapters` depends on `@ildis/domain`. But what about `getPublicUrl(key)`? How is the public URL base constructed for local dev vs. S3 CDN?
- No discussion of adapter initialization configuration (env vars, buckets, paths). Where does `S3_BUCKET` get read? In the adapter package? In the composition root? In `apps/api`?

**Shallowness**: Delete Section 7 and an agent reading `@ildis/domain/src/adapters/storage-port.ts` plus the storage adapter source files would learn the same thing.

**Solution**: Deepen this into a **Storage Facade** module that owns URL generation, path construction, and storage key conventions. The `StoragePort` stays narrow (`put`, `delete`), but a `StorageFacade` module in `@ildis/domain` owns "where files live" and "what their URLs look like."

**Benefits**:
- **Locality**: Changing the URL structure for uploaded files touches one module, not every adapter.
- **Leverage**: Local and S3 adapters both satisfy the same narrow `StoragePort`, but the facade decides `key = documents/{docId}/{kind}/{uuid}-{filename}`.

---

### 3. Hono API Routes — Section 8

**Files (in blueprint)**:
- Section 8 (`Hono API Layer`, lines 875–947)
- Section 3: `packages/api-adapter/src/routes/` (8 route files listed, only `documents.ts` designed)

**Problem**: The blueprint lists 8 route files but designs only one (`documents.ts`). The other 7 (`attachments.ts`, `subjects.ts`, `authors.ts`, `status.ts`, `feed.ts`, `admin/users.ts`, `documents-types.ts`) are completely absent from the interface discussion. The error mapper only handles `DocumentCreationError`; it doesn't know about `StatusTransitionError`, `FeedGenerationError`, or auth errors.

**Shallowness**: Remove Section 8 and an agent would write the same `Hono` routes by reading the `DocumentLifecycle` interface and the Hono docs.

**Solution**: Define a **Route Registry** module that standardizes how domain modules are mounted as HTTP routes. Show the pattern once: how `StatusTransition` gets exposed as `POST /documents/:id/status`, how `FeedGeneration` gets exposed as `GET /feed.json`. Then the pattern repeats for N modules without N× replica of middleware boilerplate.

**Benefits**:
- **Locality**: Changing how auth middleware is applied to all routes touches the registry, not every route file.
- **Leverage**: One pattern serves `DocumentLifecycle`, `StatusTransition`, `FeedGeneration`, and future modules.

---

### 4. Auth & RBAC — Section 6

**Files (in blueprint)**:
- Section 6 (`Auth Boundaries`, lines 726–786)

**Problem**: The RBAC middleware is 8 lines of `includes` check. There is no module for:
- **Permission rules**: Can a `pustakawan` create `peraturan`? Can they delete `putusan`? The legacy has `auth_assignment` with thousands of rows mapping routes to roles.
- **Institution scoping**: Users belong to institutions (JDIHN members). A `pustakawan` should only see documents from their own institution. Where does this filter live?
- **Better Auth integration depth**: The blueprint says "Better Auth owns auth" but doesn't define the seam between Better Auth and ILDIS application roles. Is `role` on the `user` table? Is it a separate `user_roles` table? Is there a `user.institution_id` FK?

**Shallowness**: The section delegates everything to Better Auth docs, but ILDIS has application-specific authorization (institution-scoped roles) that Better Auth doesn't know about.

**Solution**: Extract an `AuthorizationPolicy` module that defines "who can do what to which documents." This module accepts a `User + Role + Institution` and a `Document + Type` and returns `Permitted | Denied`. It is called by both the Hono middleware AND the `DocumentLifecycle` module (defense in depth).

**Benefits**:
- **Locality**: Authorization rules live in one file, not scattered across route middleware.
- **Leverage**: The same module validates access in HTTP routes, cron jobs, and direct API calls.

---

### 5. Feed Generation — Section 5.4

**Files (in blueprint)**:
- Section 5.4 (`FeedGeneration`, lines 700–722)
- Legacy: `console/controllers/FeedController.php`

**Problem**: The `FeedGeneration` interface has one method with a 3-line signature. But:
- How is it triggered? Cron job? HTTP endpoint? On-demand?
- What is the output format? JSON array? RSS? XML? The legacy generates JSON files for JDIHN portal.
- How does caching work? Does it regenerate from scratch every time, or incrementally?
- How does the console tier fit in the new stack? Is there an `apps/cron`? A worker queue?

**Shallowness**: The interface is as small as the concept it represents, but the concept is under-specified. An agent reading this would have to make 10 implementation decisions that the blueprint should own.

**Solution**: Define `FeedGenerator` as a deep module with a `generate(params)` entry point and an internal `FeedFormatter` adapter seam. Specify the cron trigger pattern (e.g., `apps/cron/src/feed-job.ts` using `node-cron` or a queue).

**Benefits**:
- **Locality**: Feed format changes (e.g., JDIHN updates their schema) touch one formatter adapter.
- **Leverage**: One generator serves JSON, RSS, and future formats via different formatters.

---

### 6. Status Transition — Section 5.3

**Files (in blueprint)**:
- Section 5.3 (`StatusTransition`, lines 656–697)

**Problem**: The interface defines `transition()` and `allowedNext()`, but:
- The state machine graph is not defined. What are the valid transitions? `Berlaku → dicabut`? `dicabut → Berlaku`?
- Bidirectional updates (A mencabut B, therefore B is dicabut) are mentioned but not designed. Who owns the reciprocal write?
- The module references `DocumentTypeExtensionRegistry` for per-type rules, but the registry interface (Section 5.1) defines `allowedStatusTransitions: StatusTransitionRule[]` — yet the rule shape isn't connected to the state machine implementation.

**Shallowness**: The interface exists but the implementation is vague. An agent would have to reverse-engineer the state machine from the legacy `PeraturanController::actionTambahStatus`.

**Solution**: Model the state machine explicitly as a directed graph data structure, owned by `StatusTransition`. Each document type registers its graph edges at bootstrap. The module validates transitions by looking up edges, not by inline `if` chains.

**Benefits**:
- **Locality**: The entire legal status graph lives in one file.
- **Leverage**: Adding a new transition (e.g., `Berlaku → Tidak Berlaku`) is adding one edge to the graph, not editing controller logic.

---

## Category B: Missing Domains (Not in the Blueprint at All)

These are **legacy domains that the blueprint completely ignores**. The legacy has models, controllers, and database tables for each. A future agent scaffolding from this blueprint would produce a system that can't replace the old one.

### 7. Document Search & Query Module

**Legacy files**: `backend/models/PeraturanSearch.php`, `DokumenSearch.php`, `MonografiSearch.php`, `frontend/models/DokumenDataSubyek.php` (view)

**Problem**: The blueprint explicitly says "Complex filtered listings are intentionally out of scope" for `DocumentLifecycle`. But the public portal (`apps/web`) needs search. The admin dashboard needs filtered grids. The `dokumen_data_subyek` view in the legacy exists precisely to avoid expensive joins in public search.

**Gap**: There is ZERO mention of how search works. No module. No schema for the denormalized read model. No discussion of full-text search, faceted filters, or the `dokumen_data_subyek` equivalent.

**Solution**: Introduce a `DocumentQuery` module that accepts filter parameters and returns paginated results. Optionally maintain a denormalized search table (or use MySQL full-text indexes on the JSON `metadata` column).

**Benefits**:
- **Locality**: Search logic concentrates in one module.
- **Leverage**: Frontend and backend search forms share the same query builder seam.

---

### 8. Console / Cron Tier

**Legacy files**: `console/controllers/FeedController.php`, `console/controllers/DocumentController.php`, `console/controllers/UserController.php`, `console/controllers/VisitorController.php`

**Problem**: The blueprint has no `apps/cron` or `apps/worker`. The monorepo structure (Section 3) has `apps/api`, `apps/web`, `apps/admin` — but nothing for background jobs. The `FeedGeneration` module exists but nothing calls it.

**Gap**: How do feeds get generated? How do nightly statistics get aggregated? How do batch imports run?

**Solution**: Add `apps/cron` to the monorepo. Define a `JobScheduler` module (or use an existing queue like BullMQ). Each cron job is a thin script that instantiates the same adapters as `apps/api` and calls domain modules.

**Benefits**:
- **Locality**: Cron job definitions live in one app.
- **Leverage**: Cron scripts reuse the same `DocumentLifecycle`, `FeedGeneration`, and adapter factory as the API.

---

### 9. Eksemplar (Physical Copy) & Barcode Management

**Legacy files**: `backend/models/Eksemplar.php`, `PolaEksemplar.php`, `StockOpnameEksemplar.php`, barcode generation

**Problem**: The legacy manages physical library copies (exemplars), barcode patterns, and stock opname (physical inventory). The blueprint mentions `tipe_koleksi_nomor_eksemplar` and `jumlah_eksemplar` as legacy fields but has no schema, module, or UI for this.

**Gap**: Monografi has `Eksemplar` rows with barcodes. The admin dashboard generates PDF barcodes for selected copies. This is a separate domain from document metadata.

**Solution**: Add `eksemplars` and `stock_opnames` tables to Drizzle schema. Add `EksemplarInventory` module with `generateBarcode()` and `recordStockOpname()` seams.

**Benefits**:
- **Locality**: Inventory logic doesn't leak into DocumentLifecycle.
- **Leverage**: One barcode generator serves all monografi copy tracking.

---

### 10. Circulation (Loan / Return)

**Legacy files**: `backend/models/Circulation.php`, `Member.php`, `MemberType.php`

**Problem**: The legacy has a full circulation system: members borrow monografi copies, return them, pay fines (`denda` table). The blueprint has no schema for `members`, `circulations`, or `fines`.

**Gap**: This is a distinct bounded context (library operations) from document cataloging. But it shares the same database and admin dashboard in the legacy.

**Solution**: Add `circulations`, `members`, `fines` tables. Add `CirculationManager` module. Keep it in a separate monorepo package (`@ildis/circulation`) so it can evolve independently.

**Benefits**:
- **Locality**: Loan/return bugs don't contaminate document cataloging.
- **Seam**: Circulation depends on `Eksemplar` (which copy was loaned) but not on `DocumentLifecycle`.

---

### 11. Visitor Statistics & Public Feedback

**Legacy files**: `backend/models/BukuTamu.php`, `MasukanMasyarakat.php`, visitor counters, `hit_see`, `hit_download`

**Problem**: The blueprint mentions `hitSee` and `hitDownload` as integer columns on `documents`, but there's no module for incrementing them, no schema for visitor logs, and no module for public feedback (`MasukanMasyarakat`).

**Gap**: The public portal shows "most viewed documents" and allows citizens to submit feedback. This requires a `VisitorAnalytics` module and a `PublicFeedback` module.

**Solution**: Add `visitor_events` and `public_feedback` tables. Add `VisitorAnalytics` module for aggregating views/downloads. Add `PublicFeedback` module for managing citizen submissions.

**Benefits**:
- **Locality**: Analytics logic is isolated from document cataloging.
- **Leverage**: Feed generation can include "most viewed" without touching DocumentLifecycle.

---

### 12. Institution Multi-Tenancy

**Legacy files**: `Daerah`, `Provinsi`, `Kabupaten`, `Kecamatan`, institution-scoped users

**Problem**: ILDIS is used by multiple JDIHN member institutions (central government, provinces, regencies). The blueprint mentions `institutionId` on the user table but:
- There is no `institutions` table in the schema.
- There is no `InstitutionScope` module that filters queries by the user's institution.
- Documents are not scoped to institutions in the schema.

**Gap**: In the legacy, documents appear to be institution-scoped (the `_created_by` field links to users, and users have roles). Without explicit scoping, a `pustakawan` from Province A sees documents from Province B.

**Solution**: Add `institutions` table. Add `institution_id` to `documents` and all related entities. Add `InstitutionScope` module that wraps any query with an `institution_id = ?` filter.

**Benefits**:
- **Locality**: Multi-tenant logic in one module, not duplicated in every query.
- **Security**: Cannot accidentally expose cross-institution data.

---

## Category C: Structural Gaps in the Monorepo Design

### 13. No Shared Type Package

**Problem**: `apps/web` and `apps/admin` need the same TypeScript types as `packages/domain` (e.g., `DocumentView`, `ActorContext`). But `apps/*` can't depend on `packages/domain` if domain imports Drizzle types (or can it? The blueprint says `@ildis/domain` depends on `@ildis/db`).

**Shallowness**: The dependency graph in Section 3 says `@ildis/domain` depends on `@ildis/db`, but then `@ildis/ui` (used by `apps/web`) only depends on... nothing? How does the frontend know types?

**Solution**: Introduce `@ildis/types` — a package with zero runtime dependencies, containing only TypeScript interfaces and Zod schemas. Both `@ildis/domain` AND `apps/web` depend on it. Or, make `@ildis/domain` export types separately from its implementation (tree-shakeable).

---

### 14. No Error Taxonomy Across Modules

**Problem**: `DocumentLifecycle` has `DocumentCreationError`. `StatusTransition` has `{ code: 'INVALID_TRANSITION' }`. `FeedGeneration` has no error type defined. There is no shared `ILDISError` base type.

**Gap**: The Hono error mapper must handle N different error shapes from N modules.

**Solution**: Define a shared error taxonomy in `@ildis/types` or `@ildis/domain`:

```typescript
interface ILDISError {
  code: string;
  message: string;
  context?: Record<string, unknown>;
}
```

All domain modules extend this. The Hono error mapper pattern-matches on `error.code` uniformly.

---

## Summary Table

| # | Candidate | Category | Impact |
|---|-----------|----------|--------|
| 1 | Frontend Presentation Layer | Shallow | Blocks admin dashboard scaffolding |
| 2 | Adapter Strategy | Shallow | Agent must guess adapter initialization |
| 3 | Hono API Routes | Shallow | 7 of 8 routes are undesignated |
| 4 | Auth & RBAC | Shallow | Security holes due to undesignated auth seam |
| 5 | Feed Generation | Shallow | No cron / trigger / output format specified |
| 6 | Status Transition | Incomplete | State machine graph not defined |
| 7 | Document Search & Query | Missing | Public portal can't function |
| 8 | Console / Cron Tier | Missing | Background jobs have no home |
| ~~9~~ | ~~Eksemplar / Barcode~~ | ~~Missing~~ | **DEPRECATED** per ADR-0002 |
| ~~10~~ | ~~Circulation~~ | ~~Missing~~ | **DEPRECATED** per ADR-0002 |
| 11 | Visitor Stats / Feedback | Missing | Analytics and citizen engagement missing |
| 12 | Institution Multi-Tenancy | Missing | Cross-tenant data exposure risk |
| 13 | Shared Type Package | Structural | Frontend/backend type drift |
| 14 | Error Taxonomy | Structural | Inconsistent error handling |

---

## Recommendation for Next Steps

Before a scaffold agent runs, the blueprint needs deepening in this order:

1. **P0 (Blockers)**: #4 (Auth/RBAC), #7 (Search), #12 (Institution scoping). These are load-bearing — without them, the system is insecure or non-functional.
2. **P1 (High Value)**: #1 (Frontend), #6 (Status Transition), #8 (Cron tier). These define how major features work.
3. **P2 (Backfill)**: #11 (Visitor stats / Public Feedback). These complete parity with the legacy. Note: #9 (Eksemplar) and #10 (Circulation) are **DEPRECATED** per ADR-0002 and will not be migrated.
4. **P3 (Polish)**: #2 (Adapter Strategy), #3 (Hono Routes), #13 (Types), #14 (Errors). These make the scaffold agent's job trivial.

**Which of these would you like to explore?**
