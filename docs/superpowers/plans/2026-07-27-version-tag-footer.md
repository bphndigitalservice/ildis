# ILDIS Version Tag in Footer Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Display the ILDIS application version based on the build release in the footer of both the frontend and backend applications, driven by semantic-release.

**Architecture:** Use `@semantic-release/exec` to echo the newly generated version into the `VERSION` file during release, commit it with `@semantic-release/git`, and update both backend and frontend footers to read the version from this `VERSION` file dynamically.

**Tech Stack:** PHP, Semantic Release, npm

---

### Task 1: Semantic-Release Configuration

**Files:**
- Modify: `package.json`
- Modify: `.releaserc.json`

- [ ] **Step 1: Install `@semantic-release/exec` package**

Run: `npm install @semantic-release/exec --save-dev`
Expected: Package installed successfully and `package.json` updated.

- [ ] **Step 2: Update `.releaserc.json` to configure plugins**

Modify `.releaserc.json` to include the `@semantic-release/exec` plugin before `@semantic-release/git`, and add `VERSION` to the `@semantic-release/git` assets.

```json
{
  "branches": ["main"],
  "tagFormat": "v${version}",
  "plugins": [
    [
      "@semantic-release/commit-analyzer",
      {
        "preset": "conventionalcommits",
        "releaseRules": [
          { "type": "feat", "release": "minor" },
          { "type": "fix", "release": "patch" },
          { "type": "perf", "release": "patch" },
          { "type": "refactor", "release": "patch" },
          { "type": "docs", "scope": "README", "release": "patch" },
          { "type": "build", "scope": "deps", "release": "patch" },
          { "type": "chore", "release": "patch" },
          { "type": "BREAKING CHANGE", "release": "major" }
        ]
      }
    ],
    [
      "@semantic-release/release-notes-generator",
      {
        "preset": "conventionalcommits",
        "presetConfig": {
          "types": [
            { "type": "feat", "section": "Features", "hidden": false },
            { "type": "fix", "section": "Bug Fixes", "hidden": false },
            { "type": "perf", "section": "Performance", "hidden": false },
            { "type": "refactor", "section": "Refactoring", "hidden": false },
            { "type": "docs", "section": "Documentation", "hidden": false },
            { "type": "test", "section": "Tests", "hidden": true },
            { "type": "build", "section": "Build System", "hidden": true },
            { "type": "ci", "section": "CI/CD", "hidden": true },
            { "type": "chore", "section": "Maintenance", "hidden": false }
          ]
        }
      }
    ],
    ["@semantic-release/changelog", {
      "changelogFile": "CHANGELOG.md"
    }],
    ["@semantic-release/exec", {
      "prepareCmd": "echo ${nextRelease.version} > VERSION"
    }],
    ["@semantic-release/github", {
      "addReleases": "bottom"
    }],
    ["@semantic-release/git", {
      "assets": ["CHANGELOG.md", "package.json", "VERSION"],
      "message": "chore(release): ${nextRelease.version} [skip ci]\n\n${nextRelease.notes}"
    }]
  ]
}
```

- [ ] **Step 3: Commit Semantic Release changes**

```bash
git add package.json package-lock.json .releaserc.json
git commit -m "build(deps): configure semantic-release to update VERSION file"
```

---

### Task 2: Update Backend Footer

**Files:**
- Modify: `backend/views/layouts/footer.php`

- [ ] **Step 1: Modify PHP logic to read from `VERSION`**

Update `backend/views/layouts/footer.php`. Find the existing `$packagePath` logic:

```php
$packagePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'package.json';
$package = file_exists($packagePath) ? json_decode(file_get_contents($packagePath), true) : [];
$appVersion = $package['version'] ?? 'dev';
```

Replace with:

```php
$versionPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'VERSION';
$appVersion = file_exists($versionPath) ? trim(file_get_contents($versionPath)) : 'dev';
```

- [ ] **Step 2: Commit Backend changes**

```bash
git add backend/views/layouts/footer.php
git commit -m "feat(backend): display application version from VERSION file in footer"
```

---

### Task 3: Update Frontend Footer

**Files:**
- Modify: `frontend/views/layouts/footer.php`

- [ ] **Step 1: Add PHP logic to read from `VERSION`**

At the top of `frontend/views/layouts/footer.php`, add:

```php
$versionPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'VERSION';
$appVersion = file_exists($versionPath) ? trim(file_get_contents($versionPath)) : 'dev';
```

- [ ] **Step 2: Update HTML to display version**

Find the footer-bottom__copy paragraph:

```html
      <p class="footer-bottom__copy">
        &copy; <?= date('Y') ?> <?= Html::encode($cleanInstansi) ?>
        powered by <a href="https://ildis.bphn.go.id" target="_blank" rel="noopener noreferrer" class="footer-bottom__ildis">ILDIS</a>
      </p>
```

Replace with:

```html
      <p class="footer-bottom__copy">
        &copy; <?= date('Y') ?> <?= Html::encode($cleanInstansi) ?>
        powered by <a href="https://ildis.bphn.go.id" target="_blank" rel="noopener noreferrer" class="footer-bottom__ildis">ILDIS</a> v<?= Html::encode($appVersion) ?>
      </p>
```

- [ ] **Step 3: Commit Frontend changes**

```bash
git add frontend/views/layouts/footer.php
git commit -m "feat(frontend): display application version from VERSION file in footer"
```
