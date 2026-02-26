import { defineConfig } from 'astro/config';
import tailwindcss from '@tailwindcss/vite';
import vercel from '@astrojs/vercel';

// https://astro.build/config
export default defineConfig({
  output: 'server',
  trailingSlash: 'never',
  security: { checkOrigin: false },
  adapter: vercel({
    isr: {
      expiration: 60 * 60 * 24 * 365,
      bypassToken: process.env.REVALIDATE_SECRET,
      exclude: [/^\/api\/.+/],
    },
  }),
  vite: {
    plugins: [tailwindcss()],
  },
});