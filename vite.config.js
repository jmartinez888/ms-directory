import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";
import { config } from "process";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                //
                "resources/js/app.js",
                "resources/js/forms.js",
                "resources/js/role.js",
                "resources/js/sidebar.js",
                // "DataTables/datatables.min.js",
                "resources/css/login_styles.css",
                "resources/css/main_background.css",
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
            "~bootstrap-icons": path.resolve(__dirname,"node_modules/bootstrap-icons"),
            "~admin-lte": path.resolve(__dirname, "node_modules/admin-lte"),
            'yajra-laravel-datatables-oracle': path.resolve(__dirname, 'vendor/yajra/laravel-datatables-oracle/src'),
        },
    },
});
