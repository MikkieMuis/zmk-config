<?php
/**
 * labels.php  –  Customise what is printed inside every key cell.
 *
 * The KEY (left side) is the ZMK behaviour + params, no leading &.
 * The VALUE (right side) is what gets printed in the key square.
 * Use "\n" (double quotes) to split text across two lines.
 *
 * Keys with empty string '' show as a grey empty cell.
 * Remove or comment out a line to restore the built-in default.
 *
 * Regenerate after saving:
 *   php generate_layout.php > layout.svg &&
 *   inkscape layout.svg --export-type=pdf -o layout.pdf
 */
return [

    // ── Half-labels: printed above each split side, one per layer ─────────
    // Edit the values to name your layers however you like.
    'label_0_left'  => "Base",          'label_0_right' => "Base",
    'label_1_left'  => "Warpd",         'label_1_right' => "Numeric",
    'label_2_left'  => "Symbol",        'label_2_right' => "Symbol",
    'label_3_left'  => "Sway",          'label_3_right' => "Sway",
    'label_4_left'  => "Tmux",          'label_4_right' => "Mouse",
    'label_5_left'  => "Bluetooth",     'label_5_right' => "RGB",

    // ──────────────────────────────────────────────────────────────────────
    // Layer 0 — QWERTY
    // ──────────────────────────────────────────────────────────────────────

    // Row 0
    'kp TAB'                             => "Tab", // Tab
    'kp Q'                               => "Q", // Q
    'kp W'                               => "W", // W
    'kp E'                               => "E", // E
    'kp R'                               => "R", // R
    'kp T'                               => "T", // T
    'kp C_MUTE'                          => "Mute", // Mute
    'caps_word'                          => "Caps\nWord", // Caps / Word
    'kp Y'                               => "Y", // Y
    'kp U'                               => "U", // U
    'kp I'                               => "I", // I
    'kp O'                               => "O", // O
    'kp P'                               => "P", // P
    'kp BSPC'                            => "⌫", // ⌫

    // Row 1
    'kp DOLLAR'                          => "$", // $
    'a_holdtap LEFT_ALT A'               => "A", // A
    'sl_holdtab LEFT_GUI S'              => "S", // S
    'dk_holdtap LCTRL D'                 => "D", // D
    'fj_holdtap LEFT_SHIFT F'            => "F", // F
    'kp G'                               => "G", // G
    'kp LC(RIGHT_BRACKET)'               => "CTRL\n]", // C-]
    'kp RC(E)'                           => "CTRL\nE", // C-E
    'kp H'                               => "H", // H
    'fj_holdtap RIGHT_SHIFT J'           => "J", // J
    'dk_holdtap RCTRL K'                 => "K", // K
    'sl_holdtab RMETA L'                 => "L", // L
    'a_holdtap RIGHT_ALT SEMI'           => ";", // ;
    'kp DELETE'                          => "Del", // Del

    // Row 2
    'kp STAR'                            => "*", // *
    'lt 4 Z'                             => "Z", // Z
    'lt 3 X'                             => "X", // X
    'lt 2 C'                             => "C", // C
    'lt 1 V'                             => "V", // V
    'kp B'                               => "B", // B
    'kp N'                               => "N", // N
    'lt 1 M'                             => "M", // M
    'lt 2 COMMA'                         => ",", // ,
    'lt 3 DOT'                           => ".", // .
    'lt 4 SLASH'                         => "/", // /
    'kp UNDER'                           => "_", // _

    // Thumbs
    'copilot_hold_tap 0 LC(L)'           => "CTRL\nJ / L", // Copilot/C-L
    'kp SPACE'                           => "␣", // ␣
    'kp ESCAPE'                          => "Esc", // Esc
    'kp RET'                             => "↵", // ↵
    'kp SPACE'                           => "␣", // ␣
    'kp LC(Y)'                           => "CTRL\nY", // C-Y

    // ──────────────────────────────────────────────────────────────────────
    // Layer 1 — WARPD
    // ──────────────────────────────────────────────────────────────────────

    // Row 0
    'kp LC(LG(I))'                       => "Inbox", // C-Sup-I
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'kp LG(N)'                           => "Normal", // Sup-N
    'kp LG(H)'                           => "HINT", // Sup-H
    'kp LG(G)'                           => "GRID", // Sup-G
    'none'                               => "", // (empty)
    'kp RS(RG(MINUS))'                   => "Add\nSP", // Add SP
    'none'                               => "", // (empty)
    'kp NUMBER_1'                        => "1", // 1
    'kp NUMBER_2'                        => "2", // 2
    'kp NUMBER_3'                        => "3", // 3
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)

    // Row 1
    'kp LC(LG(T))'                       => "Tasks", // C-Sup-T
    'none'                               => "", // (empty)
    'kp LG(LC(S))'                       => "GRID\nSTART\nSELECT", // Sup-C-S
    'kp LG(LC(D))'                       => "GRID\nDONE\nSELECT", // Sup-C-D
    'kp LG(LC(L))'                       => "GRID\nCOPY\nLINE", // Sup-C-L
    'kp LG(LC(C))'                       => "GRID\nCOPY\nWORD", // Sup-C-C
    'none'                               => "", // (empty)
    'kp LG(MINUS)'                       => "SP", // Sup--
    'none'                               => "", // (empty)
    'kp NUMBER_4'                        => "4", // 4
    'kp N5'                              => "5", // 5
    'kp NUMBER_6'                        => "6", // 6
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)

    // Row 2
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'kp LG(LC(V))'                       => "Paste", // Sup-C-V
    'kp LG(LS(Y))'                       => "HINT\nCOPY\nLINE", // Sup-S-Y
    'kp LG(LS(W))'                       => "HINT\nCOPY\nWORD", // Sup-S-W
    'none'                               => "", // (empty)
    'kp N7'                              => "7", // 7
    'kp N8'                              => "8", // 8
    'kp N9'                              => "9", // 9
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)

    // Thumbs
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'kp N0'                              => "0", // 0
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)

    // ──────────────────────────────────────────────────────────────────────
    // Layer 2 — SYMBOL
    // ──────────────────────────────────────────────────────────────────────

    // Row 0
    'kp F12'                             => "F12", // F12
    'kp EXCL'                            => "!", // !
    'kp AT'                              => "@", // @
    'kp HASH'                            => "#", // #
    'euro'                               => "€", // €
    'clear_terminal'                     => "Clear\nTerm", // Clear / Term
    'Pound'                              => "£", // £
    'equalequal'                         => "==", // ==
    'dash_left'                          => "→", // →
    'and'                                => "&&", // &&
    'reset_terminal'                     => "Reset\nTerm", // Reset / Term
    'notequal'                           => "!=", // !=
    'equals_left'                        => "⇒", // ⇒
    'or'                                 => "||", // ||
    'Plusminus'                          => "±", // ±
    'kp EQUAL'                           => "=", // =
    'kp PIPE'                            => "|", // |
    'kp LEFT_BRACE'                      => "{", // {
    'kp RIGHT_BRACE'                     => "}", // }
    'none'                               => "", // (empty)

    // Thumbs
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)

    // ──────────────────────────────────────────────────────────────────────
    // Layer 3 — Div + Num
    // ──────────────────────────────────────────────────────────────────────

    // Row 0
    'kp LS(LG(P))'                       => "SCRN\nREC", // S-Sup-P
    'kp LG(F1)'                          => "GF1", // Sup-F1
    'kp LG(F2)'                          => "GF2", // Sup-F2
    'kp LG(F3)'                          => "GF3", // Sup-F3
    'kp LG(F4)'                          => "GF4", // Sup-F4
    'kp LG(F5)'                          => "GF5", // Sup-F5
    'kp K_VOLUME_UP'                     => "Vol+", // Vol+
    'kp C_BRI_UP'                        => "Bri+", // Bri+
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'kp LS(LG(Q))'                       => "CLOSE\nSCRN", // S-Sup-Q

    // Row 1
    'kp RG(RS(C))'                       => "Reload", // Sup-S-C
    'none'                               => "", // (empty)
    'kp LG(LEFT_ARROW)'                  => "G\n←", // Sup-←
    'kp LG(TAB)'                         => "NEXT\nWS", // Sup-Tab
    'kp LG(PRINTSCREEN)'                 => "PRNT\nSCRN", // Sup-PrtSc
    'kp RG(RIGHT_ARROW)'                 => "G\n→", // Sup-→
    'kp K_VOLUME_DOWN'                   => "Vol-", // Vol-
    'kp C_BRI_DEC'                       => "Bri-", // Bri-
    'kp LEFT_ARROW'                      => "←", // ←
    'kp DOWN_ARROW'                      => "↓", // ↓
    'kp UP_ARROW'                        => "↑", // ↑
    'kp RIGHT_ARROW'                     => "→", // →
    'none'                               => "", // (empty)
    'kp LS(LG(F))'                       => "FULL\nSCRN", // S-Sup-F

    // Row 2
    'kp RS(RG(E))'                       => "Exit", // S-Sup-E
    'kp F1'                              => "F1", // F1
    'kp F2'                              => "F2", // F2
    'kp F3'                              => "F3", // F3
    'kp F4'                              => "F4", // F4
    'kp F5'                              => "F5", // F5
    'kp F6'                              => "F6", // F6
    'kp F7'                              => "F7", // F7
    'kp F8'                              => "F8", // F8
    'kp F9'                              => "F9", // F9
    'kp F10'                             => "F10", // F10
    'kp LS(LG(U))'                       => "Unload", // S-Sup-U

    // Thumbs
    'trans'                              => "", // (empty)
    'trans'                              => "", // (empty)
    'trans'                              => "", // (empty)
    'trans'                              => "", // (empty)
    'trans'                              => "", // (empty)
    'trans'                              => "", // (empty)

    // ──────────────────────────────────────────────────────────────────────
    // Layer 4 — TMUX_MOUSE
    // ──────────────────────────────────────────────────────────────────────

    // Row 0
    'tmux_create'                        => "tmux\nnew", // tmux new
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'tmux_split'                         => "tmux\nsplit", // tmux split
    'tmux_next'                          => "tmux\nnext", // tmux next
    'kp LG(LS(T))'                       => "Sup-S-T", // Sup-S-T
    'none'                               => "", // (empty)
    'kp PAGE_DOWN'                       => "PgDn", // PgDn
    'kp PG_UP'                           => "PgUp", // PgUp
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'kp LS(LG(K))'                       => "S-Sup-K", // S-Sup-K

    // Row 1
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'tmux_left'                          => "tmux\n←", // tmux ←
    'tmux_up'                            => "tmux\n↑", // tmux ↑
    'tmux_down'                          => "tmux\n↓", // tmux ↓
    'tmux_right'                         => "tmux\n→", // tmux →
    'tmux_prev'                          => "tmux\nprev", // tmux prev
    'kp C_PAUSE'                         => "Pause", // Pause
    'mmv MOVE_LEFT'                      => "←", // ← mse
    'mmv MOVE_DOWN'                      => "↓", // ↓ mse
    'mmv MOVE_UP'                        => "↑", // ↑ mse
    'mmv MOVE_RIGHT'                     => "→", // → mse
    'none'                               => "", // (empty)
    'kp LS(LG(L))'                       => "S-Sup-L", // S-Sup-L

    // Row 2
    'none'                               => "", // (empty)
    'tmux_zoom'                          => "tmux\nzoom", // tmux zoom
    'none'                               => "", // (empty)
    'tmux_close'                         => "tmux\nclose", // tmux close
    'tmux_vsplit'                        => "tmux\nvsplit", // tmux vsplit
    'none'                               => "", // (empty)
    'msc SCRL_LEFT'                      => "← scrl", // ← scrl
    'msc SCRL_DOWN'                      => "↓ scrl", // ↓ scrl
    'msc SCRL_UP'                        => "↑ scrl", // ↑ scrl
    'msc SCRL_RIGHT'                     => "→ scrl", // → scrl
    'none'                               => "", // (empty)
    'kp LS(LG(J))'                       => "S-Sup-J", // S-Sup-J

    // Thumbs
    'none'                               => "", // (empty)
    'tmux_pageup'                        => "tmux\npgup", // tmux pgup
    'tmux_copy_mode'                     => "tmux\ncopy", // tmux copy
    'mkp LCLK'                           => "LClk", // LClk
    'mkp MCLK'                           => "MClk", // MClk
    'mkp RCLK'                           => "RClk", // RClk

    // ──────────────────────────────────────────────────────────────────────
    // Layer 5 — BT + RGB
    // ──────────────────────────────────────────────────────────────────────

    // Row 0
    'sys_reset'                          => "Reset", // Reset
    'bt BT_CLR'                          => "BT\nCLR", // BT clr
    'bt BT_CLR_ALL'                      => "BT\nCLR\nALL", // BT clr all
    'bootloader'                         => "Boot", // Boot
    'studio_unlock'                      => "Studio\nUnlock", // Studio
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'rgb_ug RGB_ON'                      => "ON", // RGB on
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)

    // Row 1
    'none'                               => "", // (empty)
    'bt BT_SEL 0'                        => "BT 0", // BT 0
    'bt BT_SEL 1'                        => "BT 1", // BT 1
    'bt BT_SEL 2'                        => "BT 2", // BT 2
    'bt BT_SEL 3'                        => "BT 3", // BT 3
    'bt BT_SEL 4'                        => "BT 4", // BT 4
    'none'                               => "", // (empty)
    'rgb_ug RGB_OFF'                     => "OFF", // RGB off
    'rgb_ug RGB_HUI'                     => "Hue\n+", // Hue+
    'rgb_ug RGB_SAI'                     => "Sat\n+", // Sat+
    'rgb_ug RGB_BRI'                     => "Bri\n+", // Bri+
    'rgb_ug RGB_SPI'                     => "Speed\n+", // Spd+
    'rgb_ug RGB_EFF'                     => "Eff\n+", // Eff+
    'none'                               => "", // (empty)

    // Row 2
    'none'                               => "", // (empty)
    'bt BT_DISC 0'                       => "BT 0\nOFF", // BT disc 0
    'bt BT_DISC 1'                       => "BT 1\nOFF", // BT disc 1
    'bt BT_DISC 2'                       => "BT 2\nOFF", // BT disc 2
    'bt BT_DISC 3'                       => "BT 3\nOFF", // BT disc 3
    'bt BT_DISC 4'                       => "BT 4\nOFF", // BT disc 4
    'rgb_ug RGB_HUD'                     => "Hue\n-", // Hue-
    'rgb_ug RGB_SAD'                     => "Sat\n-", // Sat-
    'rgb_ug RGB_BRD'                     => "Bri\n-", // Bri-
    'rgb_ug RGB_SPD'                     => "Speed\n-", // Spd-
    'rgb_ug RGB_EFR'                     => "Eff\n-", // Eff-
    'studio_unlock'                      => "Studio\nUnlock", // Studio

    // Thumbs
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)
    'rgb_ug RGB_TOG'                     => "RGB\ntoggle", // RGB tog
    'none'                               => "", // (empty)
    'none'                               => "", // (empty)

];
