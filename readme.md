<p align="center"><img src="https://imgbbb.com/images/2020/02/09/Captura-de-Tela-2020-02-09-as-12.51.08.png" width="800"></p>

## Reembolso Application

Aplicacao de Reembolso que inclui uma API para integracao bem como uma parte WEB para gerir os status, remocao, comprovantes dos reembolsos.

## Documentacao da API

A API foi construida com base nos padroes Rest e sua documentacao está disponivel na [Documentacao do Postman](https://documenter.getpostman.com/view/6630459/SWTG7ben?version=latest)

## Modulos da API

Foi criado basicamente dois modulo. Um dos modulos denominado `Employee` fica responsavel por gerenciar os funcionarios que possuem reembolsos. Já o outro modulo denominado `Refund` fica responsavel por armazenar os reembolsos de um determinado funcionario.

## Autenticacao

Tanto a API quanto o painel WEB implementam autenticacao. A API implementa a autenticacao JWT onde possui um endpoint para realizar o login e retornar o token de acesso.

Para criar um novo usuario, a principio sera necessario rodar uma seeder presente na aplicacao. Esta seeder ira gerar um usuario ficticio para autenticacao tanto web quanto via api.

## Estruturacao Adicional

O projeto foi inicialmente projetado para ter somente a parte da api rest porem com o decorrer foi feito o painel administrativo para listar os reembolsos, alterar seus status, remove-los e mostrar o comprovante anexado.

Para isso o projeto em MVC implementa uma camada adicional de `Service` para comportar melhor as regras da aplicacao.

## Instalacao

Para rodar a aplicacao com todos os recursos e funcionalidades garantidas, recomendo os seguintes passos:

1. Clonar este projeto com  
   `git clone https://github.com/araujodev/reembolso-api.git`

2. Dentro da pasta clonada executar o `composer install`

3. A partir do `.env.example` criar um novo arquivo chamado `.env`

4. Gerar a Key da aplicacao com o comando `php artisan key:generate` e logo em seguida gerar a key do jwt com o comando `php artisan jwt:secret`

5. Atualizar os dados do banco de dados no arquivo `.env` e executar o migrate com `php artisan migrate`

6. Executar o Seeder para criar um usuario de exemplo e um funcionario com o comando `php artisan db:seed`

7. Utilizar os endpoints da api e a parte web para gerenciar a aplicacao. Para servir a aplicacao execute `php artisan serve`

## Recursos Utilizados

Abaixo deixo a lista dos recursos utilizados:

-   tymon/jwt-auth
-   nesbot/carbon
-   maatwebsite/excel

## Conclusao

Desde ja agradeço a oportunidade do desafio.

Att Leandro Souza Araujo.
