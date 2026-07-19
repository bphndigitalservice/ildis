# ILDIS Next-Version Architecture — Implementation Task List

> Derived from `/docs/BLUEPRINT-REVIEW.md` and P0 grilling loop (ADR-0003).
> This document is the handoff between architecture design and implementation scaffolding.

## Completed ✅

1. **Domain glossary** (`CONTEXT.md`) — Peraturan, Monografi, Artikel, Putusan, Lampiran, TEU, JDIHN
2. **Architecture comparison** (`ARCHITECTURE-COMPARISON.md`) — Design A selected (single deep module)
3. **ADR-0001** — Grilling decisions (DocumentLifecycle as sole seam, JSON metadata, public TypeExtensionRegistry)
4. **ADR-0002** — Eksemplar and Circulation deprecated
5. **ADR-0003** — P0 decisions (fine-grained auth, institution scope, denormalized search)
6. **Full blueprint v2.0** (`ARCHITECTURE-BLUEPRINT.md`) — Updated with all P0 interfaces and schema changes

---

## Remaining Tasks

### High Priority (P0 Blockers) — These Must Be Done Before Scaffolding

#### P0.1: DocumentLifecycle Module — Already Designed, Needs Implementation
- [x] **Interface defined**: `create()`, `of()`, `update()`, `delete()` with `Result<T,E>` return type
- [x] **Schema defined**: `documents` table with `metadata` JSON, `sourceInstitutionId`, `isExternal`
- [ ] **Implementation**: Write `DocumentLifecycleService` in `packages/domain/src/document-lifecycle.ts`
  - [ ] Transaction coordination (database transaction wrapping all writes)
  - [ ] Attachment streaming to storage adapter
  - [ ] Subject/author upsertion by name
  - [ ] Synchronous `document_search_index` update (see P0.3)
  - [ ] Fire-and-forget audit logging
- [ ] **Tests**: In-memory adapters (database, storage, audit) + unit tests for happy path + error cases

#### P0.2: AuthorizationPolicy Module — Interface Defined, Needs Implementation
- [x] **Interface defined**: `can(actor, permission)` and `canOn(actor, permission, resource)`
- [x] **Schema defined**: `permissions`, `role_permissions` tables
- [ ] **Implementation**: Write `AuthorizationPolicyService` in `packages/domain/src/authorization-policy.ts`
  - [ ] Permission lookup from database (with short-term caching)
  - [ ] `superadmin` wildcard rule
  - [ ] `:own` scope enforcement (check `resource.ownerId === actor.id`)
  - [ ] Role-to-permission mapping initializer (seed default roles at migration)
- [ ] **Hono middleware**: `requirePermission()` in `packages/api-adapter/src/middleware/rbac.ts`
- [ ] **Tests**: Test each role's default permission matrix

#### P0.3: DocumentQuery Module — Interface Defined, Needs Implementation
- [x] **Interface defined**: `search(params)` and `facets(params)` with denormalized index
- [x] **Schema defined**: `document_search_index` table (FULLTEXT on judul/teu/abstrak)
- [ ] **Implementation**: Write `DocumentQueryService` in `packages/domain/src/document-query.ts`
  - [ ] MySQL FULLTEXT query builder
  - [ ] Faceted filter composition (AND logic across all filters)
  - [ ] Pagination + cursor support
  - [ ] Facet aggregation queries
- [ ] **Index maintenance hook**: `DocumentLifecycle` must update `document_search_index` on every write
- [ ] **Tests**: Insert test documents, assert search results, test pagination edge cases

#### P0.4: InstitutionScope Middleware — Already Designed, Needs Wiring
- [x] **Interface defined**: `applyInstitutionScope()` Hono middleware
- [x] **Schema defined**: `institutions` table, `users.institutionId`
- [ ] **Implementation**: Write `applyInstitutionScope()` in `packages/api-adapter/src/middleware/institution-scope.ts`
  - [ ] Superadmin override via query param
  - [ ] Default scoping to user's own institution
  - [ ] No filtering on external documents (external docs remain visible)
- [ ] **Tests**: Assert superadmin can override, assert regular users are scoped

---

### Medium Priority (P1) — Complete Before First Deploy

#### P1.5: StatusTransition Module
- [ ] Define state machine graph as explicit edge data structure
- [ ] Implement `transition()` with bidirectional update logic (A mencabut B → B dicabut)
- [ ] Register per-type transition rules in `DocumentTypeExtensionRegistry`
- [ ] Tests: Invalid transitions blocked, valid transitions succeed, reciprocal entries created

#### P1.6: Frontend Presentation Layer (apps/web, apps/admin, packages/ui)
- [ ] Design `DocumentFormRenderer` — accepts `DocumentTypeProcessor` and renders correct fields
- [ ] Design `DocumentSearch` component for public portal
- [ ] Design auth state propagation (Better Auth Svelte client → SSR → hydration)
- [ ] Design file upload widget (multipart form, progress, pre-signed URLs for S3)
- [ ] Shared `@ildis/ui` component library setup

#### P1.7: FeedGeneration Module
- [ ] Add `apps/cron` to monorepo structure
- [ ] Implement `FeedGenerator` with `FeedFormatter` adapter seam
- [ ] Add `node-cron` trigger: `apps/cron/src/feed-job.ts`
  - [ ] Per-institution feed generation (`sourceInstitutionId` filter)
  - [ ] Exclude `isExternal = true` documents (ADR-0003)
  - [ ] JSON output format matching JDIHN schema
- [ ] Tests: In-memory formatter, verify external docs excluded

#### P1.8: Hono API Routes — Complete All 8 Route Modules
- [x] `documents.ts` — CRUD (designed)
- [ ] `documents-types.ts` — Document type registry admin routes
- [ ] `attachments.ts` — Direct attachment download/upload
- [ ] `subjects.ts` — Subject CRUD (hierarchical)
- [ ] `authors.ts` — Author CRUD
- [ ] `status.ts` — Status transitions (`POST /documents/:id/status`)
- [ ] `feed.ts` — On-demand feed generation endpoint (in addition to cron)
- [ ] `admin/users.ts` — User management (Better Auth admin)
- [ ] Standardize error mapper to handle all domain error codes uniformly

---

### Lower Priority (P2) — Backfill for Legacy Parity

#### P2.9: VisitorStats & PublicFeedback Modules
- [ ] Schema: `visitor_events` table (page views, downloads)
- [ ] Schema: `public_feedback` table (masukan masyarakat)
- [ ] Implementation: `VisitorAnalytics` module (aggregate hitSee/hitDownload)
- [ ] Implementation: `PublicFeedback` module (CRUD for citizen submissions)
- [ ] Frontend: Feedback form on public portal

#### P2.10: StorageFacade Deepening (Optional)
- [ ] Extract URL convention logic into `StorageFacade` module inside `@ildis/domain`
  - [ ] `StoragePort` stays narrow (`put`, `delete`)
  - [ ] `StorageFacade` owns key construction: `documents/{docId}/{kind}/{uuid}-{filename}`
  - [ ] `StorageFacade` owns public URL generation for local vs. S3 adapters
- [ ] This addresses the "shallow Adapter Strategy" finding in `BLUEPRINT-REVIEW.md`

---

### Structural (Can Be Done in Parallel)

#### S.11: Shared Type Package
- [ ] Extract `@ildis/types` or ensure `@ildis/domain` exports types tree-shakeably
- [ ] Guarantee `apps/web` can import `DocumentView`, `ActorContext`, etc. without pulling Drizzle

#### S.12: Error Taxonomy
- [ ] Define `ILDISError` base interface in `@ildis/types`
- [ ] Refactor all domain modules to extend `ILDISError`
- [ ] Refactor Hono error mapper to pattern-match on `error.code` uniformly

---

### Migration (Post-Scaffold)

#### M.13: Legacy Data Migration
- [ ] Write `scripts/migrate-legacy-data.ts`
  - [ ] Map `tipe_dokumen` → type string
  - [ ] Extract type-specific columns → JSON `metadata`
  - [ ] Migrate `data_lampiran` → `document_attachments` with `kind` discriminator
  - [ ] Migrate `data_subyek` → `document_subjects` + `subjects`
  - [ ] Migrate `data_pengarang` → `document_authors` + `authors`
  - [ ] Migrate `data_status` → `document_status_history`
  - [ ] Migrate `log_pustakawan` → `audit_log`
  - [ ] Infer `source_institution_id` from `_created_by` → `user` → institution mapping
  - [ ] Build `document_search_index` from post-migrated data (reindex script)
- [ ] File migration: Copy files from `@common/dokumen/` to new storage adapter
- [ ] User migration: Export users, hash passwords with Better Auth algorithm, import
- [ ] RBAC migration: Map legacy `auth_assignment` → `role_permissions` seed data

---

## Task Dependency Graph

```
[I]   packages/db schema
 | \
 |  [II]  DocumentLifecycle (P0.1)
 |   | \
 |   |  [III]  DocumentQuery (P0.3) — depends on search index
 |   |   |
 |   |  [III]  AuthorizationPolicy (P0.2) — called by DocumentLifecycle
 |   |   | \
 |   |   |  [IV]  Hono routes (P1.8) — depends on both
 |   |   |   |
 |   |   |  [IV]  InstitutionScope middleware (P0.4)
 |   |   |
 |   |  [III]  StatusTransition (P1.5)
 |   |
 |  [II]  FeedGeneration (P1.7) — depends on DocumentQuery
 |   |
 |  [II]  Frontend (P1.6) — depends on types + API
 |
[S] Shared types, Error taxonomy (S.11, S.12) — parallel with everything
```

---

## Acceptance Criteria for "Ready to Scaffold"

- [x] All P0 interfaces are defined with TypeScript types, invariants, and error modes
- [x] All P0 schema changes are reflected in Drizzle definitions
- [x] ADR-0003 decisions are incorporated into blueprint
- [ ] **TODO**: A scaffold agent can read this + `ARCHITECTURE-BLUEPRINT.md` and produce a running Turborepo monorepo with:
  - `apps/api` (Hono + Better Auth configured)
  - `apps/web` (SvelteKit + Tailwind)
  - `apps/admin` (SvelteKit + Tailwind)
  - `packages/db` (Drizzle schema + migrations)
  - `packages/domain` (DocumentLifecycle, DocumentQuery, AuthorizationPolicy implemented)
  - `packages/api-adapter` (routes + middleware)
  - First test passing: create a document via API, search for it, assert it appears in results

---

*Next action: Pick any P0 task and begin implementation.*
