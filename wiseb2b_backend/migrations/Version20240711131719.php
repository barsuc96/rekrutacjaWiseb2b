<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711131719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'fill countries';
    }

    public function up(Schema $schema): void
    {
        foreach ($this->getCountryList() as $iso => $country){
            $this->addSql("INSERT INTO country (id, id_external, is_active, sys_insert_date, sys_update_date) VALUES
                        ((SELECT nextval('country_id_seq')), '{$iso}', true, NOW(), NOW())");
            $this->addSql("INSERT INTO country_translation (id, country_id, language, name, is_active, sys_insert_date, sys_update_date) VALUES
                        ((SELECT nextval('country_translation_id_seq')), (SELECT id FROM country WHERE id_external = '{$iso}' LIMIT 1), 'pl', '{$country['pl']}', true, NOW(), NOW())");
            $this->addSql("INSERT INTO country_translation (id, country_id, language, name, is_active, sys_insert_date, sys_update_date) VALUES
                        ((SELECT nextval('country_translation_id_seq')), (SELECT id FROM country WHERE id_external = '{$iso}' LIMIT 1), 'en', '{$country['en']}', true, NOW(), NOW())");
        }


        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql("INSERT INTO country (id, id_external, is_active, sys_insert_date, sys_update_date) VALUES
//                        ((SELECT nextval('country_id_seq')), 'LI', true, NOW(), NOW())");
//        $this->addSql("INSERT INTO country_translation (id, country_id, language, name, is_active, sys_insert_date, sys_update_date) VALUES
//                        ((SELECT nextval('country_translation_id_seq')), (SELECT id FROM country WHERE id_external = 'LI'), 'pl', 'Lichtensztain', true, NOW(), NOW())");
//        $this->addSql("INSERT INTO country_translation (id, country_id, language, name, is_active, sys_insert_date, sys_update_date) VALUES
//                        ((SELECT nextval('country_translation_id_seq')), (SELECT id FROM country WHERE id_external = 'LI'), 'en', 'Liechtenstein', true, NOW(), NOW())");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql("DELETE FROM country_translation WHERE country_id NOT IN
//                            (SELECT id FROM country WHERE id_external NOT IN ('PL','CZ','EN','DE'))");
//        $this->addSql("DELETE FROM country WHERE id_external NOT IN ('PL','CZ','EN','DE')");
    }

    private function getCountryList()
    {
        return [
            'LI' => ['pl' => 'Lichtensztain', 'en' => 'Liechtenstein'],
            'LT' => ['pl' => 'Litwa', 'en' => 'Lithuania'],
            'LU' => ['pl' => 'Luksemburg', 'en' => 'Luxembourg'],
            'MO' => ['pl' => 'Makau', 'en' => 'Macau'],
            'MK' => ['pl' => 'Macedonia', 'en' => 'Macedonia'],
            'MG' => ['pl' => 'Madagaskar', 'en' => 'Madagascar'],
            'MW' => ['pl' => 'Malawi', 'en' => 'Malawi'],
            'MY' => ['pl' => 'Malezja', 'en' => 'Malaysia'],
            'MV' => ['pl' => 'Malediwy', 'en' => 'Maldives'],
            'ML' => ['pl' => 'Mali', 'en' => 'Mali'],
            'MT' => ['pl' => 'Malta', 'en' => 'Malta'],
            'MH' => ['pl' => 'Wyspy Marshalla', 'en' => 'Marshall Islands'],
            'MQ' => ['pl' => 'Martynika', 'en' => 'Martinique'],
            'MR' => ['pl' => 'Mauretania', 'en' => 'Mauritania'],
            'MU' => ['pl' => 'Mauritius', 'en' => 'Mauritius'],
            'YT' => ['pl' => 'Majotta', 'en' => 'Mayotte'],
            'MX' => ['pl' => 'Meksyk', 'en' => 'Mexico'],
            'FM' => ['pl' => 'Mikronezja', 'en' => 'Micronesia'],
            'MD' => ['pl' => 'Mołdawia', 'en' => 'Moldova'],
            'MC' => ['pl' => 'Monako', 'en' => 'Monaco'],
            'MN' => ['pl' => 'Mongolia', 'en' => 'Mongolia'],
            'ME' => ['pl' => 'Czarnogóra', 'en' => 'Montenegro'],
            'MS' => ['pl' => 'Montserrat', 'en' => 'Montserrat'],
            'MA' => ['pl' => 'Maroko', 'en' => 'Morocco'],
            'MZ' => ['pl' => 'Mozambik', 'en' => 'Mozambique'],
            'MM' => ['pl' => 'Mjanma', 'en' => 'Myanmar'],
            'NA' => ['pl' => 'Namibia', 'en' => 'Namibia'],
            'NR' => ['pl' => 'Nauru', 'en' => 'Nauru'],
            'NP' => ['pl' => 'Nepal', 'en' => 'Nepal'],
            'NL' => ['pl' => 'Holandia', 'en' => 'Netherlands'],
            'AN' => ['pl' => 'Antyle Holenderskie', 'en' => 'Netherlands Antilles'],
            'NC' => ['pl' => 'Nowa Kaledonia', 'en' => 'New Caledonia'],
            'NZ' => ['pl' => 'Nowa Zelandia', 'en' => 'New Zealand'],
            'NI' => ['pl' => 'Nikaragua', 'en' => 'Nicaragua'],
            'NE' => ['pl' => 'Niger', 'en' => 'Niger'],
            'NG' => ['pl' => 'Nigeria', 'en' => 'Nigeria'],
            'NU' => ['pl' => 'Niue', 'en' => 'Niue'],
            'NF' => ['pl' => 'Norfolk', 'en' => 'Norfolk Island'],
            'MP' => ['pl' => 'Mariany Północne', 'en' => 'Northern Mariana Islands'],
            'NO' => ['pl' => 'Norwegia', 'en' => 'Norway'],
            'OM' => ['pl' => 'Oman', 'en' => 'Oman'],
            'PK' => ['pl' => 'Pakistan', 'en' => 'Pakistan'],
            'PW' => ['pl' => 'Palau', 'en' => 'Palau'],
            'PS' => ['pl' => 'Palestyna', 'en' => 'Palestine'],
            'PA' => ['pl' => 'Panama', 'en' => 'Panama'],
            'PG' => ['pl' => 'Papua-Nowa Gwinea', 'en' => 'Papua New Guinea'],
            'PY' => ['pl' => 'Paragwaj', 'en' => 'Paraguay'],
            'PE' => ['pl' => 'Peru', 'en' => 'Peru'],
            'PH' => ['pl' => 'Filipiny', 'en' => 'Philippines'],
            'PN' => ['pl' => 'Pitcairn', 'en' => 'Pitcairn'],
            'PT' => ['pl' => 'Portugalia', 'en' => 'Portugal'],
            'PR' => ['pl' => 'Portoryko', 'en' => 'Puerto Rico'],
            'QA' => ['pl' => 'Katar', 'en' => 'Qatar'],
            'RE' => ['pl' => 'Reunion', 'en' => 'Reunion'],
            'RO' => ['pl' => 'Rumunia', 'en' => 'Romania'],
            'RU' => ['pl' => 'Rosja', 'en' => 'Russia'],
            'RW' => ['pl' => 'Rwanda', 'en' => 'Rwanda'],
            'BL' => ['pl' => 'Saint Barthelemy', 'en' => 'Saint Barthelemy'],
            'SH' => ['pl' => 'Święta Helena', 'en' => 'Saint Helena'],
            'KN' => ['pl' => 'Saint Kitts i Nevis', 'en' => 'Saint Kitts and Nevis'],
            'LC' => ['pl' => 'Saint Lucia', 'en' => 'Saint Lucia'],
            'MF' => ['pl' => 'Saint-Martin', 'en' => 'Saint Martin'],
            'PM' => ['pl' => 'Saint-Pierre i Miquelon', 'en' => 'Saint Pierre and Miquelon'],
            'VC' => ['pl' => 'Saint Vincent i Grenadyny', 'en' => 'Saint Vincent and the Grenadines'],
            'WS' => ['pl' => 'Samoa', 'en' => 'Samoa'],
            'SM' => ['pl' => 'San Marino', 'en' => 'San Marino'],
            'ST' => ['pl' => 'Sao Tome i Principe', 'en' => 'Sao Tome and Principe'],
            'SA' => ['pl' => 'Arabia Saudyjska', 'en' => 'Saudi Arabia'],
            'SN' => ['pl' => 'Senegal', 'en' => 'Senegal'],
            'RS' => ['pl' => 'Serbia', 'en' => 'Serbia'],
            'SC' => ['pl' => 'Seszele', 'en' => 'Seychelles'],
            'SL' => ['pl' => 'Sierra Leone', 'en' => 'Sierra Leone'],
            'SG' => ['pl' => 'Singapur', 'en' => 'Singapore'],
            'SK' => ['pl' => 'Słowacja', 'en' => 'Slovakia'],
            'SI' => ['pl' => 'Słowenia', 'en' => 'Slovenia'],
            'SB' => ['pl' => 'Wyspy Salomona', 'en' => 'Solomon Islands'],
            'SO' => ['pl' => 'Somalia', 'en' => 'Somalia'],
            'ZA' => ['pl' => 'Republika Południowej Afryki', 'en' => 'South Africa'],
            'GS' => ['pl' => 'Georgia Południowa i Sandwich Południowy', 'en' => 'South Georgia and the South Sandwich Islands'],
            'ES' => ['pl' => 'Hiszpania', 'en' => 'Spain'],
            'LK' => ['pl' => 'Sri Lanka', 'en' => 'Sri Lanka'],
            'SD' => ['pl' => 'Sudan', 'en' => 'Sudan'],
            'SR' => ['pl' => 'Surinam', 'en' => 'Suriname'],
            'SJ' => ['pl' => 'Svalbard i Jan Mayen', 'en' => 'Svalbard and Jan Mayen'],
            'SZ' => ['pl' => 'Eswatini', 'en' => 'Swaziland'],
            'SE' => ['pl' => 'Szwecja', 'en' => 'Sweden'],
            'CH' => ['pl' => 'Szwajcaria', 'en' => 'Switzerland'],
            'SY' => ['pl' => 'Syria', 'en' => 'Syria'],
            'TW' => ['pl' => 'Tajwan', 'en' => 'Taiwan'],
            'TJ' => ['pl' => 'Tadżykistan', 'en' => 'Tajikistan'],
            'VE' => ['pl' => 'Wenezuela', 'en' => 'Venezuela'],
            'VN' => ['pl' => 'Wietnam', 'en' => 'Vietnam'],
            'VG' => ['pl' => 'Brytyjskie Wyspy Dziewicze', 'en' => 'Virgin Islands (British)'],
            'VI' => ['pl' => 'Wyspy Dziewicze Stanów Zjednoczonych', 'en' => 'Virgin Islands (U.S.)'],
            'WF' => ['pl' => 'Wallis i Futuna', 'en' => 'Wallis and Futuna'],
            'EH' => ['pl' => 'Sahara Zachodnia', 'en' => 'Western Sahara'],
            'YE' => ['pl' => 'Jemen', 'en' => 'Yemen'],
            'ZM' => ['pl' => 'Zambia', 'en' => 'Zambia'],
            'ZW' => ['pl' => 'Zimbabwe', 'en' => 'Zimbabwe'],
            'AF' => ['pl' => 'Afganistan', 'en' => 'Afghanistan'],
            'AX' => ['pl' => 'Wyspy Alandzkie', 'en' => 'Åland Islands'],
            'AL' => ['pl' => 'Albania', 'en' => 'Albania'],
            'DZ' => ['pl' => 'Algieria', 'en' => 'Algeria'],
            'AS' => ['pl' => 'Samoa Amerykańskie', 'en' => 'American Samoa'],
            'AD' => ['pl' => 'Andora', 'en' => 'Andorra'],
            'AI' => ['pl' => 'Anguilla', 'en' => 'Anguilla'],
            'AQ' => ['pl' => 'Antarktyda', 'en' => 'Antarctica'],
            'AG' => ['pl' => 'Antigua i Barbuda', 'en' => 'Antigua and Barbuda'],
            'AR' => ['pl' => 'Argentyna', 'en' => 'Argentina'],
            'AM' => ['pl' => 'Armenia', 'en' => 'Armenia'],
            'AW' => ['pl' => 'Aruba', 'en' => 'Aruba'],
            'AU' => ['pl' => 'Australia', 'en' => 'Australia'],
            'AT' => ['pl' => 'Austria', 'en' => 'Austria'],
            'AZ' => ['pl' => 'Azerbejdżan', 'en' => 'Azerbaijan'],
            'BS' => ['pl' => 'Bahamy', 'en' => 'Bahamas'],
            'BH' => ['pl' => 'Bahrajn', 'en' => 'Bahrain'],
            'BD' => ['pl' => 'Bangladesz', 'en' => 'Bangladesh'],
            'BB' => ['pl' => 'Barbados', 'en' => 'Barbados'],
            'BY' => ['pl' => 'Białoruś', 'en' => 'Belarus'],
            'BE' => ['pl' => 'Belgia', 'en' => 'Belgium'],
            'BZ' => ['pl' => 'Belize', 'en' => 'Belize'],
            'BJ' => ['pl' => 'Benin', 'en' => 'Benin'],
            'BM' => ['pl' => 'Bermudy', 'en' => 'Bermuda'],
            'BT' => ['pl' => 'Bhutan', 'en' => 'Bhutan'],
            'BO' => ['pl' => 'Boliwia', 'en' => 'Bolivia'],
            'BA' => ['pl' => 'Bośnia i Hercegowina', 'en' => 'Bosnia and Herzegovina'],
            'BW' => ['pl' => 'Botswana', 'en' => 'Botswana'],
            'BV' => ['pl' => 'Wyspa Bouveta', 'en' => 'Bouvet Island'],
            'BR' => ['pl' => 'Brazylia', 'en' => 'Brazil'],
            'IO' => ['pl' => 'Terytorium Brytyjskie Oceanu Indyjskiego', 'en' => 'British Indian Ocean Territory'],
            'BN' => ['pl' => 'Brunei', 'en' => 'Brunei'],
            'BG' => ['pl' => 'Bułgaria', 'en' => 'Bulgaria'],
            'BF' => ['pl' => 'Burkina Faso', 'en' => 'Burkina Faso'],
            'BI' => ['pl' => 'Burundi', 'en' => 'Burundi'],
            'KH' => ['pl' => 'Kambodża', 'en' => 'Cambodia'],
            'CM' => ['pl' => 'Kamerun', 'en' => 'Cameroon'],
            'CA' => ['pl' => 'Kanada', 'en' => 'Canada'],
            'CV' => ['pl' => 'Republika Zielonego Przylądka', 'en' => 'Cape Verde'],
            'KY' => ['pl' => 'Kajmany', 'en' => 'Cayman Islands'],
            'CF' => ['pl' => 'Republika Środkowoafrykańska', 'en' => 'Central African Republic'],
            'TD' => ['pl' => 'Czad', 'en' => 'Chad'],
            'CL' => ['pl' => 'Chile', 'en' => 'Chile'],
            'CN' => ['pl' => 'Chiny', 'en' => 'China'],
            'CX' => ['pl' => 'Wyspa Bożego Narodzenia', 'en' => 'Christmas Island'],
            'CC' => ['pl' => 'Wyspy Kokosowe', 'en' => 'Cocos (Keeling) Islands'],
            'CO' => ['pl' => 'Kolumbia', 'en' => 'Colombia'],
            'KM' => ['pl' => 'Komory', 'en' => 'Comoros'],
            'CG' => ['pl' => 'Kongo', 'en' => 'Congo'],
            'CD' => ['pl' => 'Demokratyczna Republika Konga', 'en' => 'Democratic Republic of Congo'],
            'CK' => ['pl' => 'Wyspy Cooka', 'en' => 'Cook Islands'],
            'CR' => ['pl' => 'Kostaryka', 'en' => 'Costa Rica'],
            'CI' => ['pl' => 'Wybrzeże Kości Słoniowej', 'en' => 'Côte dIvoire'],
            'HR' => ['pl' => 'Chorwacja', 'en' => 'Croatia'],
            'CU' => ['pl' => 'Kuba', 'en' => 'Cuba'],
            'CY' => ['pl' => 'Cypr', 'en' => 'Cyprus'],
            'DK' => ['pl' => 'Dania', 'en' => 'Denmark'],
            'DJ' => ['pl' => 'Dżibuti', 'en' => 'Djibouti'],
            'DM' => ['pl' => 'Dominika', 'en' => 'Dominica'],
            'DO' => ['pl' => 'Dominikana', 'en' => 'Dominican Republic'],
            'EC' => ['pl' => 'Ekwador', 'en' => 'Ecuador'],
            'EG' => ['pl' => 'Egipt', 'en' => 'Egypt'],
            'SV' => ['pl' => 'Salwador', 'en' => 'El Salvador'],
            'GQ' => ['pl' => 'Gwinea Równikowa', 'en' => 'Equatorial Guinea'],
            'ER' => ['pl' => 'Erytrea', 'en' => 'Eritrea'],
            'EE' => ['pl' => 'Estonia', 'en' => 'Estonia'],
            'ET' => ['pl' => 'Etiopia', 'en' => 'Ethiopia'],
            'FK' => ['pl' => 'Falklandy (Malwiny)', 'en' => 'Falkland Islands (Malvinas)'],
            'FO' => ['pl' => 'Wyspy Owcze', 'en' => 'Faroe Islands'],
            'FJ' => ['pl' => 'Fidżi', 'en' => 'Fiji'],
            'FI' => ['pl' => 'Finlandia', 'en' => 'Finland'],
            'FR' => ['pl' => 'Francja', 'en' => 'France'],
            'GF' => ['pl' => 'Gujana Francuska', 'en' => 'French Guiana'],
            'PF' => ['pl' => 'Polinezja Francuska', 'en' => 'French Polynesia'],
            'TF' => ['pl' => 'Francuskie Terytoria Południowe i Antarktyczne', 'en' => 'French Southern Territories'],
            'GA' => ['pl' => 'Gabon', 'en' => 'Gabon'],
            'GM' => ['pl' => 'Gambia', 'en' => 'Gambia'],
            'GE' => ['pl' => 'Gruzja', 'en' => 'Georgia'],
            'GH' => ['pl' => 'Ghana', 'en' => 'Ghana'],
            'GI' => ['pl' => 'Gibraltar', 'en' => 'Gibraltar'],
            'GR' => ['pl' => 'Grecja', 'en' => 'Greece'],
            'GL' => ['pl' => 'Grenlandia', 'en' => 'Greenland'],
            'GD' => ['pl' => 'Grenada', 'en' => 'Grenada'],
            'GP' => ['pl' => 'Gwadelupa', 'en' => 'Guadeloupe'],
            'GU' => ['pl' => 'Guam', 'en' => 'Guam'],
            'GT' => ['pl' => 'Gwatemala', 'en' => 'Guatemala'],
            'GG' => ['pl' => 'Guernsey', 'en' => 'Guernsey'],
            'GN' => ['pl' => 'Gwinea', 'en' => 'Guinea'],
            'GW' => ['pl' => 'Gwinea Bissau', 'en' => 'Guinea-Bissau'],
            'GY' => ['pl' => 'Gujana', 'en' => 'Guyana'],
            'HT' => ['pl' => 'Haiti', 'en' => 'Haiti'],
            'HM' => ['pl' => 'Wyspy Heard i McDonalda', 'en' => 'Heard Island and McDonald Islands'],
            'VA' => ['pl' => 'Watykan', 'en' => 'Holy See (Vatican City State)'],
            'HN' => ['pl' => 'Honduras', 'en' => 'Honduras'],
            'HK' => ['pl' => 'Hongkong', 'en' => 'Hong Kong'],
            'HU' => ['pl' => 'Węgry', 'en' => 'Hungary'],
            'IS' => ['pl' => 'Islandia', 'en' => 'Iceland'],
            'IN' => ['pl' => 'Indie', 'en' => 'India'],
            'ID' => ['pl' => 'Indonezja', 'en' => 'Indonesia'],
            'IR' => ['pl' => 'Iran', 'en' => 'Iran'],
            'IQ' => ['pl' => 'Irak', 'en' => 'Iraq'],
            'IE' => ['pl' => 'Irlandia', 'en' => 'Ireland'],
            'IM' => ['pl' => 'Wyspa Man', 'en' => 'Isle of Man'],
            'IL' => ['pl' => 'Izrael', 'en' => 'Israel'],
            'IT' => ['pl' => 'Włochy', 'en' => 'Italy'],
            'JM' => ['pl' => 'Jamajka', 'en' => 'Jamaica'],
            'JP' => ['pl' => 'Japonia', 'en' => 'Japan'],
            'JE' => ['pl' => 'Jersey', 'en' => 'Jersey'],
            'JO' => ['pl' => 'Jordania', 'en' => 'Jordan'],
            'KZ' => ['pl' => 'Kazachstan', 'en' => 'Kazakhstan'],
            'KE' => ['pl' => 'Kenia', 'en' => 'Kenya'],
            'KI' => ['pl' => 'Kiribati', 'en' => 'Kiribati'],
            'KP' => ['pl' => 'Korea Północna', 'en' => 'North Korea'],
            'KR' => ['pl' => 'Korea Południowa', 'en' => 'South Korea'],
            'KW' => ['pl' => 'Kuwejt', 'en' => 'Kuwait'],
            'KG' => ['pl' => 'Kirgistan', 'en' => 'Kyrgyzstan'],
            'LA' => ['pl' => 'Laos', 'en' => 'Laos'],
            'LV' => ['pl' => 'Łotwa', 'en' => 'Latvia'],
            'LB' => ['pl' => 'Liban', 'en' => 'Lebanon'],
            'LS' => ['pl' => 'Lesotho', 'en' => 'Lesotho'],
            'LR' => ['pl' => 'Liberia', 'en' => 'Liberia'],
            'LY' => ['pl' => 'Libia', 'en' => 'Libya'],
        ];
    }
}

