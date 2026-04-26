<?php
/**
 * prefill_labels.php  –  generates a fully pre-filled labels.php
 *
 * Usage:
 *   php prefill_labels.php > labels.php
 *
 * Every key position in every layer is listed in layout order so you can
 * look up what is currently printed and change the label.
 * Keys that already have a value can be edited; 'none' keys are included
 * so you can add a label if you want.
 */

// ── Reuse all parsing logic from generate_layout.php ────────────────────────
// Capture its output instead of printing it
ob_start();
require __DIR__ . '/generate_layout.php';
ob_end_clean();

// $layers, $KEY_POS, $overrides, $COMBOS are now available

// ── Layout order: same visual order as the PDF ───────────────────────────────
// Row 0: indices 0-13  (left to right including inner keys 6,7)
// Row 1: indices 14-27 (including inner keys 20,21)
// Row 2: indices 28-39 (no inner keys)
// Row 3: indices 40-45 (thumbs)
$layout_order = [
    // Row 0
    0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13,
    // Row 1
    14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27,
    // Row 2
    28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39,
    // Row 3 thumbs
    40, 41, 42, 43, 44, 45,
];

$layer_names = [
    0 => 'Layer 0 — default',
    1 => 'Layer 1',
    2 => 'Layer 2',
    3 => 'Layer 3',
    4 => 'Layer 4',
    5 => 'Layer 5',
];

// Try to extract display-name from keymap
preg_match_all('/display-name\s*=\s*"([^"]+)"/', $keymap_text, $dn);
foreach ($dn[1] as $i => $name) {
    $layer_names[$i] = "Layer $i — $name";
}

// ── Build the file content ───────────────────────────────────────────────────
$pad = 36; // padding for alignment

$out  = "<?php\n";
$out .= "/**\n";
$out .= " * labels.php  –  Customise what is printed inside every key cell.\n";
$out .= " *\n";
$out .= " * The KEY (left side) is the ZMK behaviour + params, no leading &.\n";
$out .= " * The VALUE (right side) is what gets printed in the key square.\n";
$out .= " * Use \"\\n\" (double quotes) to split text across two lines.\n";
$out .= " *\n";
$out .= " * Keys with empty string '' show as a grey empty cell.\n";
$out .= " * Remove or comment out a line to restore the built-in default.\n";
$out .= " *\n";
$out .= " * Regenerate after saving:\n";
$out .= " *   php generate_layout.php > layout.svg &&\n";
$out .= " *   inkscape layout.svg --export-type=pdf -o layout.pdf\n";
$out .= " */\n";
$out .= "return [\n\n";

foreach ($layers as $li => $bindings) {
    $name = $layer_names[$li] ?? "Layer $li";
    $out .= "    // " . str_repeat('─', 70) . "\n";
    $out .= "    // $name\n";
    $out .= "    // " . str_repeat('─', 70) . "\n";

    $row_labels = [0 => 'Row 0', 1 => 'Row 1', 2 => 'Row 2', 3 => 'Thumbs'];
    $last_row = -1;

    foreach ($layout_order as $idx) {
        [$behavior, $params] = $bindings[$idx] ?? ['none', []];

        // Determine display row for section headers
        [$dcol, $drow] = $KEY_POS[$idx];
        if ($drow !== $last_row) {
            $out .= "\n    // " . ($row_labels[$drow] ?? "Row $drow") . "\n";
            $last_row = $drow;
        }

        $bkey   = trim($behavior . ' ' . implode(' ', $params));
        $label  = binding_label($behavior, $params);
        $quoted = var_export($bkey, true);

        // Pad the key for alignment
        $padded = str_pad($quoted, $pad);

        // Show current rendered label as comment
        $comment = $label !== '' ? $label : '(empty)';
        // sanitise comment (remove newlines)
        $comment = str_replace(["\n", "\r"], ' / ', $comment);

        // Value: use existing override if present, else current rendered label
        $raw = isset($overrides[$bkey]) ? $overrides[$bkey] : $label;
        // Escape for a double-quoted PHP string, preserving \n as \n
        $escaped = str_replace(["\n", "\r", "\t", '\\', '"'], ['\\n', '\\r', '\\t', '\\\\', '\\"'], $raw);
        $value = '"' . $escaped . '"';

        $out .= "    $padded => $value, // $comment\n";
    }

    $out .= "\n";
}

$out .= "];\n";

echo $out;
