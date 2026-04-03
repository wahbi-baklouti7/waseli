# task-i18n-implementation.md

## Overview
Implement multi-language support (English, Arabic, French) in the React frontend using `i18next` and `react-i18next`.

## Goals
- Add `i18next`, `react-i18next`, and `i18next-browser-languagedetector` dependencies.
- Configure i18n with EN, AR, and FR locales.
- Implement RTL (Right-to-Left) support for Arabic.
- Create a LanguageSwitcher component.
- Integrate i18n into existing pages (Login, Register).
- Ensure language persistence in LocalStorage.

## Phases

### Phase 1: Setup & Configuration (EST: 5m)
- [ ] Install dependencies: `npm install i18next react-i18next i18next-browser-languagedetector`.
- [ ] Create `src/lib/i18n.ts` for initialization.
- [ ] Create `src/locales/` directory with `en.json`, `ar.json`, and `fr.json`.
- [ ] Initialize i18n in `src/main.tsx`.

### Phase 2: RTL & Global State (EST: 5m)
- [ ] Create a `LanguageContext` (or use i18next directly) to manage `dir="rtl"` on the `html` or `body` tag.
- [ ] Update `index.css` or Tailwind config if specific RTL utilities are needed (use logical properties like `ms-`, `me-`, `ps-`, `pe-`).

### Phase 3: UI Components (EST: 10m)
- [ ] Build `LanguageSwitcher` component (premium design with flags or local names).
- [ ] Add `LanguageSwitcher` to Layout/Auth pages.

### Phase 4: Implementation (EST: 15m)
- [ ] Update Register page to use `useTranslation`.
- [ ] Update Login page to use `useTranslation`.
- [ ] Add placeholders for dashboard content.

### Phase 5: Verification (EST: 5m)
- [ ] Verify persistence on reload.
- [ ] Verify RTL layout switches correctly for Arabic.
- [ ] Run `npm run lint`.

## Verification Criteria
- [ ] The app starts without ERRORS.
- [ ] Language switching changes UI text instantly.
- [ ] Arabic switches layout to Right-to-Left.
- [ ] Selected language persists across browser sessions.
