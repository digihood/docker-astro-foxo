const WP_API_URL = (import.meta.env.WP_API_URL || 'http://localhost:8080').replace(/\/$/, '');
const PUBLIC_SITE_URL = (import.meta.env.PUBLIC_SITE_URL || 'http://localhost:4321').replace(/\/$/, '');

export async function proxySitemapRequest(pathname: string, contentType: string): Promise<Response> {
  try {
    const res = await fetch(`${WP_API_URL}${pathname}`, { signal: AbortSignal.timeout(5000) });

    if (!res.ok) {
      return new Response('Not found', { status: 404 });
    }

    let content = await res.text();
    content = content.replaceAll(WP_API_URL, PUBLIC_SITE_URL);

    // Rank Math uses protocol-relative URLs for XSL (//host/main-sitemap.xsl)
    const wpHost = WP_API_URL.replace(/^https?:/, '');
    const feHost = PUBLIC_SITE_URL.replace(/^https?:/, '');
    content = content.replaceAll(wpHost, feHost);

    return new Response(content, {
      status: 200,
      headers: {
        'Content-Type': contentType,
        'Cache-Control': 'public, max-age=3600',
      },
    });
  } catch {
    return new Response('Service unavailable', { status: 502 });
  }
}
