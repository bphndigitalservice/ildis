# ILDIS Version Tag in Footer

## Purpose
Display the ILDIS application version based on the build release in the footer of both the frontend and backend applications.

## Approach
1. **Semantic Release Configuration**: 
   - Install `@semantic-release/exec` package.
   - Update `.releaserc.json` to echo the next release version into the `VERSION` file (`"prepareCmd": "echo ${nextRelease.version} > VERSION"`).
   - Ensure the `VERSION` file is added to the `@semantic-release/git` plugin assets to be committed.
2. **Source of Version**: The version will be read from the `VERSION` file in the root of the project instead of `package.json`. The `VERSION` file will accurately reflect the true release version dynamically updated by semantic-release.
3. **Backend Changes**: 
   - Update `backend/views/layouts/footer.php`.
   - Modify the PHP logic to read from `VERSION` instead of `package.json`.
4. **Frontend Changes**:
   - Update `frontend/views/layouts/footer.php`.
   - Add PHP logic to read from `VERSION`.
   - Update the HTML to display `powered by ILDIS v{version}` in the footer text.

## Verification
- Load both frontend and backend pages.
- Verify the footer shows the version (e.g., `v4.2.0`).
- Ensure no PHP warnings/errors if the `VERSION` file is missing.
