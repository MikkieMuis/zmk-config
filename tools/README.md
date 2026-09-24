# Corne Keyboard Layout Printer

Prints all 6 layers of your Corne Choc Pro keymap on a single page.
Run after every layout change.

Two variants are generated:

- **`tools/layout.pdf`** — portrait (A4). Each key is a 1 cm square.
  Print this at **100% scale** (disable "fit to page"), laminate.
- **`tools/layout_landscape.pdf`** — landscape, 16:10 (matches a 1920×1200
  monitor) with 14 mm keys in a 2-column × 3-row grid. Best chosen for
  **viewing on screen** ("fit to window" fills the monitor edge to edge).

---

## Files

| File | Purpose |
|---|---|
| `tools/generate_layout.php` | Portrait generator. Reads the keymap, emits `layout.svg`. Never edit this. |
| `tools/generate_layout_landscape.php` | Landscape generator. Reads the keymap, emits `layout_landscape.svg`. Never edit this. |
| `tools/labels.php` | Your custom key labels. **This is the only file you need to edit.** |
| `tools/layout.svg` / `tools/layout.pdf` | Portrait output. Overwritten every run (not tracked by git). |
| `tools/layout_landscape.svg` / `tools/layout_landscape.pdf` | Landscape output. Overwritten every run (not tracked by git). |

---

## Workflow

### 1 — Update your keymap from GitHub

```bash
cd ~/github/zmk-config
git pull
```

### 2 — Generate both PDFs

```bash
cd ~/github/zmk-config/tools
php generate_layout.php > layout.svg && inkscape layout.svg --export-type=pdf -o layout.pdf
php generate_layout_landscape.php > layout_landscape.svg && inkscape layout_landscape.svg --export-type=pdf -o layout_landscape.pdf
```

If the generator prints `WARNING: expected 6 layers, found X` you likely
forgot to `git pull` first.

### 3 — Print (portrait)

Open `layout.pdf` and print at **100% scale** (disable "fit to page").
Each key square will be exactly 1 cm.

### 4 — View on screen (landscape)

Open `layout_landscape.pdf` in a viewer and select **fit to window / zoom to
fit**. The 16:10 page fills the monitor edge to edge.

---

## Customising key labels

Open `tools/labels.php` in any text editor.

The file contains a PHP array. Each line is:
```php
'zmk-binding' => 'printed text',
```

The **key** (left side) is the ZMK binding from the keymap without the leading `&`,
behaviour name and parameters separated by a single space.

The **value** (right side) is exactly what gets printed in the key square.

### Examples

```php
'accept_copilot'          => "Accept\nCopilot",   // two lines
'tmux_down'               => "tmux\n↓",
'kp LG(F1)'               => 'WS 1',              // Super+F1 → Workspace 1
'kp LC(RIGHT_BRACKET)'    => 'C-]',
'copilot_hold_tap 0 LC(L)'=> "Copilot\n/C-L",
```

### Line breaks

Use `\n` inside double quotes to split text across two lines:
```php
'my_macro' => "line one\nline two",
```

### Font size is automatic

The generator fits the text to the square automatically:

| Label length | Result |
|---|---|
| 1–3 characters | Large, bold |
| 4–6 characters | Medium |
| 7+ characters | Small, word-wrapped to 2 lines |

Short labels print big, long labels print small. You can always force a
shorter label in `labels.php` to make a key easier to read.

### Finding the right key string

If a key currently shows e.g. `Sup-F1` in the PDF and you want to rename it,
look up that key in the keymap. The binding is `&kp LG(F1)` so the array key is:

```php
'kp LG(F1)' => 'WS 1',
```

Rule: strip the `&`, keep everything else exactly as written in the keymap.

---

## After editing labels.php

```bash
cd ~/github/zmk-config/tools
php generate_layout.php > layout.svg && inkscape layout.svg --export-type=pdf -o layout.pdf
php generate_layout_landscape.php > layout_landscape.svg && inkscape layout_landscape.svg --export-type=pdf -o layout_landscape.pdf
```

Nothing else is needed. The keymap file is not touched. Both PDFs pick up
the new labels automatically.

---

## Default label translations

When no override exists in `labels.php` the generator uses these defaults:

### Special keys

| ZMK binding | Printed label |
|---|---|
| `none` | *(empty, grey cell)* |
| `trans` | ▽ |
| `caps_word` | CpsWrd |
| `sys_reset` | Reset |
| `bootloader` | Boot |

### Hold-tap keys (homerow mods etc.)

Only the **tap** key is shown. The hold modifier is not printed.
If you want both, add an override in `labels.php`:
```php
'a_holdtap LEFT_ALT A' => "A\nAlt",
```

### Layer-tap keys (`lt`)

Only the tap key is shown. The layer number is not printed.
Override example:
```php
'lt 4 Z' => "Z\n[4]",
```

### Modifier prefixes

| Prefix | Meaning |
|---|---|
| `C-` | Ctrl |
| `S-` | Shift |
| `A-` | Alt |
| `Sup-` | Super / GUI / Windows key |

So `kp LG(F2)` becomes `Sup-F2` by default.

---

## Layer layout

```
Layer N

[ 0][ 1][ 2][ 3][ 4][ 5]  [6]     [7]  [ 8][ 9][10][11][12][13]
[ 0][ 1][ 2][ 3][ 4][ 5]  [6]  N  [7]  [ 8][ 9][10][11][12][13]
[ 0][ 1][ 2][ 3][ 4][ 5]           [ 8][ 9][10][11][12][13]
               [40][41][42]         [43][44][45]
```

- The two centre columns `[6]` and `[7]` are the inner keys, centred across the 3 main rows.
- `N` is the layer number (0–5), printed at the top of the gap.
- Row 3 is the thumb cluster.
- **Combo keys** appear as the same 1 cm squares but with a **dashed blue border** and light blue fill, centred on the midpoint between their two trigger keys. They are drawn on every layer since the arrow combos have no layer restriction.

---

## Combo keys

Combo keys are read automatically from the `combos { }` block in the keymap.  
Each combo with exactly 2 key-positions becomes a virtual key drawn at the geometric
midpoint between those two keys, using a dashed blue border to indicate it is not a
physical key.

The `BT_RGB_Layer_Toggle` combo (positions 6 & 7) is skipped because those positions
are already occupied by the inner keys.

If you add a new combo to the keymap, it will appear automatically on the next run,
provided both positions are valid 46-key indices.

---

## Troubleshooting

**PDF prints at the wrong size**
: Make sure "fit to page" / "scale to fit" is turned off in your print dialog. Print at 100%.

**A key shows the wrong label**
: Add or update the line for that binding in `labels.php` and re-run.

**A key shows a raw ZMK string like `my_macro`**
: The macro name has no entry in `labels.php` yet. Add one:
```php
'my_macro' => 'My Label',
```

**"WARNING: expected 6 layers, found X"**
: The keymap structure has changed. Run `git pull` first, then re-run.
