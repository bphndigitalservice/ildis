# ILDIS Architecture Blueprint

> **Version**: 1.0
> **Date**: 2026-06-13
> **Purpose**: Complete architectural blueprint for refactoring ILDIS from Yii2 to a Turborepo monorepo with SvelteKit, Hono, Drizzle, MySQL, and Better Auth. This document is the single source of truth for future agents scaffolding and finishing the project.
> **Status**: Approved after grilling loop.

## 1. Executive Summary

ILDIS (Indonesian Law Documentation Information System) manages legal document metadata for Indonesia's JDIHN network. The legacy system is a Yii2 monolith with extremely fat controllers (1,400+ lines), zero service layer, ~1,600 PHP files, and almost no tests. This blueprint defines the greenfield replacement.

**Stack**:
- **Monorepo**: Turborepo
- **Frontend**: SvelteKit + TailwindCSS
- **API**: Hono (TypeScript)
- **Database**: MySQL via Drizzle ORM
- **Auth**: Better Auth (email/password, roles)
- **File Storage**: Local (dev) / S3-compatible (prod)

**Key Architecture Decisions**:
1. `DocumentLifecycle` is the **only** public seam for document creation (Q1 = A).
2. Single `documents` table with `metadata` JSON column for type-specific fields (Q2 = C).
3. `DocumentTypeExtensionRegistry` is a **public** seam for adding new document types without touching existing code (Q3 = B).
4. Design A (single deep module) was selected with a thin HTTP adapter for Hono.
5. All file attachments normalized into `document_attachments` with a `kind` discriminator.

---

## 2. Decisions from Grilling Loop (ADR-0001)

| ID | Decision | Rationale |
|---|---|---|
| Q1-A | `DocumentLifecycle` is the sole public seam. Even batch imports must flow through it. | Prevents bypassing validation, audit, and transaction safety. Keeps the interface surface minimal. |
| Q2-C | Single `documents` table + JSON `metadata` for type-specific data. | Avoids schema migration hell when adding fields to one type. MySQL 8.0+ JSON indexing handles performance. Type safety enforced at the application layer via TypeScript. |
| Q3-B | `DocumentTypeExtensionRegistry` exposed as public seam. | ILDIS may add `rancangan_undang_undang` or `prolegnas`. Zero existing files need modification. |

---

## 3. Monorepo Structure

```
ildis/                          # Repo root
├── turbo.json                  # Turborepo pipeline config
├── package.json                # Root workspace + devDeps
├── tsconfig.json               # Root TypeScript references
├── packages/
│   ├── db/                     # Drizzle schema + migrations
│   │   ├── src/
│   │   │   ├── schema/         # All table definitions
│   │   │   │   ├── documents.ts
│   │   │   │   ├── attachments.ts
│   │   │   │   ├── authors.ts
│   │   │   │   ├── subjects.ts
│   │   │   │   ├── status-history.ts
│   │   │   │   ├── document-relations.ts
│   │   │   │   ├── audit-log.ts
│   │   │   │   ├── users.ts    # Better Auth tables
│   │   │   │   └── ...
│   │   │   ├── index.ts
│   │   │   └── migrate.ts
│   │   ├── drizzle/
│   │   │   └── 0000_init.sql
│   │   └── package.json
│   │
│   ├── domain/                 # Core domain logic — the deep modules
│   │   ├── src/
│   │   │   ├── document-lifecycle.ts       # Deep module interface + impl
│   │   │   ├── document-type-registry.ts   # Public extension seam
│   │   │   ├── status-transition.ts        # State machine
│   │   │   ├── feed-generation.ts         # Feed builder
│   │   │   ├── types.ts                   # Domain types (commands, results, errors)
│   │   │   ├── validators.ts              # Zod/Valibot schemas
│   │   │   └── adapters/                  # Interface definitions for adapters
│   │   │       ├── database-port.ts
│   │   │       ├── storage-port.ts
│   │   │       └── audit-port.ts
│   │   └── package.json
│   │
│   ├── api-adapter/            # Hono-specific adapter layer
│   │   ├── src/
│   │   │   ├── routes/
│   │   │   │   ├── documents.ts
│   │   │   │   ├── documents-types.ts     # Document type CRUD
│   │   │   │   ├── attachments.ts
│   │   │   │   ├── subjects.ts
│   │   │   │   ├── authors.ts
│   │   │   │   ├── status.ts
│   │   │   │   ├── feed.ts
│   │   │   │   └── admin/
│   │   │   │       └── users.ts
│   │   │   ├── middleware/
│   │   │   │   ├── auth.ts               # Better Auth session middleware
│   │   │   │   ├── rbac.ts               # Role-based access control
│   │   │   │   └── error-mapper.ts        # Domain error → HTTP status
│   │   │   ├── container.ts              # Dependency injection / composition root
│   │   │   └── index.ts
│   │   └── package.json
│   │
│   ├── storage-adapters/       # File storage implementations
│   │   ├── src/
│   │   │   ├── local-storage.ts
│   │   │   ├── s3-storage.ts
│   │   │   ├── in-memory-storage.ts
│   │   │   └── index.ts
│   │   └── package.json
│   │
│   ├── audit-adapters/         # Audit log implementations
│   │   ├── src/
│   │   │   ├── mysql-audit.ts
│   │   │   ├── in-memory-audit.ts
│   │   │   └── index.ts
│   │   └── package.json
│   │
│   ├── db-adapters/            # Database port implementations
│   │   ├── src/
│   │   │   ├── drizzle-mysql.ts
│   │   │   ├── drizzle-sqlite.ts
│   │   │   ├── in-memory-db.ts
│   │   │   └── index.ts
│   │   └── package.json
│   │
│   └── ui/                     # Shared UI components for SvelteKit
│       ├── src/
│       │   ├── components/
│       │   ├── lib/
│       │   └── index.ts
│       └── package.json
│
├── apps/
│   ├── api/                    # Hono application entrypoint
│   │   ├── src/
│   │   │   └── index.ts        # Bootstraps Hono + mounts api-adapter routes
│   │   ├── package.json
│   │   └── tsconfig.json
│   │
│   ├── web/                    # SvelteKit frontend (public portal)
│   │   ├── src/
│   │   │   ├── routes/
│   │   │   ├── lib/
│   │   │   └── app.html
│   │   ├── package.json
│   │   └── svelte.config.js
│   │
│   └── admin/                  # SvelteKit admin dashboard
│       ├── src/
│       │   ├── routes/
│       │   ├── lib/
│       │   └── app.html
│       ├── package.json
│       └── svelte.config.js
│
├── docs/
│   ├── CONTEXT.md              # Domain glossary (created)
│   └── adr/
│       ├── ADR-0001-decisions-from-grilling.md
│       └── ARCHITECTURE-COMPARISON.md
│
└── scripts/
    └── migrate-legacy-data.ts  # One-off: Yii2 → new schema ingestion
```

### Dependency Graph (Package Level)

```
@ildis/db               ← no deps (schema definitions)
@ildis/storage-adapters ← depends on: @ildis/domain (StoragePort interface)
@ildis/audit-adapters   ← depends on: @ildis/domain (AuditPort interface)
@ildis/db-adapters      ← depends on: @ildis/db, @ildis/domain (DatabasePort interface)
@ildis/domain           ← depends on: @ildis/db (for type references only)
@ildis/api-adapter      ← depends on: @ildis/domain, @ildis/db-adapters, @ildis/storage-adapters, @ildis/audit-adapters
apps/api                ← depends on: @ildis/api-adapter
apps/web                ← depends on: @ildis/ui
apps/admin              ← depends on: @ildis/ui

// New packages for P0 modules:
// @ildis/search-adapters   ← depends on: @ildis/domain (DocumentQuery port)
// @ildis/auth-adapters     ← depends on: @ildis/domain (AuthorizationPolicy port)
```

**Critical rule**: No package may import from `apps/*`. Packages only depend on other packages. `apps/*` are thin composition roots.

---

## 4. Database Schema (Drizzle ORM)

### 4.1 Schema Overview

Single-table inheritance for documents with JSON metadata. All file attachments normalized into `document_attachments`. Better Auth manages its own tables in the same database.

### 4.2 Table Definitions

```typescript
// packages/db/src/schema/documents.ts
import { mysqlTable, varchar, text, timestamp, tinyint, json, int } from 'drizzle-orm/mysql-core';

export const documents = mysqlTable('documents', {
  id: varchar('id', { length: 36 }).primaryKey(),
  type: varchar('type', { length: 50 }).notNull(),
    // 'peraturan' | 'monografi' | 'artikel' | 'putusan'

  // Common fields across all types
  judul: text('judul').notNull(),
  slug: varchar('slug', { length: 255 }).notNull().unique(),
  teu: text('teu'),                           // Tanggung Jawab Hukum / author body
  tahun: int('tahun'),
  tempatTerbit: text('tempat_terbit'),
  tanggalPenetapan: timestamp('tanggal_penetapan'),
  tanggalPengundangan: timestamp('tanggal_pengundangan'),
  sumber: text('sumber'),
  bahasa: text('bahasa'),
  bidangHukum: text('bidang_hukum'),
  abstrak: text('abstrak'),                   // Text abstract (never a file reference)
  status: varchar('status', { length: 50 }).default('Berlaku'),
  statusTerakhir: varchar('status_terakhir', { length: 50 }),
  isPublish: tinyint('is_publish').default(0),

  // Added per ADR-0003:
  // Every document is attributed to one source institution (who produced it)
  // isExternal = true means this document was added from another institution's catalog
  sourceInstitutionId: varchar('source_institution_id', { length: 36 }).notNull(),
  isExternal: tinyint('is_external').default(0),

  // Type-specific metadata as JSON
  // Peraturan: { jenisPeraturan, singkatanJenis, nomorPeraturan, bentukPeraturan, pemrakarsa, daerah, penandatanganan }
  // Monografi: { nomorPanggil, penerbit, deskripsiFisik, isbn, nomorIndukBuku, cetakan, edisi, gmd, judulSeri, klasifikasi }
  // Putusan:   { lembagaPeradilan, nomorPerkara, pemohon, termohon, jenisPerkara, subKlasifikasi, amarStatus, berkekuatanHukumTetap }
  // Artikel:   { } // minimal
  metadata: json('metadata'),

  // Statistics (migrated from legacy hit_see, hit_download)
  hitSee: int('hit_see').default(0),
  hitDownload: int('hit_download').default(0),

  // Audit
  createdBy: varchar('created_by', { length: 36 }),
  updatedBy: varchar('updated_by', { length: 36 }),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow().onUpdateNow(),
});
```

```typescript
// packages/db/src/schema/attachments.ts
import { mysqlTable, varchar, text, int, timestamp, mysqlEnum } from 'drizzle-orm/mysql-core';

export const documentAttachments = mysqlTable('document_attachments', {
  id: varchar('id', { length: 36 }).primaryKey(),
  documentId: varchar('document_id', { length: 36 }).notNull(),
  kind: mysqlEnum('kind', ['abstrak', 'gambar_sampul', 'lampiran']).notNull(),
    // 'abstrak' = PDF abstract (was abstrak filename on document row)
    // 'gambar_sampul' = cover image (was gambar_sampul on monografi row)
    // 'lampiran' = supplementary attachment (was data_lampiran)
  judulLampiran: text('judul_lampiran'),
  urlLampiran: text('url_lampiran'),
  deskripsiLampiran: text('deskripsi_lampiran'),
  filename: varchar('filename', { length: 255 }).notNull(),
  mimeType: varchar('mime_type', { length: 100 }).notNull(),
  sizeBytes: int('size_bytes'),
  urutan: int('urutan'),
  status: int('status').default(1),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow().onUpdateNow(),
});
```

```typescript
// packages/db/src/schema/subjects.ts
export const subjects = mysqlTable('subjects', {
  id: varchar('id', { length: 36 }).primaryKey(),
  nama: varchar('nama', { length: 255 }).notNull(),
  tipe: varchar('tipe', { length: 50 }).default('Topik'),
  jenis: varchar('jenis', { length: 50 }).default('Primary'),
  parentId: varchar('parent_id', { length: 36 }),
  createdAt: timestamp('created_at').defaultNow(),
});

export const documentSubjects = mysqlTable('document_subjects', {
  id: varchar('id', { length: 36 }).primaryKey(),
  documentId: varchar('document_id', { length: 36 }).notNull(),
  subjectId: varchar('subject_id', { length: 36 }).notNull(),
  createdAt: timestamp('created_at').defaultNow(),
});
```

```typescript
// packages/db/src/schema/authors.ts
export const authors = mysqlTable('authors', {
  id: varchar('id', { length: 36 }).primaryKey(),
  nama: varchar('nama', { length: 255 }).notNull(),
  tipe: varchar('tipe', { length: 50 }),
  jenis: varchar('jenis', { length: 50 }),
  createdAt: timestamp('created_at').defaultNow(),
});

export const documentAuthors = mysqlTable('document_authors', {
  id: varchar('id', { length: 36 }).primaryKey(),
  documentId: varchar('document_id', { length: 36 }).notNull(),
  authorId: varchar('author_id', { length: 36 }).notNull(),
  urutan: int('urutan'),
  createdAt: timestamp('created_at').defaultNow(),
});
```

```typescript
// packages/db/src/schema/status-history.ts
export const documentStatusHistory = mysqlTable('document_status_history', {
  id: varchar('id', { length: 36 }).primaryKey(),
  documentId: varchar('document_id', { length: 36 }).notNull(),
  status: varchar('status', { length: 50 }).notNull(),
    // 'mencabut' | 'dicabut' | 'mengubah' | 'diubah'
  targetDocumentId: varchar('target_document_id', { length: 36 }),
  catatan: text('catatan'),
  tanggalPerubahan: timestamp('tanggal_perubahan'),
  createdAt: timestamp('created_at').defaultNow(),
});
```

```typescript
// packages/db/src/schema/document-relations.ts
export const documentRelations = mysqlTable('document_relations', {
  id: varchar('id', { length: 36 }).primaryKey(),
  documentId: varchar('document_id', { length: 36 }).notNull(),
  relatedDocumentId: varchar('related_document_id', { length: 36 }).notNull(),
  relationType: varchar('relation_type', { length: 50 }).notNull(),
    // 'mencabut' | 'dicabut' | 'mengubah' | 'diubah' | 'terkait'
  catatan: text('catatan'),
  urutan: int('urutan'),
  createdAt: timestamp('created_at').defaultNow(),
});
```

```typescript
// packages/db/src/schema/audit-log.ts
export const auditLog = mysqlTable('audit_log', {
  id: varchar('id', { length: 36 }).primaryKey(),
  documentId: varchar('document_id', { length: 36 }).notNull(),
  actorId: varchar('actor_id', { length: 36 }).notNull(),
  actorUsername: varchar('actor_username', { length: 255 }),
  action: varchar('action', { length: 100 }).notNull(),
    // 'DOCUMENT_CREATED' | 'DOCUMENT_UPDATED' | 'DOCUMENT_DELETED'
    // | 'ATTACHMENT_ADDED' | 'STATUS_TRANSITIONED' | ...
  resourceType: varchar('resource_type', { length: 50 }).notNull(),
    // 'document' | 'attachment' | 'status' | ...
  resourceId: varchar('resource_id', { length: 36 }),
  details: json('details'),
  ipAddress: varchar('ip_address', { length: 100 }),
  userAgent: text('user_agent'),
  occurredAt: timestamp('occurred_at').defaultNow(),
});
```

```typescript
// packages/db/src/schema/institutions.ts
export const institutions = mysqlTable('institutions', {
  id: varchar('id', { length: 36 }).primaryKey(),
  name: varchar('name', { length: 255 }).notNull(),
  singkatan: varchar('singkatan', { length: 50 }),     // Short code, e.g., "BPHN", "JATENG"
  tipe: varchar('tipe', { length: 50 }).notNull(),   // 'pusat' | 'provinsi' | 'kabupaten'
  parentId: varchar('parent_id', { length: 36 }),     // For hierarchical relationships
  alamat: text('alamat'),
  email: varchar('email', { length: 255 }),
  telepon: varchar('telepon', { length: 50 }),
  isActive: tinyint('is_active').default(1),
  createdAt: timestamp('created_at').defaultNow(),
});
```

```typescript
// packages/db/src/schema/document-search-index.ts
// Denormalized read model for search. Updated synchronously by DocumentLifecycle.
// Replaces legacy dokumen_data_subyek view.
export const documentSearchIndex = mysqlTable('document_search_index', {
  documentId: varchar('document_id', { length: 36 }).primaryKey(),
  judul: text('judul').notNull(),
  teu: text('teu'),
  abstrakText: text('abstrak_text'),
  subjects: text('subjects'),           // Comma-separated for LIKE matching
  authors: text('authors'),             // Comma-separated for LIKE matching
  type: varchar('type', { length: 50 }).notNull(),
  tahun: int('tahun'),
  sourceInstitutionId: varchar('source_institution_id', { length: 36 }).notNull(),
  isPublish: tinyint('is_publish').default(0),
  isExternal: tinyint('is_external').default(0),
  slug: varchar('slug', { length: 255 }).notNull(),
  updatedAt: timestamp('updated_at').defaultNow(),
});
```

```typescript
// packages/db/src/schema/permissions.ts
// Fine-grained permission system per ADR-0003
export const permissions = mysqlTable('permissions', {
  id: varchar('id', { length: 36 }).primaryKey(),
  name: varchar('name', { length: 255 }).notNull().unique(),
    // e.g., "document:create:peraturan", "document:delete:own", "feed:generate"
  resource: varchar('resource', { length: 50 }).notNull(),
  action: varchar('action', { length: 50 }).notNull(),
  scope: varchar('scope', { length: 50 }).notNull(),
  description: text('description'),
  createdAt: timestamp('created_at').defaultNow(),
});

export const rolePermissions = mysqlTable('role_permissions', {
  id: varchar('id', { length: 36 }).primaryKey(),
  role: varchar('role', { length: 50 }).notNull(),
    // 'superadmin' | 'koordinator_pustakawan' | 'pustakawan' | 'peraturan'
  permissionId: varchar('permission_id', { length: 36 }).notNull(),
  createdAt: timestamp('created_at').defaultNow(),
});
```

### 4.3 Better Auth Tables

Better Auth generates its own schema. Use the CLI:

```bash
npx @better-auth/cli@latest generate --drizzle --config packages/db/src/auth-schema.ts
```

Required tables (auto-generated):
- `user`, `session`, `account`, `verification`

Better Auth is configured in `apps/api/src/auth.ts` with `database: drizzleAdapter(db)`.

### 4.4 Indexes

```sql
-- documents
CREATE INDEX idx_documents_type ON documents(type);
CREATE INDEX idx_documents_slug ON documents(slug);
CREATE INDEX idx_documents_status ON documents(status);
CREATE INDEX idx_documents_is_publish ON documents(is_publish);
CREATE INDEX idx_documents_tahun ON documents(tahun);
CREATE INDEX idx_documents_created_at ON documents(created_at);
-- MySQL 8.0+ JSON index for metadata searches
CREATE INDEX idx_documents_metadata_jenis_peraturan ON documents((CAST(metadata->>'$.jenisPeraturan' AS CHAR(255) COLLATE utf8mb4_unicode_ci)));

-- document_attachments
CREATE INDEX idx_attachments_document_id ON document_attachments(document_id);
CREATE INDEX idx_attachments_kind ON document_attachments(kind);

-- document_status_history
CREATE INDEX idx_status_history_document_id ON document_status_history(document_id);
CREATE INDEX idx_status_history_status ON document_status_history(status);

-- audit_log
CREATE INDEX idx_audit_document_id ON audit_log(document_id);
CREATE INDEX idx_audit_occurred_at ON audit_log(occurred_at);

-- institutions
CREATE INDEX idx_institutions_tipe ON institutions(tipe);
CREATE INDEX idx_institutions_parent ON institutions(parent_id);

-- document_search_index (the denormalized read model)
CREATE FULLTEXT INDEX idx_search_judul ON document_search_index(judul, teu, abstrak_text);
CREATE INDEX idx_search_type ON document_search_index(type);
CREATE INDEX idx_search_tahun ON document_search_index(tahun);
CREATE INDEX idx_search_source_institution ON document_search_index(source_institution_id);
CREATE INDEX idx_search_is_publish ON document_search_index(is_publish);
CREATE INDEX idx_search_is_external ON document_search_index(is_external);
CREATE INDEX idx_search_updated ON document_search_index(updated_at);
```

---

## 5. Domain Modules

### 5.1 DocumentTypeExtensionRegistry — Public Seam

**Why public**: Adding a 5th document type (e.g., `rancangan_undang_undang`) must require zero changes to existing code.

**Interface**:

```typescript
// packages/domain/src/document-type-registry.ts

export interface DocumentTypeProcessor<TMetadata = unknown> {
  type: string;
  displayName: string;

  // Validation: returns [] if valid, array of errors otherwise
  validateMetadata(metadata: unknown): Array<{ field: string; message: string }>;

  // Default values for new documents of this type
  getDefaults(): Partial<DocumentCreateCommand<TMetadata>>;

  // Allowed attachment kinds for this type
  allowedAttachmentKinds: Array<'abstrak' | 'gambar_sampul' | 'lampiran'>;

  // Allowed status transitions (see StatusTransition module)
  allowedStatusTransitions: StatusTransitionRule[];

  // Feed-export field selector — which fields go into the public JSON feed
  toFeedFields(document: DocumentView): Record<string, unknown>;
}

export interface DocumentTypeExtensionRegistry {
  register(processor: DocumentTypeProcessor): void;
  get(type: string): DocumentTypeProcessor | undefined;
  list(): DocumentTypeProcessor[];
  types(): string[];
}
```

**Default processors** (hardcoded at bootstrap):

```typescript
// packages/domain/src/processors/peraturan-processor.ts
export const peraturanProcessor: DocumentTypeProcessor<PeraturanMetadata> = {
  type: 'peraturan',
  displayName: 'Peraturan',
  validateMetadata(metadata) {
    const m = metadata as PeraturanMetadata;
    const errors: Array<{ field: string; message: string }> = [];
    if (!m.jenisPeraturan) errors.push({ field: 'jenisPeraturan', message: 'Jenis peraturan wajib diisi' });
    if (!m.nomorPeraturan) errors.push({ field: 'nomorPeraturan', message: 'Nomor peraturan wajib diisi' });
    if (!m.tahun) errors.push({ field: 'tahun', message: 'Tahun wajib diisi' });
    // Uniqueness: (jenisPeraturan, nomorPeraturan, tahun) must be unique
    return errors;
  },
  getDefaults() {
    return { type: 'peraturan' as const, metadata: { status: 'Berlaku' } };
  },
  allowedAttachmentKinds: ['abstrak', 'lampiran'],
  allowedStatusTransitions: peraturanStatusRules, // see 5.3
  toFeedFields(doc) {
    return {
      jenis_peraturan: doc.metadata.jenisPeraturan,
      singkatan_jenis: doc.metadata.singkatanJenis,
      nomor: doc.metadata.nomorPeraturan,
      tahun: doc.tahun,
      judul: doc.judul,
      tanggal_penetapan: doc.tanggalPenetapan,
    };
  },
};
```

Similar processors for `monografi`, `artikel`, `putusan`.

### 5.2 DocumentLifecycle — Deep Module (The Sole Public Seam)

**Only interface a caller needs to create a document.** Everything else is behind the seam.

```typescript
// packages/domain/src/types.ts

// Identity types (branded for safety)
type DocumentId = string & { readonly __brand: 'DocumentId' };
type AttachmentId = string & { readonly __brand: 'AttachmentId' };
type ActorId = string & { readonly __brand: 'ActorId' };

interface ActorContext {
  id: ActorId;
  username: string;
  roles: string[];          // ['pustakawan', 'peraturan', ...]
  ipAddress?: string;
  userAgent?: string;
}

interface FileAttachment {
  fieldName: string;          // e.g., 'abstrak', 'gambar_sampul', 'lampiran_1'
  file: File;                // Web File API (Node.js 18+)
}

// Discriminated by type, with strongly-typed metadata
interface DocumentCreateCommand<TType extends string, TMetadata> {
  type: TType;
  judul: string;
  slug: string;
  teu?: string;
  tahun?: number;
  tempatTerbit?: string;
  tanggalPenetapan?: Date;
  tanggalPengundangan?: Date;
  sumber?: string;
  bahasa?: string;
  bidangHukum?: string;
  abstrak?: string;          // Text abstract
  isPublish?: boolean;
  metadata: TMetadata;
  attachments: Array<{
    kind: 'abstrak' | 'gambar_sampul' | 'lampiran';
    file: File;
    judulLampiran?: string;
    deskripsi?: string;
  }>;
  subjects?: string[];         // Subject names (resolved/upserted by name)
  authors?: Array<{ nama: string; urutan?: number }>;
}

// Result type
interface DocumentCreated {
  id: DocumentId;
  type: string;
  judul: string;
  slug: string;
  createdAt: Date;
}

type DocumentCreationError =
  | { code: 'VALIDATION_FAILED'; field: string; message: string }
  | { code: 'UNIQUE_CONSTRAINT_VIOLATION'; fields: string[] }
  | { code: 'STORAGE_REJECTED'; filename: string; reason: string }
  | { code: 'DATABASE_ERROR'; message: string }
  | { code: 'UNAUTHORIZED'; hint: string }
  | { code: 'TYPE_NOT_REGISTERED'; type: string }
  | { code: 'UNKNOWN'; message: string };

type Result<T, E> = { ok: true; value: T } | { ok: false; error: E };
```

```typescript
// packages/domain/src/document-lifecycle.ts

export interface DocumentLifecycle {
  /**
   * Creates a document, its attachments, related entities, and audit entry.
   * INVARIANTS:
   * - `judul` is non-empty, ≤ 500 chars.
   * - `slug` is unique across all documents.
   * - Document type must be registered in DocumentTypeExtensionRegistry.
   * - Attachments are validated against the type's allowedAttachmentKinds.
   * - The operation is atomic: either the document + attachments + relations + audit all persist,
   *   or none do. If file storage succeeds but DB transaction fails, files are cleaned up.
   *
   * ORDERING (internal, visible in failure):
   * 1. Validate command against type processor.
   * 2. Upsert authors/subjects by name.
   * 3. Begin DB transaction.
   * 4. Insert document row.
   * 5. Stream attachments to storage adapter.
   * 6. Insert attachment rows with storage URLs.
   * 7. Insert junction rows (authors, subjects).
   * 8. Commit transaction.
   * 9. Record audit log entry (outside transaction, fire-and-forget).
   */
  create<TType extends string, TMetadata>(
    command: DocumentCreateCommand<TType, TMetadata>,
    actor: ActorContext
  ): Promise<Result<DocumentCreated, DocumentCreationError>>;

  /**
   * Retrieves a fully hydrated document by ID.
   * No authorization checks — enforce those at the route layer.
   */
  of(id: DocumentId): Promise<Result<DocumentView, { code: 'NOT_FOUND'; id: DocumentId }>>;

  /**
   * Updates an existing document.
   * Replaces metadata entirely; does NOT merge.
   * Replacing attachments requires an explicit list (empty list = remove all).
   */
  update<TType extends string, TMetadata>(
    id: DocumentId,
    patch: Partial<DocumentCreateCommand<TType, TMetadata>>,
    actor: ActorContext
  ): Promise<Result<DocumentCreated, DocumentCreationError>>;

  /**
   * Soft-delete (or hard-delete based on config).
   * Records an audit entry before deletion.
   */
  delete(
    id: DocumentId,
    actor: ActorContext
  ): Promise<Result<void, { code: 'NOT_FOUND' } | { code: 'CONSTRAINT_VIOLATION'; message: string }>>;
}
```

**Implementation strategy**:

```typescript
export interface DocumentLifecycleDeps {
  database: DatabasePort;      // see 7. Adapter Strategy
  storage: StoragePort;
  audit: AuditPort;
  registry: DocumentTypeExtensionRegistry;
  authorizationPolicy: AuthorizationPolicy;  // Per ADR-0003 (fine-grained permissions)
}

export class DocumentLifecycleService implements DocumentLifecycle {
  constructor(private deps: DocumentLifecycleDeps) {}

  async create(command, actor) {
    // 0. Authorization (defense in depth — also checked at route layer)
    const canCreate = await this.deps.authorizationPolicy.can(
      actor, `document:create:${command.type}`
    );
    if (!canCreate) {
      return { ok: false, error: { code: 'UNAUTHORIZED', hint: `Cannot create ${command.type}` } };
    }

    // 1. Look up processor
    const processor = this.deps.registry.get(command.type);
    if (!processor) return { ok: false, error: { code: 'TYPE_NOT_REGISTERED', type: command.type } };

    // 2. Authorization check
    if (!this.canCreate(command.type, actor.roles)) {
      return { ok: false, error: { code: 'UNAUTHORIZED', hint: `Role cannot create ${command.type}` } };
    }

    // 3. Validate metadata
    const validation = processor.validateMetadata(command.metadata);
    if (validation.length > 0) {
      return { ok: false, error: { code: 'VALIDATION_FAILED', field: validation[0].field, message: validation[0].message } };
    }

    // 4. Validate attachments
    for (const att of command.attachments) {
      if (!processor.allowedAttachmentKinds.includes(att.kind)) {
        return { ok: false, error: { code: 'VALIDATION_FAILED', field: `attachment.${att.kind}`, message: `${command.type} does not allow ${att.kind}` } };
      }
    }

    // 5. Upsert subjects/authors
    const subjectIds = await this.upsertSubjects(command.subjects ?? []);
    const authorIds = await this.upsertAuthors(command.authors ?? []);

    // 6. Execute within transaction
    return this.deps.database.transaction(async (trx) => {
      // 6a. Insert document
      const doc = await trx.documents.create({ ...command, metadata: JSON.stringify(command.metadata) });

      // 6b. Store files
      const storedAttachments: StoredAttachment[] = [];
      for (const att of command.attachments) {
        const storageKey = `documents/${doc.id}/${att.kind}/${crypto.randomUUID()}-${att.file.name}`;
        const result = await this.deps.storage.put(storageKey, att.file);
        storedAttachments.push({ ...att, documentId: doc.id, storageKey, url: result.url });
      }

      // 6c. Insert attachment rows
      for (const sa of storedAttachments) {
        await trx.attachments.create(sa);
      }

      // 6d. Insert junctions
      for (const sid of subjectIds) await trx.documentSubjects.create({ documentId: doc.id, subjectId: sid });
      for (const aid of authorIds) await trx.documentAuthors.create({ documentId: doc.id, authorId: aid });

      return doc;
    });
  }
}
```

### 5.3 StatusTransition — State Machine Module

> **Refined per ADR-0004** (`/docs/adr/ADR-0004-status-transition-module.md`). The original placeholder has been replaced with the full design.

**Purpose**: The sole seam for creating, reversing, or reverting legal status relationships between `peraturan` documents. No caller touches `document.status` or `document.status_terakhir` directly.

```typescript
// packages/domain/src/status-transition.ts

export type TransitionAction = 'mencabut' | 'mengubah';

export interface TransitionSpec {
  sourceDocumentId: DocumentId;
  targetDocumentId: DocumentId;
  action: TransitionAction;
  notes?: string;
  effectiveDate?: Date;
}

export interface TransitionReceipt {
  transitionId: string;
  sourceDocumentId: DocumentId;
  targetDocumentId: DocumentId;
  action: TransitionAction;
  reciprocalTransitionId: string;
  previousSourceStatus: string;
  previousTargetStatus: string;
  appliedAt: Date;
}

export interface StatusTransition {
  /**
   * Apply a legal status transition between two peraturan documents.
   * INVARIANTS:
   * - Both documents MUST be type 'peraturan'.
   * - sourceDocumentId MUST NOT equal targetDocumentId.
   * - For 'mencabut' or 'mengubah': target document MUST NOT have status = 'Tidak Berlaku'.
   * - Creates a reciprocal entry in document_status_history.
   * - Updates document.status and document.status_terakhir on BOTH documents atomically.
   */
  apply(
    spec: TransitionSpec,
    actor: ActorContext
  ): Promise<Result<TransitionReceipt, StatusTransitionError>>;

  /**
   * Revert a previously applied transition by its ID.
   * Restores both documents' status to 'Berlaku' and removes both history entries.
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

### StatusTransitionCatalog — Read-Only UI Adapter (P1.5)

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

### 5.4 AuthorizationPolicy — Fine-Grained Permission Module (P0)

**Purpose**: Database-driven permission checks. Separates "who" (roles) from "what" (permissions). Called by both Hono middleware (fast-fail) and `DocumentLifecycle` (defense in depth).

```typescript
// packages/domain/src/authorization-policy.ts

export interface AuthorizationPolicy {
  /**
   * Check if an actor has a specific permission.
   * INVARIANTS:
   * - `superadmin` role always returns true (wildcard).
   * - Permissions are looked up from the database at runtime (not hardcoded).
   * - Scoped permissions (`:own`) require resource ownership verification.
   *
   * PERMISSION SYNTAX: "resource:action:scope"
   *   "document:create:peraturan"  → create peraturan documents
   *   "document:update:own"        → update own documents only
   *   "document:read:*"            → read any document
   *   "feed:generate"              → trigger feed generation
   *   "user:manage"               → full user management
   */
  can(actor: ActorContext, permission: string): Promise<boolean>;

  /**
   * Check if actor can perform action on a specific resource instance.
   * Used for `:own` scoped permissions.
   */
  canOn(
    actor: ActorContext,
    permission: string,
    resource: { type: string; id: string; ownerId?: string }
  ): Promise<boolean>;

  /**
   * List all permissions granted to a role.
   */
  permissionsForRole(role: string): Promise<string[]>;
}
```

**Notes**:
- Permissions are cached in-memory after first lookup (short TTL, e.g., 60s) to avoid N+1 queries.
- The `role_permissions` table seeds default permissions at migration time.
- Custom permissions can be added via admin UI without code deployment.

---

### 5.5 DocumentQuery — Search and Listing Module (P0)

**Purpose**: Unified search for public portal and admin dashboard. Reads from the denormalized `document_search_index` table (ADR-0003, Option B).

```typescript
// packages/domain/src/document-query.ts

export interface DocumentQuery {
  /**
   * Search documents with full-text, faceted filters, and pagination.
   * INVARIANTS:
   * - Returns only documents matching ALL provided filters (AND logic).
   * - Full-text search uses MySQL MATCH AGAINST on denormalized index.
   * - Results are ordered by relevance (full-text) then by sort params.
   * - Never exposes documents where isPublish = 0 unless explicitly filtered.
   */
  search(params: SearchParams): Promise<PaginatedResult<DocumentSearchResult>>;

  /**
   * Get aggregated facet counts for a search result set.
   * Useful for UI filter panels (e.g., "Peraturan (42)", "2024 (15)").
   */
  facets(params: SearchParams): Promise<FacetCounts>;
}

export interface SearchParams {
  query?: string;            // Full-text search string (judul, teu, abstrak)
  filters?: {
    type?: string[];         // ['peraturan', 'monografi']
    tahun?: number | { min: number; max: number };
    subjects?: string[];
    authors?: string[];
    sourceInstitutionId?: string;
    isExternal?: boolean;
    isPublish?: boolean;
    // Institution scoping is applied at the Hono route layer, not here.
    // This module is context-agnostic: it searches what it's told to search.
  };
  pagination: { page: number; limit: number };
  sort?: Array<{ field: 'judul' | 'tahun' | 'created_at'; direction: 'asc' | 'desc' }>;
}

export interface DocumentSearchResult {
  id: DocumentId;
  type: string;
  judul: string;
  slug: string;
  teu?: string;
  tahun?: number;
  sourceInstitutionId: string;
  isPublish: boolean;
  isExternal: boolean;
  subjects: string[];
  authors: string[];
  updatedAt: Date;
  // Note: Does NOT include metadata JSON — that requires a follow-up fetch
}

export interface PaginatedResult<T> {
  items: T[];
  total: number;
  page: number;
  limit: number;
  totalPages: number;
}

export interface FacetCounts {
  byType: Array<{ value: string; count: number }>;
  byTahun: Array<{ value: number; count: number }>;
  bySubject: Array<{ value: string; count: number }>;
  bySourceInstitution: Array<{ value: string; count: number }>;
}
```

**Notes**:
- The `DocumentQuery` module is **context-agnostic**. It does not know if it's serving a public or admin request.
- The Hono route layer normalizes the request: public portal sets `isPublish: true`; admin sets `sourceInstitutionId` to user's institution.
- This is a **read-only** module. Writes are owned by `DocumentLifecycle`.

---

### 5.6 InstitutionScope — Middleware Filter (P0)

**Purpose**: NOT a domain module. This is a **Hono middleware** that applies institution filtering at the route layer (ADR-0003, Option C). Keeps `DocumentQuery` and `DocumentLifecycle` institution-agnostic.

```typescript
// packages/api-adapter/src/middleware/institution-scope.ts

export interface InstitutionScopeMiddleware {
  /**
   * Injects `institutionId` into query parameters based on the authenticated user.
   * RULES:
   * - If user.role === 'superadmin': allow override via `?institutionId=` query param.
   * - If user.role !== 'superadmin': force `institutionId` to user's own institution.
   * - Does NOT filter out external documents. External docs remain visible.
   */
  apply(c: HonoContext): { institutionId: string | null };
}
```

**Notes**:
- This middleware applies only to routes that list documents. It does NOT apply to:
  - Public feed generation (feed is scoped by `source_institution_id` at generation time)
  - Document creation (institution is implicit via user's membership)
  - Public portal search (no institution filter by default)

---

### 5.7 FeedGeneration — Public Feed Builder

```typescript
// packages/domain/src/feed-generation.ts

export interface FeedGeneration {
  /**
   * Generate public feed data for JDIHN portal integration.
   * INVARIANTS:
   * - Only includes documents where isPublish = 1.
   * - Only includes documents where isExternal = 0 (per ADR-0003).
   *   External documents (added from other institutions) are excluded from feeds.
   * - Uses each document type's toFeedFields processor method.
   * - Feed is generated per sourceInstitutionId (one feed per institution).
   */
  generate(params: {
    sourceInstitutionId: string;   // Required per ADR-0003
    documentTypes?: string[];
    since?: Date;
    limit?: number;
  }): Promise<{
    generatedAt: Date;
    count: number;
    items: Array<Record<string, unknown>>;
  }>;
}
```

---

## 6. Auth Boundaries (Better Auth)

### 6.1 Responsibility Split

Better Auth owns:
- User registration, login, logout, sessions
- Password reset, email verification
- Session cookies and CSRF protection
- OAuth (if configured later)

ILDIS application owns:
- **Roles**: `superadmin`, `koordinator_pustakawan`, `pustakawan`, `peraturan`
- **Permissions**: CRUD on document types per role
- **Institution scoping**: Users belong to an institution (for multi-tenant JDIHN members)

### 6.2 Configuration

```typescript
// apps/api/src/auth.ts
import { betterAuth } from 'better-auth';
import { drizzleAdapter } from 'better-auth/adapters/drizzle';
import { db } from '@ildis/db';

export const auth = betterAuth({
  database: drizzleAdapter(db),
  emailAndPassword: { enabled: true },
  plugins: [],
  // Better Auth generates user/session/account/verification tables
});
```

### 6.3 Custom Fields on User Table

Add `institutionId` and `role` to the user table via Better Auth's `user.additionalFields`:

```typescript
user: {
  additionalFields: {
    institutionId: { type: 'string', required: true },
      // FK to institutions.id; every user belongs to exactly one institution
    role: { type: 'string', required: true },
      // 'superadmin' | 'koordinator_pustakawan' | 'pustakawan' | 'peraturan'
  },
},
```

Re-run schema generation after adding fields.

### 6.4 RBAC Middleware (Hono)

**Two-layer auth**: Fast-fail at route layer (`requirePermission`), defense in depth at domain layer (`AuthorizationPolicy.can()`).

```typescript
// packages/api-adapter/src/middleware/rbac.ts
export function requirePermission(permission: string) {
  return createMiddleware(async (c, next) => {
    const actor = c.get('session').user;
    const authorizationPolicy = c.get('authorizationPolicy');

    const permitted = await authorizationPolicy.can(
      { id: actor.id, username: actor.name, roles: [actor.role] },
      permission
    );

    if (!permitted) {
      return c.json({ error: 'Forbidden', required: permission }, 403);
    }
    await next();
  });
}
```

```typescript
// Usage example in Hono routes:
app.post('/documents', requirePermission('document:create:peraturan'), async (c) => {
  // ... route handler
});
```

### 6.5 InstitutionScope Middleware (Hono) (P0)

Per ADR-0003 (Decision Q3), institution filtering happens at the **route layer**, not inside domain modules.

```typescript
// packages/api-adapter/src/middleware/institution-scope.ts
export function applyInstitutionScope() {
  return createMiddleware(async (c, next) => {
    const actor = c.get('session').user;
    const query = c.req.query();

    // Superadmin can override institution via query param
    // All other users are scoped to their own institution
    const institutionId = actor.role === 'superadmin'
      ? query.institutionId ?? actor.institutionId
      : actor.institutionId;

    // Inject into context so route handlers can pass it to DocumentQuery
    c.set('scopedInstitutionId', institutionId);
    await next();
  });
}
```

```typescript
// Usage example:
app.get('/documents', requireAuth, applyInstitutionScope(), async (c) => {
  const institutionId = c.get('scopedInstitutionId');
  const result = await documentQuery.search({
    ...c.req.query(),
    filters: { ...c.req.query().filters, sourceInstitutionId: institutionId },
  });
  return c.json(result);
});
```

---

## 7. Adapter Strategy

### 7.1 Port Definitions (in `@ildis/domain`)

```typescript
// packages/domain/src/adapters/database-port.ts
export interface DatabasePort {
  transaction<T>(fn: (trx: TransactionPort) => Promise<T>): Promise<T>;
}

export interface TransactionPort {
  documents: DocumentRepository;
  attachments: AttachmentRepository;
  subjects: SubjectRepository;
  authors: AuthorRepository;
  documentSubjects: DocumentSubjectRepository;
  documentAuthors: DocumentAuthorRepository;
  statusHistory: StatusHistoryRepository;
  auditLog: AuditLogRepository;
}

// packages/domain/src/adapters/storage-port.ts
export interface StoragePort {
  put(key: string, file: File): Promise<{ url: string }>;
  delete(key: string): Promise<void>;
  getPublicUrl(key: string): string;
}

// packages/domain/src/adapters/audit-port.ts
export interface AuditPort {
  record(event: {
    action: string;
    actorId: string;
    documentId: string;
    resourceType: string;
    resourceId: string;
    details?: Record<string, unknown>;
    ipAddress?: string;
    userAgent?: string;
  }): Promise<void>;
}
```

### 7.2 Adapter Matrix

| Concern | Production | Dev | Test |
|---------|-----------|-----|------|
| Database | `DrizzleMysqlAdapter` | `DrizzleSQLiteAdapter` | `InMemoryDatabaseAdapter` |
| Storage | `S3StorageAdapter` | `LocalFileSystemAdapter` | `InMemoryStorageAdapter` |
| Audit | `MySqlAuditAdapter` | `ConsoleAuditAdapter` | `InMemoryAuditAdapter` |

### 7.3 Composition Root

```typescript
// packages/api-adapter/src/container.ts
import { DocumentLifecycleService, DocumentQueryService, AuthorizationPolicyService } from '@ildis/domain';
import { DrizzleMysqlAdapter } from '@ildis/db-adapters';
import { S3StorageAdapter } from '@ildis/storage-adapters';
import { MySqlAuditAdapter } from '@ildis/audit-adapters';
import { buildDefaultRegistry } from '@ildis/domain';
import { db } from '@ildis/db';

export function createContainer() {
  const storage = process.env.STORAGE_DRIVER === 's3'
    ? new S3StorageAdapter({ bucket: process.env.S3_BUCKET!, endpoint: process.env.S3_ENDPOINT })
    : new LocalFileSystemAdapter({ basePath: './uploads' });

  const database = new DrizzleMysqlAdapter(db);
  const audit = new MySqlAuditAdapter(db);
  const registry = buildDefaultRegistry();
  const authorizationPolicy = new AuthorizationPolicyService(db); // Reads from permissions tables

  const documentLifecycle = new DocumentLifecycleService({
    database,
    storage,
    audit,
    registry,
    authorizationPolicy,
  });

  const documentQuery = new DocumentQueryService(db); // Reads from document_search_index

  return {
    documentLifecycle,
    documentQuery,
    authorizationPolicy,
    // ... other modules
  };
}
```

---

## 8. Hono API Layer

### 8.1 Hono Route Handler Examples

```typescript
// packages/api-adapter/src/routes/documents.ts
import { Hono } from 'hono';
import { requireAuth } from '../middleware/auth';
import { requirePermission } from '../middleware/rbac';
import { mapDomainErrorToHttp } from '../middleware/error-mapper';
import type { DocumentLifecycle } from '@ildis/domain';

export function createDocumentsRouter(documentLifecycle: DocumentLifecycle) {
  const app = new Hono();

  app.post('/', requireAuth, requirePermission('document:create:peraturan'), async (c) => {
    const actor = {
      id: c.get('session').user.id,
      username: c.get('session').user.name,
      roles: [c.get('session').user.role],
      ipAddress: c.req.header('x-forwarded-for'),
      userAgent: c.req.header('user-agent'),
    };

    const form = await c.req.formData();
    const command = parseMultipartFormToCommand(form); // thin parser

    const result = await documentLifecycle.create(command, actor);

    if (!result.ok) {
      const { status, body } = mapDomainErrorToHttp(result.error);
      return c.json(body, status);
    }

    return c.json(result.value, 201);
  });

  app.get('/:id', requireAuth, async (c) => {
    const id = c.req.param('id');
    const result = await documentLifecycle.of(id);
    if (!result.ok) return c.json(result.error, 404);
    return c.json(result.value);
  });

  app.get('/', requireAuth, applyInstitutionScope(), async (c) => {
    const institutionId = c.get('scopedInstitutionId');
    const result = await documentQuery.search({
      ...c.req.query(),
      filters: { ...c.req.query().filters, sourceInstitutionId: institutionId },
    });
    return c.json(result);
  });

  return app;
}
```

### 8.2 Error Mapper

```typescript
// packages/api-adapter/src/middleware/error-mapper.ts
import type { DocumentCreationError } from '@ildis/domain';

export function mapDomainErrorToHttp(error: DocumentCreationError) {
  switch (error.code) {
    case 'VALIDATION_FAILED':
      return { status: 422, body: { error: 'validation_failed', field: error.field, message: error.message } };
    case 'UNIQUE_CONSTRAINT_VIOLATION':
      return { status: 409, body: { error: 'duplicate', fields: error.fields } };
    case 'STORAGE_REJECTED':
      return { status: 400, body: { error: 'storage_rejected', filename: error.filename } };
    case 'TYPE_NOT_REGISTERED':
      return { status: 400, body: { error: 'unknown_document_type', type: error.type } };
    case 'UNAUTHORIZED':
      return { status: 403, body: { error: 'forbidden', hint: error.hint } };
    case 'DATABASE_ERROR':
    case 'UNKNOWN':
    default:
      return { status: 500, body: { error: 'internal_error' } };
  }
}
```

---

## 9. SvelteKit Frontend

### 9.1 Frontend Architecture

- **`apps/web`** — Public JDIHN portal (search, download, view documents).
- **`apps/admin`** — Backend dashboard (CRUD, verification, reports, user management).

Both apps consume the Hono API. No direct database access from frontend.

> **Refined per ADR-0005** (`/docs/adr/ADR-0005-frontend-presentation-layer.md`). The original placeholder has been replaced with the full design.

### 9.2 Auth Integration (SSR-First)

Per Q3-A, SvelteKit server-side hooks read the session cookie and validate via Better Auth:

```typescript
// apps/admin/src/hooks.ts
import { createSessionHook } from '@ildis/ui/auth';
import { betterAuthClient } from '$lib/auth-client';

export const handle = createSessionHook({ betterAuthClient });

// apps/admin/src/app.d.ts
interface Locals {
  user: { id: string; name: string; role: string; institutionId: string } | null;
}

// apps/admin/src/routes/+layout.ts
export const load = async ({ locals }) => ({ user: locals.user });
```

### 9.3 DocumentForm — Primary Seam (Deep Component)

**One public component with slots for escape hatches.** Default case: one line. Override case: slots.

```svelte
<!-- Default: trivial -->
<DocumentForm type="peraturan" on:success={() => goto('/documents')} />

<!-- Override: custom layout via slots -->
<DocumentForm type="peraturan" let:schema let:formStore>
  <div class="grid grid-cols-2 gap-4" slot="metadata-fields" let:fields>
    <section>
      {#each fields.filter(f => f.category === 'legal') as field}
        <CustomField {field} bind:value={$formStore[field.name]} />
      {/each}
    </section>
  </div>
</DocumentForm>
```

**Props:**
- `type` — Document type key ('peraturan' | 'monografi' | 'artikel' | 'putusan')
- `documentId` — Edit mode trigger (optional)
- `mode` — 'create' | 'edit' (inferred from documentId)
- `showCommon` — Toggle common fields (default: true)
- `showMetadata` — Toggle metadata fields (default: true)
- `showUpload` — Toggle file upload (default: true)
- `showSubjects` — Toggle subject selector (default: true)
- `showAuthors` — Toggle author selector (default: true)
- `schemaAdapter` — Override schema → field mapping (optional)
- `validate` — Override validation function (optional)

**Slots:** `default`, `common-fields`, `metadata-fields`, `upload-area`, `subject-selector`, `author-selector`, `actions`

### 9.4 Pure Functions (Framework-Agnostic)

```typescript
// packages/ui/src/document-form/utils.ts

export async function resolveDocumentSchema(type: string, fetch: typeof globalThis.fetch): Promise<DocumentTypeSchema>;

export function validateDocumentForm(schema: DocumentTypeSchema, values: Record<string, unknown>): Record<string, string>;

export function createDocumentPayload(
  commonValues: Record<string, unknown>,
  metadataValues: Record<string, unknown>,
  attachments: { kind: string; file: File }[],
  subjects: string[],
  authors: Array<{ nama: string; urutan?: number }>
): CreateDocumentCommand;
```

### 9.5 File Upload (Server-Mediated)

Per Q2-A: Browser → Hono API → S3. No pre-signed URLs.

```
User selects file → build FormData → POST /api/documents/{id}/attachments
→ Hono receives file → StoragePort.put() → S3 → return URL
→ DocumentForm updates internal attachments state
```

Upload progress tracked via XMLHttpRequest inside the component.

### 9.6 Data Fetching

Use SvelteKit's `load` functions with `fetch` to the Hono API:

```typescript
// apps/web/src/routes/peraturan/+page.ts
export async function load({ fetch, url }) {
  const params = new URLSearchParams(url.searchParams);
  const response = await fetch(`/api/documents?type=peraturan&${params}`);
  return { documents: await response.json() };
}
```

### 9.7 Search UI — Separate per App (Per Q4-B)

Search is NOT shared between public portal and admin dashboard. `@ildis/ui` exports shared primitives:

```typescript
// packages/ui/src/search/index.ts
export { documentApi } from './api';
export { default as DocumentCard } from './DocumentCard.svelte';
export { default as FacetCheckbox } from './FacetCheckbox.svelte';
export { default as Pagination } from './Pagination.svelte';
```

Each app implements its own search layout:
- `apps/web/src/routes/search/` — public discovery-oriented search
- `apps/admin/src/routes/search/` — admin CRUD-oriented search with filters, bulk actions

### 9.8 TailwindCSS

Install Tailwind in each SvelteKit app:
```bash
cd apps/web && npx svelte-add@latest tailwindcss
cd apps/admin && npx svelte-add@latest tailwindcss
```

---

## 10. Migration Strategy

### 10.1 From Legacy (Yii2 → New Stack)

1. **Schema migration**: Run Drizzle migrations to create new tables.
2. **Data migration**: One-off script `scripts/migrate-legacy-data.ts`:
   - Read legacy `document` rows.
   - Map `tipe_dokumen` → type string.
   - Extract type-specific columns → JSON `metadata`.
   - Migrate `data_lampiran` → `document_attachments`.
   - Migrate `data_subyek` → `document_subjects` + `subjects`.
   - Migrate `data_pengarang` → `document_authors` + `authors`.
   - Migrate `data_status` → `document_status_history`.
   - Migrate `log_pustakawan` → `audit_log`.
3. **File migration**: Copy files from `@common/dokumen/` to new storage adapter.
4. **User migration**: Export users from legacy `user` table, hash passwords with Better Auth's algorithm, import.
5. **RBAC migration**: Map legacy `auth_assignment` roles to Better Auth user role field.

### 10.2 Parallel Run (Recommended)

Before cutover, run both systems in parallel for a validation period:
- Legacy writes are mirrored to the new system via CDC or nightly batch sync.
- New system is read-only for public portal (`apps/web`).
- After validation, switch writes to the new system.

---

## 11. Rules for Future Agents

When scaffolding or extending this codebase, follow these rules:

1. **The `@ildis/domain` package is sacred.** It contains only interfaces and pure logic. No external framework imports (no Hono, no Drizzle, no S3 SDK). Only TypeScript built-ins and Web API types (`File`, `ReadableStream`).

2. **One public seam for document creation.** All document creation, update, and deletion must flow through `DocumentLifecycle`. Never call `db.documents.insert()` directly from a route handler.

3. **Adapters are swappable.** Every external dependency (DB, storage, audit) is behind a port interface. Production and test code swap adapters at the composition root, never in application code.

4. **Document types are add-only.** To add a new document type, create a `DocumentTypeProcessor` and register it in the registry. Do not modify existing processors.

5. **Better Auth owns auth.** Application code never hashes passwords, generates tokens, or validates sessions manually. Use Better Auth's `createAuthMiddleware` and `session.user.role`.

6. **JSON metadata is validated at the application layer.** Use Zod or Valibot to parse `metadata` JSON into typed objects. Never assume the JSON shape is correct at the database level.

7. **File attachments are normalized.** Every file — whether an abstract PDF, a cover image, or a supplementary attachment — lives in `document_attachments` with a `kind` discriminator. No file references in text columns on `documents`.

8. **Audit logging is fire-and-forget.** The `DocumentLifecycle` module records audit events after the transaction commits. Audit failures are logged but do not fail the overall operation.

9. **No HTTP concerns in `@ildis/domain`.** Status codes, header parsing, cookie reading, and content-negotiation live in `@ildis/api-adapter`. The domain module speaks in domain errors (`VALIDATION_FAILED`, `STORAGE_REJECTED`), not `400` or `500`.

10. **Use the document type processor for feed fields.** The `FeedGeneration` module delegates field selection to each type's `toFeedFields` method. Do not hardcode feed fields per type in the feed module.

11. **External documents are excluded from feeds.** Per ADR-0003, documents with `isExternal = true` must NOT appear in JDIHN feed generation. They are visible in search and UI but excluded from `FeedGeneration`.

12. **DocumentQuery reads from denormalized index only.** The `document_search_index` table is the sole read model for search. `DocumentLifecycle` must synchronously update this table on every create/update/delete. Do not query the normalized tables for search results.

---

## 12. Appendix: Legacy → New Entity Mapping

| Legacy Table | New Table(s) | Notes |
|---|---|---|
| `document` | `documents` | Type-specific columns → `metadata` JSON |
| `data_lampiran` | `document_attachments` | Added `kind` column |
| `data_subyek` | `document_subjects` + `subjects` | Normalized subjects |
| `data_pengarang` | `document_authors` + `authors` | Normalized authors |
| `data_status` | `document_status_history` | Renamed for clarity |
| `peraturan_terkait` + `dokumen_terkait` | `document_relations` | Unified relation table |
| `log_pustakawan` | `audit_log` | Structured, actor details |
| `user` | `user` (Better Auth) | Add `role`, `institutionId` |
| `auth_assignment` | `permissions` + `role_permissions` | Fine-grained per ADR-0003 |
| `daerah` + `provinsi` + `kabupaten` + `kecamatan` | `institutions` | Single table with hierarchy |
| `dokumen_data_subyek` (view) | `document_search_index` | Denormalized read model per ADR-0003 |
| `eksemplar` | — | **DEPRECATED** (ADR-0002) |
| `pola_eksemplar` | — | **DEPRECATED** (ADR-0002) |
| `stock_opname_*` | — | **DEPRECATED** (ADR-0002) |
| `circulation` | — | **DEPRECATED** (ADR-0002) |
| `member` / `member_type` | `user` (Better Auth) | **DEPRECATED** (ADR-0002) |
| `denda` | — | **DEPRECATED** (ADR-0002) |
| `log_pustakawan` | `audit_log` | Structured, actor details |

> **Note on deprecation**: Eksemplar and Circulation modules are deprecated per ADR-0002. The new stack does not track physical copies, barcodes, loans, or fines. See `/docs/adr/ADR-0002-deprecate-eksemplar-circulation.md`.

---

*End of Blueprint. Next step: scaffold the Turborepo monorepo using this document as the sole specification.*
