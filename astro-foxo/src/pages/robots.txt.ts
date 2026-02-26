import type { APIRoute } from 'astro';
import { proxySitemapRequest } from '../lib/sitemap-proxy';

export const GET: APIRoute = async () => {
  return proxySitemapRequest('/robots.txt', 'text/plain; charset=utf-8');
};
