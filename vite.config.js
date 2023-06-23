import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'


export default ({ mode }) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd())};

    return  defineConfig({
        server: {
            host: true,
            hmr: {
                host: process.env.PUSHER_HOST,
                clientPort: process.env.VITE_PORT,
            },
        },
        plugins: [
            vue(),
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                    'resources/js/axios.js',
                    'resources/js/bootstrap.js',
                    'resources/js/friend.js',
                    'resources/js/friend_check.js',
                    'resources/js/home.js',
                    'resources/js/login_page.js',
                    'resources/js/random.js',
                    'resources/js/register.js',
                    'resources/js/websocket.js',
                    'resources/js/callback.js',
                ],
                refresh: true,
            }),
        ],
    });
}

