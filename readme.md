<p align="center"><img src="https://imgbbb.com/images/2020/02/09/Captura-de-Tela-2020-02-09-as-12.51.08.png" width="800"></p>

## Reembolso Application

Aplicação de Reembolso que inclui uma API para integração e também uma parte WEB para gerir os status, remoção, comprovantes dos reembolsos.

## Documentação da API

A API foi construida com base nos padrões Rest e sua documentação está disponivel na [Documentação do Postman](https://documenter.getpostman.com/view/6630459/SWTG7ben?version=latest)

## Módulos da API

Foram criados basicamente dois módulos. Um dos módulos denominado `Employee` fica responsável por gerenciar os funcionarios que possuem reembolsos. Já o outro módulo denominado `Refund` fica responsável por armazenar os reembolsos de um determinado funcionário.

## Autenticação

Tanto a API quanto o painel WEB implementam autenticação. A API implementa a autenticação JWT onde possui um endpoint para realizar o login e retornar o token de acesso.

Para criar um novo usuário, será necessário rodar um seeder presente na aplicação. Esta seeder irá gerar um usuário de exemplo para autenticação tanto web quanto via api.

## Estruturação Adicional

O projeto foi inicialmente projetado para ter somente a parte da api rest porém com o decorrer do desenvolvimento foi feito um painel administrativo para gerir os reembolsos.

Para isso o projeto em MVC implementa uma camada adicional de `Service` para comportar melhor as regras da aplicação.

## Instalação

Para rodar a aplicação com todos os recursos e funcionalidades garantidas, recomendo os seguintes passos:

1. Clonar este projeto com  
   `git clone https://github.com/araujodev/reembolso-api.git`

2. Dentro da pasta clonada executar o `composer install`

3. A partir do `.env.example` criar um novo arquivo chamado `.env`

4. Gerar a Key da aplicação com o comando `php artisan key:generate` e logo em seguida gerar a key do jwt com o comando `php artisan jwt:secret`

5. Atualizar os dados do banco de dados no arquivo `.env` e executar o migrate com `php artisan migrate`

6. Executar o Seeder para criar um usuário de exemplo e um funcionário com o comando `php artisan db:seed`

7. Utilizar os endpoints da api e a parte web para gerenciar a aplicação. Para servir a aplicação execute `php artisan serve`

## Recursos Utilizados

Abaixo deixo a lista dos recursos utilizados:

-   tymon/jwt-auth
-   nesbot/carbon
-   maatwebsite/excel

## Conclusao

Att Leandro Souza Araujo.

leandro.souara.web@gmail.com
