import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        screens: {
            'xs': '475px',
            'sm': '640px',
            'md': '768px', // Critical tablet breakpoint
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
            // Professional consultation-focused breakpoints
            'tablet': '768px',
            'desktop': '1024px',
            'wide': '1440px',
        },
        extend: {
            fontFamily: {
                sans: ['Inter', 'Space Grotesk', 'system-ui', 'sans-serif'],
                display: ['Poppins', 'system-ui', 'sans-serif'],
                mono: ['JetBrains Mono', 'Fira Code', 'SF Mono', 'monospace'],
            },
            colors: {
                // Enhanced Digital Transformation Consultant Brand Colors
                'electric-purple': '#8b5cf6',
                'cyber-pink': '#ec4899',
                'neon-green': '#10b981',
                'sunset-orange': '#f97316',
                'aurora-blue': '#06b6d4',
                'neon-yellow': '#fbbf24',
                'dark-surface': '#0f0f23',
                'card-surface': '#1a1a2e',
                'border-subtle': '#374151',

                // Professional Consulting Brand Palette
                'consulting': {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    500: '#06b6d4',
                    600: '#0891b2',
                    700: '#0e7490',
                    900: '#164e63',
                },
                'manufacturing': {
                    50: '#fef3c7',
                    100: '#fde68a',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    900: '#78350f',
                },
            },
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
                '5xl': ['3rem', { lineHeight: '1.1' }],
                '6xl': ['3.75rem', { lineHeight: '1.1' }],
                '7xl': ['4.5rem', { lineHeight: '1.1' }],
                '8xl': ['6rem', { lineHeight: '1.1' }],
                '9xl': ['8rem', { lineHeight: '1.1' }],
                // Professional responsive typography
                'display': ['clamp(2.5rem, 8vw, 5rem)', { lineHeight: '1.1' }],
                'hero': ['clamp(1.875rem, 5vw, 3rem)', { lineHeight: '1.2' }],
                'subtitle': ['clamp(1.125rem, 3vw, 1.5rem)', { lineHeight: '1.4' }],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
                // Professional spacing scale
                '15': '3.75rem',
                '72': '18rem',
                '84': '21rem',
                '96': '24rem',
            },
            maxWidth: {
                '8xl': '88rem',
                '9xl': '96rem',
                'screen-2xl': '1536px',
            },
            animation: {
                'gradient-shift': 'gradient-shift 4s ease infinite',
                'float-gentle': 'float-gentle 8s ease-in-out infinite',
                'pulse-glow': 'pulse-glow 3s ease-in-out infinite',
                'bounce-gentle': 'bounce-gentle 2s ease-in-out infinite',
                'rotate-slow': 'rotate-slow 20s linear infinite',
                'fade-in': 'fadeIn 0.6s ease forwards',
                'slide-up': 'slideUp 0.6s ease forwards',
                'slide-in-left': 'slideInLeft 0.8s cubic-bezier(0.4, 0, 0.2, 1)',
                'slide-in-right': 'slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1)',
            },
            keyframes: {
                'gradient-shift': {
                    '0%': { 'background-position': '0% 50%' },
                    '50%': { 'background-position': '100% 50%' },
                    '100%': { 'background-position': '0% 50%' },
                },
                'float-gentle': {
                    '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                    '33%': { transform: 'translateY(-8px) rotate(1deg)' },
                    '66%': { transform: 'translateY(4px) rotate(-1deg)' },
                },
                'pulse-glow': {
                    '0%, 100%': {
                        transform: 'scale(1)',
                        'box-shadow': '0 0 20px rgba(139, 92, 246, 0.3)',
                    },
                    '50%': {
                        transform: 'scale(1.05)',
                        'box-shadow': '0 0 30px rgba(139, 92, 246, 0.6)',
                    },
                },
                'bounce-gentle': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                },
                'rotate-slow': {
                    'from': { transform: 'rotate(0deg)' },
                    'to': { transform: 'rotate(360deg)' },
                },
                'fadeIn': {
                    'from': {
                        opacity: '0',
                        transform: 'translateY(20px)',
                    },
                    'to': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                'slideUp': {
                    'from': {
                        opacity: '0',
                        transform: 'translateY(30px)',
                    },
                    'to': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                'slideInLeft': {
                    '0%': { transform: 'translateX(-100px)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                'slideInRight': {
                    '0%': { transform: 'translateX(100px)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
            },
            backgroundImage: {
                'gradient-hero': 'linear-gradient(135deg, #8b5cf6 0%, #ec4899 50%, #06b6d4 100%)',
                'gradient-cards': 'linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(236, 72, 153, 0.05) 100%)',
                'gradient-accent': 'linear-gradient(135deg, #10b981 0%, #06b6d4 100%)',
                'gradient-cyber': 'linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%)',
            },
            boxShadow: {
                'glow-purple': '0 0 20px rgba(139, 92, 246, 0.4), 0 0 40px rgba(139, 92, 246, 0.2)',
                'glow-pink': '0 0 20px rgba(236, 72, 153, 0.4), 0 0 40px rgba(236, 72, 153, 0.2)',
                'glow-blue': '0 0 20px rgba(6, 182, 212, 0.4), 0 0 40px rgba(6, 182, 212, 0.2)',
                'glow-neon': '0 0 20px rgba(251, 191, 36, 0.4), 0 0 40px rgba(251, 191, 36, 0.2)',
            },
            backdropBlur: {
                'xs': '2px',
            },
        },
    },

    plugins: [forms, typography],

    // Professional optimization settings
    corePlugins: {
        preflight: true,
        container: true,
    },

    // Content-based purge safelist for dynamic classes
    safelist: [
        {
            pattern: /(bg|text|border)-(electric-purple|cyber-pink|neon-green|consulting|manufacturing)-(50|100|500|600|700|900)/,
        },
        {
            pattern: /(animate)-(fade-in|slide-up|float|pulse-glow|gradient)/,
        },
        {
            pattern: /(grid-cols|md:grid-cols|lg:grid-cols)-(1|2|3|4|5|6)/,
        },
        {
            pattern: /(w|h)-(1\/2|1\/3|2\/3|1\/4|3\/4|full)/,
        },
        // Tablet responsive classes (critical 768px breakpoint)
        {
            pattern: /md:(hidden|block|flex|grid|p|m|text|w|h)-.+/,
        },
    ],
};

// Professional Digital Transformation Consultant Configuration
// Optimized for:
// - Manufacturing industry presentations
// - Gen Z professional appeal
// - Cross-device consulting credibility
// - High-performance content management
// - Accessible professional design
