# Design System Document

## 1. Overview & Creative North Star: "The Digital Concierge"
This design system moves away from the "utility app" aesthetic to embrace an editorial, high-end marketplace feel. Our Creative North Star is **"The Digital Concierge"**—a system that feels authoritative yet approachable, blending the heritage of the Tunisian flag with a sophisticated, modern layout.

To move beyond the generic, we reject the rigid "box-in-a-box" layout. Instead, we use **intentional asymmetry**, **aspherical depth**, and **tonal layering**. We treat the mobile screen as a canvas of fine paper and frosted glass, where importance is signaled by elevation and color temperature rather than heavy outlines.

---

## 2. Colors & Surface Architecture
Our palette is rooted in the deep Red of Tunisia, balanced by a clinical White and a high-performance Green.

### The "No-Line" Rule
**Explicit Instruction:** Designers are prohibited from using 1px solid borders to define sections. Layout boundaries must be achieved exclusively through background color shifts. For example, a `surface-container-low` section should sit directly on a `surface` background to create a soft, structural break.

### Surface Hierarchy & Nesting
Treat the UI as a series of physical layers. Use the following tokens to create "nested" depth:
* **Base Layer:** `surface` (#fcf9f8)
* **Secondary Sectioning:** `surface-container-low` (#f6f3f2)
* **Elevated Content Cards:** `surface-container-lowest` (#ffffff)
* **High-Priority Overlays:** `surface-bright` (#fcf9f8)

### The Glass & Gradient Rule
To ensure a premium feel, floating elements (like Bottom Navigation or Sticky Headers) should utilize **Glassmorphism**:
* **Fill:** `surface` at 85% opacity.
* **Effect:** Backdrop-blur (20px to 40px).
* **Signature Texture:** Main CTAs should use a subtle linear gradient from `primary` (#a20513) to `primary-container` (#c62828) at a 135-degree angle to add "soul" and dimension.

---

## 3. Typography: Editorial Authority
We pair **Plus Jakarta Sans** for high-impact displays with **Inter** for functional reading. This contrast establishes an "editorial" hierarchy.

* **Display & Headlines (Plus Jakarta Sans):** Used for "Trips" and "Requests" headers. These should be tight-tracked (-2%) to feel modern and bold.
* **Body & Labels (Inter):** Used for all peer-to-peer communication and request details. Inter’s tall x-height ensures maximum legibility on mobile screens without the need for images.

**Hierarchy as Identity:**
* `display-sm` (2.25rem) is reserved for empty state headings and major milestones.
* `title-md` (1.125rem) is the workhorse for card titles, providing a clear, bold entry point for the user's eye.

---

## 4. Elevation & Depth
Depth in this system is a result of light and shadow, not lines.

* **The Layering Principle:** Avoid shadows for static content. Achieve hierarchy by stacking `surface-container-lowest` cards on top of `surface-container` backgrounds.
* **Ambient Shadows:** For floating action buttons or modal sheets, use extra-diffused shadows.
* *Shadow Setting:* Y: 8px, Blur: 24px, Color: `on-surface` at 6% opacity. This mimics natural light.
* **The "Ghost Border" Fallback:** If a container requires more definition for accessibility, use a "Ghost Border": `outline-variant` (#e4beba) at 15% opacity. 100% opaque borders are forbidden.
* **Tonal Tinting:**
* **Trips:** Use `tertiary-container` (#006da9) with 10% opacity as a background tint to signal traveler-centric data.
* **Requests:** Use a soft orange tint (Custom: #FFF4E5) to signal buyer-centric data.

---

## 5. Components

### Buttons
* **Primary:** Pill-shaped (`rounded-full`), utilizing the Red gradient. 16px horizontal padding.
* **CTA (WhatsApp):** `secondary` (#006d2f) background with `on-secondary` text. Used exclusively for final conversion actions.
* **Secondary/Ghost:** `surface-container-high` background with `primary` text. No border.

### Chips (Pill-Shaped)
* **Status Chips:** `rounded-full`. Use `tertiary-fixed` for "In Transit" and `secondary-fixed` for "Delivered."
* **Interaction:** 0.5px "Ghost Border" is allowed here to maintain shape at small scales.

### Input Fields
* **Styling:** No bottom line or full box. Use a `surface-container-highest` fill with a `xl` (1.5rem) border radius.
* **Typography:** Labels use `label-md` in `on-surface-variant` for a muted, sophisticated look.

### Cards & Lists (Text-Centric)
* **Rule:** Forbid divider lines.
* **Separation:** Use `spacing-6` (1.5rem) of vertical white space to separate list items.
* **Requests:** Since no images are allowed, use `headline-sm` for the item name and `body-md` for the description, ensuring the text-to-background contrast ratio exceeds 7:1 for premium readability.

---

## 6. Do’s and Don’ts

### Do:
* **Embrace Whitespace:** Let the `surface` color breathe. Use `spacing-8` or `spacing-10` between major sections.
* **Use Tonal Transitions:** Transition from `surface` to `surface-container-low` to signal a change in context (e.g., from a list of requests to a filtered view).
* **Prioritize Typography:** In the absence of images, let the weight and scale of `Plus Jakarta Sans` do the visual storytelling.

### Don't:
* **No High-Contrast Borders:** Never use #000000 or high-opacity grays for lines.
* **No Standard Shadows:** Avoid the "Default" Figma/CSS drop shadow. It looks cheap. Always tint your shadows with the `on-surface` color.
* **No Center-Alignment for Lists:** Keep all peer-to-peer data left-aligned (or right-aligned for RTL locales) to maintain a clean, architectural vertical axis.