import type { APIRoute } from 'astro';
import { proxySitemapRequest } from '../lib/sitemap-proxy';

export const GET: APIRoute = async () => {
  return proxySitemapRequest('/main-sitemap.xsl', 'application/xslt+xml; charset=utf-8');
};
