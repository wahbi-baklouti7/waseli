export interface CountryCode {
  id: string;
  code: string;
  country: string;
  label: string;
}

/**
 * List of prioritized country phone codes for the Wasitni platform.
 * Sync with database CountrySeeder (Alphabetical Order)
 */
export const COUNTRY_CODES: CountryCode[] = [
  { id: '1',  code: '+213', country: 'DZ', label: 'Algeria' },
  { id: '2',  code: '+973', country: 'BH', label: 'Bahrain' },
  { id: '3',  code: '+32',  country: 'BE', label: 'Belgium' },
  { id: '4',  code: '+1',   country: 'CA', label: 'Canada' },
  { id: '5',  code: '+86',  country: 'CN', label: 'China' },
  { id: '6',  code: '+45',  country: 'DK', label: 'Denmark' },
  { id: '7',  code: '+20',  country: 'EG', label: 'Egypt' },
  { id: '8',  code: '+33',  country: 'FR', label: 'France' },
  { id: '9',  code: '+49',  country: 'DE', label: 'Germany' },
  { id: '10', code: '+39',  country: 'IT', label: 'Italy' },
  { id: '11', code: '+81',  country: 'JP', label: 'Japan' },
  { id: '12', code: '+965', country: 'KW', label: 'Kuwait' },
  { id: '13', code: '+218', country: 'LY', label: 'Libya' },
  { id: '14', code: '+222', country: 'MR', label: 'Mauritania' },
  { id: '15', code: '+212', country: 'MA', label: 'Morocco' },
  { id: '16', code: '+31',  country: 'NL', label: 'Netherlands' },
  { id: '17', code: '+47',  country: 'NO', label: 'Norway' },
  { id: '18', code: '+968', country: 'OM', label: 'Oman' },
  { id: '19', code: '+351', country: 'PT', label: 'Portugal' },
  { id: '20', code: '+974', country: 'QA', label: 'Qatar' },
  { id: '21', code: '+966', country: 'SA', label: 'Saudi Arabia' },
  { id: '22', code: '+34',  country: 'ES', label: 'Spain' },
  { id: '23', code: '+46',  country: 'SE', label: 'Sweden' },
  { id: '24', code: '+41',  country: 'CH', label: 'Switzerland' },
  { id: '25', code: '+216', country: 'TN', label: 'Tunisia' },
  { id: '26', code: '+90',  country: 'TR', label: 'Turkey' },
  { id: '27', code: '+971', country: 'AE', label: 'United Arab Emirates' },
  { id: '28', code: '+44',  country: 'GB', label: 'United Kingdom' },
  { id: '29', code: '+1',   country: 'US', label: 'USA' },
];

/**
 * Helper to get the default country code based on a country code string (e.g., 'TN')
 */
export const getDefaultCountryCode = (country: string = 'TN'): string => {
  return COUNTRY_CODES.find(c => c.country === country)?.code || '+216';
};
