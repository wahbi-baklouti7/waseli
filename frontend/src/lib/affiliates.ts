export function getAffiliateLink(storeUrl: string, tag: string): string {
  try {
    const url = new URL(storeUrl);
    url.searchParams.set('ref', tag);
    return url.toString();
  } catch (error) {
    console.error('Invalid URL provided to getAffiliateLink');
    return storeUrl;
  }
}
