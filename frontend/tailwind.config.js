import forms from "@tailwindcss/forms";
import containerQueries from "@tailwindcss/container-queries";

/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        primary: "#a20513",
        "primary-fixed": "#ffdad6",
        "surface-bright": "#fcf9f8",
        "surface-container-lowest": "#ffffff",
        "surface-container-high": "#eae7e7",
        error: "#ba1a1a",
        "primary-container": "#c62828",
        "error-container": "#ffdad6",
        "tertiary-fixed": "#cee5ff",
        background: "#fcf9f8",
        "on-tertiary-fixed": "#001d32",
        "on-tertiary-container": "#d7eaff",
        "on-primary": "#ffffff",
        tertiary: "#005483",
        "on-background": "#1b1c1c",
        "inverse-primary": "#ffb4ac",
        "on-secondary-fixed": "#002109",
        "on-error-container": "#93000a",
        "on-secondary-container": "#007232",
        "tertiary-fixed-dim": "#96ccff",
        "on-secondary": "#ffffff",
        "secondary-fixed": "#66ff8e",
        outline: "#8f706c",
        "on-primary-fixed": "#410003",
        "surface-dim": "#dcd9d9",
        "on-tertiary": "#ffffff",
        "inverse-surface": "#303030",
        surface: "#fcf9f8",
        "surface-container-low": "#f6f3f2",
        secondary: "#006d2f",
        "on-secondary-fixed-variant": "#005322",
        "on-primary-container": "#ffe0dd",
        "surface-container": "#f0eded",
        "inverse-on-surface": "#f3f0ef",
        "surface-variant": "#e5e2e1",
        "surface-container-highest": "#e5e2e1",
        "secondary-container": "#5dfd8a",
        "surface-tint": "#b91d20",
        "on-error": "#ffffff",
        "tertiary-container": "#006da9",
        "outline-variant": "#e4beba",
        "secondary-fixed-dim": "#3de273",
        "primary-fixed-dim": "#ffb4ac",
        "on-surface-variant": "#5b403d",
        "on-surface": "#1b1c1c",
        "on-tertiary-fixed-variant": "#004a75",
        "on-primary-fixed-variant": "#93000e",
      },
      fontFamily: {
        headline: ["Plus Jakarta Sans", "sans-serif"],
        body: ["Inter", "sans-serif"],
        label: ["Inter", "sans-serif"],
      },
      borderRadius: {
        DEFAULT: "0.25rem",
        lg: "0.5rem",
        xl: "0.75rem",
        full: "9999px",
      },
      keyframes: {
        shake: {
          '0%, 100%': { transform: 'translateX(0)' },
          '10%, 30%, 50%, 70%, 90%': { transform: 'translateX(-6px)' },
          '20%, 40%, 60%, 80%': { transform: 'translateX(6px)' },
        },
        'shake-minor': {
          '0%, 100%': { transform: 'translateX(0)' },
          '25%': { transform: 'translateX(-2px)' },
          '75%': { transform: 'translateX(2px)' },
        },
        'fade-in-up': {
          '0%': { opacity: '0', transform: 'translateY(10px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        }
      },
      animation: {
        shake: 'shake 0.6s cubic-bezier(.36,.07,.19,.97) both',
        'shake-minor': 'shake-minor 0.35s cubic-bezier(.36,.07,.19,.97) both',
        'in': 'fade-in-up 0.4s ease-out both',
      }
    },
  },
  plugins: [forms, containerQueries],
};
