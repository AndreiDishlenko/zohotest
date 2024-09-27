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
                chunkFileNames: `js/plugins.[hash].js`,
                // assetFileNames: 'css/style.[hash].[ext]',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) 
                        return 'css/styles.[hash].[ext]';    // Все CSS-файлы будут сохранены в папку 'styles'
                        // return 'styles/[name]-[hash][extname]'; // Все CSS-файлы будут сохранены в папку 'styles'
                    
                    return 'assets/[name]-[hash][extname]'; // Другие ассеты (изображения и т.д.) в папку 'assets'
                },
            },
        },
        cssCodeSplit: false,
        cssMinify: true,
    },
    server: {
        watch: {
            // Настройки отслеживания файлов
            usePolling: true, // Для стабильного отслеживания изменений (особенно в виртуальных машинах или сетевых дисках)
        },
    },
});
