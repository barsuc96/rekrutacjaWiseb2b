<?php

namespace Wise\Core\Generator\Generators\ServiceFileGenerator;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Twig\Environment;
use Wise\Core\Generator\Abstract\AbstractGeneratorFile;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;

class ServiceFileGenerator extends AbstractGeneratorFile
{
    protected const EXTENSION = '.php';

    protected string $name = 'SERVICE';
    protected string $description = 'Generowanie plików dla serwisu';

    public function __construct(
        private readonly ConfigServiceInterface $configService,
        private readonly Environment $twig,
    ){}

    function generateFiles(InputInterface $input, OutputInterface $output, mixed $helper): void
    {
        $output->writeln('generowanie plików serwisu');

        // Wybór elementów do generowania
        $output->writeln('');
        $chosenOptions = $this->chooseElements($input, $output, $helper);

        // Ścieżka gdzie wygenerować serwis
        $output->writeln('');
        $path = $this->pathToGenerate($input, $output, $helper);

        // Nazwa serwisu
        $output->writeln('');
        $name = $this->nameFileGenerate($input, $output, $helper);

        $output->writeln('Wybrano: '. implode(', ', $chosenOptions));
        $output->writeln('Ścieżka: '. $path);
        $output->writeln('Nazwa: '. $name);
    }

    /**
     * Metoda wyświetla zapytanie do użytkownika o wybranie opcji generatora
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @return mixed
     */
    protected function chooseElements(InputInterface $input, OutputInterface $output, mixed $helper)
    {
        $question = new ChoiceQuestion(
            'Wybierz opcje:',
            [
                'params' => 'Dodatkowa klasa parametrów',
                'result' => 'Klasa rezultatu',
                'CommonServiceDto' => 'Parametr oraz Rezultat jako CommonServiceDto',
                'none' => 'Brak'
            ],
            'CommonServiceDto'
        );
        $question->setErrorMessage('Opcja %s niepoprawna.');
        $question->setMultiselect(true);
        return $helper->ask($input, $output, $question);
    }

    /**
     * Metoda pytająca o ścieżkę gdzie wygenerować plik
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @return string
     */
    protected function pathToGenerate(InputInterface $input, OutputInterface $output, mixed $helper): string
    {
        $question = new Question('Podaj ścieżke gdzie wygenerować plik (np: Wise/Cart/Service/Cart): ',);

        return $helper->ask($input, $output, $question);
    }

    /**
     * Nazwa serwisu
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param mixed $helper
     * @return string
     */
    protected function nameFileGenerate(InputInterface $input, OutputInterface $output, mixed $helper): string
    {
        $question = new Question('Nazwa serwisu (np. AddProductToCartService): ',);

        return $helper->ask($input, $output, $question);
    }


    protected function generateFilesService(array $chosenOptions, string $path, string $name): void
    {
        $localPath = $this->configService->get('app_project_dir');
        $namespace = str_replace('/', '\\', $path);
        $path = $localPath . '/' . str_replace('\\', '/', $namespace);
        $service = '';
        $serviceParams = '';
        $serviceResult = '';
        $params = '';
        $result = 'void';

        $params = in_array('params', $chosenOptions);

        if(!str_contains($name, 'Service')) {
            $name = $name.'Service';
        }

        if(in_array('params', $chosenOptions)) {

            $nameParams = str_replace('Service', 'Params', $name);
            $params = $nameParams . ' $params';
            $serviceParams = 'use ' . $namespace . '\\' . $nameParams . ';';

            $this->generateFile(
                path: $path . '/' . $nameParams . static::EXTENSION,
                templateName: 'ServiceParamsTemplate.twig',
                parameters: [
                    'namespace' => $namespace,
                    'name' => $nameParams,
                ]
            );
        }

        if(in_array('result', $chosenOptions)) {
            $nameResult = str_replace('Service', 'Result', $name);
            $result = $nameResult;

            $serviceResult = 'use ' . $namespace . '\\' . $nameResult . ';';

            $this->generateFile(
                path: $path . '/' . $nameResult . static::EXTENSION,
                templateName: 'ServiceResultTemplate.twig',
                parameters: [
                    'namespace' => $namespace,
                    'name' => $nameResult,
                ]
            );
        }

        if(in_array('CommonServiceDto', $chosenOptions)) {
            $serviceParams = 'use Wise\Core\Dto\CommonServiceDTO;';
            $service = 'use Wise\Core\Dto\CommonServiceDTO;';
            $params = 'CommonServiceDTO $commonServiceDTO';
            $result = 'CommonServiceDTO';
        }

        $namespaceInterface = $namespace . '\Interfaces';

        $this->generateFile(
            path: $path . '/' . 'Interfaces/' . $name . 'Interface' . static::EXTENSION,
            templateName: 'ServiceInterfaceTemplate.twig',
            parameters: [
                'namespace' => $namespace . '\Interfaces',
                'name' => $name . 'Interface',
                'params' => $params,
                'result' => $result,
                'serviceParams' => $serviceParams,
                'serviceResult' => $serviceResult,
            ]
        );


        $this->generateFile(
            path: $path . '/' . $name . static::EXTENSION,
            templateName: 'ServiceTemplate.twig',
            parameters: [
                'namespace' => $namespace,
                'namespaceInterface' => $namespaceInterface,
                'name' => $name,
                'params' => $params,
                'result' => $result,
                'service' => $service,
            ]
        );

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

    public function generate()
    {
        $this->generateFilesService(
          chosenOptions: ['params', 'result'],
          path: 'Wise/Cart/Service/Cart',
          name: 'TestCosTam'
        );
    }
}
