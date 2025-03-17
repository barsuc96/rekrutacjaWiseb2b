<?php

namespace Wise\Core\Generator\Generators\EndpointFileGenerator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Wise\Cart\Domain\Cart\Cart;
use Wise\Core\Generator\Abstract\AbstractGeneratorFile;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;

class EndpointFileGenerator extends AbstractGeneratorFile
{
    protected const CONTROLLER = 'Controller';
    protected const DTO = 'Dto';
    protected const SERVICE = 'Service';
    protected const INTERFACE = 'Interface';
    protected const EXTENSION = '.php';

    protected string $name = 'ENDPOINT';
    protected string $description = 'Generowanie plików endpointów';

    public function __construct(
        private readonly ConfigServiceInterface $configService,
        private readonly Environment $twig,
        private readonly EntityManagerInterface $entityManager
    ){}

    function generateFiles(InputInterface $input, OutputInterface $output, mixed $helper): void
    {
        // Wybór typu endpointu (ApiUi, ApiAdmin)
        $output->writeln('');
        $typeEndpoint = $this->getTypeEndpoint($input, $output, $helper);

        // Wybór metody endpointu (Get, Post, Put, Delete)
        $output->writeln('');
        $methodEndpoint = $this->getMethodEndpoint($input, $output, $helper, $typeEndpoint);

        // Wybór modułu dla którego chcemy utworzyć endpoint
        $output->writeln('');
        $module = $this->getModuleToCreateEndpoint($input, $output, $helper);

        // Wskazanie podkatalogu w których zostaną utworzone pliki
        $output->writeln('');
        $dirName = $this->askForDirName($input, $output, $helper, $typeEndpoint, $module);

        // Nazwa endpointu
        $output->writeln('');
        $name = $this->getNameEndpoint($input, $output, $helper);

        // Czy wygenerować puste pliki, czy w dto umieścić pola z domen
        $output->writeln('');
        $chosenOptionGenerate = $this->askForOptionGenerate($input, $output, $helper);

        $domainsFields = [];
        $domain = '';
        $domainFields = [];
        if($chosenOptionGenerate === 'W dto umieścić pola z domen'){
            $domainsInformation = $this->getDomains($module);

            $domain = $this->askForDomainInformation($input, $output, $helper, $domainsInformation);
            if(!empty($domain) && class_exists($domain)){
                $domainFields = $this->askForDomainFields($input, $output, $helper, $domain, $domainsInformation['domains']);
            }else{
                $output->writeln('Wybrana domena jest niepoprawna');
            }
        }

        $this->generateFilesByInformation($output, $typeEndpoint, $methodEndpoint, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields);

        $output->writeln('Typ endpointu: ' . $typeEndpoint);
        $output->writeln('Metoda endpointu: ' . $methodEndpoint);
        $output->writeln('Moduł: ' . $module);
        $output->writeln('Nazwa: ' . $name);
        $output->writeln('Podkatalog: ' . $dirName);
        $output->writeln('Opcja generowania: ' . $chosenOptionGenerate);
        $output->writeln('Domena: ' . $domain);
        $output->writeln('Wybrane pola z domeny: ' . implode(', ', $domainFields));
    }

    public function generate()
    {
//
    }

    /**
     * Generowanie plików na podstawie informacji
     * @param OutputInterface|null $output
     * @param string|null $typeEndpoint
     * @param string|null $methodEndpoint
     * @param string|null $module
     * @param string|null $name
     * @param string|null $dirName
     * @param string|null $chosenOptionGenerate
     * @param string|null $domain
     * @param array|null $domainFields
     */
    protected function generateFilesByInformation(?OutputInterface $output, ?string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        if($typeEndpoint === 'ApiUi'){
            $this->generateApiUiFiles($output, $typeEndpoint, $methodEndpoint, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields);
        } elseif($typeEndpoint === 'ApiAdmin'){
            $this->generateApiAdminFiles($output, $typeEndpoint, $methodEndpoint, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields);
        }
    }

    /**
     * @param OutputInterface|null $output
     * @param string $typeEndpoint
     * @param string|null $methodEndpoint
     * @param string|null $module
     * @param string|null $name
     * @param string|null $dirName
     * @param string|null $chosenOptionGenerate
     * @param string|null $domain
     * @param array|null $domainFields
     * @return void
     */
    protected function generateApiUiFiles(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        if($methodEndpoint === 'ALL'){
            $methods = ['GetList', 'GetDetails', 'Post', 'Put', 'Delete'];
        } else {
            $methods = [$methodEndpoint];
        }

        foreach ($methods as $method) {
            match($method){
                'GetList' => $this->generateApiUiFilesByMethodGetList($output, $typeEndpoint, $method, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields),
                'GetDetails' => $this->generateApiUiFilesByMethodGetDetails($output, $typeEndpoint, $method, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields),
                'Post' => $this->generateApiUiFilesByMethodPost($output, $typeEndpoint, $method, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields),
                'Put' => $this->generateApiUiFilesByMethodPut($output, $typeEndpoint, $method, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields),
                'Delete' => $this->generateApiUiFilesByMethodDelete($output, $typeEndpoint, $method, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields),
            };
        }
    }

    protected function generateApiUiFilesByMethodGetList(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $dirBackend = $this->configService->get('backend_dictionary');
        $methodEndpoint = 'Get';
        $name = $name .'s';

        // Controller
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $pluralFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['plural'] . static::DTO;
        $namespaceInterface =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName . '\\' . static::INTERFACE . 's';
        $fileNameServiceInterface = $methodEndpoint . $name . static::SERVICE . static::INTERFACE;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'GetListUiApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $pluralFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );

        // Dto - GetList
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::DTO. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::DTO . '\\' . $dirName;

        $this->generateFile(
            path: $path . '/' . $pluralFileNameDto . static::EXTENSION,
            templateName: 'GetListUiApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $pluralFileNameDto,
                'singularClass' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
            ]
        );


        // Dto - GetDetails
        $this->generateFile(
            path: $path . '/' . $singularFileNameDto . static::EXTENSION,
            templateName: 'GetDetailsUiApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
                'fields' => $this->getStraightFieldsFromDomain($domain, $domainFields)
            ]
        );

        $this->addDtoToNelmioApiDoc(
            module: $module ?? 'default',
            namespace: $namespace . '\\' . $pluralFileNameDto,
            typeEndpoint: $typeEndpoint
        );


        // Service

        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName;
        $pathInterface = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName . '/' . static::INTERFACE . 's';
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName;
        $fileNameService = $methodEndpoint . $name . static::SERVICE;

        if(!empty($domain)){
            $domain = explode('\\',$domain);
            $domain = !empty($domain) ? end($domain) : 'DOMAIN';
        }else{
            $domain = 'DOMAIN';
        }

        $this->generateFile(
            path: $pathInterface . '/' . $fileNameServiceInterface . static::EXTENSION,
            templateName: 'GetListUiApiServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespaceInterface,
                'interfaceName' => $fileNameServiceInterface,
            ]
        );

        $this->generateFile(
            path: $path . '/' . $fileNameService . static::EXTENSION,
            templateName: 'GetListUiApiServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileNameService,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
                'applicationInterface' => 'List' . $domain . 'ServiceInterface',
            ]
        );

    }
    protected function generateApiUiFilesByMethodGetDetails(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $dirBackend = $this->configService->get('backend_dictionary');
        $methodEndpoint = 'Get';

        // Controller
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $namespaceInterface =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName . '\\' . static::INTERFACE . 's';
        $fileNameServiceInterface = $methodEndpoint . $name . static::SERVICE . static::INTERFACE;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'GetDetailsUiApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $singularFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );

        // Dto - GetDetails
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::DTO. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::DTO . '\\' . $dirName;

        $fields = $this->getStraightFieldsFromDomain($domain, $domainFields);
        if(empty($fields)){
            $fields['id'] = [
                'type' => 'int',
                'allowNull' => false,
                'name' => 'id',
                'nameU' => 'Id',
                'description' => 'Identyfikator'
            ];
        }

        // Dto - GetDetails
        $this->generateFile(
            path: $path . '/' . $singularFileNameDto . static::EXTENSION,
            templateName: 'GetDetailsUiApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
                'fields' => $fields
            ]
        );

        $this->addDtoToNelmioApiDoc(
            module: $module ?? 'default',
            namespace: $namespace . '\\' . $singularFileNameDto,
            typeEndpoint: $typeEndpoint
        );


        // Service

        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName;
        $pathInterface = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName . '/' . static::INTERFACE . 's';
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName;
        $fileNameService = $methodEndpoint . $name . static::SERVICE;

        if(!empty($domain)){
            $domain = explode('\\',$domain);
            $domain = !empty($domain) ? end($domain) : 'DOMAIN';
        }else{
            $domain = 'DOMAIN';
        }

        $this->generateFile(
            path: $pathInterface . '/' . $fileNameServiceInterface . static::EXTENSION,
            templateName: 'GetDetailsUiApiServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespaceInterface,
                'interfaceName' => $fileNameServiceInterface,
            ]
        );

        $this->generateFile(
            path: $path . '/' . $fileNameService . static::EXTENSION,
            templateName: 'GetDetailsUiApiServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileNameService,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
                'applicationInterface' => 'List' . $domain . 'ServiceInterface',
            ]
        );
    }

    protected function generateApiUiFilesByMethodPost(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $dirBackend = $this->configService->get('backend_dictionary');
        $methodEndpoint = 'Post';

        // Controller
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $namespaceInterface =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName . '\\' . static::INTERFACE . 's';
        $fileNameServiceInterface = $methodEndpoint . $name . static::SERVICE . static::INTERFACE;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'PostUiApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $singularFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );

        // Dto - GetList
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::DTO. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::DTO . '\\' . $dirName;


        if(!empty($domainFields)){
            $domainFields = array_filter($domainFields, function($field){
                return $field !== 'id';
            });
        }
        $fields = $this->getStraightFieldsFromDomain($domain, $domainFields);

        // Dto - GetDetails
        $this->generateFile(
            path: $path . '/' . $singularFileNameDto . static::EXTENSION,
            templateName: 'PostUiApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
                'fields' => $fields
            ]
        );

        $this->addDtoToNelmioApiDoc(
            module: $module ?? 'default',
            namespace: $namespace . '\\' . $singularFileNameDto,
            typeEndpoint: $typeEndpoint
        );


        // Service

        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName;
        $pathInterface = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName . '/' . static::INTERFACE . 's';
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName;
        $fileNameService = $methodEndpoint . $name . static::SERVICE;

        if(!empty($domain)){
            $domain = explode('\\',$domain);
            $domain = !empty($domain) ? end($domain) : 'DOMAIN';
        }else{
            $domain = 'DOMAIN';
        }

        $this->generateFile(
            path: $pathInterface . '/' . $fileNameServiceInterface . static::EXTENSION,
            templateName: 'PostUiApiServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespaceInterface,
                'interfaceName' => $fileNameServiceInterface,
            ]
        );

        $this->generateFile(
            path: $path . '/' . $fileNameService . static::EXTENSION,
            templateName: 'PostUiApiServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileNameService,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
                'applicationInterface' => 'AddOrModify' . $domain . 'ServiceInterface',
            ]
        );
        $fsd = 'sdfsdf';
    }

    protected function generateApiUiFilesByMethodPut(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $dirBackend = $this->configService->get('backend_dictionary');
        $methodEndpoint = 'Put';

        // Controller
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $namespaceInterface =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName . '\\' . static::INTERFACE . 's';
        $fileNameServiceInterface = $methodEndpoint . $name . static::SERVICE . static::INTERFACE;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'PutUiApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $singularFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );

        // Dto - Put
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::DTO. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::DTO . '\\' . $dirName;

        $fields = $this->getStraightFieldsFromDomain($domain, $domainFields);
        if(empty($fields)){
            $fields['id'] = [
                'type' => 'int',
                'allowNull' => false,
                'name' => 'id',
                'nameU' => 'Id',
                'description' => 'Identyfikator'
            ];
        }

        $this->generateFile(
            path: $path . '/' . $singularFileNameDto . static::EXTENSION,
            templateName: 'PutUiApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
                'fields' => $fields
            ]
        );

        $this->addDtoToNelmioApiDoc(
            module: $module ?? 'default',
            namespace: $namespace . '\\' . $singularFileNameDto,
            typeEndpoint: $typeEndpoint
        );


        // Service
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName;
        $pathInterface = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName . '/' . static::INTERFACE . 's';
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName;
        $fileNameService = $methodEndpoint . $name . static::SERVICE;

        if(!empty($domain)){
            $domain = explode('\\',$domain);
            $domain = !empty($domain) ? end($domain) : 'DOMAIN';
        }else{
            $domain = 'DOMAIN';
        }

        $this->generateFile(
            path: $pathInterface . '/' . $fileNameServiceInterface . static::EXTENSION,
            templateName: 'PutUiApiServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespaceInterface,
                'interfaceName' => $fileNameServiceInterface,
            ]
        );

        $this->generateFile(
            path: $path . '/' . $fileNameService . static::EXTENSION,
            templateName: 'PutUiApiServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileNameService,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
                'applicationInterface' => 'AddOrModify' . $domain . 'ServiceInterface',
            ]
        );
        $fsd = 'sdfsdf';
    }

    protected function generateApiUiFilesByMethodDelete(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $dirBackend = $this->configService->get('backend_dictionary');
        $methodEndpoint = 'Delete';

        // Controller
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $namespaceInterface =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName . '\\' . static::INTERFACE . 's';
        $fileNameServiceInterface = $methodEndpoint . $name . static::SERVICE . static::INTERFACE;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'DeleteUiApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $singularFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );


        // Dto - Delete
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::DTO. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::DTO . '\\' . $dirName;

        $this->generateFile(
            path: $path . '/' . $singularFileNameDto . static::EXTENSION,
            templateName: 'DeleteUiApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
                'fields' => []
            ]
        );

        $this->addDtoToNelmioApiDoc(
            module: $module ?? 'default',
            namespace: $namespace . '\\' . $singularFileNameDto,
            typeEndpoint: $typeEndpoint
        );


        // Service
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName;
        $pathInterface = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName . '/' . static::INTERFACE . 's';
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName;
        $fileNameService = $methodEndpoint . $name . static::SERVICE;

        if(!empty($domain)){
            $domain = explode('\\',$domain);
            $domain = !empty($domain) ? end($domain) : 'DOMAIN';
        }else{
            $domain = 'DOMAIN';
        }

        $this->generateFile(
            path: $pathInterface . '/' . $fileNameServiceInterface . static::EXTENSION,
            templateName: 'DeleteUiApiServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespaceInterface,
                'interfaceName' => $fileNameServiceInterface,
            ]
        );

        $this->generateFile(
            path: $path . '/' . $fileNameService . static::EXTENSION,
            templateName: 'DeleteUiApiServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileNameService,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
                'applicationInterface' => 'Remove' . $domain . 'ServiceInterface',
            ]
        );
    }

    /**
     *
     * @param OutputInterface|null $output
     * @param string $typeEndpoint
     * @param string|null $methodEndpoint
     * @param string|null $module
     * @param string|null $name
     * @param string|null $dirName
     * @param string|null $chosenOptionGenerate
     * @param string|null $domain
     * @param array|null $domainFields
     * @return void
     */
    protected function generateApiAdminFiles(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        if($methodEndpoint === 'ALL'){
            $methods = ['GetList', 'PutAndPatch', 'Delete'];
        } else {
            $methods = [$methodEndpoint];
        }

        foreach ($methods as $method) {
            match($method){
                'GetList' => $this->generateApiAdminFilesByMethodGetList($output, $typeEndpoint, $method, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields),
                'PutAndPatch' => $this->generateApiAdminFilesByMethodPutAndPatch($output, $typeEndpoint, $method, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields),
                'Delete' => $this->generateApiAdminFilesByMethodDelete($output, $typeEndpoint, $method, $module, $name, $dirName, $chosenOptionGenerate, $domain, $domainFields),
            };
        }
    }

    protected function generateApiAdminFilesByMethodGetList(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $dirBackend = $this->configService->get('backend_dictionary');
        $methodEndpoint = 'Get';
        $name = $name .'s';

        // Controller
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $pluralFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['plural'] . static::DTO;
        $namespaceInterface =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName . '\\' . static::INTERFACE . 's';
        $fileNameServiceInterface = $methodEndpoint . $name . static::SERVICE . static::INTERFACE;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'GetListAdminApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $pluralFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );

        // Dto - GetList
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::DTO. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::DTO . '\\' . $dirName;

        $this->generateFile(
            path: $path . '/' . $pluralFileNameDto . static::EXTENSION,
            templateName: 'GetListAdminApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $pluralFileNameDto,
                'singularClass' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
            ]
        );

        if(!empty($domainFields)){
            $domainFields = array_filter($domainFields, function($field){
                return $field !== 'id' && $field !== 'idExternal';
            });
        }
        $fields = $this->getStraightFieldsFromDomain($domain, $domainFields);


        // Dto - GetDetails
        $this->generateFile(
            path: $path . '/' . $singularFileNameDto . static::EXTENSION,
            templateName: 'GetDetailsAdminApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
                'fields' => $fields
            ]
        );

        $this->addDtoToNelmioApiDoc(
            module: $module ?? 'default',
            namespace: $namespace . '\\' . $pluralFileNameDto,
            typeEndpoint: $typeEndpoint
        );


        // Service

        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName;
        $pathInterface = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName . '/' . static::INTERFACE . 's';
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName;
        $fileNameService = $methodEndpoint . $name . static::SERVICE;

        if(!empty($domain)){
            $domain = explode('\\',$domain);
            $domain = !empty($domain) ? end($domain) : 'DOMAIN';
        }else{
            $domain = 'DOMAIN';
        }

        $this->generateFile(
            path: $pathInterface . '/' . $fileNameServiceInterface . static::EXTENSION,
            templateName: 'GetListAdminApiServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespaceInterface,
                'interfaceName' => $fileNameServiceInterface,
            ]
        );

        $this->generateFile(
            path: $path . '/' . $fileNameService . static::EXTENSION,
            templateName: 'GetListAdminApiServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileNameService,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
                'applicationInterface' => 'List' . $domain . 'ServiceInterface',
            ]
        );
    }

    protected function generateApiAdminFilesByMethodPutAndPatch(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $dirBackend = $this->configService->get('backend_dictionary');
        $methodEndpoint = 'Put';

        // Controller
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $pluralFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['plural'] . static::DTO;
        $namespaceInterface =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName . '\\' . static::INTERFACE . 's';
        $fileNameServiceInterface = $methodEndpoint . $name . static::SERVICE . static::INTERFACE;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'PutAdminApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $pluralFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );

        // Dto - List
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::DTO. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::DTO . '\\' . $dirName;

        $this->generateFile(
            path: $path . '/' . $pluralFileNameDto . static::EXTENSION,
            templateName: 'PutListAdminApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $pluralFileNameDto,
                'singularClass' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
            ]
        );

        if(!empty($domainFields)){
            $domainFields = array_filter($domainFields, function($field){
                return $field !== 'id' && $field !== 'idExternal';
            });
        }
        $fields = $this->getStraightFieldsFromDomain($domain, $domainFields);


        // Dto - Single
        $this->generateFile(
            path: $path . '/' . $singularFileNameDto . static::EXTENSION,
            templateName: 'PutDetailsAdminApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
                'fields' => $fields
            ]
        );

        $this->addDtoToNelmioApiDoc(
            module: $module ?? 'default',
            namespace: $namespace . '\\' . $pluralFileNameDto,
            typeEndpoint: $typeEndpoint
        );


        // Service
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName;
        $pathInterface = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName . '/' . static::INTERFACE . 's';
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName;
        $fileNameService = $methodEndpoint . $name . static::SERVICE;

        if(!empty($domain)){
            $domain = explode('\\',$domain);
            $domain = !empty($domain) ? end($domain) : 'DOMAIN';
        }else{
            $domain = 'DOMAIN';
        }

        $this->generateFile(
            path: $pathInterface . '/' . $fileNameServiceInterface . static::EXTENSION,
            templateName: 'PutAdminApiServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespaceInterface,
                'interfaceName' => $fileNameServiceInterface,
            ]
        );

        $this->generateFile(
            path: $path . '/' . $fileNameService . static::EXTENSION,
            templateName: 'PutAdminApiServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileNameService,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
                'applicationInterface' => 'AddOrModify' . $domain . 'ServiceInterface',
            ]
        );

        // =================== PATCH ===================

        $methodEndpoint = 'Patch';
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $pluralFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['plural'] . static::DTO;
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'PatchAdminApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $pluralFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );

        // Dto - List
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::DTO. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::DTO . '\\' . $dirName;

        $this->generateFile(
            path: $path . '/' . $pluralFileNameDto . static::EXTENSION,
            templateName: 'PutListAdminApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $pluralFileNameDto,
                'singularClass' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
            ]
        );

        if(!empty($domainFields)){
            $domainFields = array_filter($domainFields, function($field){
                return $field !== 'id' && $field !== 'idExternal';
            });
        }
        $fields = $this->getStraightFieldsFromDomain($domain, $domainFields);


        // Dto - Single
        $this->generateFile(
            path: $path . '/' . $singularFileNameDto . static::EXTENSION,
            templateName: 'PutDetailsAdminApiDtoTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $singularFileNameDto,
                'dtoName' => $methodEndpoint . $name . static::DTO,
                'fields' => $fields
            ]
        );

        $this->addDtoToNelmioApiDoc(
            module: $module ?? 'default',
            namespace: $namespace . '\\' . $pluralFileNameDto,
            typeEndpoint: $typeEndpoint
        );
    }

    protected function generateApiAdminFilesByMethodDelete(?OutputInterface $output, string $typeEndpoint, ?string $methodEndpoint, ?string $module, ?string $name, ?string $dirName, ?string $chosenOptionGenerate, ?string $domain, ?array $domainFields): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $dirBackend = $this->configService->get('backend_dictionary');
        $methodEndpoint = 'Delete';

        // Controller
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::CONTROLLER. '/' . $dirName;
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::CONTROLLER . '\\' . $dirName;
        $fileName = $methodEndpoint . $name . static::CONTROLLER;
        $singularFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['singular'] . static::DTO;
        $pluralFileNameDto = $methodEndpoint . static::getSingularAndPlural($name)['plural'] . static::DTO;
        $namespaceInterface =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName . '\\' . static::INTERFACE . 's';
        $fileNameServiceInterface = $methodEndpoint . $name . static::SERVICE . static::INTERFACE;

        $this->generateFile(
            path: $path . '/' . $fileName . static::EXTENSION,
            templateName: 'DeleteAdminApiControllerTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileName,
                'tag' => $dirName . $module,
                'dtoName' => $pluralFileNameDto,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
            ]
        );

        // Service
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName;
        $pathInterface = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/' . static::SERVICE. '/' . $dirName . '/' . static::INTERFACE . 's';
        $namespace =                $dirBackend . '\\' . $module . '\\' . $typeEndpoint . '\\' . static::SERVICE . '\\' . $dirName;
        $fileNameService = $methodEndpoint . $name . static::SERVICE;

        if(!empty($domain)){
            $domain = explode('\\',$domain);
            $domain = !empty($domain) ? end($domain) : 'DOMAIN';
        }else{
            $domain = 'DOMAIN';
        }

        $this->generateFile(
            path: $pathInterface . '/' . $fileNameServiceInterface . static::EXTENSION,
            templateName: 'DeleteAdminApiServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespaceInterface,
                'interfaceName' => $fileNameServiceInterface,
            ]
        );

        $this->generateFile(
            path: $path . '/' . $fileNameService . static::EXTENSION,
            templateName: 'DeleteAdminApiServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'className' => $fileNameService,
                'interface' => $fileNameServiceInterface,
                'interfaceNamespace' => $namespaceInterface . '\\' . $fileNameServiceInterface,
                'applicationInterface' => 'Remove' . $domain . 'ServiceInterface',
            ]
        );
    }





    /**
     * Metoda generująca wszystkie katalogi
     * @param string $path
     * @return void
     */
    protected function generateAllDirectories(string $path): void
    {
        $directories = explode('/', $path);
        $path = '/';
        $i = 0;
        foreach ($directories as $directory) {
            $i++;
            if($directory === ''){
                continue;
            }

            $path .= $directory . '/';

            if($i < 2){
                continue;
            }

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chgrp($path, 'www-data');
            }
        }
    }

    /**
     * Pobiera od użytkownika informacje, jakiego typu endpointa chce wygenerować
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @return string
     */
    protected function getTypeEndpoint(InputInterface $input, OutputInterface $output, mixed $helper): string
    {
        $question = new ChoiceQuestion(
            'Jaki typ endpointu Cię interesuje?',
            ['ApiUi', 'ApiAdmin'],
            'ApiUi'
        );
        $question->setErrorMessage('Typ %s niepoprawny.');

        return $helper->ask($input, $output, $question);
    }

    /**
     * Pobiera od użytkownika informacje, jakiej metody endpointa chce wygenerować
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @param string $typeEndpoint
     * @return string
     */
    protected function getMethodEndpoint(InputInterface $input, OutputInterface $output, mixed $helper, string $typeEndpoint): string
    {
        $methods = [];
        if($typeEndpoint === 'ApiUi'){
            $methods = ['ALL', 'GetList', 'GetDetails', 'Post', 'Put', 'Delete'];
        } elseif($typeEndpoint === 'ApiAdmin'){
            $methods = ['ALL', 'GetList', 'PutAndPatch', 'Delete'];
        }

        $question = new ChoiceQuestion(
            'Jaka metoda endpointu Cię interesuje?',
            $methods,
            'GET'
        );
        $question->setErrorMessage('Metoda %s niepoprawna.');

        return $helper->ask($input, $output, $question);
    }

    /**
     * Pobiera od użytkownika informacje, dla jakiego modułu chce wygenerować endpoint
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @return string
     * @throws \Exception
     */
    protected function getModuleToCreateEndpoint(InputInterface $input, OutputInterface $output, mixed $helper): string
    {
        $question = new ChoiceQuestion(
            'W jakim module chcesz utworzyć endpoint?',
            $this->getModulesList(),
            'Core'
        );
        $question->setErrorMessage('Moduł %s niepoprawny.');

        return $helper->ask($input, $output, $question);
    }

    /**
     * Pobiera od użytkownika nazwę endpointa
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @return string
     * @throws \Exception
     */
    protected function getNameEndpoint(InputInterface $input, OutputInterface $output, mixed $helper): string
    {
        $question = new Question('Jak nazwać endpoint? (np. PanelManagementContract): ');

        return $helper->ask($input, $output, $question);
    }

    /**
     * Metoda wyświetla zapytanie do użytkownika o wybranie opcji generowania
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @return string
     * @throws \Exception
     */
    protected function askForOptionGenerate(InputInterface $input, OutputInterface $output, mixed $helper): string
    {
        $question = new ChoiceQuestion(
            'Wybierz opcje generowania (domyślnie: Wygeneruj puste pliki)',
            [
                'Wygeneruj puste pliki',
                'W dto umieścić pola z domen'
            ],
            'Wygeneruj puste pliki'
        );
        $question->setErrorMessage('Opcja %s niepoprawna.');

        return $helper->ask($input, $output, $question);
    }

    protected function askForDomainInformation(InputInterface $input, OutputInterface $output, mixed $helper, array $domainsInformation): string
    {
        $domainModule[] = 'Pobierz pełną listę domen';
        $domainModule = [...$domainModule, ...$domainsInformation['domainModule']];

        $question = new ChoiceQuestion(
            'Wybierz opcje generowania (domyślnie: Wygeneruj puste pliki)',
            $domainModule,
            'Wygeneruj puste pliki'
        );
        $question->setErrorMessage('Domena %s niepoprawna.');

        $chosenDomain =  $helper->ask($input, $output, $question);

        if($chosenDomain === 'Pobierz pełną listę domen'){
            $question = new ChoiceQuestion(
                'Wybierz domenę',
                $domainsInformation['domains'],
                'Wygeneruj puste pliki'
            );
            $question->setErrorMessage('Domena %s niepoprawna.');

            $chosenDomain =  $helper->ask($input, $output, $question);
            $chosenDomain = $domainsInformation['domains'][$chosenDomain];
        }else{
            $chosenDomain = $domainModule[$chosenDomain];
        }

        return $chosenDomain;
    }

    /**
     * Pobiera od użytkownika informacje, jakie pola z domeny chce umieścić w dto
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @param string $domain
     * @param array $listOfDomains
     * @return array
     */
    protected function askForDomainFields(InputInterface $input, OutputInterface $output, mixed $helper, string $domain, array $listOfDomains): array
    {
        $fields = [];
        $fields['all'] = 'Wszystkie pola';
        $fieldsToChoose = $this->getStraightFieldsFromDomain($domain);
        $chosenFields = [];
        if(!empty($fieldsToChoose)){
            foreach ($fieldsToChoose as $fieldName => $field) {
                $fields[$fieldName] = $fieldName;
            }

            $question = new ChoiceQuestion(
                'Wybierz pola z domeny',
                $fields,
                'id'
            );
            $question->setErrorMessage('Pole %s niepoprawne.');
            $question->setMultiselect(true);
            $chosenFields =  $helper->ask($input, $output, $question);
        }


        if (in_array('all', $chosenFields)) {
            return array_keys($fieldsToChoose);
        }

        return $chosenFields;
    }

    /**
     * Pobiera listę modułów
     * @return array
     * @throws \Exception
     */
    protected function getModulesList(): array
    {
        $standardVendor = 'vendor/wiseb2b-git/wiseb2b_20_backend/Wise';
        $localPath = $this->configService->get('app_project_dir') . '/';
        $dirBackend = $this->configService->get('backend_dictionary');

        $foundedPath = null;

        if(is_dir($localPath . $standardVendor )){
            $foundedPath = $localPath . $standardVendor;
        } elseif(is_dir($localPath . $dirBackend)){
            $foundedPath = $localPath . $dirBackend;
        }

        if($foundedPath == null){
            throw new \Exception('Nie znaleziono modułów');
        }

        $directories = array_filter(scandir($foundedPath), function($item) use ($foundedPath) {
            return is_dir($foundedPath . DIRECTORY_SEPARATOR . $item) && !in_array($item, ['.', '..']);
        });

        return $directories;
    }

    /**
     * W jakim podkatologu mają zostać utworzone pliki
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @param string $typeEndpoint
     * @param string $module
     * @return string
     */
    protected function askForDirName(InputInterface $input, OutputInterface $output, mixed $helper, string $typeEndpoint, string $module): string
    {
        $localPath = $this->configService->get('app_project_dir') . '/';
        $dirBackend = $this->configService->get('backend_dictionary');

        // W jakim podkatologu mają zostać utworzone endpointy
        $path = $localPath . '/' .  $dirBackend . '/' . $module . '/' . $typeEndpoint . '/Controller';

        if(is_dir($path)){

            $directories = array_filter(scandir($path), function($item) use ($path) {
                return is_dir($path . DIRECTORY_SEPARATOR . $item) && !in_array($item, ['.', '..']);
            });

            if(!empty($directories)){
                $directories[] = 'Chce wpisać ręcznie';

                $question = new ChoiceQuestion(
                    'W jakim podkatologu mają zostać utworzone pliki?',
                    $directories,
                );
                $question->setErrorMessage('Moduł %s niepoprawny.');

                $answer = $helper->ask($input, $output, $question);

                if($answer !== 'Chce wpisać ręcznie'){
                    return $answer;
                }
            }
        }

        $question = new Question('W jakim podkatologu mają zostać utworzone pliki? (np. Contract): ');

        return $helper->ask($input, $output, $question);
    }

    /**
     * Pobiera listę domen
     * @param string $module
     * @return array[]
     */
    protected function getDomains(string $module): array
    {
        $domains = [];
        $domainModule = [];
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        foreach ($metadata as $meta) {
            $nameFull = $meta->getName();
            $name = explode('\\', $nameFull);
            $name = end($name);

            $domains[$name] = $nameFull;
            if(str_contains($meta->getName(), $module)){
                $domainModule[$name] = $nameFull;
            }
        }

        return [
            'domains' => $domains,
            'domainModule' => $domainModule
        ];
    }

    /**
     * Generowanie pliku
     * @param string $path
     * @param string $templateName
     * @param array $parameters
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function generateFile(string $path, string $templateName, array $parameters): void
    {
        $content = $this->twig->render($templateName, $parameters);

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $content);
    }

    /**
     * Metoda zwraca liczbę mnogą i pojedynczą słowa
     * @param string $word
     * @return array
     */
    public static function getSingularAndPlural(string $word): array
    {
        $isPlural = str_ends_with($word, 's');

        if ($isPlural) {
            // Jeśli jest liczba mnoga, usuń 's' na końcu
            $singular = rtrim($word, 's');
            $plural = $word; // To już liczba mnoga
        } else {
            // Jeśli jest liczba pojedyncza, dodaj 's' na końcu
            $singular = $word;
            $plural = $word . 's';
        }

        return [
            'singular' => $singular,
            'plural' => $plural
        ];
    }

    /**
     * Zwraca pola z domeny (tylko typy proste)
     * @param string|null $class
     * @param array|null $chosenFields
     * @return array|null
     */
    protected function getStraightFieldsFromDomain(?string $class, ?array $chosenFields = null): ?array
    {
        if (empty($class) || !class_exists($class)) {
            return null;
        }

        $fields = [];

        $reflectionClass = new \ReflectionClass($class);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {

            if(!empty($chosenFields) && !in_array($property->getName(), $chosenFields)){
                continue;
            }

            $type = $property->getType()->getName();
            $allowNull = $property->getType()->allowsNull();

            if(in_array($type, ['string', 'float', 'bool', 'boolean','int'])){
                // Pobierz komentarz (docblock) właściwości
                $docComment = $property->getDocComment();
                $description = null;

                if ($docComment !== false) {
                    // Wyodrębnij pierwszą linię z komentarza (po usunięciu znaczników `/**` i `*/`)
                    $lines = explode("\n", $docComment);
                    if(isset($lines[1])){
                        $description = trim(str_replace(['/**', '*/', '*'], '', $lines[1]));
                    }
                }

                $fields[$property->getName()] = [
                    'type' => $type,
                    'allowNull' => $allowNull,
                    'name' => $property->getName(),
                    'nameU' => ucwords($property->getName()),
                    'description' => empty($description) ? null : $description
                ];
            }
        }

        return $fields;
    }

    protected function addDtoToNelmioApiDoc(string $module, string $namespace, string $typeEndpoint): void
    {
        $nameYaml = strtolower($module). '.yaml';
        $area = $typeEndpoint === 'ApiUi' ? 'api_ui_v2' : 'api_admin_v2';

        $path = $this->configService->get('app_project_dir') . '/config/packages/';
        $data = Yaml::parseFile($path . 'nelmio_api_doc.yaml');
        $foundFileInImport = false;
        foreach ($data['imports'] as $file){
            if(str_contains($file['resource'], $nameYaml)){
                $foundFileInImport = true;
                break;
            }
        }

        if (!$foundFileInImport) {
            $data['imports'][] = [
                'resource' => './nelmio_api_doc/' . $nameYaml
            ];

            $dumper = new Dumper();
            $yamlContent = $dumper->dump($data); // "2" oznacza poziom zagnieżdżenia

            file_put_contents($path . 'nelmio_api_doc.yaml', $yamlContent);
        }

        if(!file_exists($path  . 'nelmio_api_doc/' . $nameYaml)){
            $alias = explode('\\', $namespace);
            $alias = end($alias);
            $data = [];
            $data['nelmio_api_doc'] = [
                'models' => [
                    'names' => [
                        [
                            'alias' => $alias,
                            'type' => $namespace,
                            'areas' => [$area]
                        ]
                    ]
                ]
            ];

            $dumper = new Dumper();
            $yamlContent = $dumper->dump($data);

            file_put_contents($path  . 'nelmio_api_doc/' . $nameYaml, $yamlContent);
        }else{
            $data = Yaml::parseFile($path . 'nelmio_api_doc/' . $nameYaml);
            $foundModel = false;
            foreach ($data['nelmio_api_doc']['models']['names'] as $model){
                if($model['type'] === $namespace){
                    $foundModel = true;
                    break;
                }
            }

            if(!$foundModel){
                $alias = explode('\\', $namespace);
                $alias = end($alias);
                $data['nelmio_api_doc']['models']['names'][] = [
                    'alias' => $alias,
                    'type' => $namespace,
                    'areas' => [$area]
                ];

                $dumper = new Dumper();
                $yamlContent = $dumper->dump($data);

                file_put_contents($path  . 'nelmio_api_doc/' . $nameYaml, $yamlContent);
            }
        }
    }
}
