// === Základní typy ===

export interface WPImage {
  url: string;
  alt: string;
  width: number;
  height: number;
}

export interface WPLink {
  title: string;
  url: string;
  target: string;
}

// === Block data typy ===

export interface HeroBlockData {
  hero_heading_line1: string;
  hero_heading_line2: string;
  hero_description: string;
  hero_cta_primary: WPLink | null;
  hero_cta_secondary: WPLink | null;
  hero_youtube_url: string;
}

export interface AboutFeature {
  icon: WPImage | null;
  title: string;
  description: string;
}

export interface AboutBlockData {
  about_heading: string;
  about_heading_highlight: string;
  about_description: string;
  about_features: AboutFeature[];
}

export interface ServiceFeature {
  feature_text: string;
}

export interface ServiceItem {
  icon: WPImage | null;
  title: string;
  description: string;
  features: ServiceFeature[];
}

export interface ServicesBlockData {
  services_heading: string;
  services_heading_highlight: string;
  services_description: string;
  services_items: ServiceItem[];
}

export interface ReferenceClient {
  name: string;
  description: string;
  icon: WPImage | null;
}

export interface ReferenceTestimonial {
  quote: string;
  author: string;
  position: string;
  rating: number;
}

export interface ReferencesBlockData {
  ref_heading: string;
  ref_heading_highlight: string;
  ref_description: string;
  ref_clients: ReferenceClient[];
}

export interface TestimonialsBlockData {
  test_heading: string;
  test_description: string;
  test_items: ReferenceTestimonial[];
}

export interface ContactInfoCard {
  icon: WPImage | null;
  label: string;
  value: string;
}

export interface ContactBlockData {
  contact_heading: string;
  contact_description: string;
  contact_info_cards: ContactInfoCard[];
  contact_form: number | null;
}

// === API Response typy ===

export interface WPBlock {
  blockName: string;
  attrs: Record<string, unknown>;
  acf?: Record<string, unknown>;
}

export interface WPPageResponse {
  id: number;
  slug: string;
  path: string;
  title: string;
  status: string;
  parent: number;
  blocks: WPBlock[];
  content: string;
}

export interface WPMenuItem {
  id: number;
  title: string;
  url: string;
  target: string;
  parent: number;
  order: number;
  classes: string[];
}

export interface WPSiteOptions {
  logo: WPImage | null;
  favicon: string;
  footer_description: string;
  footer_contact: string;
  social: {
    instagram: string;
    linkedin: string;
    youtube: string;
    facebook: string;
  };
  copyright_text: string;
}

export interface WPSeoHead {
  success: boolean;
  head: string;
}

// === CF7 Form typy ===

export interface CF7Field {
  tag_type?: string;
  name?: string;
  required?: boolean;
  placeholder?: string;
  options?: string[];
  input_type?: string;
  values?: string[];
  content?: string;
  css_class?: string;
  // submit button
  type?: string;
  label?: string;
}

export interface CF7Form {
  id: number;
  title: string;
  fields: CF7Field[];
  additional_settings: string;
}
