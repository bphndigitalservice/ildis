# ILDIS Architecture Comparison — Document Lifecycle Orchestrator

> Generated from the grilling loop. Three designs were explored by parallel sub-agents. This document compares them and recommends a direction.

## Design A: Single Entry Point (Minimal Interface)

| Property | Value |
|----------|-------|
| **Seam** | `interface DocumentLifecycle { create(...); of(...); }` |
| **Entry points** | 2 |
| **Public types** | `CreateDocumentCommand` (discriminated union), `ActorContext`, `FilePayload`, `Result<T,E>` |
| **Dependencies** | Injected via constructor: `DatabasePort`, `StoragePort`, `AuditPort` |
| **Caller pattern** | `lifecycle.create(command, actor)` |

**Depth**: Very high. Two methods hide validation, type dispatch, transactions, file streaming, relational writes, and audit logging.

**Locality**: Very high. All orchestration lives in one implementation file.

**What it's bad at**: Querying (`of(id)` is too simple for complex filters). Bulk imports not optimized.

---

## Design B: Multi-Seam Decomposition (Maximum Flexibility)

| Property | Value |
|----------|-------|
| **Seams** | 8 modules: `DocumentStore`, `AttachmentManager`, `AuditLog`, `RelatedEntityResolver`, `DocumentValidator`, `DocumentTypeExtensionRegistry`, `StorageAdapter`, `DocumentLifecycleCoordinator` |
| **Entry points** | Coordinator: 3 (`createDocument`, `updateDocument`, `batchCreateDocuments`) + per-call `PhaseOverrides` |
| **Public types** | `CreateDocumentCommand`, `PhaseOverrides`, `BatchResult`, plus all sub-module interfaces |
| **Dependencies** | Every module receives its own dependencies; coordinator composes them |
| **Caller pattern** | `coordinator.createDocument(command, { skipAudit: true })` |

**Depth**: Low-to-medium per module, but high at the coordinator seam.

**Locality**: Lost. A creation bug spans DocumentStore + AttachmentManager + AuditLog + RelatedEntityResolver + coordinator compensation logic.

**What it's bad at**: Over-engineered for 4 document types. Transaction passing (`tx?`) pollutes every method. Per-call overrides risk making the coordinator a framework with convention-based branching.

---

## Design C: API-First (FormData-Centric)

| Property | Value |
|----------|-------|
| **Seam** | `interface DocumentModule { create(formData: FormData, actor: Actor): Promise<CreateResult>; }` |
| **Entry points** | 1+ (one per CRUD operation) |
| **Public types** | `FormData`, `Actor`, `CreateResult` (carries HTTP status codes) |
| **Dependencies** | Injected via factory: `DocumentRepositoryPort`, `StoragePort`, `AuditPort`, `ValidatorPort` |
| **Caller pattern** | `docModule.create(await c.req.formData(), actor)` returns `{ status: 201, body: ... }` |

**Depth**: High for HTTP callers. One FormData triggers parsing, dispatch, validation, storage, persistence, audit.

**Locality**: High for HTTP callers, but status codes leak into the domain module.

**What it's bad at**: Non-HTTP callers must wrap data in `new File(...)` and `FormData`. Loss of type safety (flat string keys). HTTP semantics (`status: 409`) belong in the transport layer, not the domain module.

---

## Comparison Matrix

| Criterion | Design A | Design B | Design C |
|-----------|----------|----------|----------|
| **Interface surface (small = good)** | ⭐⭐⭐ Excellent | ⭐ Too many modules | ⭐⭐ Good (1 method) |
| **Depth (leverage per entry point)** | ⭐⭐⭐ Excellent | ⭐⭐ High at coordinator, low elsewhere | ⭐⭐⭐ Excellent |
| **Locality (bugs concentrate)** | ⭐⭐⭐ Excellent | ⭐ Lost across 8 modules | ⭐⭐ Good for HTTP, bad for domain |
| **Flexibility (extension)** | ⭐⭐ Good | ⭐⭐⭐ Excellent (new type = new processor) | ⭐⭐ Good |
| **Type safety at seam** | ⭐⭐⭐ Excellent | ⭐⭐⭐ Excellent | ⭐ Poor (FormData is untyped) |
| **Hono route thinness** | ⭐⭐ Good (parse → call → map error) | ⭐⭐⭐ Excellent (coordinator does everything) | ⭐⭐⭐ Excellent (1 line) |
| **Non-HTTP caller ergonomics** | ⭐⭐⭐ Excellent | ⭐⭐⭐ Excellent | ⭐ Awkward (must construct FormData) |
| **Testability** | ⭐⭐⭐ Excellent (3 in-memory adapters) | ⭐⭐⭐ Excellent (swap any module) | ⭐⭐ Good (but FormData makes tests verbose) |
| **Prepared for future AI agent** | ⭐⭐⭐ Excellent | ⭐ Complex wiring | ⭐⭐ Good |

---

## Recommendation: Design A with a Thin HTTP Adapter

**Use Design A's module depth with a small concession to HTTP convenience.**

The winning pattern is:

1. **`DocumentLifecycle` module** exposes a type-safe, framework-agnostic interface (Design A).
2. **`@ildis/api-adapter` package** converts domain errors to Hono JSON responses. The Hono route stays thin (~5 lines) but the domain module never carries HTTP status codes.
3. Accept `File`/`Blob` primitives at the seam instead of a custom `FilePayload` — no manual construction needed, available natively in Node.js 18+.
4. Internally, borrow Design B's `DocumentTypeExtensionRegistry` concept (not exposed to callers) so new document types are add-only.

### Why this wins:

- **Depth**: One entry point hides everything. Future agent scaffolds `DocumentLifecycle` in one file.
- **Locality**: Orchestration logic is in one place. Bugs don't scatter.
- **Type safety**: The compiler checks `CreateDocumentCommand` completeness. No `FormData` string keys.
- **Non-HTTP**: Cron scripts call the exact same interface, just with in-memory adapters.
- **Hono routes**: Still under 10 lines because the api-adapter handles error mapping.

### The concession:

Instead of requiring `FilePayload` (which the agent would have to define and construct manually), the seam accepts the platform-standard `File` type:

```typescript
// Before (Design A)
interface FilePayload { fieldName: string; filename: string; mimeType: string; size: number; content: ReadableStream | Buffer; }

// After (Refined)
interface FileAttachment { fieldName: string; file: File; } // File = global Web File API (Node.js 18+)
```

This removes one abstraction layer without coupling to HTTP.

---

## Next Step

Proceed to the grilling loop to resolve:
1. Should a non-HTTP caller be able to bypass the full lifecycle (e.g., create a Document row without triggering storage + audit)?
2. Where does the unified Drizzle schema live in the monorepo?
3. Does the `DocumentTypeExtensionRegistry` (internal to the module) get exposed for a future "custom document type" feature?

**Status**: Pending user decision on the recommended direction before we scaffold the full monorepo structure.
