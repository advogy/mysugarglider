/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                cream: {
                    DEFAULT: '#FAF7F2',
                    dark: '#F0EBE1',
                },
                sage: {
                    DEFAULT: '#5C8A6E',
                    light: '#7BAE92',
                    dark: '#3D6B52',
                    50:  '#F0F7F3',
                    100: '#D9EDE3',
                    200: '#B3DBD0',
                },
                honey: {
                    DEFAULT: '#F5C842',
                    light: '#FAD96E',
                    dark: '#D4A820',
                    50: '#FFFBEA',
                },
                sky: {
                    DEFAULT: '#E0F2FE',
                    dark: '#BAE6FD',
                },
                bark: {
                    DEFAULT: '#2D2D2D',
                    light: '#555555',
                    muted: '#888888',
                },
            },
            fontFamily: {
                display: ['"DM Serif Display"', 'Georgia', 'serif'],
                body: ['Nunito', 'system-ui', 'sans-serif'],
                ui: ['Inter', 'system-ui', 'sans-serif'],
                number: ['Outfit', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
                '5xl': '2.5rem',
            },
            boxShadow: {
                'soft':  '0 2px 20px 0 rgba(92, 138, 110, 0.10)',
                'card':  '0 4px 32px 0 rgba(45, 45, 45, 0.08)',
                'hover': '0 8px 40px 0 rgba(92, 138, 110, 0.18)',
                'inner-soft': 'inset 0 2px 8px 0 rgba(0,0,0,0.04)',
            },
            animation: {
                'float':    'float 4s ease-in-out infinite',
                'float-2':  'float 5s ease-in-out infinite 0.8s',
                'float-3':  'float 6s ease-in-out infinite 1.6s',
                'fade-in':  'fadeIn 0.5s ease-out',
                'slide-up': 'slideUp 0.4s ease-out',
                'blob':     'blob 8s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%':      { transform: 'translateY(-12px)' },
                },
                fadeIn: {
                    '0%':   { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideUp: {
                    '0%':   { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                blob: {
                    '0%, 100%': { borderRadius: '60% 40% 30% 70% / 60% 30% 70% 40%' },
                    '25%':      { borderRadius: '30% 60% 70% 40% / 50% 60% 30% 60%' },
                    '50%':      { borderRadius: '50% 60% 30% 60% / 30% 60% 70% 40%' },
                    '75%':      { borderRadius: '40% 60% 70% 30% / 60% 40% 60% 30%' },
                },
            },
            transitionDuration: {
                '400': '400ms',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
