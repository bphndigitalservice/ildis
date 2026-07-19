# ILDIS Architecture — Deepening Opportunities

Based on exploration of the ILDIS Yii2 codebase. Each candidate below is framed as a deepening: turning a shallow, scattered surface into a deep module with a small interface hiding a lot of behavior.

Language per [LANGUAGE.md](LANGUAGE.md): module, interface, implementation, depth, seam, adapter, leverage, locality.

---

## 1. Document Lifecycle Orchestrator — Extract from Fat Controllers

**Files**: `backend/controllers/PeraturanController.php`, `MonografiController.php`, `ArtikelController.php`, `PutusanController.php`, `DokumenPembentukanPuuController.php`

**Problem**: The Document lifecycle — create → save → attach files → tag subjects → link authors → log → transition status — is orchestrated inside HTTP controllers. Each controller action is 100–150 lines of inline `UploadedFile` handling, manual `htmlentities()` escaping, direct `LogPustakawan` instantiation, and multi-model saves with no transaction wrapping. The **interface** to "create a regulation" is the entire `actionCreate()` method body; callers (tests, other tiers) have no smaller seam to use.

**Depth issue**: These controllers are shallow — the interface (understanding the action method) is nearly as complex as reading the implementation. The **deletion test** fails: delete `PeraturanController` and the complexity just reappears in `MonografiController` with the same lines copy-pasted.

**Solution**: Extract a `DocumentLifecycle` module (class or namespace) that accepts a plain DTO and returns a result. The controller becomes a thin adapter: parse request → call `DocumentLifecycle::ingest()` → set flash message. All file handling, transaction wrapping, logging, and subject/author/linking moves behind the module's interface.

**Benefits**:
- **Locality**: Bugs in "how to create a Document" concentrate in one place instead of 5 controllers.
- **Leverage**: One interface serves backend web, console import, and (future) API adapters.
- **Tests**: Unit-test document creation without bootstrapping HTTP or Yii request objects.

---

## 2. Unified Document Model — Eliminate Table-Per-Tier Duplication

**Files**: `backend/models/Peraturan.php`, `backend/models/DokumenJdih.php`, `frontend/models/Dokumen.php`, `frontend/models/DokumenDataSubyek.php` (view model), plus empty `Query` classes (`PeraturanQuery`, `MonografiQuery`, etc.)

**Problem**: Four classes point at the same `document` table. `Peraturan`, `DokumenJdih`, and `frontend\Dokumen` duplicate validation rules, attribute labels, behaviors, and date-formatting getters. Changing one column requires editing 3+ models. The empty `*Query` classes are scaffolding with no scope methods — they add indirection with zero abstraction.

**Depth issue**: Each model is shallow because its interface (public attributes + rules) is nearly identical to its implementation. The **deletion test** on `PeraturanQuery` or `MonografiQuery`: delete them and nothing changes — they were pass-throughs.

**Solution**: Move a single `Document` model to `common/models/Document` with all shared behaviors (Timestamp, Blameable, Sluggable). Use Yii2 **scenarios** (`SCENARIO_PERATURAN`, `SCENARIO_MONOGRAFI`) or lightweight Form Models for per-type validation differences. Retain `DokumenDataSubyek` as a read-only view adapter. Remove empty `*Query` classes; introduce real scope methods on a single `DocumentQuery` only when needed.

**Benefits**:
- **Locality**: Schema changes touch one file.
- **Leverage**: One `Document` interface used by backend, frontend, console, and future API tiers.
- **Tests**: Single model test suite instead of scattered duplicates.

---

## 3. File Attachment Module — Transactional Upload with a Deep Interface

**Files**: `backend/controllers/*Controller.php` (file upload blocks), `common/components/FileUploadService.php` (static, 42 lines, barely used), `backend/models/DataLampiran.php`

**Problem**: File upload logic is copy-pasted in ~15 controller actions: `UploadedFile::getInstance()`, `FileHelper::sanitizeFilename()`, `Yii::getAlias('@common')`, `saveAs()`. There is no transaction wrapping: if `DataLampiran::save()` fails after the document row is committed, the database and filesystem are inconsistent. `FileUploadService` exists but is ignored; controllers inline the same 8-line pattern.

**Depth issue**: The "interface" to save an attachment is the exact code to do it — no abstraction. `FileUploadService` is shallow because its interface (`sanitizeFilename`) is as complex as doing the replacement inline.

**Solution**: Create a `DocumentAttachment` module with an interface like `attach(DocumentId, UploadedFile): Result<AttachmentId>`. The implementation wraps database transaction + filesystem save + error rollback. The controller passes the uploaded file to the module; the module decides paths, names, and rollback.

**Benefits**:
- **Locality**: Path traversal, filename sanitization, and storage policy live in one place.
- **Leverage**: Every document type uses the same attachment seam.
- **Tests**: Test the attachment module with an in-memory filesystem adapter; no HTTP required.

---

## 4. Status Transition State Machine — Extract from Controller Logic

**Files**: `backend/controllers/PeraturanController::actionTambahStatus()`, `actionHapusStatus()`

**Problem**: The legal status state machine (dicabut ↔ mencabut, diubah ↔ mengubah) is implemented as a ~150-line method inside `PeraturanController`. It performs bidirectional updates on related `Peraturan` records, validates mutual exclusivity, and updates `DataStatus` history. This is core domain logic trapped in a UI controller.

**Depth issue**: The state machine rules are buried inside HTTP request handling. Understanding status transitions requires reading controller code. There is no seam for console commands or future APIs to trigger a transition safely.

**Solution**: Extract a `StatusTransition` module with an interface like `transition(DocumentId, fromStatus, toStatus): Result<void>`. The implementation owns the state graph, validation, and bidirectional bookkeeping.

**Benefits**:
- **Locality**: The state machine graph and invariants live in one file.
- **Leverage**: Backend web, console batch jobs, and (future) API endpoints all use the same seam.
- **Tests**: Unit-test state transitions with in-memory document store; no database or HTTP needed.

---

## 5. Search Query Builder — Replace Bloated Search Models

**Files**: `backend/models/PeraturanSearch.php`, `DokumenSearch.php`, `MonografiSearch.php`, `frontend/models/DokumenDataSubyek` search usage

**Problem**: Search models extend full ActiveRecord models, inheriting all attributes, table coupling, and database rules. Their interface surface is ~60+ filterable fields with trivial implementation (`andFilterWhere`). They are shallow: the interface (all the filterable attributes) is as large as the implementation.

**Depth issue**: Search models conflate "form parameters" with "database entity." Changing a search field requires editing a model that also carries `rules()`, `tableName()`, and relations.

**Solution**: Convert search classes into plain `yii\base\Model` form objects that compose a `DocumentQueryBuilder` module. The builder accepts filter parameters and returns an `ActiveQuery` without inheriting from ActiveRecord. The seam is at the query-builder interface, not at the form model.

**Benefits**:
- **Locality**: Query composition logic concentrates in the builder; form objects only define parameters.
- **Leverage**: Frontend and backend search forms can reuse the same query builder with different defaults.
- **Tests**: Unit-test query composition without hitting the database.

---

## 6. Public Feed Adapter — Decouple Feed Generation from Console Tier

**Files**: `console/controllers/FeedController.php`

**Problem**: The public JSON feed (for JDIHN portal integration) is generated by a console command that directly queries `Document`, `DataSubyek`, `DataPengarang`, and writes to JSON files. No other tier can reuse this logic. The feed generation is tightly coupled to Yii2 console runtime and filesystem paths.

**Depth issue**: The feed generation interface is "run this console command." There is no adapter for testing with in-memory output or for generating feeds on-demand via HTTP.

**Solution**: Extract a `FeedGeneration` module with an interface like `generate(condition, outputStream): Result<void>`. The console controller becomes one adapter; a future HTTP endpoint or cron job becomes another. The module accepts a query condition and writes to an abstract stream (file, memory, HTTP response).

**Benefits**:
- **Locality**: Feed formatting rules (field selection, JSON schema) live in one module.
- **Leverage**: One interface, multiple adapters: console cron, on-demand API, test harness.
- **Tests**: Generate feeds into a string/stream and assert on JSON structure — no filesystem or database needed.

---

## 7. Logging Cross-Cutting Concern — Replace Inline Duplication with a Behavior

**Files**: All `backend/controllers/*Controller.php` (logging blocks), `common/components/LogService.php` (unused), `backend/models/LogPustakawan.php`

**Problem**: Every controller action manually instantiates `LogPustakawan` with the same 6-line block. `LogService` exists but is ignored. Logging is a cross-cutting concern implemented as copy-pasted inline code.

**Depth issue**: The "interface" to log an action is the exact 6 lines to construct and save the model. No module hides the `DateHelper` formatting, user identity lookup, or controller/aksi naming convention.

**Solution**: Implement either a Yii2 `LoggableBehavior` attached to `Document` (fires on insert/update/delete) or a `DocumentAuditLog` module with an interface like `record(action, actor, documentId)`. The controller calls one method; the module handles formatting, persistence, and timestamping.

**Benefits**:
- **Locality**: Audit formatting and persistence live in one place.
- **Leverage**: Every document controller uses the same seam; cannot accidentally forget to log.
- **Tests**: Assert on audit log module with an in-memory adapter.

---

## How These Prepare for Migration

If the long-term direction is Turborepo + Hono (API) + Drizzle (ORM) + SvelteKit (frontend), each deepening above creates a domain module that can be reimplemented:
- **DocumentLifecycle** → Hono route handler calling a Drizzle-backed service
- **Unified Document** → Single Drizzle schema + shared types package
- **DocumentAttachment** → Storage adapter (local/S3) with same interface
- **StatusTransition** → Pure TypeScript state machine, testable independently
- **FeedGeneration** → Reusable across Hono API and cron worker
- **Logging** → Structured logging middleware in Hono

The work is not throwaway: deepened Yii2 modules map directly to deep modules in the new stack.

---

Which of these would you like to explore?