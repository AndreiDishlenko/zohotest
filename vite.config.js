import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/about.js', 
                'resources/js/home.js'
            ],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        outDir: 'public/dist', // Задаем путь к директории сборки
        // assetsDir: 'public/storage', // Задаем путь к директории сборки
        rollupOptions: {
            output: {
                // cache: false,
                entryFileNames: `js/main.[hash].js`, // Настройка имен JS-файлов
                chunkFileNames: `js/data.[hash].js`,
                // assetFileNames: '[name].[hash].[ext]',
            },
        },
    },
    server: {
        watch: {
            // Настройки отслеживания файлов
            usePolling: true, // Для стабильного отслеживания изменений (особенно в виртуальных машинах или сетевых дисках)
        },
    },
});
