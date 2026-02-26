import type { APIRoute } from 'astro';
import { proxySitemapRequest } from '../lib/sitemap-proxy';

export const GET: APIRoute = async ({ params }) => {
  return proxySitemapRequest(`/${params.type}-sitemap.xml`, 'application/xml; charset=utf-8');
};
