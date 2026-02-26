import type { APIRoute } from 'astro';

const WP_API_URL = (import.meta.env.WP_API_URL || 'http://localhost:8080').replace(/\/$/, '');

export const POST: APIRoute = async ({ request }) => {
  try {
    const formData = await request.formData();
    const cf7Id = formData.get('_wpcf7');

    if (!cf7Id) {
      return new Response(JSON.stringify({ status: 'error', message: 'Missing form ID' }), {
        status: 400,
        headers: { 'Content-Type': 'application/json' },
      });
    }

    // Forward to CF7 REST API (server-to-server, no CORS)
    const res = await fetch(
      `${WP_API_URL}/wp-json/contact-form-7/v1/contact-forms/${cf7Id}/feedback`,
      {
        method: 'POST',
        body: formData,
      }
    );

    const result = await res.json();

    return new Response(JSON.stringify(result), {
      status: res.status,
      headers: { 'Content-Type': 'application/json' },
    });
  } catch {
    return new Response(JSON.stringify({ status: 'error', message: 'Server error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' },
    });
  }
};
