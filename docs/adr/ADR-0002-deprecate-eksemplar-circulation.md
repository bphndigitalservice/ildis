# ADR-0002: Deprecate Eksemplar and Circulation Modules

## Status
Accepted

## Context

The legacy Yii2 system manages physical library operations through two distinct bounded contexts:
- **Eksemplar (Exemplar) Management**: Physical copy tracking, barcode generation, stock opname (inventory), pola eksemplar (barcode pattern templates).
- **Circulation**: Member registration, book loans/returns, overdue fines (`denda`).

These domains are represented by legacy tables: `eksemplar`, `pola_eksemplar`, `stock_opname_*`, `circulation`, `member`, `member_type`, `denda`, and related models.

## Decision

**Eksemplar and Circulation modules will NOT be migrated to the new stack.** They are deprecated and will be dropped entirely.

### Rationale

1. **Scope Reduction**: ILDIS's primary mission is JDIHN (National Law Documentation and Information Network) document metadata management for Indonesia's legal information portal. Physical library operations (barcodes, loans, fines) are secondary concerns that belong in a dedicated Integrated Library System (ILS), not in a legal document metadata platform.

2. **Maintenance Burden**: The legacy `Eksemplar` and `Circulation` codebases are tightly coupled to Yii2's ActiveRecord patterns and share the same anti-patterns as the document domain (fat controllers, inline logic, no tests). Porting them would require the same deepening effort as the document domain for marginal value.

3. **Separation of Concerns**: Physical copy management is orthogonal to legal document cataloging. A future dedicated ILS (e.g., Koha, SLiMS, or a custom microservice) should own this domain. Mixing it into ILDIS creates architectural leakage.

## Consequences

### What This Means

- No `eksemplars` table in Drizzle schema.
- No `circulations` table in Drizzle schema.
- No `members` table in Drizzle schema (member management is handled by Better Auth's `user` table).
- No `denda` table.
- No barcode generation logic.
- No stock opname functionality.
- The `monografi` document type retains its metadata fields (ISBN, penerbit, deskripsiFisik, etc.) but does NOT track physical copies or availability.

### Legacy Field Cleanup

The following legacy fields on the `document` table are **retained** because they are bibliographic metadata, not physical inventory:
- `isbn` (moved to metadata JSON)
- `penerbit` (moved to metadata JSON)
- `deskripsi_fisik` (moved to metadata JSON)
- `cetakan`, `edisi`, `gmd`, `judul_seri`, `klasifikasi` (all moved to metadata JSON)

The following fields are **dropped** because they are physical inventory:
- `tipe_koleksi_nomor_eksemplar`
- `pola_nomor_eksemplar`
- `jumlah_eksemplar`

### Data Migration Exclusion

The migration script (`scripts/migrate-legacy-data.ts`) will NOT migrate:
- `eksemplar` rows
- `pola_eksemplar` rows
- `stock_opname_*` rows
- `circulation` rows
- `member` / `member_type` rows
- `denda` rows

These legacy tables will be archived and the legacy system kept running in read-only mode for historical circulation records if needed.

## Future Reconsideration

If JDIHN institutions later require physical copy tracking, this decision should be revisited. The correct approach would be:
1. Build a separate `@ildis/physical-collections` microservice.
2. Integrate via API calls from `@ildis/api-adapter` (not in the core monolith).
3. Do NOT reintroduce these concerns into `@ildis/domain`.

---
*Recorded during architecture grilling loop, 2026-06-13.*
