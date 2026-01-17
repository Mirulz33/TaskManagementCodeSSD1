import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import colors from 'tailwindcss/colors'

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                amber: colors.amber,
                orange: colors.orange,
            },
        },
    },

    plugins: [forms],

    safelist: [
        // Green (Promote / Create)
        'bg-green-600', 'hover:bg-green-700',

        // Amber (Demote)
        'bg-amber-500', 'hover:bg-amber-600',

        // Red (Block / Delete)
        'bg-red-600', 'hover:bg-red-700',

        // Disabled states
        'bg-gray-300',
        'text-white',
        'cursor-not-allowed',
        'opacity-50',
    ],
}
