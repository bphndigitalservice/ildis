# Frontend Architecture Design Comparison

> Comparison of three interface designs for ILDIS SvelteKit frontend. Decisions from grilling: Q1-C (hybrid form), Q2-A (server-mediated upload), Q3-A (SSR auth), Q4-B (separate search).

---

## Design A: Minimal Interface (Single Deep Component)

| Property | Value |
|----------|-------|
| **Seams** | `<DocumentForm />`, `documentApi`, `createSessionHook()` |
| **Entry points** | 3 |
| **Caller pattern** | `<DocumentForm typeId="peraturan" onSaved={...} />` |

**Depth**: Very high. One component hides schema fetching, field rendering, file upload, validation, serialization.

**Locality**: Good. All form logic in one component. But it's a single 800-line component.

**What it's bad at**: The caller cannot control layout. The form renders a fixed two-column layout. If admin needs a different layout, the component needs a `layout` prop — which leaks layout concerns into a data component.

---

## Design B: Multi-Seam Composition (Maximum Flexibility)

| Property | Value |
|----------|-------|
| **Seams** | `DocumentMetadataForm`, `SchemaField`, `FileUploadAdapter`, `SubjectSelector`, `DocumentFormContext` |
| **Entry points** | 5+ components |
| **Caller pattern** | Page composes all pieces via Svelte Context |

**Depth**: Mixed. `SchemaField` is shallow (just renders one input). `DocumentMetadataForm` is medium-depth. The page is deep (orchestrates everything).

**Locality**: Lost. Form state is in Context, validation is in `SchemaField`, upload is in `FileUploadAdapter`. A bug in "how the form submits" requires understanding 5 components.

**What it's bad at**: Too many seams for a system with only ~3 use cases (create, edit, search). Onboarding friction: new developers must learn which modules exist before they can build a page.

---

## Design C: Convention-Driven / SvelteKit-Optimized

| Property | Value |
|----------|-------|
| **Seams** | `<DocumentForm>` (props + slots), internal Context |
| **Entry points** | 1 main component + slot escape hatches |
| **Caller pattern** | Default: `<DocumentForm type="peraturan" />`. Override: slots + `schemaAdapter` prop. |

**Depth**: High for the default case. The component hides everything. Slots provide controlled escape hatches without fragmenting.

**Locality**: Best. Default case is one line. Override case keeps all customization in the same file (the SvelteKit route). No hunting across modules.

**What it's bad at**: Svelte-specific (slots, `let:`, Context). Not portable to React/Vue. But ILDIS has no portability requirement.

---

## Comparison Matrix

| Criterion | Design A | Design B | Design C |
|-----------|----------|----------|----------|
| **Default case simplicity** | Good (1 component) | Poor (must compose) | Excellent (1 line) |
| **Override flexibility** | Poor (props only) | Excellent (compose anything) | Good (slots + props) |
| **SvelteKit idiomatic** | Medium | Medium | Excellent (slots, Context) |
| **Depth at default seam** | Very high | Mixed | High |
| **Locality** | Good (one file) | Lost (5+ modules) | Excellent (one file, slots inline) |
| **Portability** | Medium | High | Low (Svelte-only) |
| **Onboarding friction** | Low | High | Very low |
| **Layout control** | Poor (fixed) | Excellent | Good (slot-based) |

---

## Recommendation: Design C — With One Refinement

**Use the SvelteKit-native `<DocumentForm>` with slots as the primary seam, but extract the non-Svelte logic into `@ildis/ui` pure functions.**

The winning pattern:

1. **`<DocumentForm>`** — Svelte component with props (`type`, `documentId`, `mode`) and slots (`common-fields`, `metadata-fields`, `upload-area`, `actions`). Default case is one line. Override case uses slots.

2. **Pure functions in `@ildis/ui`** — Separate from the Svelte component, export framework-agnostic utilities:
   - `resolveDocumentSchema(type)` — fetch schema from API
   - `createDocumentPayload(formData)` — serialize form to API shape
   - `validateDocumentForm(schema, values)` — validate against schema
   - These are used BY the Svelte component internally, but are also available to custom implementations.

3. **Search UIs** — Per Q4-B, `apps/web` and `apps/admin` implement their own search components. `@ildis/ui` exports shared helpers (`documentApi.search()`, `FacetPanel`, `DocumentCard`) but not a monolithic `<SearchShell>`.

### Why this wins:

- **Default case is trivial**: `<DocumentForm type="peraturan" />` — any junior developer can add a new document creation page.
- **Override is localized**: Custom layout happens in the route file via slots. No hunting across `@ildis/ui` components.
- **Pure functions are testable**: `validateDocumentForm` can be unit tested without mounting Svelte components.
- **Svelte-native**: Uses idioms (slots, Context, stores) that SvelteKit developers expect.

### The interface:

```typescript
// packages/ui/src/document-form/DocumentForm.svelte
interface DocumentFormProps {
  type: string;                          // 'peraturan' | 'monografi' | 'artikel' | 'putusan'
  documentId?: string;                 // Edit mode if provided
  mode?: 'create' | 'edit';           // Defaults based on documentId
  // Visibility toggles
  showCommon?: boolean;                 // Default: true
  showMetadata?: boolean;             // Default: true
  showUpload?: boolean;                // Default: true
  showSubjects?: boolean;              // Default: true
  showAuthors?: boolean;               // Default: true
  // Escape hatches
  schemaAdapter?: SchemaAdapter;        // Override field rendering
  validate?: ValidateFn;              // Override validation
}

// Slots (all optional)
// <svelte:fragment slot="common-fields" let:fields let:value let:onChange>
// <svelte:fragment slot="metadata-fields" let:fields let:schema>
// <svelte:fragment slot="upload-area" let:status let:progress>
// <svelte:fragment slot="actions" let:submitting let:disabled>
```

### Usage: Default (trivial)

```svelte
<!-- apps/admin/src/routes/documents/[type]/new/+page.svelte -->
<script lang="ts">
  import { DocumentForm } from '@ildis/ui';
  export let data: PageData;
</script>

<DocumentForm type={data.type} on:success={() => goto(`/documents`)} />
```

### Usage: Override (custom layout)

```svelte
<DocumentForm type="peraturan" let:schema let:formStore>
  <div class="grid grid-cols-2 gap-4" slot="metadata-fields" let:fields>
    <section class="col-span-2">
      <!-- Custom layout for legal fields -->
      {#each fields.filter(f => f.category === 'legal') as field}
        <CustomField {field} bind:value={$formStore[field.name]} />
      {/each}
    </section>
  </div>

  <svelte:fragment slot="actions" let:submitting>
    <button type="submit" disabled={submitting}>
      {submitting ? 'Menyimpan...' : 'Simpan Peraturan'}
    </button>
  </svelte:fragment>
</DocumentForm>
```

---

## Search UI — Separate per App (Q4-B)

Per the grilling decision, public and admin search are separate. `@ildis/ui` exports shared primitives, not search shells.

### Shared helpers (in `@ildis/ui`):

```typescript
// Data
export const documentApi = {
  search: (params: SearchParams) => Promise<PaginatedResult<DocumentSearchResult>>;
  facets: (params: SearchParams) => Promise<FacetCounts>;
};

// UI Primitives
export { default as FacetCheckbox } from './search/FacetCheckbox.svelte';
export { default as DocumentCard } from './search/DocumentCard.svelte';
export { default as Pagination } from './search/Pagination.svelte';
```

### App-specific search (not shared):

```svelte
<!-- apps/web/src/routes/search/+page.svelte -->
<script>
  import { documentApi, DocumentCard, FacetCheckbox } from '@ildis/ui';
  // ... public search layout
</script>

<!-- apps/admin/src/routes/search/+page.svelte -->
<script>
  import { documentApi, DataTable, BulkActions } from '@ildis/ui';
  // ... admin search layout with filters, verification status, bulk actions
</script>
```

---

## Auth Integration (SSR-first)

Per Q3-A: SvelteKit server-side hooks read the session, inject into `locals.user`.

```typescript
// apps/admin/src/hooks.ts
import { createSessionHook } from '@ildis/ui/auth';
export const handle = createSessionHook({ betterAuthClient });

// apps/admin/src/app.d.ts
interface Locals {
  user: { id: string; name: string; role: string; institutionId: string } | null;
}

// apps/admin/src/routes/+layout.ts
export const load = async ({ locals }) => ({ user: locals.user });
```

Components access auth via page data, not global stores:
```svelte
<script>
  import { getContext } from 'svelte';
  const pageData = getContext(' page');
  const user = pageData.user;
</script>
```

---

## File Upload (Server-mediated)

Per Q2-A: Browser → Hono API → S3.

Implementation inside `<DocumentForm>`:
```typescript
// When user selects files:
const formData = new FormData();
formData.append('file', file);
formData.append('kind', 'abstrak'); // or 'gambar_sampul' or 'lampiran'

// Upload to Hono API
await fetch(`/api/documents/${documentId}/attachments`, {
  method: 'POST',
  body: formData,
});
// Hono receives the file, streams to S3 via StoragePort, returns URL
```

The browser does not know about S3. Upload progress is tracked via `XMLHttpRequest` inside the component.

---

## Summary

| Metric | Design C Target |
|--------|-----------------|
| Public UI seams | 1 (`DocumentForm`) + shared primitives |
| Frontend code touched per new doc type | 0 (if default layout suffices) |
| Default form code | 1 line |
| Search UIs | Separate per app (Q4-B) |
| Auth state | SSR via `locals.user` (Q3-A) |
| File upload | Server-mediated (Q2-A) |

---

## Next Step

Proceed with this recommendation and produce the ADR for Frontend Presentation Layer.
