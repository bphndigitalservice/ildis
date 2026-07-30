# ADR-0005: Frontend Presentation Layer Design

## Status
Proposed (pending user approval)

## Context

The ILDIS frontend layer lives in two SvelteKit apps (`apps/web` for public portal, `apps/admin` for CRUD dashboard). The current blueprint Section 9 only says "use SvelteKit `load` functions and Better Auth client" — which provides zero leverage for a future scaffold agent. Three designs were compared (see `/docs/adr/FRONTEND-DESIGN-COMPARISON.md`):

- **Design A**: Single `<DocumentForm />` deep component
- **Design B**: Multi-seam composition (many small components)
- **Design C**: SvelteKit-native convention-driven `<DocumentForm>` with slots

The recommendation is **Design C with pure function extraction**.

---

## Decision

The `@ildis/ui` package exports:
1. **One deep Svelte component** `<DocumentForm>` with props and slots
2. **Pure functions** for schema resolution, validation, and serialization (framework-agnostic)
3. **Shared UI primitives** (DocumentCard, FacetCheckbox, Pagination) for search
4. **Auth adapter** `createSessionHook` for SvelteKit SSR integration

Search UIs are **not shared** between apps (per Q4-B).

---

## Interface

### `<DocumentForm>` — Primary Seam

```svelte
<!-- packages/ui/src/document-form/DocumentForm.svelte -->

<script lang="ts" context="module">
  export interface DocumentFormProps {
    type: string;
    documentId?: string;
    mode?: 'create' | 'edit';
    showCommon?: boolean;      // Default: true
    showMetadata?: boolean;    // Default: true
    showUpload?: boolean;      // Default: true
    showSubjects?: boolean;    // Default: true
    showAuthors?: boolean;     // Default: true
    schemaAdapter?: SchemaAdapter;
    validate?: ValidateFn;
  }
</script>

<script lang="ts">
  import { createEventDispatcher } from 'svelte';
  
  export let type: string;
  export let documentId: string | undefined = undefined;
  export let mode: 'create' | 'edit' = documentId ? 'edit' : 'create';
  export let showCommon = true;
  export let showMetadata = true;
  export let showUpload = true;
  export let showSubjects = true;
  export let showAuthors = true;
  
  const dispatch = createEventDispatcher<{
    success: { document: DocumentView };
    error: { error: unknown };
  }>();
  
  // Internal: fetches schema, manages form state, handles upload + submit
</script>
```

### Slots (Escape Hatches)

All slots are optional. If not provided, the component renders default layouts.

| Slot | Params | Replaces |
|------|--------|----------|
| `default` | — | Entire form body |
| `common-fields` | `{ fields, value, onChange }` | Common fields section (judul, tahun, abstrak text) |
| `metadata-fields` | `{ fields, schema, value, onChange }` | Type-specific metadata section |
| `upload-area` | `{ status, files, progress }` | File attachment upload zone |
| `subject-selector` | `{ subjects, onChange }` | Subject tagging section |
| `author-selector` | `{ authors, onChange }` | Author (TEU) section |
| `actions` | `{ submitting, disabled, onSubmit }` | Submit / cancel buttons |

### Pure Functions (Framework-Agnostic)

```typescript
// packages/ui/src/document-form/utils.ts

/**
 * Fetch document type schema from backend.
 */
export async function resolveDocumentSchema(
  type: string,
  fetch: typeof globalThis.fetch
): Promise<DocumentTypeSchema>;

/**
 * Validate form values against a schema.
 */
export function validateDocumentForm(
  schema: DocumentTypeSchema,
  values: Record<string, unknown>
): Record<string, string>; // field → error message

/**
 * Serialize form values to API payload shape.
 * Separates common fields from metadata JSON.
 */
export function createDocumentPayload(
  commonValues: Record<string, unknown>,
  metadataValues: Record<string, unknown>,
  attachments: { kind: string; file: File }[],
  subjects: string[],
  authors: Array<{ nama: string; urutan?: number }>
): CreateDocumentCommand;
```

### Auth Integration

```typescript
// packages/ui/src/auth/session-hook.ts

import type { Handle } from '@sveltejs/kit';

export function createSessionHook(options: {
  betterAuthClient: BetterAuthClient;
  cookieName?: string;
}): Handle;

// Returns a SvelteKit handle that:
// 1. Reads the session cookie from the request
// 2. Validates it via Better Auth client
// 3. Sets event.locals.user if valid
```

### Search Primitives

```typescript
// packages/ui/src/search/index.ts

// Data
export { documentApi } from './api';

// UI Components (Svelte)
export { default as DocumentCard } from './DocumentCard.svelte';
export { default as FacetCheckbox } from './FacetCheckbox.svelte';
export { default as Pagination } from './Pagination.svelte';
export { default as SortDropdown } from './SortDropdown.svelte';
```

---

## Usage Examples

### Default Case (Trivial)

```svelte
<!-- apps/admin/src/routes/documents/[type]/new/+page.svelte -->
<script lang="ts">
  import { DocumentForm } from '@ildis/ui';
  import { goto } from '$app/navigation';
  import type { PageData } from './$types';

  export let data: PageData;
</script>

<div class="max-w-3xl mx-auto py-8">
  <h1 class="text-2xl font-bold mb-6">Buat Dokumen Baru</h1>
  
  <DocumentForm
    type={data.type}
    on:success={(e) => goto(`/documents/${e.detail.document.id}`)}
  />
</div>
```

### Edit Mode

```svelte
<!-- apps/admin/src/routes/documents/[id]/edit/+page.svelte -->
<script lang="ts">
  import { DocumentForm } from '@ildis/ui';
  export let data: PageData;
</script>

<DocumentForm
  type={data.document.type}
  documentId={data.document.id}
  on:success={() => goto(`/documents/${data.document.id}`)}
/>
```

### Custom Layout (Using Slots)

```svelte
<!-- apps/admin/src/routes/documents/[type]/wizard/+page.svelte -->
<script lang="ts">
  import { DocumentForm } from '@ildis/ui';
  export let data: PageData;
</script>

<DocumentForm type={data.type} let:schema let:formStore>
  <div class="grid grid-cols-2 gap-4" slot="metadata-fields" let:fields>
    <section class="col-span-2 bg-gray-50 p-4 rounded">
      <h3 class="font-semibold mb-2">Data Legal</h3>
      {#each fields.filter(f => f.category === 'legal') as field}
        <label class="block mb-2">
          {field.label}
          <input
            type={field.type}
            bind:value={$formStore[field.name]}
            class="w-full border rounded px-2 py-1"
          />
        </label>
      {/each}
    </section>
    
    <section class="bg-gray-50 p-4 rounded">
      <h3 class="font-semibold mb-2">Data Administrasi</h3>
      {#each fields.filter(f => f.category === 'admin') as field}
        <label class="block mb-2">
          {field.label}
          <input
            type={field.type}
            bind:value={$formStore[field.name]}
            class="w-full border rounded px-2 py-1"
          />
        </label>
      {/each}
    </section>
  </div>

  <svelte:fragment slot="actions" let:submitting let:onSubmit>
    <button
      type="button"
      on:click={onSubmit}
      disabled={submitting}
      class="bg-blue-600 text-white px-4 py-2 rounded"
    >
      {submitting ? 'Menyimpan...' : 'Simpan'}
    </button>
  </svelte:fragment>
</DocumentForm>
```

### Metadata-Only Form (Skipping Upload)

```svelte
<!-- apps/web/src/routes/upload-simple/+page.svelte -->
<DocumentForm
  type="artikel"
  showUpload={false}
  showSubjects={false}
  showAuthors={false}
  on:success={() => alert('Artikel tersimpan')}
/>
```

---

## Implementation Notes

### Internal Architecture

`<DocumentForm>` is composed internally (but these are NOT public seams):

```
DocumentForm (public seam)
├── CommonFields (hardcoded: judul, tahun, abstrak text, tempatTerbit, etc.)
├── MetadataFields (schema-driven: renders from backend schema)
├── AttachmentUploader (file input + progress + server upload)
├── SubjectSelector (search + hierarchical tree)
├── AuthorSelector (TEU input + order management)
└── DocumentFormContext (Svelte context: formStore, schemaStore, progressStore)
```

### Schema Fetching Strategy

```typescript
// Inside DocumentForm.svelte
import { onMount } from 'svelte';
import { resolveDocumentSchema } from './utils';

let schema: DocumentTypeSchema | null = null;

onMount(async () => {
  schema = await resolveDocumentSchema(type, fetch);
});
```

- Default: client-side fetch on mount (progressive enhancement)
- SSR optimization: route's `+page.ts` can preload schema and pass as `initialSchema` prop

### File Upload Flow

```
User selects file
→ browser builds FormData()
→ POST /api/documents/{id}/attachments (multipart)
→ Hono receives file
→ Hono calls StoragePort.put()
→ S3 returns URL
→ Hono returns { url, filename }
→ DocumentForm updates internal attachments state
```

Upload progress tracked via `XMLHttpRequest` progress events.

### Auth State Flow

```
Browser request
→ SvelteKit server hook (hooks.ts)
   → reads session cookie
   → validates with Better Auth
   → sets event.locals.user
→ +page.ts load function
   → reads locals.user
   → passes to page data
→ +page.svelte
   → accesses user via $page.data.user
   → DocumentForm reads user for created_by
```

---

## Trade-offs

| Concern | Decision | Rationale |
|---------|----------|-----------|
| Svelte-only slots | Accept | ILDIS has no React/Vue requirement. Portability to other frameworks is not a P1 concern. If needed later, extract pure functions and wrap in React component. |
| Monolithic DocumentForm | Accept | The component is internally composed (CommonFields, MetadataFields, etc.) but exposes only one public seam. Internal decomposition is an implementation concern, not an interface concern. |
| Search not shared | Accept | Q4-B decision. Public and admin search have divergent UX (discovery vs. CRUD). Shared primitives are sufficient. |
| Client-side schema fetch | Accept | Default is client-side for progressive enhancement. SSR preloading is optional (`initialSchema` prop). |
| No pre-signed URLs | Accept | Q2-A decision. Server-mediated upload is simpler. Latency is acceptable for legal document sizes (< 50MB PDFs). |

---

## Consequences

### Positive
- Default case is one line of Svelte markup
- Slots provide escape hatches without fragmenting into many modules
- Pure functions are framework-agnostic and testable
- Auth state is SSR-first (no client-side loading states)

### Negative
- `<DocumentForm>` is Svelte-specific. Future React admin panel would need re-wrapping.
- Slot props expose internal `formStore` via `let:`. If store shape changes, all slot overrides break.
- Client-side schema fetch causes a brief loading state before type-specific fields appear.

---

## Related
- `/docs/adr/FRONTEND-DESIGN-COMPARISON.md` — Three designs compared
- `/docs/ARCHITECTURE-BLUEPRINT.md` Section 9 — Original placeholder section (now superseded)

---
*Proposed during P1.6 exploration, 2026-06-13.*
