const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

function withOpacityValue(variable) {
    return ({ opacityValue }) => {
        if (opacityValue === undefined) {
            return `rgb(var(${variable}))`;
        }
        return `rgb(var(${variable}) / ${opacityValue})`;
    };
}

module.exports = {
    content: [
        "./node_modules/flowbite/**/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                sky: colors.sky,
                teal: colors.teal,
                meron: "#F14F3B",
                wala: "#2C73C6",
                win: "#3AB047",
                lose: "#808080",
                cancel: "#FFA500",
                draw: "#22c55e",
                // fill: "#DDDADA",
                // border: "#ECA84B",
                border: withOpacityValue("--color-status-border"),
                ods: "#E9D991",
                bg: withOpacityValue("--color-bg"),
                "navbar-from": withOpacityValue("--color-navbar-from"),
                "navbar-to": withOpacityValue("--color-navbar-to"),
                "navbar-item": withOpacityValue("--color-navbar-item"),
                "navbar-item-text": withOpacityValue(
                    "--color-navbar-item-text"
                ),
                "status-head": withOpacityValue("--color-status-head"),
                "status-body": withOpacityValue("--color-status-body"),
                "bet-body": withOpacityValue("--color-bet-body"),
                "bet-text": withOpacityValue("--color-bet-text"),
                "table-body": withOpacityValue("--color-table-body"),
                td: withOpacityValue("--color-td"),
                fill: withOpacityValue("--color-fill"),
                "match-result-text": withOpacityValue(
                    "--color-match-result-text"
                ),
                "chanel-1": withOpacityValue("--color-chanel-1"),
                "chanel-2": withOpacityValue("--color-chanel-2"),
                "chanel-3": withOpacityValue("--color-chanel-3"),
                "chanel-4": withOpacityValue("--color-chanel-4"),
                dropdown: withOpacityValue("--color-dropdown"),
                "dropdown-msg-text": withOpacityValue(
                    "--color-dropdown-msg-text"
                ),
                "dropdown-link-text": withOpacityValue(
                    "--color-dropdown-link-text"
                ),
                "dropdown-link-hover": withOpacityValue(
                    "--color-dropdown-link-hover"
                ),
                label: withOpacityValue("--color-label"),
                tab: withOpacityValue("--color-tab"),
                "login-form": withOpacityValue("--color-login-form"),
                "login-button": withOpacityValue("--color-login-button"),
                dragon: {
                    red: "#A51B1B",
                    blue: "#2859FC",
                    teal: "#146454",
                    primary: "#F1C428",
                    clear: "#1ABC9C",
                    green: "#12BA23",
                    dragon: "#4B0507",
                    tiger: "#05234B",
                    tie: "#2A5E21",
                    bg: "#08332A",
                    nav: "#146454",
                    balance: "#251201",
                    fill: "#2B2B2B",
                    "balance-border": "#0C0500",
                    "balance-button": "#F2B425",
                    "balance-button-border": "#CC801B",
                },
            },
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
                rodfat: ["Rodfat", "sans-serif"],
            },
            backgroundImage: {
                "home-bg": "url('../images/bg.png')",
                "new-year": "url('../images/bg-new-year.png')",
                "new-year-mobile": "url('../images/bg-new-year-mobile.png')",
                "new-year-mobile-ip6": "url('../images/bg-new-year-i6.png')",
                "new-year-mobile-ip6p": "url('../images/bg-new-year-i6p.png')",
            },
            height: {
                110: "27.5rem",
            },
            width: {
                110: "27.5rem",
            },
            rotate: {
                225: "225deg",
                270: "270deg",
            },
            screens: {
                sm: "640px",
                md: "768px",
                lg: "1024px",
                xl: "1280px",
                "2xl": "1366px",
                "3xl": "1440px",
                "4xl": "1680px",
                ip6: { raw: "(max-width: 375px) and (max-height: 667px)" },
                ip6p: { raw: "(max-width: 414px) and (max-height: 736px)" },
                web: { raw: "(min-width: 1024px) and (min-height: 768px)" },
            },
            container: {
                center: true,
            },
            animation: {
                marquee: "marquee 25s linear infinite",
                marquee2: "marquee2 25s linear infinite",
            },
            keyframes: {
                marquee: {
                    "0%": { transform: "translateX(100%)" },
                    "100%": { transform: "translateX(-100%)" },
                },
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("@tailwindcss/aspect-ratio"),
        require("tailwind-scrollbar-hide"),
        // require("@tailwindcss/line-clamp"),
        require("flowbite/plugin"),
        require("prettier-plugin-tailwindcss"),
    ],
};
