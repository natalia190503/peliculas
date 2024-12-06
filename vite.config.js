import {defineConfig} from "vite"
export default defineConfig({
    base:'/peliculasDB/dist',
    build: {
      // genera el archivo .vite/manifest.json en outDir
      manifest: true,
      rollupOptions: {
        // sobreescribe la entrada por defecto .html
        input: [
            'resources/css/app.css',
            'resources/js/app.js'
        ],
      },
    },
  })