<?php namespace Pensoft\Projectinitiatives\Updates;

use October\Rain\Database\Updates\Seeder;
use Pensoft\Projectinitiatives\Models\Data;
use Pensoft\Projectinitiatives\Models\Type;
use Pensoft\Projectinitiatives\Models\Approach;
use Pensoft\Projectinitiatives\Models\Landscape;
use Pensoft\Projectinitiatives\Models\Region;
use Pensoft\Projectinitiatives\Models\Funding;
use RainLab\Location\Models\Country;

class SeedPensoftProjectinitiativesData extends Seeder
{
    protected $typeMap = [
        'Cropland/arable land' => 'Arable land',
        'Grassland' => 'Grassland',
        'Permanent crops' => 'Permanent crops',
        'Mixed crops' => 'Mixed crops',
    ];

    protected $approachMap = [
        'Markets and sales' => 'Markets and sales',
        'Knowledge networks and Living Labs' => 'Knowledge networks and Living Labs',
        'Labels and certification' => 'Labels and certification',
        'Research and pilot study' => 'Research and pilot study',
        'Volunteer mobilization and citizen science' => 'Volunteer mobilization and citizen involvement',
        'Volunteer mobilization and citizen involvement' => 'Volunteer mobilization and citizen involvement',
        'Subsidy schemes' => 'Subsidies',
        'Subsidies' => 'Subsidies',
        'Policy support' => 'Policy support',
        'Governmental project' => 'Governmental project',
        'NGO or civil society organisation project' => 'NGO or civil society organization project',
        'NGO or civil society organization project' => 'NGO or civil society organization project',
    ];

    protected $landscapeMap = [
        'Woody' => 'Woody',
        'Grassy' => 'Grassy',
        'Wet' => 'Wet',
        'Stony' => 'Stony',
        'All' => 'All landscape features',
        'All types of landscape features' => 'All landscape features',
        'All landscape features' => 'All landscape features',
    ];

    protected $regionMap = [
        'Western' => 'Western Europe',
        'Northern' => 'Northern Europe',
        'Eastern' => 'Eastern Europe',
        'Southern' => 'Southern Europe',
    ];

    protected $fundingMap = [
        'Public' => 'Public',
        'Private' => 'Private',
        'Public/Private' => 'Mixed funding',
        'Mixed funding' => 'Mixed funding',
    ];

    public function run()
    {
        $rows = $this->getData();

        foreach ($rows as $index => $row) {
            $data = Data::firstOrCreate(
                ['title' => $row['title']],
                [
                    'description' => $row['description'] ?? null,
                    'institution' => $row['institution'] ?? null,
                    'website' => $row['website'] ?? null,
                    'links' => $row['links'] ?? null,
                    'sort_order' => $index + 1,
                ]
            );

            // Countries
            if (!empty($row['countries'])) {
                $countryIds = [];
                foreach ($row['countries'] as $name) {
                    $country = Country::where('name', $name)->first();
                    if ($country) {
                        $countryIds[] = $country->id;
                    }
                }
                if ($countryIds) {
                    $data->countries()->syncWithoutDetaching($countryIds);
                }
            }

            // Regions
            if (!empty($row['regions'])) {
                $ids = [];
                foreach ($row['regions'] as $name) {
                    $mapped = $this->regionMap[$name] ?? $name;
                    $record = Region::where('title', $mapped)->first();
                    if ($record) $ids[] = $record->id;
                }
                if ($ids) $data->regions()->syncWithoutDetaching($ids);
            }

            // Types
            if (!empty($row['types'])) {
                $ids = [];
                foreach ($row['types'] as $name) {
                    $mapped = $this->typeMap[$name] ?? $name;
                    $record = Type::where('title', $mapped)->first();
                    if ($record) $ids[] = $record->id;
                }
                if ($ids) $data->types()->syncWithoutDetaching($ids);
            }

            // Approaches
            if (!empty($row['approaches'])) {
                $ids = [];
                foreach ($row['approaches'] as $name) {
                    $mapped = $this->approachMap[$name] ?? $name;
                    $record = Approach::where('title', $mapped)->first();
                    if ($record) $ids[] = $record->id;
                }
                if ($ids) $data->approaches()->syncWithoutDetaching($ids);
            }

            // Landscapes
            if (!empty($row['landscapes'])) {
                $ids = [];
                foreach ($row['landscapes'] as $name) {
                    $mapped = $this->landscapeMap[$name] ?? $name;
                    $record = Landscape::where('title', $mapped)->first();
                    if ($record) $ids[] = $record->id;
                }
                if ($ids) $data->landscapes()->syncWithoutDetaching($ids);
            }

            // Fundings
            if (!empty($row['fundings'])) {
                $ids = [];
                foreach ($row['fundings'] as $name) {
                    $mapped = $this->fundingMap[$name] ?? $name;
                    $record = Funding::where('title', $mapped)->first();
                    if ($record) $ids[] = $record->id;
                }
                if ($ids) $data->fundings()->syncWithoutDetaching($ids);
            }
        }
    }

    protected function getData()
    {
        return [
            [
                'title' => 'Le Label Haie',
                'description' => 'Le Label Haie is a French initiative promoting the sustainable management of hedgerows in agricultural landscapes. It provides a quality certification for the entire value chain, from planting and maintenance to the use of hedge products. The label encourages ecological practices, supports local economies, and aims to restore biodiversity.',
                'countries' => ['France'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land', 'Grassland'],
                'approaches' => ['Markets and sales'],
                'landscapes' => ['Woody'],
                'fundings' => ['Public/Private'],
                'links' => [['link_url' => 'https://labelhaie.fr/']],
            ],
            [
                'title' => 'Agora Natura',
                'description' => 'AgoraNatura is a German online marketplace connecting land managers with private funders to finance nature conservation projects. Farmers can offer projects, such as creating flower strips or wetlands, and sell "nature conservation certificates" to companies and individuals, creating a market-based mechanism for funding on-farm biodiversity enhancements.',
                'countries' => ['Germany'],
                'regions' => ['Western'],
                'types' => ['Grassland', 'Cropland/arable land'],
                'approaches' => ['Markets and sales'],
                'landscapes' => ['Grassy'],
                'fundings' => ['Private'],
                'links' => [['link_url' => 'https://agora-natura.de/en/about-agora-natura/']],
            ],
            [
                'title' => 'F.R.A.N.Z. (Für Ressourcen, Agrarwirtschaft & Naturschutz mit Zukunft)',
                'description' => 'F.R.A.N.Z. (Future Resources, Agriculture & Nature) is a German collaborative project where farmers and environmentalists jointly develop and test biodiversity-enhancing measures on ten demonstration farms. It aims to identify ecologically effective, practical, and economically viable conservation practices for conventional agriculture, with the goal of influencing future agricultural policy.',
                'countries' => ['Germany'],
                'regions' => ['Western'],
                'types' => ['Grassland', 'Cropland/arable land'],
                'approaches' => ['Knowledge networks and Living Labs'],
                'landscapes' => ['Grassy'],
                'fundings' => ['Public/Private'],
                'links' => [['link_url' => 'https://www.franz-projekt.de/website/english-summary']],
            ],
            [
                'title' => 'LIFE DESERT ADAPT',
                'description' => 'LIFE DESERT ADAPT addressed land degradation and desertification in Southern Mediterranean EU countries (Italy, Portugal, Spain). The project demonstrated and disseminated sustainable land management and climate adaptation practices, such as agroforestry and soil conservation techniques, to improve the resilience of agricultural systems against climate change and erosion.',
                'countries' => ['Italy', 'Portugal', 'Spain'],
                'regions' => ['Southern'],
                'types' => ['Cropland/arable land', 'Grassland'],
                'approaches' => ['Knowledge networks and Living Labs'],
                'landscapes' => ['Woody'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'http://www.desert-adapt.it/index.php/en/']],
            ],
            [
                'title' => 'Jagava Permafarma Veselice farm',
                'description' => 'This Czech farm serves as a good practice example of integrating CAP-funded agroforestry into its operations. By planting rows of fruit and timber trees on arable land and grasslands, the farm has improved its resilience to climate change, enhanced local water quality, and diversified its production of healthy food.',
                'countries' => ['Czech Republic'],
                'regions' => ['Eastern'],
                'types' => ['Cropland/arable land', 'Grassland'],
                'approaches' => ['Knowledge networks and Living Labs'],
                'landscapes' => ['Woody'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'https://eu-cap-network.ec.europa.eu/good-practice/agroforestry-systems-permanent-grasslands-and-arable-land_en']],
            ],
            [
                'title' => 'Ardo Mimosa+',
                'description' => 'Ardo, a major European producer of frozen vegetables, runs the MIMOSA+ program to promote sustainable and regenerative agriculture among its 3,500 partner farmers. Through its agronomist team, Ardo supports the implementation of practices like creating flower strips and hedgerows to enhance biodiversity and soil health in its supply chain.',
                'countries' => ['Belgium'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land'],
                'approaches' => ['Labels and certification'],
                'landscapes' => ['Grassy', 'Woody'],
                'fundings' => ['Private'],
                'links' => [['link_url' => 'https://ardo.com/nl-be/duurzaamheid/agronomie-en-mimosa']],
            ],
            [
                'title' => 'Terres et bocages',
                'description' => 'Terres et Bocages is a French cooperative initiative focused on restoring hedgerow (bocage) landscapes in Brittany. It brings farmers together to pool equipment, skills, and resources for planting and managing hedges and trees. The initiative aims to combine agricultural and forestry knowledge to better integrate woody features into farming systems.',
                'countries' => ['France'],
                'regions' => ['Western'],
                'types' => ['Grassland', 'Cropland/arable land'],
                'approaches' => ['Knowledge networks and Living Labs'],
                'landscapes' => ['Woody'],
                'fundings' => ['Public/Private'],
                'links' => [['link_url' => 'https://terresetbocages.org/']],
            ],
            [
                'title' => 'Akkernatuur',
                'description' => 'Akkernatuur is a Belgian project that works with farmers in the Zuid-Hageland region to create landscape features specifically designed to support farmland birds. By establishing unploughed grass strips and other habitats, the initiative aims to provide food, shelter, and breeding grounds for declining bird species in arable landscapes.',
                'countries' => ['Belgium'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land'],
                'approaches' => ['NGO or civil society organisation project'],
                'landscapes' => ['Grassy'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'https://www.regionalelandschappen.be/projecten/akkernatuur']],
            ],
            [
                'title' => 'Biotopverbund Grasland',
                'description' => 'This German project focuses on creating a network of grassland biotopes in Lower Saxony and Bremen to combat species decline caused by habitat isolation. It promotes the creation and connection of biodiverse meadows and pastures, working with farmers to enhance these habitats and improve connectivity across the agricultural landscape.',
                'countries' => ['Germany'],
                'regions' => ['Western'],
                'types' => ['Grassland'],
                'approaches' => ['Research and pilot study'],
                'landscapes' => ['Grassy'],
                'fundings' => ['Public'],
                'links' => [
                    ['link_url' => 'https://www.lwk-niedersachsen.de/lwk/news/35200_DBU-Projekt_Biotopverbund_Grasland'],
                    ['link_url' => 'https://www.gruenlandzentrum.org/projekte/biotopverbund-grasland/'],
                ],
            ],
            [
                'title' => 'Ritobäcken two-stage channel with selectively maintained vegetated floodplains for agricultural drainage/flood management',
                'description' => 'This Finnish initiative is a case study on creating a two-stage channel in an agricultural stream as a nature-based solution for flood and nutrient management. By excavating a narrow, vegetated floodplain, the project reduces the need for dredging, traps pollutants, enhances biodiversity, and improves water quality and climate resilience.',
                'countries' => ['Finland'],
                'regions' => ['Northern'],
                'types' => ['Cropland/arable land'],
                'approaches' => ['Knowledge networks and Living Labs'],
                'landscapes' => ['Wet', 'Grassy', 'Woody'],
                'fundings' => ['Public'],
                'links' => [
                    ['link_url' => 'https://www.nature.com/articles/s41598-024-84956-2'],
                    ['link_url' => 'https://www.sciencedirect.com/science/article/pii/S0301479724006066'],
                    ['link_url' => 'https://www.mdpi.com/2071-1050/13/16/9349'],
                    ['link_url' => 'https://www.tandfonline.com/doi/pdf/10.1080/15715124.2011.572888'],
                ],
            ],
            [
                'title' => 'Uuhikonoja 2-stage channel',
                'description' => 'This project in Southwestern Finland demonstrates the benefits of a two-stage channel for agricultural water management. By creating a vegetated floodplain alongside a dredged stream, it offers an ecological alternative that reduces maintenance, improves water quality by trapping nutrients, and boosts local riparian and aquatic biodiversity.',
                'countries' => ['Finland'],
                'regions' => ['Northern'],
                'types' => ['Cropland/arable land'],
                'approaches' => ['Research and pilot study'],
                'landscapes' => ['Grassy', 'Wet'],
                'fundings' => ['Public/Private'],
                'links' => [['link_url' => 'https://doi.org/10.1177/25148486241281226']],
            ],
            [
                'title' => 'Case study of The River Raaseporinjoki',
                'description' => 'This large-scale river renovation in Southern Finland was a collaborative effort between the local municipality and landowners to mitigate agricultural flooding. The project constructed a variety of wet landscape features, including two-stage channels, wetlands, and ponds, resulting in reduced field flooding and the creation of multiple ecosystem benefits.',
                'countries' => ['Finland'],
                'regions' => ['Northern'],
                'types' => ['Cropland/arable land', 'Grassland'],
                'approaches' => ['Research and pilot study'],
                'landscapes' => ['Grassy', 'Wet'],
                'fundings' => ['Public/Private'],
                'links' => [
                    ['link_url' => 'https://doi.org/10.1016/j.jenvman.2024.120620'],
                    ['link_url' => 'https://doi.org/10.1177/25148486241281226'],
                ],
            ],
            [
                'title' => 'LIFEcharcos Life12/NAT/PT/997',
                'description' => 'The LIFE Charcos project focused on the conservation of Mediterranean temporary ponds on the southwest coast of Portugal. It demonstrated good management practices for their restoration, produced a best-practice manual, and established a land stewardship network of "Guardians" to ensure the long-term monitoring and preservation of these unique habitats.',
                'countries' => ['Portugal'],
                'regions' => ['Southern'],
                'types' => ['Grassland', 'Cropland/arable land'],
                'approaches' => ['Knowledge networks and Living Labs'],
                'landscapes' => ['Wet'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'https://lifecharcos.lpn.pt/en/']],
            ],
            [
                'title' => 'Stone wall restoration in Serra do Sicó',
                'description' => 'This project in central Portugal focuses on conserving and promoting the cultural heritage of dry-stone wall construction, a practice recognized by UNESCO. It aims to restore these landscape features to support tourism and preserve traditional building techniques, involving knowledge transfer and collaboration with other territories to value this ancient craft.',
                'countries' => ['Portugal'],
                'regions' => ['Southern'],
                'types' => ['Permanent crops', 'Grassland', 'Cropland/arable land'],
                'approaches' => ['Knowledge networks and Living Labs'],
                'landscapes' => ['Stony'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'https://www.terrasdesico.pt/projetos-cofinanciados-centro-2020/centro-05-5141-feder-000961-a-arte-de-construcao-dos-muros-de-pedra-seca-a-patrimonio-cultural-imaterial-da-humanidade-unesco']],
            ],
            [
                'title' => 'Valio biodiversity survey of dairy farms',
                'description' => 'Finnish dairy company Valio conducted a pilot project to raise biodiversity awareness among its farmers. Experts surveyed 64 farms, assessing landscape elements and providing reports with tailored recommendations for restoration and maintenance. While deemed inefficient for large-scale monitoring, the project provided valuable on-farm advice and spurred development of satellite-based tools.',
                'countries' => ['Finland'],
                'regions' => ['Northern'],
                'types' => ['Cropland/arable land'],
                'approaches' => ['Research and pilot study'],
                'landscapes' => ['All', 'Woody', 'Grassy', 'Stony', 'Wet'],
                'fundings' => [],
                'links' => [['link_url' => 'https://www.maajakotitalousnaiset.fi/ajankohtaista/maatilat-avainasemassa-luonnon-monimuotoisuuden-lisaamisessa-parolan-tila-mikkelissa-tarttuu-toimiin-ammattilaisten-avustuksella']],
            ],
            [
                'title' => 'WWF Finland wet landscape restoration in Western Uusimaa',
                'description' => 'In Western Uusimaa, WWF Finland collaborates with landowners to restore wet landscape features for water protection and biodiversity. Through a series of successful projects, they create wetlands, construct two-stage ditches with floodplains on agricultural land, and restore the natural meandering of streams to reduce nutrient runoff into the Baltic Sea.',
                'countries' => ['Finland'],
                'regions' => ['Northern'],
                'types' => ['Cropland/arable land'],
                'approaches' => ['NGO or civil society organisation project'],
                'landscapes' => ['Wet'],
                'fundings' => [],
                'links' => [
                    ['link_url' => 'https://wwf.fi/valuta-hankkeen-kohteet/'],
                    ['link_url' => 'https://wwf.fi/elinymparistot/itameri/valuta/'],
                    ['link_url' => 'https://wwf.fi/elinymparistot/itameri/rankku/'],
                    ['link_url' => 'https://wwf.fi/elinymparistot/itameri/kuormitus-kuriin/'],
                ],
            ],
            [
                'title' => 'Biodiversity monitoring by farmers',
                'description' => 'This Austrian project empowers farmers by involving them directly in biodiversity monitoring on their land. Through close cooperation with ecologists, farmers learn to observe and document species in grasslands, field margins, and flower strips. This participatory approach increases awareness and motivation for implementing and maintaining biodiversity-friendly landscape features.',
                'countries' => ['Austria'],
                'regions' => ['Western'],
                'types' => ['Grassland'],
                'approaches' => ['Volunteer mobilization and citizen science'],
                'landscapes' => ['Grassy'],
                'fundings' => ['Public/Private'],
                'links' => [['link_url' => 'case uit de agricultural mapping book']],
            ],
            [
                'title' => 'Result oriented nature conservation planning (Austria)',
                'description' => 'This Austrian initiative uses a results-oriented approach for nature conservation payments. Instead of prescribing specific measures, it sets ecological targets (e.g., presence of indicator species) and allows farmers the flexibility to choose their own management practices to achieve them, giving them greater responsibility and adapting actions to local conditions.',
                'countries' => ['Austria'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land', 'Grassland', 'Permanent crops', 'Mixed crops'],
                'approaches' => ['Subsidy schemes'],
                'landscapes' => ['Woody', 'Grassy', 'Wet'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'AE mapping book volume 1']],
            ],
            [
                'title' => 'Result oriented nature conservation planning (Germany)',
                'description' => 'Focusing on Lower Saxony and Bremen, this German initiative applies results-oriented payments to create and maintain networks of biodiverse grassland biotopes. The project emphasizes the importance of ecological connectivity, rewarding farmers for achieving specific environmental outcomes that help link isolated habitats and protect grassland biodiversity across the landscape.',
                'countries' => ['Germany'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land', 'Grassland', 'Permanent crops', 'Mixed crops'],
                'approaches' => ['Subsidy schemes'],
                'landscapes' => ['Grassy'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'part of mapping book 1 _agroecology']],
            ],
            [
                'title' => 'Biodiversity model farms (living lab)',
                'description' => 'This German initiative establishes biodiversity model farms in North Rhine-Westphalia that act as living labs. Through close collaboration between farmers, advisors, and scientists, the project trials and adapts biodiversity-enhancing measures to ensure they are practical, economically viable for the farm, and effective from a nature conservation perspective.',
                'countries' => ['Germany'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land', 'Grassland', 'Permanent crops', 'Mixed crops'],
                'approaches' => ['Knowledge networks and Living Labs'],
                'landscapes' => ['All'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'agroecology mapping book vol 1']],
            ],
            [
                'title' => 'Model eco-regions Bavaria',
                'description' => "The 'Öko-Modellregionen' in Bavaria, Germany, are state-supported regions that promote organic farming and regional value chains. By involving entire communities, farmers, and institutions, these regions aim to protect soil and water, enhance biodiversity and landscape features, and strengthen the local economy through sustainable agriculture and tourism.",
                'countries' => ['Germany'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land', 'Grassland', 'Permanent crops', 'Mixed crops'],
                'approaches' => ['Markets and sales'],
                'landscapes' => ['All'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'agroecology mapping vol 1']],
            ],
            [
                'title' => 'KoLa Leipzig',
                'description' => 'KoLa Leipzig is a large-scale community-supported agriculture (CSA) cooperative near Leipzig, Germany. Transitioning from conventional farming, the initiative focuses on organic production and crop diversification. It actively integrates landscape features like agroforestry systems to enhance biodiversity and ecological resilience on its collectively managed land.',
                'countries' => ['Germany'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land'],
                'approaches' => ['Volunteer mobilization and citizen science'],
                'landscapes' => ['Woody'],
                'fundings' => ['Public'],
                'links' => [['link_url' => 'https://kolaleipzig.de/']],
            ],
            [
                'title' => 'Habitat restoration in Homberg next to Ratingen',
                'description' => 'This local habitat restoration project in Homberg, Germany, was led by the municipal environmental agency. A former arable area was transformed into a diverse wood pasture landscape by planting fruit trees, extensifying grassland management, restoring hedgerows, and adding deadwood features to enhance local biodiversity and ecological structure.',
                'countries' => ['Germany'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land', 'Grassland'],
                'approaches' => ['Governmental project'],
                'landscapes' => ['Woody', 'Grassy'],
                'fundings' => [],
                'links' => [],
            ],
            [
                'title' => 'Grasslife',
                'description' => 'The GrassLIFE project in Latvia focused on restoring and maintaining EU priority grassland habitats. It developed a grassland connectivity model, provided practical and financial support to farmers for sustainable grassland management, and promoted the value of these habitats through publications on medicinal plants, linking nature conservation with cultural heritage.',
                'countries' => ['Latvia'],
                'regions' => ['Northern'],
                'types' => ['Grassland'],
                'approaches' => ['Markets and sales'],
                'landscapes' => ['Grassy'],
                'fundings' => ['Public', 'Public/Private'],
                'links' => [['link_url' => 'https://grasslife.lv/en/']],
            ],
            [
                'title' => 'Flemish Hedgerow plan',
                'description' => 'The Flemish Hedgerow Plan is a government-led action plan aiming to strengthen the hedgerow network in Flanders by 2030. Implemented by the Flemish Land Agency (VLM), it uses project calls and subsidies to encourage farmers, private landowners, and local authorities to plant and sustainably manage hedgerows, enhancing landscape quality and biodiversity.',
                'countries' => ['Belgium'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land', 'Grassland', 'Permanent crops'],
                'approaches' => ['Subsidy schemes'],
                'landscapes' => ['Woody'],
                'fundings' => ['Public'],
                'links' => [
                    ['link_url' => 'https://pers.vlm.be/400000-euro-voor-38-km-nieuwe-houtkanten'],
                    ['link_url' => 'https://www.vlm.be/nl/themas/platteland/landschapskwaliteit%20en%20onderhoud/Houtkantenplan/Paginas/default.aspx'],
                ],
            ],
            [
                'title' => 'Somerset Catchment Market',
                'description' => "The Somerset Catchment Market is an environmental trading platform in the UK. It facilitates private investment in nature-based solutions on agricultural land, such as creating wetlands and woodlands. The market's primary goal is to accelerate projects that reduce phosphate pollution and deliver other ecosystem services like carbon sequestration and biodiversity.",
                'countries' => ['United Kingdom'],
                'regions' => ['Northern'],
                'types' => ['Cropland/arable land', 'Grassland'],
                'approaches' => ['Markets and sales'],
                'landscapes' => ['Wet', 'Grassy'],
                'fundings' => ['Private'],
                'links' => [['link_url' => 'https://www.somersetcatchmentmarket.uk/']],
            ],
            [
                'title' => 'Carta del Mulino',
                'description' => "The 'Carta del Mulino' is a sustainability charter developed by the Italian food brand Mulino Bianco. It sets standards for its soft wheat suppliers, including a requirement to dedicate 3% of their farmland to flowering field margins ('fiori del mulino'). This private-sector initiative directly incentivizes the creation of grassy landscape features.",
                'countries' => ['Italy'],
                'regions' => ['Southern'],
                'types' => ['Cropland/arable land'],
                'approaches' => ['Labels and certification'],
                'landscapes' => ['Grassy'],
                'fundings' => ['Private'],
                'links' => [['link_url' => 'https://www.mulinobianco.it/lacartadelmulino/Carta-del-mulino-regole.pdf']],
            ],
            [
                'title' => 'Trees for All',
                'description' => 'Trees for All is a long-standing Dutch non-profit that finances tree planting projects nationally and internationally. Supported by diversified private funding, including corporate partnerships and citizen donations for CO2 compensation, it plants millions of trees, often on agricultural lands to create agroforestry systems, hedgerows, and new woodlands.',
                'countries' => ['Netherlands'],
                'regions' => ['Western'],
                'types' => ['Cropland/arable land', 'Grassland', 'Permanent crops'],
                'approaches' => ['NGO or civil society organisation project'],
                'landscapes' => ['Woody'],
                'fundings' => ['Private'],
                'links' => [['link_url' => 'https://treesforall.nl/']],
            ],
        ];
    }
}
