import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },

    build: {
        // Enhanced minification for professional performance
        minify: 'terser',
        cssMinify: true,

        // Rollup options for better optimization
        rollupOptions: {
            output: {
                // Manual chunk splitting simplified
                manualChunks: (id) => {
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                    if (id.includes('modules/')) {
                        return 'modules';
                    }
                },
                // Asset naming for professional deployment
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/\\.(png|jpe?g|svg|gif|tiff|bmp|ico)$/i.test(assetInfo.name)) {
                        return `assets/images/[name]-[hash][extname]`;
                    }
                    if (/\\.(woff2?|eot|ttf|otf)$/i.test(assetInfo.name)) {
                        return `assets/fonts/[name]-[hash][extname]`;
                    }
                    return `assets/[ext]/[name]-[hash][extname]`;
                },
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
            },
        },

        // Performance optimizations
        target: 'es2018',
        cssCodeSplit: true,
        sourcemap: false,

        // Terser options for advanced minification
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
            format: {
                comments: false,
            },
        },

        // Chunk size warnings
        chunkSizeWarningLimit: 1000,

        // Asset inline threshold
        assetsInlineLimit: 4096,

        // Report compressed size
        reportCompressedSize: true,
    },

    // Development server configuration
    server: {
        host: 'localhost',
        port: 5173,
        strictPort: true,
        hmr: {
            overlay: true,
        },
        cors: true,
    },

    // CSS configuration
    css: {
        // CSS modules configuration (simplified)
        modules: {
            localsConvention: 'camelCaseOnly',
        },
        // PostCSS is handled by Laravel Vite plugin
        devSourcemap: true,
    },

    // Production optimizations
    define: {
        __VUE_OPTIONS_API__: false,
        __VUE_PROD_DEVTOOLS__: false,
    },

    // Dependency optimization
    optimizeDeps: {
        include: [],
        exclude: [],
    },

    // Preview server (for production testing)
    preview: {
        port: 4173,
        strictPort: true,
        host: 'localhost',
        cors: true,
    },

    // Environment configuration
    envPrefix: 'VITE_',

    // Professional logging
    logLevel: 'info',

    // Clear screen on dev server start
    clearScreen: true,
});

/**
 * Professional Digital Transformation Consultant Portfolio
 * Optimized Vite Configuration
 *
 * Features:
 * - Terser minification for maximum compression
 * - Manual chunk splitting for optimal caching
 * - Asset optimization for professional loading speeds
 * - Professional font and image optimization
 * - Hot module replacement for development efficiency
 */