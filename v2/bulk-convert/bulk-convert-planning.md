# Bulk WebP Conversion — Implementation Plan

This is the step-by-step execution plan for the WebP thumbnail-conversion feature defined by `m1.md` → `m4.md` in this directory. It records the verified ground truth the four milestone prompts rely on, the exact order of operations, the decisions that must be made (and verified, never guessed) along the way, and the gates that stop work from advancing prematurely.

## 0. Verified starting state (confirmed by direct inspection, not assumed)

- **Plugin**: `wp-content/plugins/custom-thumbnail-generator` (v1.0.2). Global `CTGEN_`-prefixed classes, `ctgen_`-prefixed functions, single loader at `includes/init.php`.
- **No version control**: the project root is not a git repository. There is no revert safety net for any of this work.
- **No test harness**: no PHPUnit, no `composer.json`/`package.json`, no `tests/` directory anywhere in the project.
- **No lint config**: no PHPCS ruleset file anywhere in the project.
- **Existing AJAX/security pattern**: every handler in `includes/class-ajax-handlers.php` (class `CTGEN_Ajax_Handlers`) checks `check_ajax_referer( 'ctgen_media_actions', 'security' )` then `current_user_can( 'manage_options' )` before acting; input is sanitized with `sanitize_text_field( wp_unslash( ... ) )` / `absint()`.
- **Existing regeneration UI**: bulk button `#ctgen-regenerate` drives a sequential per-attachment AJAX loop (`ctgen_get_attachments` → repeated `ctgen_regenerate_single`) updating `#ctgen-progressbar`/`#ctgen-progress`/`#ctgen-percentage-increment`/`#ctgen-generator-status`; this loop does **not** check per-item success, a known gap the new feature must not repeat. A separate per-row action (`.ctgen-regen-thumbs`, in the custom-sizes table only) sends one unbounded request that loops the whole media library server-side with no progress bar.
- **Two separate size tables**: the default/core/theme sizes table (`CTGEN_Functions::ctgen_render_default_thumbnail_list_table`) has **no action column today**; the custom-sizes table (`templates/ctgen-custom-thumb-list.html.php`, `#ctgen-thumb-list`, dynamically reloaded via `ctgen_reload_thumb_list`) already has a two-button action column (regenerate, remove).
- **JS asset**: `assets/js/ctgen-media-actions.min.js` is the only shipped script, pre-minified, with no source file checked in anywhere.
- **Environment**: WordPress site served through Lando (recipe `wordpress`); Plugin Check plugin is installed at `wp-content/plugins/plugin-check`. WP-CLI commands should be tried Lando-first (`lando wp ...`), falling back to a plain `wp ...` if Lando's WP-CLI is unavailable.

Everything above was confirmed by reading the actual files, not inferred. Milestone 1 must re-confirm anything time-sensitive (tool availability, plugin activation state) before relying on it.

## 1. Prerequisites before Milestone 1 starts

1. **Recommend initializing git** for at least the plugin directory (or the whole site) before any code is written. There is currently no way to diff or revert a bad change. This is a recommendation, not a hard requirement of the milestone prompts — confirm with the site owner before doing it, since it touches the whole working directory, not just this plugin.
2. **Take a manual backup** of the plugin directory and, if end-to-end testing will touch real media, of `wp-content/uploads` (or at least the fixture files that will be used), since there is no version control to recover from an accidental overwrite.
3. **Confirm Plugin Check is active**: `lando wp plugin list --status=active` (fallback `wp plugin list --status=active`) and look for `plugin-check`.
4. **Confirm Lando is up**: the milestone prompts assume `lando wp ...` works; if Lando is not running, start it first and confirm with a trivial command (`lando wp core version`) before relying on it throughout the milestones.

## 2. Execution order

Run the four milestone prompts strictly in order — `m1.md`, then `m2.md`, then `m3.md`, then `m4.md` — feeding each one only after the previous one's **STRICT COMPLETION GATE** has been satisfied with real, pasted evidence. Do not run them out of order and do not start milestone *N+1* while milestone *N* has any open item.

### Milestone 1 — Safe WebP Conversion Core
- Builds the standalone converter service (one file in → one WebP file out), with no bulk/AJAX/UI involvement.
- **Also resolves, for the whole feature**: whether a real automated test harness can be established, and whether a PHPCS ruleset can be established — both by actually attempting them and reporting the true outcome, not by assumption. These two decisions are inherited by milestones 2–4.
- Gate: all 23 checklist items in `m1.md` pass with evidence; PHP lint clean; PHPCS/Plugin Check run (or explicitly reported as unavailable) with zero unresolved findings.

### Milestone 2 — Secure Thumbnail Conversion Operations
- Builds the AJAX backend: an eligible-attachments discovery endpoint (image-only, unlike the existing unfiltered `ctgen_get_all_attachments`) and a per-attachment conversion endpoint (all-sizes or one validated size slug), both nonce- and capability-gated, both calling the Milestone 1 converter directly.
- Extends whichever test/PHPCS path Milestone 1 actually established; does not start a second, parallel one.
- Gate: all 25 checklist items in `m2.md` pass with evidence, including that existing regeneration endpoints were re-exercised and are unchanged.

### Milestone 3 — Conversion Controls and Live Progress
- Adds the bulk button and per-row buttons (including adding an action column to the default sizes table, which has none today), client-side sequencing against the Milestone 2 endpoints, an independently-identified progress bar, accessibility, localization, and a maintainable non-minified JS source (since none currently exists).
- Must not copy the existing bulk-regenerate loop's success-blind counting, nor the existing per-row regenerate action's unbounded single-request pattern.
- Gate: all 22 checklist items in `m3.md` pass with evidence, all UI items actually exercised against the live admin page (or real browser automation), existing regenerate/remove controls re-confirmed unchanged.

### Milestone 4 — End-to-End Hardening and Release Verification
- Cross-milestone audit: re-runs (not re-cites) every prior milestone's evidence, closes any regression, runs the full 40-row regression matrix across the whole feature, updates `readme.txt`, and issues the final release verdict.
- Gate: all 40 regression-matrix rows pass with evidence gathered in this session; PHPCS/Plugin Check clean across the whole plugin; documentation matches only verified behavior.

## 3. Standing rules that apply across all four milestones

- **No guessing.** Every capability claim (WebP output support, animated-GIF preservation support, PHPCS/test-tooling availability) must be verified against the real environment in the session doing the work, not assumed from documentation or a prior belief.
- **No skipping ahead.** Each milestone's STRICT COMPLETION GATE must be fully satisfied, with pasted command output/observed results, before the next milestone's prompt is started.
- **No silent scope growth.** Each milestone touches only what its prompt scopes in; cross-cutting fixes to earlier milestones happen only when a defect is demonstrated, and must be reported as such.
- **No destructive testing against real user media.** Use fixtures or a disposable media set for anything that writes files; back up manually first given the lack of version control.
- **Security non-negotiables carried through every milestone**: nonce (`ctgen_media_actions`) + `manage_options` capability check on every privileged AJAX action; no client-supplied filesystem paths ever accepted; no filesystem paths or server internals leaked in any response; every input sanitized at the entry point and every output escaped for its context.
- **Preservation non-negotiables carried through every milestone**: original attachment files, original thumbnail files, and existing attachment metadata are never modified, deleted, or replaced. WebP files are always separate sidecar derivatives.

## 4. Open decisions to track as milestones execute

Record the actual outcome of each of these (they cannot be predicted in advance without guessing):

| Decision | Resolved in | Notes |
|---|---|---|
| Real PHPUnit harness vs. manual/`wp eval-file` substitute | Milestone 1 | Must be attempted for real; outcome documented and reused by 2–4. |
| PHPCS ruleset availability and content | Milestone 1 | Must confirm WPCS is actually installed before authoring `.phpcs.xml.dist`. |
| Existing destination collision policy (stable overwrite vs. deterministic suffix) | Milestone 1 | Must be a single, documented, retry-safe rule reused everywhere later. |
| Whether the active image editor (GD/Imagick) in this environment supports WebP output and animated-GIF preservation | Milestone 1 (and re-confirmed in Milestone 4) | Drives the animated-GIF skip/preserve behavior and any documented limitation in `readme.txt`. |
| Maintainable JS source/build approach given only a checked-in `.min.js` exists | Milestone 3 | Must keep the enqueued file and the readable source in sync and prove it. |

## 5. Definition of "ready to deploy"

The feature is ready to deploy only when Milestone 4's STRICT COMPLETION GATE is fully satisfied: all 40 regression-matrix rows pass with fresh evidence, PHPCS and Plugin Check are clean across the whole plugin (or every residual finding is explicitly justified as unresolvable with its risk stated), original files/metadata are proven untouched, and `readme.txt` describes only verified behavior. Anything short of that is reported as a named, open risk — never rounded up to "done."
