/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                theme: ["var(--theme-font-family)"],
                sans: ["var(--theme-font-family)"],
                poppins: ["var(--theme-font-family)"],
                monasans: ["var(--theme-font-family)"],
            },
            boxShadow: {
                "box-shadow-36":
                    "rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;",
                "box-shadow-11":
                    "rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px",
                "box-shadow-12":
                    "rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px",
                "box-shadow-8":
                    "rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px",
                "box-shadow-7":
                    "rgba(0, 0, 0, 0.16) 0px 1px 4px, rgb(51, 51, 51) 0px 0px 0px 3px",
                "box-shadow-38":
                    "rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px",
                "box-shadow-39":
                    "rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px",
                "box-shadow-46": "rgba(0, 0, 0, 0.1) 0px 10px 50px",
                "box-shadow-36": "rgba(0, 0, 0, 0.05) 0px 0px 0px 1px",
                "box-shadow-4": "rgba(0, 0, 0, 0.16) 0px 1px 4px",
            },
            backgroundColor: {
                "gradient-1":
                    "linear-gradient(to bottom left, #B381F4, #5038ED)",
            },
            screens: {
                "large-screen": { max: "1920px" },
                "medium-screen" : {max: "1200px"},
                "small-desktop": { max: "992px" },
                "mobile-max": { max: "480px" },
                tablet: { max: "768px" },
            },
            backgroundImage: {
                "image-one": "url('/images/pexels-alphatradezone-5833304.jpg')",
            },
            keyframes: {
                float: {
                    "0%, 100%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-10px)" },
                },
            },
            animation: {
                float: "float 3s ease-in-out infinite",
            },
        },
    },
    plugins: [],
};
