<?php
/**
 * generate_layout.php  –  Corne Choc Pro keyboard layout printer
 *
 * Reads: ../config/corne_choc_pro.keymap
 *        ./labels.php          (your custom key labels)
 *
 * Usage:
 *   php generate_layout.php > layout.svg
 *   inkscape layout.svg --export-type=pdf -o layout.pdf
 *
 * Then print layout.pdf at 100 % scale on A4.
 */

// ── Files ────────────────────────────────────────────────────────────────────
$keymap_file = __DIR__ . '/../config/corne_choc_pro.keymap';
$labels_file = __DIR__ . '/labels.php';

if (!file_exists($keymap_file)) {
    fwrite(STDERR, "ERROR: keymap file not found: $keymap_file\n");
    exit(1);
}

$keymap_text = file_get_contents($keymap_file);
$overrides   = file_exists($labels_file) ? require $labels_file : [];

// ── Page / grid dimensions (all in mm) ──────────────────────────────────────
const PW       = 210;   // A4 width
const PH       = 297;   // A4 height
const KW       = 10;    // key width
const KH       = 10;    // key height
const ML       = 30;    // left margin to keyboard left edge
const MT       = 5;     // top margin
const RS       = 10;    // row step  (rows are contiguous, no gap)
const THUMB_Y  = 34;    // y from layer-top to thumb row  (3*RS + 4 mm gap)
const LAYER_H  = 44;    // total layer height  (THUMB_Y + KH)
const LAYER_G  = 4.5;   // gap between layers

// ── 46-key position map: binding-index => [display_col, display_row] ────────
//
// Physical layout (from corne_choc_pro.json, default_layout):
//   Row 0  cols 0-13  (14 keys, including 2 inner keys at cols 6 & 7)
//   Row 1  cols 0-13  (14 keys)
//   Row 2  cols 0-5 + 8-13  (12 keys, no inner keys)
//   Row 3  cols 3,4,5 + 8,9,10  (6 thumb keys)
//
// Display columns:  0-6 are left of the gap, 7-13 are right of the gap.
// The gap (one key-width wide) sits between display col 6 and 7 and holds
// the layer number.
//
$KEY_POS = [
    // Row 0
     0=>[0,0],  1=>[1,0],  2=>[2,0],  3=>[3,0],  4=>[4,0],  5=>[5,0],
     6=>[6,0],  7=>[7,0],  8=>[8,0],  9=>[9,0], 10=>[10,0], 11=>[11,0],
    12=>[12,0], 13=>[13,0],
    // Row 1
    14=>[0,1], 15=>[1,1], 16=>[2,1], 17=>[3,1], 18=>[4,1], 19=>[5,1],
    20=>[6,1], 21=>[7,1], 22=>[8,1], 23=>[9,1], 24=>[10,1], 25=>[11,1],
    26=>[12,1], 27=>[13,1],
    // Row 2  (no inner keys → display cols 6 & 7 intentionally empty)
    28=>[0,2], 29=>[1,2], 30=>[2,2], 31=>[3,2], 32=>[4,2], 33=>[5,2],
    34=>[8,2], 35=>[9,2], 36=>[10,2], 37=>[11,2], 38=>[12,2], 39=>[13,2],
    // Row 3  thumbs
    40=>[3,3], 41=>[4,3], 42=>[5,3],
    43=>[8,3], 44=>[9,3], 45=>[10,3],
];

// ── Coordinate helpers ───────────────────────────────────────────────────────

/**
 * Display column → x offset from keyboard left edge (mm).
 * A one-key-wide gap lives between cols 6 and 7.
 */
function col_x(int $col): float
{
    return $col <= 6 ? $col * KW : ($col + 1) * KW;
}

/** Display row → y offset from layer top (mm). */
function row_y(int $row): float
{
    return $row < 3 ? $row * RS : THUMB_Y;
}

/**
 * y offset for the inner centre keys.
 * There are 2 keys stacked per side (indices 6+20 left, 7+21 right).
 * We centre the pair (20 mm total) within the 3 main rows (30 mm):
 *   gap = (30 - 20) / 2 = 5 mm
 *   first key  → 5 mm from layer top
 *   second key → 15 mm from layer top
 *
 * $inner_row: 0 = top key of the pair, 1 = bottom key of the pair
 */
function inner_key_y(int $inner_row): float
{
    $start = (3 * RS - 2 * KH) / 2;   // = 5 mm
    return $start + $inner_row * KH;
}

// ── ZMK keycode → readable label ────────────────────────────────────────────
$KC = [
    // Letters
    'A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G',
    'H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L','M'=>'M','N'=>'N',
    'O'=>'O','P'=>'P','Q'=>'Q','R'=>'R','S'=>'S','T'=>'T','U'=>'U',
    'V'=>'V','W'=>'W','X'=>'X','Y'=>'Y','Z'=>'Z',
    // Numbers (both ZMK forms)
    'N0'=>'0','N1'=>'1','N2'=>'2','N3'=>'3','N4'=>'4',
    'N5'=>'5','N6'=>'6','N7'=>'7','N8'=>'8','N9'=>'9',
    'NUMBER_0'=>'0','NUMBER_1'=>'1','NUMBER_2'=>'2','NUMBER_3'=>'3',
    'NUMBER_4'=>'4','NUMBER_5'=>'5','NUMBER_6'=>'6','NUMBER_7'=>'7',
    'NUMBER_8'=>'8','NUMBER_9'=>'9',
    // Navigation / editing
    'SPACE'=>'Spc',  'RET'=>'↵',    'ENTER'=>'↵',    'TAB'=>'Tab',
    'ESC'=>'Esc',    'ESCAPE'=>'Esc',
    'BSPC'=>'⌫',     'BACKSPACE'=>'⌫',
    'DEL'=>'Del',    'DELETE'=>'Del',
    'INSERT'=>'Ins', 'INS'=>'Ins',
    'HOME'=>'Home',  'END'=>'End',
    'PG_UP'=>'PgUp', 'PAGE_UP'=>'PgUp',
    'PG_DN'=>'PgDn', 'PAGE_DOWN'=>'PgDn',
    'UP_ARROW'=>'↑', 'DOWN_ARROW'=>'↓',
    'LEFT_ARROW'=>'←','RIGHT_ARROW'=>'→',
    'UP'=>'↑','DOWN'=>'↓','LEFT'=>'←','RIGHT'=>'→',
    // Modifiers
    'LCTRL'=>'LCtrl',  'RCTRL'=>'RCtrl',
    'LSHIFT'=>'LSft',  'RSHIFT'=>'RSft',
    'LEFT_SHIFT'=>'LSft','RIGHT_SHIFT'=>'RSft',
    'LALT'=>'LAlt',    'RALT'=>'RAlt',
    'LEFT_ALT'=>'LAlt','RIGHT_ALT'=>'RAlt',
    'LGUI'=>'Sup',     'RGUI'=>'Sup',
    'LMETA'=>'Sup',    'RMETA'=>'Sup',
    'LEFT_GUI'=>'Sup', 'RIGHT_GUI'=>'Sup',
    // Symbols
    'EXCL'=>'!',  'AT'=>'@',   'HASH'=>'#',   'DOLLAR'=>'$',  'PRCNT'=>'%',
    'CARET'=>'^', 'AMPS'=>'&', 'STAR'=>'*',   'LPAR'=>'(',    'RPAR'=>')',
    'MINUS'=>'-', 'PLUS'=>'+', 'EQUAL'=>'=',  'UNDER'=>'_',
    'GRAVE'=>'`', 'TILDE'=>'~',
    'LBKT'=>'[',  'RBKT'=>']',
    'LEFT_BRACKET'=>'[', 'RIGHT_BRACKET'=>']',
    'LBRC'=>'{',  'RBRC'=>'}',
    'LEFT_BRACE'=>'{','RIGHT_BRACE'=>'}',
    'BACKSLASH'=>'\\','BSLH'=>'\\',
    'PIPE'=>'|',  'SEMI'=>';', 'COLON'=>':',
    'SQT'=>"'",   'SINGLE_QUOTE'=>"'",  'APOS'=>"'",
    'DQT'=>'"',   'DOUBLE_QUOTES'=>'"',
    'COMMA'=>',', 'DOT'=>'.', 'SLASH'=>'/',
    'LT'=>'<',    'GT'=>'>',  'QMARK'=>'?',
    // Function keys
    'F1'=>'F1','F2'=>'F2','F3'=>'F3','F4'=>'F4',
    'F5'=>'F5','F6'=>'F6','F7'=>'F7','F8'=>'F8',
    'F9'=>'F9','F10'=>'F10','F11'=>'F11','F12'=>'F12',
    // Media / system
    'C_MUTE'=>'Mute',    'C_VOL_UP'=>'Vol+',  'C_VOL_DN'=>'Vol-',
    'K_VOLUME_UP'=>'Vol+','K_VOLUME_DOWN'=>'Vol-',
    'C_BRI_UP'=>'Bri+',  'C_BRI_DN'=>'Bri-',
    'C_BRI_DEC'=>'Bri-', 'C_BRI_INC'=>'Bri+',
    'C_PREV'=>'Prev',    'C_NEXT'=>'Next',
    'C_PP'=>'Play',      'C_PAUSE'=>'Pause',  'C_PLAY_PAUSE'=>'Play',
    'PRINTSCREEN'=>'PrtSc','PSCRN'=>'PrtSc',
    'CAPS'=>'Caps',
];

/** Recursively parse a ZMK keycode/modifier-wrapper into a label. */
function parse_kp(string $token): string
{
    global $KC;
    // Modifier wrappers:  LC(x)  LS(x)  LG(x)  LA(x)  RC RC RS RA RG
    if (preg_match('/^(LC|RC|LS|RS|LA|RA|LG|RG)\((.+)\)$/', $token, $m)) {
        $mod_short = [
            'LC'=>'C','RC'=>'C','LS'=>'S','RS'=>'S',
            'LA'=>'A','RA'=>'A','LG'=>'Sup','RG'=>'Sup',
        ];
        $mod   = $mod_short[$m[1]] ?? $m[1];
        $inner = parse_kp($m[2]);
        return $mod . '-' . $inner;
    }
    return $KC[$token] ?? $token;
}

// ── Behaviour → parameter count ─────────────────────────────────────────────
// -1 = special handling  (bt)
$BEHAV_PARAMS = [
    'kp'=>1,  'none'=>0, 'trans'=>0, 'caps_word'=>0,
    'sys_reset'=>0,  'bootloader'=>0,  'studio_unlock'=>0,
    'tog'=>1, 'lt'=>2,  'mt'=>2,
    'rgb_ug'=>1,  'mmv'=>1, 'mkp'=>1, 'msc'=>1,
    // custom hold-taps  (2 params)
    'a_holdtap'=>2, 'fj_holdtap'=>2, 'dk_holdtap'=>2,
    'sl_holdtab'=>2, 'copilot_hold_tap'=>2, 'del_hoid_tap'=>2,
    'lalt_hrm'=>2, 'lctrl_hrm'=>2, 'lshift_hrm'=>2, 'lgui_hrm'=>2,
    'ralt_hrm'=>2, 'rctrl_hrm'=>2, 'rshift_hrm'=>2, 'rgui_hrm'=>2,
    // macros  (0 params)
    'dash_left'=>0,'equals_left'=>0,'or'=>0,'and'=>0,
    'equalequal'=>0,'notequal'=>0,'euro'=>0,'Pound'=>0,'Plusminus'=>0,
    'clear_terminal'=>0,'accept_copilot'=>0,
    'tmux_down'=>0,'tmux_up'=>0,'tmux_left'=>0,'tmux_right'=>0,
    'tmux_vsplit'=>0,'tmux_split'=>0,'tmux_zoom'=>0,'tmux_close'=>0,
    'tmux_copy_mode'=>0,'tmux_pageup'=>0,'tmux_create'=>0,
    'tmux_next'=>0,'tmux_prev'=>0,'reset_terminal'=>0,
    // bt  handled separately
    'bt'=>-1,
];

$RGB_LABELS = [
    'RGB_ON'=>'RGB on',  'RGB_OFF'=>'RGB off', 'RGB_TOG'=>'RGB tog',
    'RGB_HUI'=>'Hue+',   'RGB_HUD'=>'Hue-',
    'RGB_SAI'=>'Sat+',   'RGB_SAD'=>'Sat-',
    'RGB_BRI'=>'Bri+',   'RGB_BRD'=>'Bri-',
    'RGB_SPI'=>'Spd+',   'RGB_SPD'=>'Spd-',
    'RGB_EFF'=>'Eff+',   'RGB_EFR'=>'Eff-',
];

$BT_LABELS = [
    'BT_CLR'=>'BT clr',  'BT_CLR_ALL'=>'BT clr all',
    'BT_SEL'=>'BT',      'BT_DISC'=>'BT disc',
    'BT_NXT'=>'BT nxt',  'BT_PRV'=>'BT prv',
];

$MOUSE_LABELS = [
    'MOVE_LEFT'=>'← mse',  'MOVE_RIGHT'=>'→ mse',
    'MOVE_UP'=>'↑ mse',    'MOVE_DOWN'=>'↓ mse',
    'SCRL_LEFT'=>'← scrl', 'SCRL_RIGHT'=>'→ scrl',
    'SCRL_UP'=>'↑ scrl',   'SCRL_DOWN'=>'↓ scrl',
    'LCLK'=>'LClk', 'RCLK'=>'RClk', 'MCLK'=>'MClk',
    'MB1'=>'LClk',  'MB2'=>'RClk',  'MB3'=>'MClk',
];

// ── Tokenise a bindings = <...> block ───────────────────────────────────────
function tokenize_bindings(string $text): array
{
    global $BEHAV_PARAMS;

    // Strip line comments and compress whitespace
    $text = preg_replace('/\/\/[^\n]*/', ' ', $text);
    $text = preg_replace('/\s+/', ' ', trim($text));

    $tokens   = preg_split('/\s+/', $text);
    $bindings = [];
    $i        = 0;
    $count    = count($tokens);

    while ($i < $count) {
        $tok = $tokens[$i];
        if ($tok === '' || $tok[0] !== '&') { $i++; continue; }

        $behavior = ltrim($tok, '&');
        $params   = [];
        $i++;

        $n = $BEHAV_PARAMS[$behavior] ?? null;

        if ($n === null) {
            // Unknown: grab up to 2 non-& tokens
            for ($j = 0; $j < 2 && $i < $count && $tokens[$i][0] !== '&'; $j++) {
                $params[] = $tokens[$i++];
            }
        } elseif ($n === -1) {
            // bt: first param always present
            if ($i < $count) {
                $action = $tokens[$i++];
                $params[] = $action;
                // BT_SEL / BT_DISC take one more int param
                if (in_array($action, ['BT_SEL', 'BT_DISC'])
                        && $i < $count && $tokens[$i][0] !== '&') {
                    $params[] = $tokens[$i++];
                }
            }
        } else {
            for ($j = 0; $j < $n; $j++) {
                if ($i < $count && $tokens[$i][0] !== '&') {
                    $params[] = $tokens[$i++];
                }
            }
        }

        $bindings[] = [$behavior, $params];
    }

    return $bindings;
}

// ── Convert one parsed binding to a display label ───────────────────────────
function binding_label(string $behavior, array $params): string
{
    global $KC, $RGB_LABELS, $BT_LABELS, $MOUSE_LABELS, $overrides;

    // Check override by  "behavior param1 param2 …"  key
    $key = trim($behavior . ' ' . implode(' ', $params));
    if (isset($overrides[$key]))            return $overrides[$key];
    if (isset($overrides[$behavior]))       return $overrides[$behavior];

    switch ($behavior) {
        case 'none':            return '';
        case 'trans':           return '';
        case 'caps_word':       return 'CpsWrd';
        case 'sys_reset':       return 'Reset';
        case 'bootloader':      return 'Boot';
        case 'studio_unlock':   return 'Studio';

        case 'kp':
            return parse_kp($params[0] ?? '?');

        case 'tog':
            return 'Lay ' . ($params[0] ?? '?');

        case 'lt':
            // layer-tap: only show the tap key
            return parse_kp($params[1] ?? '?');

        case 'mt':
            // mod-tap: only show the tap key
            return parse_kp($params[1] ?? '?');

        case 'rgb_ug':
            return $RGB_LABELS[$params[0] ?? ''] ?? ($params[0] ?? 'RGB');

        case 'mmv':
            return $MOUSE_LABELS[$params[0] ?? ''] ?? 'mouse';

        case 'mkp':
            return $MOUSE_LABELS[$params[0] ?? ''] ?? 'click';

        case 'msc':
            return $MOUSE_LABELS[$params[0] ?? ''] ?? 'scroll';

        case 'bt':
            $action = $params[0] ?? 'BT';
            $base   = $BT_LABELS[$action] ?? $action;
            return isset($params[1]) ? $base . ' ' . $params[1] : $base;

        // Homerow / custom hold-taps: show the tap key (second param)
        case 'a_holdtap':
        case 'fj_holdtap':
        case 'dk_holdtap':
        case 'sl_holdtab':
        case 'lalt_hrm':
        case 'lctrl_hrm':
        case 'lshift_hrm':
        case 'lgui_hrm':
        case 'ralt_hrm':
        case 'rctrl_hrm':
        case 'rshift_hrm':
        case 'rgui_hrm':
            return parse_kp($params[1] ?? '?');

        case 'copilot_hold_tap':
            // hold = accept_copilot,  tap = kp $params[1]
            return 'Copilot/' . parse_kp($params[1] ?? '');

        case 'del_hoid_tap':
            // hold = kp DELETE,  tap = none
            return parse_kp($params[0] ?? 'Del');

        default:
            return $behavior;
    }
}

// ── Parse all layer bindings from the keymap ────────────────────────────────
// Only match  "bindings = <...>"  not  "sensor-bindings = <...>"
preg_match_all('/(?<!-)bindings\s*=\s*<([^>]+)>/s', $keymap_text, $raw_matches);

$layers = [];
foreach ($raw_matches[1] as $raw) {
    $bindings = tokenize_bindings($raw);
    // Only keep blocks that look like a full 46-key layer
    if (count($bindings) === 46) {
        $layers[] = $bindings;
    }
}

if (count($layers) !== 6) {
    fwrite(STDERR, sprintf(
        "WARNING: expected 6 layers, found %d.\n", count($layers)
    ));
}

// ── Parse combos from keymap ─────────────────────────────────────────────────
/**
 * Returns array of combos, each:
 *   ['label' => string, 'positions' => [int, int]]
 *
 * Skips the BT_RGB combo (key-positions <6 7>) because those are inner keys.
 */
function parse_combos(string $keymap_text): array
{
    // Extract the combos { ... } block
    if (!preg_match('/combos\s*\{[^}]*compatible\s*=\s*"zmk,combos"[^}]*\}(.*?)(?=\n\s{4}\};)/s', $keymap_text, $m)) {
        // Fallback: grab everything between 'combos {' and the matching closing '};'
        if (!preg_match('/combos\s*\{(.+?)\n\s{4}\};/s', $keymap_text, $m)) {
            return [];
        }
    }
    $block = $m[0];

    // Each combo node: name { bindings = <...>; key-positions = <...>; };
    preg_match_all('/\w+\s*\{[^}]+\}/s', $block, $nodes);

    $combos = [];
    foreach ($nodes[0] as $node) {
        // Skip if it's the outer combos block header
        if (strpos($node, 'compatible') !== false) continue;

        // key-positions
        if (!preg_match('/key-positions\s*=\s*<([^>]+)>/', $node, $kp)) continue;
        $positions = array_map('intval', preg_split('/\s+/', trim($kp[1])));
        if (count($positions) !== 2) continue;

        // Skip BT_RGB (positions 6 and 7)
        if ($positions === [6, 7] || $positions === [7, 6]) continue;

        // bindings
        if (!preg_match('/bindings\s*=\s*<([^>]+)>/', $node, $bm)) continue;
        $raw = trim($bm[1]);
        $parsed = tokenize_bindings($raw);
        if (empty($parsed)) continue;
        [$beh, $params] = $parsed[0];
        $label = binding_label($beh, $params);

        $combos[] = ['label' => $label, 'positions' => $positions];
    }
    return $combos;
}

$COMBOS = parse_combos($keymap_text);

// ── SVG text rendering ───────────────────────────────────────────────────────

/**
 * Word-wrap $label to at most 2 lines, then choose font-size & weight
 * so the text fits inside a KW×KH mm cell with 0.5 mm padding.
 *
 * Returns an SVG <text> element string centred on ($cx, $cy).
 */
function key_text_svg(string $label, float $cx, float $cy): string
{
    if ($label === '') return '';

    $max_w = KW - 1.0;  // 9 mm usable width
    $max_h = KH - 1.0;  // 9 mm usable height

    // Split on explicit \n first
    $lines = explode("\n", $label);

    // Word-wrap any line that is still too long
    $final = [];
    foreach ($lines as $line) {
        $words = preg_split('/(?<=\s)|(?=\s)/', $line, -1, PREG_SPLIT_NO_EMPTY);
        // Try to wrap long single line near the middle character
        if (strlen($line) > 9 && count($words) > 1) {
            $mid  = strlen($line) / 2;
            $best_pos = -1;
            $best_d   = PHP_INT_MAX;
            $pos = 0;
            foreach ($words as $wi => $w) {
                if ($wi > 0 && trim($w) !== '') {
                    $d = abs($pos - $mid);
                    if ($d < $best_d) { $best_d = $d; $best_pos = $wi; }
                }
                $pos += strlen($w);
            }
            if ($best_pos > 0) {
                $final[] = rtrim(implode('', array_slice($words, 0, $best_pos)));
                $final[] = ltrim(implode('', array_slice($words, $best_pos)));
            } else {
                $final[] = $line;
            }
        } else {
            $final[] = $line;
        }
    }

    // Limit to 3 lines max
    $final = array_slice($final, 0, 3);
    $n     = count($final);

    $max_chars = max(array_map('strlen', $final));
    if ($max_chars === 0) return '';

    // Approximate character width = font_size × 0.58  (sans-serif)
    $CWF = 0.58;
    $LHF = 1.25; // line-height / font-size ratio

    // Largest font-size that fits the widest line
    $fs = min(4.5, $max_w / max(1, $max_chars * $CWF));

    // Also ensure all lines fit vertically
    $max_fs_for_h = $max_h / ($n * $LHF);
    $fs = min($fs, $max_fs_for_h);
    $fs = max($fs, 1.4); // never go below 1.4 mm

    $fw = 'normal'; // no bold

    $lh = $fs * $LHF;
    $total_h = $n * $lh;
    // y of the first baseline
    $y0 = $cy - $total_h / 2 + $lh * 0.75;

    // font-size in plain user-units (1 unit = 1 mm in this viewBox)
    $fss  = number_format($fs, 2);
    $svg  = "<text x=\"{$cx}\" font-size=\"{$fss}\" font-weight=\"{$fw}\" "
          . "font-family=\"DejaVu Sans,Liberation Sans,sans-serif\" "
          . "text-anchor=\"middle\" fill=\"#111\">";

    foreach ($final as $i => $line) {
        $y  = $y0 + $i * $lh;
        $ys = number_format($y, 3);
        $svg .= "<tspan x=\"{$cx}\" y=\"{$ys}\">"
              . htmlspecialchars($line, ENT_XML1 | ENT_QUOTES, 'UTF-8')
              . "</tspan>";
    }

    $svg .= "</text>\n";
    return $svg;
}

// ── Compute the centre (cx, cy) of a key by binding index ───────────────────
function key_center(int $idx, float $layer_top): array
{
    global $KEY_POS;
    $inner_top    = [6, 7];
    $inner_bottom = [20, 21];

    [$dcol, $drow] = $KEY_POS[$idx];
    $kx = ML + col_x($dcol);
    if (in_array($idx, $inner_top)) {
        $ky = $layer_top + inner_key_y(0);
    } elseif (in_array($idx, $inner_bottom)) {
        $ky = $layer_top + inner_key_y(1);
    } else {
        $ky = $layer_top + row_y($drow);
    }
    return [$kx + KW / 2, $ky + KH / 2];
}

/**
 * Compute the (cx, cy) where a combo box should be drawn.
 *
 * Combos are placed OUTSIDE the keyboard grid so they don't overlap trigger keys:
 *  - Horizontal pair (same display row): placed left of left-most key or right of
 *    right-most key (whichever is the outer edge), 1 mm gap.
 *  - Vertical pair (same display col): placed to the right (right-half cols) or
 *    left (left-half cols), 1 mm gap.
 *
 * y (for horizontal) or x (for vertical) is the midpoint between the two trigger
 * key centres so the combo visually reads as "between" them.
 */
function combo_center(int $p0, int $p1, float $layer_top): array
{
    global $KEY_POS;

    [$col0, $row0] = $KEY_POS[$p0];
    [$col1, $row1] = $KEY_POS[$p1];

    [$cx0, $cy0] = key_center($p0, $layer_top);
    [$cx1, $cy1] = key_center($p1, $layer_top);

    $gap = 1.0; // mm between keyboard edge and combo box centre

    if ($row0 === $row1) {
        // Horizontal pair → place below the row, centred between the two keys.
        // (Thumb keys only occupy cols 3-5 and 8-10, so cols 0-1 and 12-13 are clear.)
        $mid_cx  = ($cx0 + $cx1) / 2;
        $bot_edge = $cy0 + KH / 2;          // bottom edge of the row
        return [$mid_cx, $bot_edge + KH / 2 + $gap];
    } else {
        // Vertical pair → place outside on the outer horizontal edge of that col
        $mid_cy = ($cy0 + $cy1) / 2;
        $dcol   = $col0; // both keys share the same display col
        if ($dcol < 7) {
            $left_edge = ML + col_x($dcol);
            return [$left_edge - KW / 2 - $gap, $mid_cy];
        } else {
            $right_edge = ML + col_x($dcol) + KW;
            return [$right_edge + KW / 2 + $gap, $mid_cy];
        }
    }
}

// ── Render a single key cell ─────────────────────────────────────────────────
function key_svg(float $x, float $y, string $label, bool $empty = false): string
{
    $xf = number_format($x, 3);
    $yf = number_format($y, 3);

    $fill   = $empty ? '#f0f0f0' : '#ffffff';
    $stroke = '#444444';

    $rect = "<rect x=\"{$xf}\" y=\"{$yf}\" width=\"" . KW . "\" height=\"" . KH . "\" "
          . "fill=\"{$fill}\" stroke=\"{$stroke}\" stroke-width=\"0.3\" rx=\"1\" />\n";

    $cx = $x + KW / 2;
    $cy = $y + KH / 2;

    return $rect . key_text_svg($label, number_format($cx, 3), number_format($cy, 3));
}

// ── Render a combo key (same size as normal key, dashed border, blue tint) ───
function combo_svg(float $cx, float $cy, string $label): string
{
    $x  = number_format($cx - KW / 2, 3);
    $y  = number_format($cy - KH / 2, 3);
    $cxf = number_format($cx, 3);
    $cyf = number_format($cy, 3);

    $rect = "<rect x=\"{$x}\" y=\"{$y}\" width=\"" . KW . "\" height=\"" . KH . "\" "
          . "fill=\"#dff0ff\" stroke=\"#3366aa\" stroke-width=\"0.4\" "
          . "stroke-dasharray=\"1.5 0.8\" rx=\"1\" />\n";

    return $rect . key_text_svg($label, $cxf, $cyf);
}

// ── Generate full SVG ────────────────────────────────────────────────────────
$svg  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$svg .= "<svg xmlns=\"http://www.w3.org/2000/svg\"\n"
      . "     width=\"" . PW . "mm\" height=\"" . PH . "mm\"\n"
      . "     viewBox=\"0 0 " . PW . " " . PH . "\">\n\n";

// White background
$svg .= "<rect width=\"" . PW . "\" height=\"" . PH . "\" fill=\"white\"/>\n\n";

foreach ($layers as $layer_idx => $bindings) {

    $layer_top = MT + $layer_idx * (LAYER_H + LAYER_G);

    // ── Half labels (above each split, in the gap between layers) ────────────
    // Centre of the gap above this layer; for layer 0 use half the top margin.
    $label_y  = $layer_idx === 0 ? MT / 2 : $layer_top - LAYER_G / 2;
    $ly = number_format($label_y, 3);

    foreach ([
        ['left',  ML + col_x(0),             'start'],
        ['right', ML + col_x(13) + KW,       'end'  ],
    ] as [$side, $hx, $anchor]) {
        $hkey  = "label_{$layer_idx}_{$side}";
        $htext = $overrides[$hkey] ?? '';
        if ($htext !== '') {
            $hxf = number_format($hx, 3);
            $svg .= "<text x=\"{$hxf}\" y=\"{$ly}\" "
                  . "font-size=\"3\" font-weight=\"bold\" "
                  . "font-family=\"DejaVu Sans,Liberation Sans,sans-serif\" "
                  . "text-anchor=\"{$anchor}\" dominant-baseline=\"middle\" "
                  . "fill=\"#555\">" . htmlspecialchars($htext, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</text>\n";
        }
    }

    // ── Layer number in the gap (col 6.5 centre, row 2 height) ──────────────
    $gap_cx = ML + col_x(6) + KW + KW / 2;  // centre of the gap between the splits
    $gap_cy = $layer_top + row_y(0);          // centre of number sits on top edge of row 0
    $gx     = number_format($gap_cx, 3);
    $gy     = number_format($gap_cy, 3);

    $svg .= "<text x=\"{$gx}\" y=\"{$gy}\" "
          . "font-size=\"6\" font-weight=\"bold\" "
          . "font-family=\"DejaVu Sans,Liberation Sans,sans-serif\" "
          . "text-anchor=\"middle\" dominant-baseline=\"middle\" "
          . "fill=\"#888\">{$layer_idx}</text>\n";

    // Binding indices 6,7 = top inner keys; 20,21 = bottom inner keys.
    // Both pairs share the same two display columns (6 and 7).
    // Render them centred as a 2-key stack across the 3 main rows.
    $inner_top    = [6, 7];
    $inner_bottom = [20, 21];

    foreach ($KEY_POS as $idx => [$dcol, $drow]) {

        $kx = ML + col_x($dcol);
        if (in_array($idx, $inner_top)) {
            $ky = $layer_top + inner_key_y(0);
        } elseif (in_array($idx, $inner_bottom)) {
            $ky = $layer_top + inner_key_y(1);
        } else {
            $ky = $layer_top + row_y($drow);
        }

        [$behavior, $params] = $bindings[$idx] ?? ['none', []];

        $label = binding_label($behavior, $params);
        $empty = ($label === '');

        $svg .= key_svg($kx, $ky, $label, $empty);
    }

    // ── Combo keys: only on layer 0 (base layer), drawn outside the grid ────
    if ($layer_idx === 0) {
        foreach ($COMBOS as $combo) {
            [$p0, $p1] = $combo['positions'];
            if (!isset($KEY_POS[$p0], $KEY_POS[$p1])) continue;
            [$cx, $cy] = combo_center($p0, $p1, $layer_top);
            $svg .= combo_svg($cx, $cy, $combo['label']);
        }
    }

    $svg .= "\n";
}

$svg .= "</svg>\n";

echo $svg;
