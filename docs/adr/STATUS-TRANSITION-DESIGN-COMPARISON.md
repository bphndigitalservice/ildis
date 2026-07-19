# StatusTransition Module Design Comparison

> Comparison of three interface designs for the Indonesian legal document status transition domain. Generated from parallel sub-agents during P1.5 exploration.

---

## Design A: Minimal Interface (Black Box)

| Property | Value |
|----------|-------|
| **Seam** | `StatusTransitionPort { apply(spec): TransitionReceipt; revert(id): TransitionReceipt; }` |
| **Entry points** | 2 |
| **Public types** | `TransitionSpec`, `TransitionDirection`, `TransitionReceipt` |
| **Caller pattern** | `statusTransition.apply(new TransitionSpec(sourceId, targetId, MENCABUT, note))` |

**Depth**: Very high. Two methods hide 150 lines of controller logic: validation, reciprocal creation, document updates, audit logging, transaction wrapping.

**Locality**: Excellent. All status-transition logic lives in one implementation directory.

**What it's bad at**: Callers cannot explain WHY a transition is invalid without asking the module. UI builders cannot render available actions without hardcoding the list.

---

## Design B: Explicit Graph (Transparent)

| Property | Value |
|----------|-------|
| **Seam** | `StatusTransition { readonly graph: LegalStatusGraph; transition(...); reverse(...); }` |
| **Entry points** | 3 (graph + transition + reverse) |
| **Public types** | `LegalStatusGraph`, `StatusNode`, `TransitionEdge`, `GuardRule`, `TransitionOutcome` |
| **Caller pattern** | Query `graph.outgoing(currentStatus)` → validate with `graph.validate(...)` → execute `transition(...)` |

**Depth**: High for `transition()` and `reverse()`. The `graph` is intentionally exposed — not a leak, but the interface.

**Locality**: Good. Domain rules live in the graph data structure (one file). But the graph is large enough that understanding it requires reading edge definitions.

**What it's bad at**: Exposing guards as data (`GuardRule` structs) means externalizing domain rules as declarative data. If a guard later requires an external API call (e.g., validating against a national legal registry), the data-driven guard breaks.

---

## Design C: DocumentRelationship (Reframe)

| Property | Value |
|----------|-------|
| **Seam** | `DocumentRelationship { establish(source, target, type, reason): Relationship; dissolve(id): void; statusOf(id): ComputedStatus; }` |
| **Entry points** | 3 |
| **Public types** | `RelationshipType`, `Relationship`, `ComputedStatus` |
| **Caller pattern** | `DocumentRelationship::establish(sourceId, targetId, MENCABUT, reason)` |

**Depth**: Highest. The module redefines what "status" means: it's a COMPUTED property from the relationship graph, not a mutable column.

**Locality**: Excellent. No `status` column to drift out of sync. The truth is always in the relationship rows.

**What it's bad at**: High migration cost. The legacy has `status` and `status_terakhir` columns that must be frozen. Need a database view (`v_document_status`) for backward compatibility. Performance hit from JOINs in queries.

---

## Comparison Matrix

| Criterion | Design A | Design B | Design C |
|-----------|----------|----------|----------|
| **Interface surface (small = good)** | Excellent (2 methods) | Medium (graph + methods) | Good (3 methods) |
| **Depth** | Excellent | Good (graph exposed) | Excellent |
| **Locality** | Excellent | Good | Excellent |
| **UI builder friendliness** | Poor (must hardcode actions) | Excellent (render from graph) | Good (render from relationships) |
| **Validation explainability** | Poor ("forbidden" with no detail) | Excellent (shows exact guard) | Good (relationship-level errors) |
| **Migration cost** | Low | Low | **High** (view + freeze columns) |
| **Performance** | Best (direct column updates) | Best (direct column updates) | Worst (JOIN for status) |
| **Correctness** | Medium (status can drift) | Medium (status can drift) | Excellent (impossible to drift) |
| **Legacy parity** | High | High | Low (reframes the whole model) |

---

## Recommendation: Design A with a Graph Query Adapter

**Use the depth of Design A but add a read-only query capability that returns the available actions.**

The winning pattern:

1. **Core interface** (`StatusTransition`): `apply()` + `revert()` — two methods, maximum depth.
2. **Query adapter** (`StatusTransitionQuery` or method on `DocumentTypeExtensionRegistry`): Returns available transitions for a given document type + current status. Used by the UI to populate dropdowns. This is a READ concern, not a MUTATION concern.
3. **Keep `status` and `status_terakhir` as mutable columns** — for now. The migration cost of Design C is too high for a P1 task. Use Design C as a future ADR if performance or correctness demands it.
4. **Expose guard violations in error messages**: `apply()` throws `GuardViolationException` with structured data about WHICH guard failed (`targetAlreadyInvalid`, `sameDocument`, etc.). This addresses Design A's "poor explainability" without exposing the full graph.

### Why this wins:

- **Depth**: `apply()` remains the single seam for all mutations. Two methods hide 150 lines.
- **Migration**: Zero schema changes beyond the existing `document_status_history` + `document_relations` tables.
- **UI friendliness**: A separate query returns `[{ id: 'mencabut', label: 'Mencabut peraturan lain', guards: [...] }, ...]` for rendering dropdowns.
- **Correctness**: Guard violations are explicit and structured. Callers can show meaningful error messages.
- **Locality**: All mutation logic in one module. Query logic in a separate read concern.

### The interface:

```typescript
// Core seam (deep module)
interface StatusTransition {
  apply(spec: TransitionSpec): Promise<Result<TransitionReceipt, StatusTransitionError>>;
  revert(transitionId: string): Promise<Result<TransitionReceipt, StatusTransitionError>>;
}

// Read model (shallow seam, used by UI)
interface StatusTransitionCatalog {
  availableActions(documentType: string, currentStatus: string): Array<{
    action: string;
    label: string;
    reciprocalLabel: string;
    requiresTargetDocument: boolean;
    requiresNotes: boolean;
    guards: GuardDescription[];
  }>;
}

interface StatusTransitionError {
  code: 'GUARD_VIOLATION' | 'TRANSITION_NOT_FOUND' | 'SAME_DOCUMENT' | 'TARGET_NOT_FOUND';
  violatedGuard?: { kind: string; message: string };
}
```

---

## Next Step

Proceed with this recommendation and produce the ADR for StatusTransition design.
