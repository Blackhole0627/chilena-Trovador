# T3 — Visual rebrand + light/dark toggle

## Good news: dark mode + toggle already existed in the base script

- Per-user theme is driven by the `app_theme` cookie, falling back to the
  `site.default_user_theme` admin setting (`GenericHelperServiceProvider::getSiteTheme()`).
- A **toggle switcher already ships**: `resources/views/elements/footer/dark-mode-switcher.blade.php`,
  included in `footer.blade.php`, `footer-compact.blade.php`, and `user-side-menu.blade.php`.
- Separate compiled CSS variants exist (`bootstrap.dark.scss` etc.) selected by
  `getThemeCssSuffix()`.

So the client's "add a light/dark toggle" is **already satisfied** — no new toggle needed.

## What T3 changed — the Trovador palette (SCSS variables)

Light (`resources/sass/_variables.scss`):
- `$primary` `#cb0c9f` → **`#FF5A5F`** (accent)

Dark (`resources/sass/_variables-dark.scss`):
- `$primary-alt` `#cb0c9f` → **`#FF5A5F`** (accent)
- `$gray-900-alt` → **`#141210`** (body background)
- `$gray-800-alt` → **`#1E1C1A`** (cards / surfaces)
- `$gray-700-alt` → **`#262422`** (borders)
- `$gray-100-alt` → **`#E8E4E0`** (primary text)

These cascade through Bootstrap's theme maps, so most components pick up the brand
automatically.

## Required to take effect (on the VPS / build step)

1. **Recompile assets:** `npm run prod` (SCSS → CSS). Changes are invisible until then.
2. **Set the default theme** to dark so Trovador is dark-first: admin panel →
   default user theme = `dark` (or set `site.default_user_theme`). Users can still
   toggle to light via the existing switcher.
3. **Visual QA pass** — this is the one task that genuinely needs eyes on a screen.
   After `npm run prod`, walk the main screens in BOTH modes (feed, profile, post
   card, checkout, streams, Filament admin) and fix any spots where a hardcoded color
   still shows the old magenta. Grep `#cb0c9f` / `#FF1493` across `resources/` and
   `public/css` for stragglers.

## Remaining (do during visual QA)
- Hardcoded colors in individual blade/CSS files (if any) not covered by the SCSS vars.
- The border-radius (min 8px) / no-shadow "Apple style" pass from the brief — best
  done visually against the running site.
