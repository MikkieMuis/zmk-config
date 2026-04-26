<?php
/**
 * generate_blank.php  –  Blank Corne Choc Pro keyboard template
 *
 * Generates an A4 sheet with 6 empty keyboard layers — no labels, no combos,
 * no grey cells. All keys are white. Use it as a template to annotate by hand.
 *
 * Usage:
 *   php generate_blank.php > blank.svg
 *   inkscape blank.svg --export-type=pdf -o blank.pdf
 */

// ── Page / grid dimensions (mm) — must match generate_layout.php ─────────────
const PW      = 210;
const PH      = 297;
const KW      = 10;
const KH      = 10;
const ML      = 30;
const MT      = 5;
const RS      = 10;
const THUMB_Y = 34;
const LAYER_H = 44;
const LAYER_G = 4.5;
const LAYERS  = 6;

// ── 46-key position map: index => [display_col, display_row] ─────────────────
$KEY_POS = [
    // Row 0
     0=>[0,0],  1=>[1,0],  2=>[2,0],  3=>[3,0],  4=>[4,0],  5=>[5,0],
     6=>[6,0],  7=>[7,0],  8=>[8,0],  9=>[9,0], 10=>[10,0], 11=>[11,0],
    12=>[12,0], 13=>[13,0],
    // Row 1
    14=>[0,1], 15=>[1,1], 16=>[2,1], 17=>[3,1], 18=>[4,1], 19=>[5,1],
    20=>[6,1], 21=>[7,1], 22=>[8,1], 23=>[9,1], 24=>[10,1], 25=>[11,1],
    26=>[12,1], 27=>[13,1],
    // Row 2  (no inner keys)
    28=>[0,2], 29=>[1,2], 30=>[2,2], 31=>[3,2], 32=>[4,2], 33=>[5,2],
    34=>[8,2], 35=>[9,2], 36=>[10,2], 37=>[11,2], 38=>[12,2], 39=>[13,2],
    // Row 3  thumbs
    40=>[3,3], 41=>[4,3], 42=>[5,3],
    43=>[8,3], 44=>[9,3], 45=>[10,3],
];

// ── Coordinate helpers ────────────────────────────────────────────────────────
function col_x(int $col): float
{
    return $col <= 6 ? $col * KW : ($col + 1) * KW;
}

function row_y(int $row): float
{
    return $row < 3 ? $row * RS : THUMB_Y;
}

function inner_key_y(int $inner_row): float
{
    $start = (3 * RS - 2 * KH) / 2;
    return $start + $inner_row * KH;
}

// ── SVG ───────────────────────────────────────────────────────────────────────
$svg  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$svg .= "<svg xmlns=\"http://www.w3.org/2000/svg\"\n"
      . "     width=\"" . PW . "mm\" height=\"" . PH . "mm\"\n"
      . "     viewBox=\"0 0 " . PW . " " . PH . "\">\n\n";

$svg .= "<rect width=\"" . PW . "\" height=\"" . PH . "\" fill=\"white\"/>\n\n";

$inner_top    = [6, 7];
$inner_bottom = [20, 21];

for ($layer_idx = 0; $layer_idx < LAYERS; $layer_idx++) {

    $layer_top = MT + $layer_idx * (LAYER_H + LAYER_G);

    foreach ($KEY_POS as $idx => [$dcol, $drow]) {

        $kx = ML + col_x($dcol);

        if (in_array($idx, $inner_top)) {
            $ky = $layer_top + inner_key_y(0);
        } elseif (in_array($idx, $inner_bottom)) {
            $ky = $layer_top + inner_key_y(1);
        } else {
            $ky = $layer_top + row_y($drow);
        }

        $xf = number_format($kx, 3);
        $yf = number_format($ky, 3);

        $svg .= "<rect x=\"{$xf}\" y=\"{$yf}\" width=\"" . KW . "\" height=\"" . KH . "\" "
              . "fill=\"white\" stroke=\"#444444\" stroke-width=\"0.3\" rx=\"1\" />\n";
    }

    $svg .= "\n";
}

$svg .= "</svg>\n";

echo $svg;
