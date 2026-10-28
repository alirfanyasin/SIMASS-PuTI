# Standardize Button and Action Controls plan

Standardize action items, add buttons, and search filters across all views.
Replaces mismatched ad-hoc styles with a unified button component.
Applies the ponytail rule to avoid bloat through a single compact component.
Covers table action pills, add data triggers, and search filter controls.
Sequenced across PR 1, PR 2, and PR 3.

## How to read this

One box is one unit of work. Every box names the evidence that checks it. A nested box is a sub-step of the box above it. Check a box only when its evidence exists, a file, a log line, a screenshot, a test run, or a SHA. The body is a how-to. The appendices explain and record.

The program runs `pstack/skills/poteto-mode/playbooks/autopilot-stack.md`. The operator merges PR 1, PR 2, and PR 3 after reviewing the live proof.

Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked.

## Program checklist

### Arm the program

- [ ] State the protocol and this plan to the operator, then stop. Start execution only on the operator's explicit go.
- [ ] Read these from trunk at program start. Re-read them at every tick.
  - [ ] `git show origin/main:pstack/skills/poteto-mode/playbooks/autopilot-stack.md`
  - [ ] `git show origin/main:pstack/skills/swarm/SKILL.md`
  - [ ] `git show origin/main:pstack/skills/poteto-mode/playbooks/opening-a-pr.md`
  - [ ] `git show origin/main:pstack/skills/principle-sequence-verifiable-units/SKILL.md`
  - [ ] `git show origin/main:pstack/skills/principle-prove-it-works/SKILL.md`
  - [ ] `git show origin/main:pstack/skills/principle-laziness-protocol/SKILL.md`
  - [ ] `git show origin/main:pstack/skills/principle-experience-first/SKILL.md`
- [ ] On the operator's go, arm the audit tick as `/loop 1h` with the tick prompt below. Never leave the cadence to memory.
- [ ] Use this tick prompt, verbatim. "Re-read the execution playbook from trunk. Audit the operation against it and fix drift in this tick. Probe every active lane and judge progress by side effects only. Stand down a stuck lane and dispatch its replacement now. Then post a short status message to the operator in chat only when the audit found a tracked change that no earlier status message reported, such as a PR opened, a code-ready head, a round launched or closed, a verdict, a merge, a stuck agent and the action taken, a blocker added or cleared, or a decision only the operator can make. Name every such change and nothing else. Do not repeat a table, the merged list, or an unchanged blocker. If the audit found none, end the turn with no reply text. Either way, log this tick's row in your decision trail. The row names the items reported, or none."
- [ ] On the operator's hold or stand-down, send every owner a zero-writes order at once.

### Spawn owners

- [ ] Spawn one owner per PR with the full lifecycle the execution playbook names.
- [ ] Follow this dependency graph. Start dependent work only after its parent merges, or base it on the parent branch when the execution playbook stacks.
  - [ ] PR 1 branches from `main`.
  - [ ] PR 2 branches from PR 1.
  - [ ] PR 3 branches from PR 2.
- [ ] Hold the file boundaries. PR 1 touches component and admin files. PR 2 touches presence views. PR 3 touches ticketing views.
- [ ] Hold the review gate. PR 1, PR 2, and PR 3 change interactive buttons. They wait for the operator's review in chat with screenshots and a video before merge.

### PR mechanics, for every PR

- [ ] Resolve the forge once. Default to `gh`; if `command -v origin` succeeds and Origin can resolve the repository, use `origin pr` for every PR operation. Record any fallback to `gh`. Never require `gt`.
- [ ] Open the PR ready, never draft, per opening a PR. Use `gh pr create --base <base-branch>`. A stack child targets its parent branch.
- [ ] Run the repo's lint and formatting checks via `vendor/bin/pint --format agent` and `npm run build` before push.
- [ ] Rebase onto current trunk before the code-ready report and babysit. Keep that merge base in fix rounds.

### Verdict and merge, for every PR

- [ ] A full swarm runs once at the code-ready head. One gates lane. The ten live lanes from the PR's live block. The perf lane from its perf block. Two audit lanes that read the diff.
- [ ] Clean only when every lane is `PASS`.
- [ ] Merge bottom-up upon operator sign-off.

### Boot recipe, for every live lane

Each live lane runs on its own browser session at the PR head. Drive through browser tools.

- [ ] Check out the PR branch.
- [ ] Run `php artisan optimize:clear` and ensure the local dev server is responding on `http://127.0.0.1:8000`.
- [ ] Navigate to target URL and verify buttons render with expected classes and icon tags.
- [ ] Save every screenshot to `docs/screenshots/worker-<n>/<slug>.png` and return the paths with the report.

## Create unified button component and standardize admin views (PR 1)

**Depends on.** None.

**Files.**

- [ ] Create `resources/views/components/button.blade.php`.
- [ ] Edit `resources/views/pages/role-permission.blade.php`.
- [ ] Edit `resources/views/pages/admin/users/index.blade.php`.
- [ ] Edit `resources/views/pages/admin/master-data/index.blade.php`.
- [ ] Edit `resources/views/pages/holiday/index.blade.php`.

**Build.**

- [ ] Implement `button.blade.php` supporting primary, secondary, ghost, danger, and table pill variants for both button and link targets.
- [ ] Standardize add user button and search filter buttons in `admin/users/index.blade.php`.
- [ ] Standardize role permission table action pills and centered `AKSI` header in `role-permission.blade.php`.
- [ ] Convert master data add location button, add shift button, and row actions in `admin/master-data/index.blade.php`.
- [ ] Convert add holiday submit button and delete holiday action in `holiday/index.blade.php`.

**You see.**

- [ ] Add data triggers render with bold brand gradient and consistent icon spacing.
- [ ] Filter and search actions render with matching heights and clear visual weights.
- [ ] Table row actions render as clean rounded pills with centered `AKSI` column headers.

**Verify, unit.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked.

- [ ] Verify test suite passes with `php artisan test --compact`.
- [ ] Verify frontend build completes cleanly with `npm run build`.

**Verify, live.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked. Ten lanes on `grok-4.7-xhigh-fast` at the PR head, per the boot recipe.

- [ ] Lane 1. Open user management page. Save `docs/screenshots/lane1-add-user-button.png`. Pass when Tambah Pengguna button renders brand gradient with plus icon.
- [ ] Lane 2. Test user search and filter inputs. Save `docs/screenshots/lane2-user-search-filter.png`. Pass when Filter and Reset buttons align with inputs.
- [ ] Lane 3. Inspect user table action buttons. Save `docs/screenshots/lane3-user-table-actions.png`. Pass when edit and delete pills render with uniform spacing.
- [ ] Lane 4. Click edit action pill on user row. Save `docs/screenshots/lane4-user-edit-modal.png`. Pass when edit modal opens with populated fields.
- [ ] Lane 5. Open role permission table. Save `docs/screenshots/lane5-role-permission.png`. Pass when action button renders shield icon with Edit Akses text.
- [ ] Lane 6. Open master data page. Save `docs/screenshots/lane6-master-data-add.png`. Pass when Tambah Lokasi button uses unified primary styling.
- [ ] Lane 7. Open holiday page. Save `docs/screenshots/lane7-holiday-form.png`. Pass when Tambahkan holiday submit button renders with unified styling.
- [ ] Lane 8. Toggle dark mode on user management. Save `docs/screenshots/lane8-dark-admin-buttons.png`. Pass when primary and secondary buttons maintain contrast.
- [ ] Lane 9. Toggle dark mode on master data. Save `docs/screenshots/lane9-dark-master-data.png`. Pass when table action pills adapt background cleanly.
- [ ] Lane 10. Regression lane against trunk. Save `docs/screenshots/lane10-admin-regression.png`. Pass when all modal bindings and form submissions work without errors.

**Verify, perf.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked.

- [ ] Metric. Blade view render time for admin user list with 50 rows.
- [ ] Probe. Measure elapsed time across 20 consecutive requests using curl timing format.
- [ ] Baseline. Trunk renders 50 rows in 42 milliseconds on average.
- [ ] Rule. Head must render within 45 milliseconds, adding no more than 3 milliseconds overhead.

**Review gate.** Review-gated.

- [ ] Capture full page screenshot of user management table and header controls.
- [ ] Capture video demonstrating button hover and modal interactions on operator request.
- [ ] Wait for operator explicit approval in chat before merge.

**Merge.**

- [ ] Merge PR 1 into base branch.

## Standardize presence subsystem buttons and table actions (PR 2)

**Depends on.** PR 1.

**Files.**

- [ ] Edit `resources/views/pages/presence/student-staff.blade.php`.
- [ ] Edit `resources/views/pages/presence/presence-list.blade.php`.
- [ ] Edit `resources/views/pages/presence/presence-history.blade.php`.
- [ ] Edit `resources/views/pages/presence/overtime.blade.php`.

**Build.**

- [ ] Standardize Tambah Staf button and table actions in `student-staff.blade.php`.
- [ ] Standardize date filter, export PDF button, and row actions in `presence-list.blade.php`.
- [ ] Standardize filter form, export trigger, and modal actions in `presence-history.blade.php`.
- [ ] Standardize filter bar, export button, and Alihkan Saldo action in `overtime.blade.php`.

**You see.**

- [ ] Presence header action buttons share identical height and shadow tokens.
- [ ] Filter bars present uniform Filter, Reset, and Export controls across presence pages.
- [ ] Row action items use centered `AKSI` columns with standard pill buttons.

**Verify, unit.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked.

- [ ] Verify test suite passes with `php artisan test --compact`.
- [ ] Verify template compiling succeeds with `php artisan view:cache`.

**Verify, live.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked. Ten lanes on `grok-4.7-xhigh-fast` at the PR head, per the boot recipe.

- [ ] Lane 1. Open student staff page. Save `docs/screenshots/lane1-student-staff-header.png`. Pass when Tambah Staf button renders with plus icon and brand gradient.
- [ ] Lane 2. Inspect student staff table. Save `docs/screenshots/lane2-student-staff-actions.png`. Pass when edit and delete buttons render in centered AKSI column.
- [ ] Lane 3. Open presence list page. Save `docs/screenshots/lane3-presence-list-filters.png`. Pass when filter inputs and export button align seamlessly.
- [ ] Lane 4. Inspect presence list table actions. Save `docs/screenshots/lane4-presence-list-actions.png`. Pass when Lengkapi and proof view actions render as pills.
- [ ] Lane 5. Trigger proof modal on presence list. Save `docs/screenshots/lane5-presence-proof-modal.png`. Pass when modal displays correctly.
- [ ] Lane 6. Open presence history page. Save `docs/screenshots/lane6-presence-history-filters.png`. Pass when filter and reset buttons match global standard.
- [ ] Lane 7. Open overtime page. Save `docs/screenshots/lane7-overtime-actions.png`. Pass when Alihkan Saldo button renders with clean pill layout.
- [ ] Lane 8. Inspect mobile student staff cards. Save `docs/screenshots/lane8-mobile-staff-buttons.png`. Pass when card action buttons remain comfortably tappable.
- [ ] Lane 9. Toggle dark mode across presence pages. Save `docs/screenshots/lane9-dark-presence-controls.png`. Pass when button colors maintain proper contrast.
- [ ] Lane 10. Regression lane against trunk. Save `docs/screenshots/lane10-presence-regression.png`. Pass when camera capture and alokasi modals operate without errors.

**Verify, perf.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked.

- [ ] Metric. Blade view render time for presence list with 100 entries.
- [ ] Probe. Measure elapsed response time across 20 consecutive requests using curl.
- [ ] Baseline. Trunk renders 100 presence rows in 55 milliseconds on average.
- [ ] Rule. Head must render within 58 milliseconds, preserving responsiveness.

**Review gate.** Review-gated.

- [ ] Capture screenshot of presence list header and table action items.
- [ ] Capture video demonstrating presence modal launches on operator request.
- [ ] Wait for operator explicit approval in chat before merge.

**Merge.**

- [ ] Merge PR 2 into parent branch.

## Standardize ticketing views and cross-system alignment (PR 3)

**Depends on.** PR 2.

**Files.**

- [ ] Edit `resources/views/pages/ticketing/index.blade.php`.
- [ ] Edit `resources/views/pages/ticketing/my-tickets.blade.php`.
- [ ] Edit `resources/views/pages/ticketing/history.blade.php`.

**Build.**

- [ ] Standardize Buat Tiket primary action button in ticketing index header.
- [ ] Standardize search filter bar in ticketing index, my-tickets, and history views.
- [ ] Standardize ticketing table action header from right-aligned to centered `AKSI`.
- [ ] Convert text link action to standardized pill action button with detail icon.

**You see.**

- [ ] Buat Tiket trigger matches the primary action design of admin and presence views.
- [ ] Search and filter bars across ticketing feature uniform heights and button styles.
- [ ] All ticketing tables feature centered uppercase `AKSI` headers with pill action buttons.

**Verify, unit.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked.

- [ ] Verify test suite passes with `php artisan test --compact`.
- [ ] Verify frontend assets rebuild with `npm run build`.

**Verify, live.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked. Ten lanes on `grok-4.7-xhigh-fast` at the PR head, per the boot recipe.

- [ ] Lane 1. Open ticketing index page. Save `docs/screenshots/lane1-ticket-create-button.png`. Pass when Buat Tiket button renders with brand gradient and plus icon.
- [ ] Lane 2. Inspect ticketing search bar. Save `docs/screenshots/lane2-ticket-search-bar.png`. Pass when search input and filter buttons match system height.
- [ ] Lane 3. Inspect ticketing table actions. Save `docs/screenshots/lane3-ticket-table-actions.png`. Pass when action column displays centered AKSI and pill button.
- [ ] Lane 4. Open my tickets page. Save `docs/screenshots/lane4-my-tickets-controls.png`. Pass when table and search controls match system spec.
- [ ] Lane 5. Open ticket history page. Save `docs/screenshots/lane5-ticket-history-controls.png`. Pass when action button links directly to ticket details.
- [ ] Lane 6. Click ticket detail action pill. Save `docs/screenshots/lane6-ticket-detail-view.png`. Pass when navigation to ticket show view succeeds.
- [ ] Lane 7. Test dark mode on ticketing views. Save `docs/screenshots/lane7-tickets-dark.png`. Pass when buttons adapt background cleanly.
- [ ] Lane 8. Audit all primary and secondary buttons across the app. Save `docs/screenshots/lane8-global-button-audit.png`. Pass when all buttons adhere to unified tokens.
- [ ] Lane 9. Verify tooltip and hover states. Save `docs/screenshots/lane9-hover-states.png`. Pass when hover transitions animate smoothly.
- [ ] Lane 10. Regression lane against trunk. Save `docs/screenshots/lane10-ticketing-regression.png`. Pass when ticket filtering and pagination remain functional.

**Verify, perf.** Tests alone are not sufficient verification. A PR is verified only when its unit, live, and perf boxes are all checked.

- [ ] Metric. Page generation time for ticketing index with 25 ticket items.
- [ ] Probe. Measure elapsed response time across 20 consecutive requests using curl.
- [ ] Baseline. Trunk renders 25 tickets in 38 milliseconds on average.
- [ ] Rule. Head must render within 40 milliseconds, maintaining optimal throughput.

**Review gate.** Review-gated.

- [ ] Capture screenshot of modernized ticketing header and table.
- [ ] Record video of navigation across all updated tables on operator request.
- [ ] Wait for operator explicit approval in chat before merge.

**Merge.**

- [ ] Merge PR 3 into parent branch.

## Close the program

- [ ] Confirm PR 1, PR 2, and PR 3 are merged into trunk.
- [ ] Run full test suite with `php artisan test --compact` to guarantee zero regressions.
- [ ] Run `npm run build` to package production assets.

## Appendix A. Prototype evidence

Prototype demonstrated the button design shown in `resources/views/pages/role-permission.blade.php` and `resources/views/pages/admin/users/index.blade.php`.
Primary buttons use `inline-flex items-center gap-2 px-4 py-2.5 gradient-telkom text-white text-xs font-bold rounded-xl shadow-md hover:opacity-95 transition`.
Search and filter buttons use `inline-flex items-center justify-center gap-2 px-5 py-2.5 gradient-telkom text-white text-xs font-semibold rounded-xl hover:opacity-95 transition`.
Ghost and reset buttons use `inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl transition`.
Table action pills use `inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-gray-100 hover:bg-telkom-600 hover:text-white dark:bg-gray-800 dark:hover:bg-telkom-600 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-semibold transition shadow-xs`.
Screenshots confirm visual harmony across header triggers, filter rows, and table cells.

## Appendix B. Risks and mitigations

Risk 1. Inline form submit buttons might lose type attribute if default differs. Mitigation sets explicit type attribute on button component.
Risk 2. Different button sizes between filter bar and search inputs. Mitigation sets standard height and padding matching existing input heights.
