import type { APIRoute } from 'astro';
import { proxySitemapRequest } from '../lib/sitemap-proxy';

export const GET: APIRoute = async () => {
  return proxySitemapRequest('/sitemap_index.xml', 'application/xml; charset=utf-8');
};
