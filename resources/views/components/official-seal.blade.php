@props(['size' => 'w-24 h-24', 'class' => ''])

<div class="relative inline-flex items-center justify-center select-none {{ $size }} {{ $class }}">
    <svg class="w-full h-full" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <!-- Top Text Arc (clockwise) -->
            <path id="classic-seal-top" d="M 32,120 A 88,88 0 0,1 208,120" />
            <!-- Bottom Text Arc (clockwise from left to right for upright text) -->
            <path id="classic-seal-bottom" d="M 208,120 A 88,88 0 0,1 32,120" />
        </defs>

        <!-- Outer Double Border (Classic Legal Notary Style) -->
        <circle cx="120" cy="120" r="114" stroke="#047857" stroke-width="4" fill="none" />
        <circle cx="120" cy="120" r="108" stroke="#047857" stroke-width="1.5" stroke-dasharray="3 2" fill="none" />
        <circle cx="120" cy="120" r="103" stroke="#047857" stroke-width="1.5" fill="none" />

        <!-- Inner Core Ring -->
        <circle cx="120" cy="120" r="66" stroke="#047857" stroke-width="2" fill="#047857" fill-opacity="0.04" />
        <circle cx="120" cy="120" r="61" stroke="#047857" stroke-width="1" stroke-dasharray="2 2" fill="none" />

        <!-- Upper Arc Text (Serif Classic Stamp Font) -->
        <text font-family="Georgia, serif" font-size="12" font-weight="bold" fill="#047857" letter-spacing="3.5">
            <textPath href="#classic-seal-top" startOffset="50%" text-anchor="middle">
                PROJECT AFTERLIFE
            </textPath>
        </text>

        <!-- Lower Arc Text -->
        <text font-family="Georgia, serif" font-size="9.5" font-weight="bold" fill="#047857" letter-spacing="2.5">
            <textPath href="#classic-seal-bottom" startOffset="50%" text-anchor="middle">
                PROVENANCE &bull; REGISTRY
            </textPath>
        </text>

        <!-- Left and Right Flanking Stars -->
        <g fill="#047857">
            <!-- Left Star -->
            <polygon points="26,120 28,116 32,116 29,119 30,123 26,121 22,123 23,119 20,116 24,116" />
            <!-- Right Star -->
            <polygon points="214,120 216,116 220,116 217,119 218,123 214,121 210,123 211,119 208,116 212,116" />
        </g>

        <!-- Centerpiece: Classic Notary Shield & Official Seal Text -->
        <g text-anchor="middle" font-family="Georgia, serif" fill="#047857">
            <!-- Top 3 Stars -->
            <g fill="#047857" transform="scale(0.8) translate(30, 20)">
                <polygon points="105,92 106,89 109,89 107,91 108,94 105,92 102,94 103,91 101,89 104,89" />
                <polygon points="120,88 121,85 124,85 122,87 123,90 120,88 117,90 118,87 116,85 119,85" />
                <polygon points="135,92 136,89 139,89 137,91 138,94 135,92 132,94 133,91 131,89 134,89" />
            </g>

            <!-- Center Text -->
            <text x="120" y="116" font-size="14" font-weight="bold" letter-spacing="2">OFFICIAL</text>
            <text x="120" y="132" font-size="14" font-weight="bold" letter-spacing="2">SEAL</text>

            <!-- Center Divider Line -->
            <line x1="88" y1="139" x2="152" y2="139" stroke="#047857" stroke-width="1.2" />

            <!-- Bottom Year -->
            <text x="120" y="152" font-size="9" font-weight="bold" letter-spacing="1.5" font-family="monospace">EST. 2026</text>
        </g>
    </svg>
</div>
