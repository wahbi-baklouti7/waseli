export interface Region {
  id: string;
  name: string;
}

export interface City {
  id: string;
  regionId: string;
  name: string;
}

/**
 * Tunisian Governorates (Regions)
 */
export const TUNISIAN_REGIONS: Region[] = [
  { id: '1', name: 'Ariana' },
  { id: '2', name: 'Béja' },
  { id: '3', name: 'Ben Arous' },
  { id: '4', name: 'Bizerte' },
  { id: '5', name: 'Gabès' },
  { id: '6', name: 'Gafsa' },
  { id: '7', name: 'Jendouba' },
  { id: '8', name: 'Kairouan' },
  { id: '9', name: 'Kasserine' },
  { id: '10', name: 'Kebili' },
  { id: '11', name: 'Kef' },
  { id: '12', name: 'Mahdia' },
  { id: '13', name: 'Manouba' },
  { id: '14', name: 'Médenine' },
  { id: '15', name: 'Monastir' },
  { id: '16', name: 'Nabeul' },
  { id: '17', name: 'Sfax' },
  { id: '18', name: 'Sidi Bouzid' },
  { id: '19', name: 'Siliana' },
  { id: '20', name: 'Sousse' },
  { id: '21', name: 'Tataouine' },
  { id: '22', name: 'Tozeur' },
  { id: '23', name: 'Tunis' },
  { id: '24', name: 'Zaghouan' },
];

/**
 * Tunisian Cities mapped to Regions
 */
export const TUNISIAN_CITIES: City[] = [
  // Ariana (Region 1)
  { id: '1', regionId: '1', name: 'Ennasr' },
  { id: '2', regionId: '1', name: 'Raoued' },
  { id: '3', regionId: '1', name: 'Sidi Thabet' },

  // Sfax (Region 17)
  { id: '4', regionId: '17', name: 'Sakiet Eddayer' },
  { id: '5', regionId: '17', name: 'Sakiet Ezzit' },

  // Sousse (Region 20)
  { id: '6', regionId: '20', name: 'Akouda' },
  { id: '7', regionId: '20', name: 'Hammam Sousse' },
  { id: '8', regionId: '20', name: 'Kalaa Sghira' },

  // Tunis (Region 23)
  { id: '9',  regionId: '23', name: 'Carthage' },
  { id: '10', regionId: '23', name: 'La Marsa' },
  { id: '11', regionId: '23', name: 'Le Bardo' },
  { id: '12', regionId: '23', name: 'Sidi Bou Said' },
];

/**
 * Helper to get cities for a specific region
 */
export const getCitiesByRegion = (regionId: string): City[] => {
  return TUNISIAN_CITIES.filter(city => city.regionId === regionId);
};
