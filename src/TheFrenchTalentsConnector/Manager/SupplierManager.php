<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Manager;

class SupplierManager
{
    /**
     * Get channel choices
     * Allow to list channels in an array like array[<code>] = <label>
     *
     * @return string[]
     */
    public function getChoices()
    {
        return $this->options;
    }

    /**
     * @var array
     */
    private $options = [
        727  => 'Acrochet\'Moi',
        728  => 'Alex Doré',
        957  => 'Alkalene',
        1191 => 'Anna&Co',
        864  => 'Anouma',
        876  => 'Azucar Bijoux',
        172  => 'Boregart',
        1241 => 'bénéchap',
        658  => 'Bregal Pelchat',
        1217 => 'CABAÏA',
        1348 => 'Cagecreations',
        969  => 'Cerise & Louis',
        777  => 'CHEWÖ COUTURE',
        1291 => 'DETIME',
        1301 => 'DUOO underwear',
        1017 => 'DKS',
        1335 => 'EON Paris',
        666  => 'EROS & AGAPE',
        1164 => 'Été 36',
        716  => 'FAGUO',
        1487 => 'Frandi',
        1214 => 'FYB Paris',
        255  => 'Germaine des prés',
        575  => 'inoow design',
        1142 => 'Ikonizaboy',
        1243 => 'Jonksion',
        240  => 'Josephine Bono',
        713  => 'Katell Leclaire',
        1272 => 'Kimeko',
        1052 => 'Kingies',
        1041 => 'Kitchen Trotter',
        1234 => 'La Maison Borrelly',
        723  => 'La Vie Française',
        1055 => 'Le Flageolet',
        1092 => 'Les Couleurs du Noir',
        1004 => 'Les French Demoiselles',
        936  => 'Lou Crie à Tort',
        930  => 'LUFA',
        1198 => 'M de Ségo',
        771  => 'MANU HANCOCK',
        553  => 'Mauve & Capucine',
        803  => 'Majesté Couture',
        1247 => 'Mes lacets',
        564  => 'Michel Le Brun Paris',
        621  => 'MOERO',
        396  => 'Monsieur Simone',
        544  => 'Monsieur Charli',
        1284 => 'Mööti',
        893  => 'Myphilosophy',
        921  => 'Nina Kaufman',
        391  => 'NO COMMENT Paris',
        897  => 'Nusya',
        588  => 'OVER',
        1252 => 'Ozed',
        721  => 'Paloma Casile',
        859  => 'Pieradi',
        468  => 'Piment de Mer',
        232  => 'Pochette Square',
        527  => 'Rendez-Vous Paris',
        848  => 'Ripauste',
        823  => 'Rubi & Cube',
        793  => 'SAMSmotif',
        802  => 'SITE COROT ',
        873  => 'Soran',
        1120 => 'Square Up',
        891  => 'The French Kiss',
        1249 => 'THE WOODS',
        257  => 'Vijoli Concept',
        622  => 'Xme',
        1074 => 'ZEDE',
    ];
}
