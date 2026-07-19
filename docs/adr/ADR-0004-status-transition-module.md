# ADR-0004: StatusTransition Module Design

## Status
Proposed (pending user approval)

## Context

The legacy `PeraturanController` implements Indonesian legal document status transitions in a ~150-line `actionTambahStatus()` method. The logic handles:

- `dicabut` ↔ `mencabut` (revoked by ↔ revokes)
- `diubah` ↔ `mengubah` (amended by ↔ amends)
- Guard conditions: cannot revoke/amend a document already `Tidak Berlaku`
- Bidirectional updates: both source and target documents' `status` and `status_terakhir` columns are updated
- Reversibility: deleting a status entry restores both documents to `Berlaku`
- Applicable ONLY to `peraturan` document type

Three designs were explored (see `/docs/adr/STATUS-TRANSITION-DESIGN-COMPARISON.md`):
- **Design A**: Minimal black-box interface (2 methods)
- **Design B**: Explicit graph interface
- **Design C**: Reframe as `DocumentRelationship` with computed status

The recommendation is **Design A with a read-only catalog adapter**.

---

## Decision

The `StatusTransition` module will expose a minimal interface for mutations (`apply`/`revert`) with a separate read-only `StatusTransitionCatalog` for UI rendering. The module is the **sole seam** for creating, modifying, or reversing status transitions. No caller (controller, route handler, cron job) directly manipulates `document.status`, `document.status_terakhir`, or `document_status_history` rows.

---

## Interface

```typescript
// packages/domain/src/status-transition.ts

export type TransitionAction = 'mencabut' | 'mengubah';

export interface TransitionSpec {
  sourceDocumentId: DocumentId;    // The document doing the acting
  targetDocumentId: DocumentId;      // The document being acted upon
  action: TransitionAction;          // 'mencabut' = source revokes target
  notes?: string;                  // Catatan / reason
  effectiveDate?: Date;            // Defaults to now
}

export interface TransitionReceipt {
  transitionId: string;             // The canonical history entry ID
  sourceDocumentId: DocumentId;
  targetDocumentId: DocumentId;
  action: TransitionAction;
  reciprocalTransitionId: string;   // The inverse entry
  previousSourceStatus: string;     // For audit / undo
  previousTargetStatus: string;
  appliedAt: Date;
}

export interface StatusTransition {
  /**
   * Apply a legal status transition between two documents.
   * INVARIANTS:
   * - Both documents MUST be type 'peraturan'.
   * - sourceDocumentId MUST NOT equal targetDocumentId.
   * - For 'mencabut' or 'mengubah': target document MUST NOT have status = 'Tidak Berlaku'.
   * - The creates a reciprocal entry (dicabut ↔ mencabut, diubah ↔ mengubah).
   * - Updates document.status and document.status_terakhir on BOTH documents.
   * - Wraps all writes in a database transaction.
   * - Records an audit entry in document_status_history.
   */
  apply(
    spec: TransitionSpec,
    actor: ActorContext
  ): Promise<Result<TransitionReceipt, StatusTransitionError>>;

  /**
   * Revert a previously applied transition by its ID.
   * Restores both documents' status to 'Berlaku' and status_terakhir to empty.
   * Removes both the canonical and reciprocal history entries.
   * INVARIANTS:
   * - The transitionId MUST correspond to an existing, non-reverted entry.
   * - Both documents MUST still exist (soft-deletion of relationship, not documents).
   */
  revert(
    transitionId: string,
    actor: ActorContext
  ): Promise<Result<TransitionReceipt, StatusTransitionError>>;
}

export type StatusTransitionError =
  | { code: 'GUARD_VIOLATION'; violatedGuard: string; message: string }
  | { code: 'SAME_DOCUMENT'; message: string }
  | { code: 'TARGET_NOT_FOUND'; message: string }
  | { code: 'TARGET_ALREADY_INVALID'; message: string }
  | { code: 'TRANSITION_NOT_FOUND'; message: string }
  | { code: 'DOCUMENT_TYPE_INVALID'; message: string };
```

### Read-Only Catalog (for UI)

```typescript
// packages/domain/src/status-transition-catalog.ts

export interface StatusTransitionCatalog {
  /**
   * List available actions for a document, given its current status.
   * Returns empty array for non-peraturan documents.
   * Used by frontend to populate action dropdowns.
   */
  availableActions(
    documentType: string,
    currentStatus: string
  ): Array<{
    action: TransitionAction;
    label: string;
    reciprocalLabel: string;
    requiresTargetDocument: boolean;
    requiresNotes: boolean;
    guards: Array<{ kind: string; description: string }>;
  }>;
}
```

---

## Implementation Details

### What Gets Created

For `apply({ source: A, target: B, action: 'mencabut' })`:

1. **Validate guards**:
   - A.type === 'peraturan' && B.type === 'peraturan'
   - A.id !== B.id
   - B.status !== 'Tidak Berlaku'

2. **Create history entries** (inside DB transaction):
   - `document_status_history` row 1: `{ documentId: A, status: 'mencabut', targetDocumentId: B, ... }`
   - `document_status_history` row 2: `{ documentId: B, status: 'dicabut', targetDocumentId: A, ... }`

3. **Update document rows** (inside same transaction):
   - `A.status = 'Berlaku'`, `A.status_terakhir = 'mencabut'`
   - `B.status = 'Tidak Berlaku'`, `B.status_terakhir = 'dicabut'`

4. **Record audit log** (outside transaction, fire-and-forget):
   - `audit_log` entry: action='STATUS_TRANSITION_APPLIED', details={ transitionId, action, sourceId, targetId }

### Reversal Logic

For `revert(transitionId)`:

1. Find the history entry by `transitionId`
2. Find its reciprocal entry by `targetDocumentId`
3. **Revert both documents** to `status = 'Berlaku'`, `status_terakhir = null`
4. **Delete** both history entries (hard delete — or soft-delete if audit retention required)
5. Record audit log

### Per-Type Rules

Only `peraturan` documents participate in this module. The `DocumentTypeExtensionRegistry` registers:

```typescript
peraturanProcessor.allowedStatusTransitions = [
  { action: 'mencabut', reciprocal: 'dicabut', guard: 'targetNotInvalid' },
  { action: 'mengubah', reciprocal: 'diubah', guard: 'targetNotInvalid' },
];
```

---

## Schema

No new tables needed beyond existing `document_status_history` and `documents`.

`document_status_history` fields:
- `id`: uuid PK
- `document_id`: uuid FK → documents
- `status`: string ('mencabut', 'dicabut', 'mengubah', 'diubah')
- `target_document_id`: uuid FK → documents (nullable)
- `catatan`: text
- `tanggal_perubahan`: timestamp
- `created_by`: string (actor id)
- `created_at`: timestamp

---

## Trade-offs

| Concern | Decision | Rationale |
|---------|----------|-----------|
| Keep `status` as mutable column | Yes (for now) | Migration cost of Design C (computed status) is too high. Address in future ADR if drift becomes a problem. |
| Separate read-only catalog | Yes | UI needs to render available actions without hardcoding. Catalog is shallow; mutations are deep. |
| Hard-delete vs soft-delete on revert | Hard-delete | The legacy hard-deletes. If audit retention is needed, keep rows with `reverted_at` timestamp. |
| Only `peraturan` participates | Yes | Monografi, Artikel, Putusan do not have legal revocation/amendment semantics. |

---

## Consequences

### Positive
- `PeraturanController::actionTambahStatus` shrinks from ~150 lines to ~10 lines.
- Guard conditions are centralized and cannot be bypassed.
- Tests validate guard logic without bootstrapping HTTP.

### Negative
- `status` and `status_terakhir` columns remain mutable, creating a small risk of drift if someone writes to them outside the module.
- The catalog interface (`availableActions`) duplicates some domain knowledge that also lives in the module's guard logic.

---

## Related
- `/docs/adr/STATUS-TRANSITION-DESIGN-COMPARISON.md` — Three designs explored
- `/docs/ARCHITECTURE-BLUEPRINT.md` Section 5.3 — Original placeholder interface

---
*Proposed during P1.5 exploration, 2026-06-13.*
