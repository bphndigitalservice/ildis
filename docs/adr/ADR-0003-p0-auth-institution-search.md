# ADR-0003: P0 Architecture Decisions — Auth, Institution Scope, and Search

## Status
Accepted

## Context

During the P0 grilling loop, six decisions were made that define how authorization, institution scoping, and search work in the new ILDIS stack. These are cross-cutting concerns that every subsequent module depends on.

---

## Decision Q1: Institution Membership (Option A + Custom Nuance)

### Ruling
Every user belongs to exactly ONE institution (mandatory `institutionId`). The system is NOT multi-tenant SaaS — users cannot switch institutions. However, users CAN add documents produced by OTHER institutions. These are called **external documents**.

### Key Implications

- `users.institution_id` — mandatory FK to `institutions`
- `documents.source_institution_id` — the institution that produced the document
- `documents.is_external` — boolean flag. `true` = added from another institution.
- When a user creates a document, `source_institution_id` defaults to their own institution.
- When a user "imports" a document from another institution (or manually enters metadata for one), `source_institution_id` = that other institution, `is_external = true`.

### Feed Exclusion
External documents are searchable and viewable in the UI, but they **MUST NOT** appear in the JDIHN feed generation. The `FeedGeneration` module filters: `WHERE is_external = false AND is_publish = 1`.

> This is the single most important discriminator in the schema. It separates "documents we manage" from "documents we know about."

---

## Decision Q2: Authorization Matrix (Option B — Fine-Grained)

### Ruling
Permissions are fine-grained and database-driven, similar to the legacy `auth_item` / `auth_assignment` tables but normalized.

### Permission Model

```
Permission = resource:action:scope
Example: "document:create:peraturan"     // Can create peraturan
Example: "document:delete:own"             // Can delete own documents
Example: "feed:generate"                   // Can trigger feed generation
Example: "user:manage"                     // Can manage users (superadmin only)
```

### Role → Permission Mapping

Roles are NOT hardcoded to capabilities. Instead, roles are collections of permissions stored in the database. This allows future customization without code changes.

| Role | Default Permissions |
|------|-------------------|
| `pustakawan` | `document:create:*`, `document:update:own`, `document:read:*`, `document:delete:own`, `attachment:*` |
| `koordinator_pustakawan` | All `pustakawan` permissions + `document:verify`, `document:publish`, `document:delete:any`, `user:read` |
| `peraturan` | `document:create:peraturan`, `document:update:own:peraturan`, `document:read:peraturan` |
| `superadmin` | `*` (wildcard — all permissions) |

### Enforcement Strategy

- **`DocumentLifecycle` module** asks `AuthorizationPolicy.can(actor, 'document:create:peraturan')` before creation.
- **`DocumentQuery` module** asks `AuthorizationPolicy.can(actor, 'document:read:putusan')` — if no, filters out `putusan` from results.
- **Hono middleware** (`rbac.ts`) checks `requirePermission('document:create:peraturan')` on the route. This is a fast-fail before the domain module is called.

> Defense in depth: middleware validates at the route level; `DocumentLifecycle` validates at the domain level.

---

## Decision Q3: Institution Scoping Location (Option C — Route Layer)

### Ruling
Institution scoping is applied at the **Hono route layer**, not inside `DocumentLifecycle` or the database adapter.

### How It Works

```typescript
// Hono middleware adds institution filter to query params
app.get('/documents', requireAuth, async (c) => {
  const actor = c.get('session').user;
  const params = {
    ...c.req.query(),           // Filter params from URL
    institutionId: actor.role === 'superadmin' 
      ? c.req.query('institutionId')   // Superadmin can override
      : actor.institutionId,           // Everyone else sees own institution
  };
  
  const result = await documentQuery.search(params);
  return c.json(result);
});
```

### Why Not Domain Layer?

- **Option C** keeps `DocumentLifecycle` and `DocumentQuery` institution-agnostic. They accept `institutionId` as a regular filter parameter, not as an auth concern.
- This allows cron jobs and system jobs to query across institutions (via `institutionId = 'all'`) without impersonating a user.
- The domain module remains pure: it validates business rules, not access rules.

### External Document Handling

The route layer does NOT filter out external documents. External documents are visible in the UI. The only place external documents are excluded is:
1. Feed generation (`is_external = false`)
2. Export to JDIHN portal

---

## Decision Q4: Search Visibility (Option A)

### Ruling

| Context | Default Scope | Can Override? |
|---------|--------------|---------------|
| Public portal (`apps/web`) | All published documents (`isPublish = 1`), any institution | No institution filter by default. User can search by institution. |
| Admin dashboard (`apps/admin`) | User's own institution + external documents | `superadmin` can view `all` or filter by `institutionId` |
| Feed generation | Institution's own documents (`isExternal = false`, `isPublish = 1`) | No override. Each institution's feed contains only their own documents. |

### Why This Matters

The public portal is a national JDIHN discovery tool. A citizen searching for "UU Ketenagakerjaan" should find it regardless of which ministry published it. The institution filter is a refinement, not a gate.

---

## Decision Q5: Search Implementation (Option B — Denormalized)

### Ruling
A dedicated `document_search_index` table is maintained alongside the normalized schema. It is the **single read model** for all search queries.

### Why Denormalized?

- The legacy `dokumen_data_subyek` view proves that JOINs across document + subjects + authors + status are too slow for public search.
- MySQL FULLTEXT on JSON columns is limited in MySQL 8.
- The denormalized table allows filtering by `judul`, `subjects`, `authors`, `type`, `tahun`, `institution_id`, `is_publish`, `is_external` in a single `SELECT`.

### How It Is Maintained

The `DocumentLifecycle` module updates the `document_search_index` row after every create/update/delete. This is synchronous within the transaction.

### Schema

```typescript
document_search_index: {
  document_id: string (PK),
  judul: text (FULLTEXT indexed),
  teu: text (FULLTEXT indexed),
  abstrak_text: text (FULLTEXT indexed),
  subjects: text (comma-separated for simple LIKE matching),
  authors: text (comma-separated),
  type: string,
  tahun: int,
  institution_id: string,
  source_institution_id: string,
  is_publish: boolean,
  is_external: boolean,
  slug: string,
  updated_at: timestamp,
}
```

> Note: Multi-value fields (`subjects`, `authors`) are stored as comma-separated strings for `LIKE` compatibility. A future iteration can add a proper junction table for faceted search.

---

## Decision Q6: Search Module Unification (Option A — One Module)

### Ruling
A single `DocumentQuery` module serves both public portal and admin dashboard.

### Interface

```typescript
interface DocumentQuery {
  search(params: SearchParams): Promise<PaginatedResults<DocumentSearchResult>>;
}

interface SearchParams {
  query?: string;           // Full-text search on judul/teu/abstrak
  filters?: {
    type?: string[];
    tahun?: number | [number, number];
    subjects?: string[];
    authors?: string[];
    institutionId?: string;
    isExternal?: boolean;
    isPublish?: boolean;
  };
  pagination: { page: number; limit: number };
  sort?: { field: string; direction: 'asc' | 'desc' };
}
```

### Route Responsibility

The Hono route normalizes params for the context:
- Public portal: sets `isPublish: true`, omits `institutionId` default.
- Admin portal: sets `institutionId: user.institutionId` (unless superadmin override).

The `DocumentQuery` module is context-agnostic. It executes what it's told.

---

## Errata / Clarifications

### On "Own Documents" vs "Any Document"

The fine-grained permission system supports scoped actions:
- `document:update:own` — user can only update documents they created
- `document:update:any` — user can update any document in their institution
- `document:delete:own` vs `document:delete:any`

This is enforced by the `AuthorizationPolicy` module, not by the database. The module checks `document.created_by === actor.id` when the permission is `:own`.

### On `source_institution_id` vs `institution_id` in Search

- `source_institution_id` = who produced the document (for attribution and feed grouping)
- `institution_id` in search params = the scope of the current query (usually user's own institution)

These are different concepts. A document has one `source_institution_id` but appears in many institutions' search results if it was imported as external.

### On Feed Generation Scope

The `FeedGeneration` module generates a feed per institution. It queries:
```
SELECT * FROM documents 
WHERE source_institution_id = ? 
  AND is_external = false 
  AND is_publish = 1
```

Each institution runs this for their own `source_institution_id`. The JDIHN aggregator pulls N feeds (one per institution) and merges them.

---

## Consequences

1. **Schema changes**: Add `institutions` table, `users.institution_id`, `documents.source_institution_id`, `documents.is_external`, `document_search_index` table.
2. **Permission system**: Need `permissions`, `role_permissions`, and `user_permissions` tables (or columns).
3. **Feed generation changes**: Must filter on `is_external = false`.
4. **Migration complexity**: Legacy documents need `source_institution_id` inferred from `_created_by` → `user` → institution mapping.
5. **Performance**: Search is fast (denormalized). Writes are slightly slower (must update both `documents` and `document_search_index` in the same transaction).

---
*Recorded during P0 grilling loop, 2026-06-13.*
