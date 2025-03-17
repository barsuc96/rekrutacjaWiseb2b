<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108091755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Truncate and add ContractTypeDictionary';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE contract_type_dictionary;');

        $this->addSql("
        INSERT INTO contract_type_dictionary (id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, payload_bag, id_external, name, symbol) VALUES
        (1, null, '2024-12-03 06:32:33', '2024-12-03 06:32:33', null, 0, null, null, 'Regulamin', 'RULES'),
        (2, null, '2024-12-03 06:33:12', '2024-12-03 06:33:12', null, 0, null, null, 'RODO', 'RODO'),
        (3, null, '2024-12-03 06:33:40', '2024-12-03 06:33:40', null, 0, null, null, 'Newsletter', 'NEWSLETTER'),
        (4, null, '2025-01-08 09:14:08', '2025-01-08 09:19:13', 'a61b8bafcae00b4ea5f50ab8f14afcc9701d53afeee7a18fdeb811a021340662', 0, null, null, 'Faktury Online', 'ONLINE_INVOICE');
        ");

        $this->addSql("SELECT setval('contract_type_dictionary_id_seq', 4, true);");

        $this->addSql('TRUNCATE TABLE contract;');

        $this->addSql("
            INSERT INTO contract (id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, payload_bag, id_external, requirement, impact, contexts, symbol, type, roles, status, from_date, to_date, deprecated_date, in_active_date, dtype) VALUES
            (3, true, '2025-01-08 09:43:55', '2025-01-08 09:43:55', null, 0, null, null, 3, 3, 'CHECKOUT', 'INVOICE_ONLINE', 'ONLINE_INVOICE', 'ROLE_USER_MAIN;ROLE_USER;ROLE_ADMIN', 2, null, null, null, null, 'contract'),
            (2, true, '2025-01-08 09:36:40', '2025-01-08 09:36:40', null, 0, null, null, 3, 2, 'CHECKOUT', 'NEWSLETTER', 'NEWSLETTER', 'ROLE_USER_MAIN;ROLE_USER;ROLE_ADMIN', 2, null, null, null, null, 'contract'),
            (1, true, '2025-01-08 09:24:45', '2025-01-08 09:29:12', '03d768459ad7aa8210f7d4310135c4cda8e58d3dfe57a63e2d6e062f08334be3', 2, null, null, 1, 2, 'HOME_PAGE', 'REGULAMIN', 'RULES', 'ROLE_USER_MAIN;ROLE_USER;ROLE_ADMIN', 2, null, null, null, null, 'contract');

        ");

        $this->addSql("SELECT setval('contract_id_seq', 4, true);");


        $this->addSql('TRUNCATE TABLE contract_translation;');

        $this->addSql("
INSERT INTO contract_translation (id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, payload_bag, contract_id, language, name, content, testimony) VALUES
(4, true, '2025-01-08 09:36:40', '2025-01-08 09:36:40', null, 0, null, 2, 'pl', 'Zgoda na newsletter', '<div>   <h2>Oświadczenie zgody na przetwarzanie danych osobowych</h2>   <p>     Wyrażam zgodę na przetwarzanie moich danych osobowych przez <strong>[Nazwa firmy]</strong> z siedzibą w <strong>[adres firmy]</strong> w następujących celach:   </p>   <h4>1. Otrzymywanie newslettera</h4>   <p>     <em>Przetwarzanie moich danych osobowych w celu wysyłania informacji o nowych produktach, usługach, promocjach i wydarzeniach związanych z działalnością [Nazwa firmy].</em>   </p>   <h4>2. Analizy statystyczne</h4>   <p>     <em>Przeprowadzanie analiz rynkowych oraz badanie efektywności działań marketingowych w celu lepszego dostosowania oferty do moich potrzeb.</em>   </p>   <h4>3. Personalizacja treści</h4>   <p>     <em>Dostosowywanie treści marketingowych w oparciu o moje preferencje oraz historię interakcji z [Nazwa firmy].</em>   </p>   <p>     Dane będą przetwarzane zgodnie z <strong>Rozporządzeniem Parlamentu Europejskiego i Rady (UE) 2016/679 z dnia 27 kwietnia 2016 r. (RODO)</strong> oraz innymi obowiązującymi przepisami dotyczącymi ochrony danych osobowych.   </p>   <h4>Prawo do wycofania zgody</h4>   <p>     Zgoda jest <strong>dobrowolna</strong>, a jej brak nie wpłynie na możliwość korzystania z innych usług oferowanych przez [Nazwa firmy]. Mogę w każdej chwili wycofać swoją zgodę, kontaktując się z administratorem danych za pośrednictwem:     <ul>       <li>Adresu e-mail: <strong><a href=\"mailto:kontakt@firma.pl\">kontakt@firma.pl</a></strong></li>       <li>Numeru telefonu: <strong>[Numer telefonu]</strong></li>     </ul>   </p>   <p>     Więcej informacji na temat przetwarzania danych oraz przysługujących mi praw znajduje się w <strong><a href=\"/polityka-prywatnosci\" target=\"_blank\">Polityce Prywatności</a></strong>.   </p> </div>', 'Wyrażam zgodę na przetwarzanie moich danych osobowych w celu otrzymywania newslettera oraz informacji marketingowych.'),
(6, true, '2025-01-08 09:43:55', '2025-01-08 09:43:55', null, 0, null, 3, 'pl', 'Faktura elektroniczna', ' ', 'Wyrażam zgodę na otrzymywanie faktur elektronicznych'),
(2, true, '2025-01-08 09:24:45', '2025-01-08 09:29:12', null, 0, null, 1, 'pl', 'Regulamin', ' ', 'Oświadczam, że znam i akceptuję postanowienia <a href=\"https://test.wiseb2b.pl/pl/cms/SUBPAGE/REGULAMIN\">Regulaminu</a>.'),
(1, true, '2025-01-08 09:24:45', '2025-01-08 09:24:45', null, 0, null, 1, 'en', 'Terms and Conditions', ' ', 'I declare that I know and accept the provisions of the <a href=\"https://test.wiseb2b.pl/pl/cms/SUBPAGE/REGULAMIN\">Regulations</a>.'),
(5, true, '2025-01-08 09:43:55', '2025-01-08 09:43:55', null, 0, null, 3, 'en', 'Electronic invoice', '', 'I agree to receive electronic invoices'),
(3, true, '2025-01-08 09:36:40', '2025-01-08 09:36:40', null, 0, null, 2, 'en', 'Consent to newsletter', '<div>   <h2>Declaration of Consent for Data Processing</h2>   <p>     I consent to the processing of my personal data by <strong>[Company Name]</strong>, located at <strong>[Company Address]</strong>, for the following purposes:   </p>   <h4>1. Receiving the Newsletter</h4>   <p>     <em>The processing of my personal data to send information about new products, services, promotions, and events related to the activities of [Company Name].</em>   </p>   <h4>2. Statistical Analysis</h4>   <p>     <em>Conducting market research and evaluating the effectiveness of marketing activities to better tailor the company''s offer to my needs.</em>   </p>   <h4>3. Content Personalization</h4>   <p>     <em>Customizing marketing content based on my preferences and interaction history with [Company Name].</em>   </p>   <p>     The data will be processed in accordance with the <strong>General Data Protection Regulation (GDPR) – Regulation (EU) 2016/679</strong> and other applicable data protection laws.   </p>   <h4>Right to Withdraw Consent</h4>   <p>     The consent is <strong>voluntary</strong>, and its absence will not affect my ability to use other services offered by [Company Name]. I can withdraw my consent at any time by contacting the data controller via:     <ul>       <li>Email: <strong><a href=\"mailto:contact@company.com\">contact@company.com</a></strong></li>       <li>Phone: <strong>[Phone Number]</strong></li>     </ul>   </p>   <p>     More information about data processing and my rights can be found in the <strong><a href=\"/privacy-policy\" target=\"_blank\">Privacy Policy</a></strong>.   </p> </div>', 'I consent to the processing of my personal data for the purpose of receiving newsletters and marketing information.');
        ");

        $this->addSql("SELECT setval('contract_translation_id_seq', 7, true);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
