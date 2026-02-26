import type { WPPageResponse, WPMenuItem, WPSiteOptions, WPSeoHead, CF7Form } from './types';

const WP_API_URL = import.meta.env.WP_API_URL || 'http://localhost:8080';

async function fetchAPI<T>(endpoint: string): Promise<T> {
  const url = `${WP_API_URL}/wp-json/foxo/v1${endpoint}`;
  const res = await fetch(url);

  if (!res.ok) {
    throw new Error(`WP API error: ${res.status} ${res.statusText} — ${url}`);
  }

  return res.json();
}

/**
 * Získá stránku s parsovanými ACF bloky
 */
export async function getPage(slug: string, preview = false, token?: string): Promise<WPPageResponse> {
  let endpoint = `/page/${slug}`;
  const params = new URLSearchParams();

  if (preview) {
    params.set('preview', 'true');
    if (token) params.set('token', token);
  }

  const qs = params.toString();
  if (qs) endpoint += `?${qs}`;

  return fetchAPI<WPPageResponse>(endpoint);
}

/**
 * Získá menu podle lokace (main_menu, footer_menu)
 */
export async function getMenu(location: string): Promise<WPMenuItem[]> {
  return fetchAPI<WPMenuItem[]>(`/menu/${location}`);
}

/**
 * Získá globální nastavení webu (logo, kontakt, sociální sítě)
 */
export async function getOptions(): Promise<WPSiteOptions> {
  return fetchAPI<WPSiteOptions>('/options');
}

/**
 * Získá SEO head tagy z Rank Math headless endpoint
 * Vrací kompletní HTML meta tagy jako string
 */
/**
 * Získá CF7 formulář (pole, nastavení)
 */
export async function getForm(formId: number): Promise<CF7Form> {
  return fetchAPI<CF7Form>(`/form/${formId}`);
}

export async function getSeoHead(pageUrl: string): Promise<string> {
  const url = `${WP_API_URL}/wp-json/rankmath/v1/getHead?url=${encodeURIComponent(pageUrl)}`;
  const res = await fetch(url);

  if (!res.ok) {
    return '';
  }

  const data: WPSeoHead = await res.json();

  if (!data.success || !data.head) {
    return '';
  }

  return data.head;
}
