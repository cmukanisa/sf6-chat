# Sf6-Chat

L’objectif de cette exercice et de développer une messagerie instantané en utilisant une API
GraphQL

![Screenshot de l'app](./Screenshot-from-2022-11-10-08-49-30.png)
![Screenshot de l'app](./Screenshot-from-2022-11-10-08-17-00.png)
![Screenshot de l'app](./Screenshot-from-2022-11-10-08-17-07.png)

## TECHNOLOGIES UTILISEES

- Symfony 6.1
- graphql-php 
- Bootstrapp 5.2
- Api-platform pour l'intégration graphql
- MySQL (5.7)

# COMMENT DEMARRER L'APPLICATION

- Se positionner sur le dossier de travail:
````
cd sf6-chat
````
- Installer les packages PHP:
````
composer install
````
- Installer les packages javascript:
````
yarn install
````
- Compiler les packages:
````
yarn encore prod
````
- Generer la base des données:   
On suppose l'utilisateur mysql est __root__ et n'a pas de mot de passe.
````
php bin/console doctrine:database:create
````
- Generer mettre à jour le schémas de la BD:
````
php bin/console doctrine:migrations:migrate
````
- Generer les fausses données (fixtures) pour les tests:
````
php bin/console  doctrine:fixtures:load
````
- Installer les assets (pour api-platform et graphql) (qui se fait aussi automatiquement lors de l'installation du paquet): 
````
php bin/console  assets:install 
````
- Lancer messenger pour excuter les acivités en file d'attente (nous n'avons pas integré  le composant mercure vu que l'application dans notre cas fait pas du temps réel)
````
php bin/console messenger:consume async -vv
````

- Lancer le serveur:  
- S'assurer d'avoir installer symfony-cli (le binaire de symfony inclus dans ce projet est pour windows pour ceux qui utilise Windows)
- Dans notre cas, nous avons travailler sur ubuntu où nous avons eu à installer le projet symfony globalement (https://symfony.com/download)
````
symfony serve --port=8000
````

# EXPLORER L'APP

- Url de l'app (verifier que le port est le meme que celui du serveur)
````
http://localhost:8000
````

- Url de Graphql (verifier que le port est le meme que celui du serveur)
````
http://localhost:8000/api/graphql
````

# IDENTIFIANTS DE CONNEXION

Nous avons inserer 13 users ayant tous comme mot de passe __388221__ et des emails du type __email1@sf6chat.com__ à __email13@sf6chat.com__


# S'AUTHENTIFIER A L'API

````
curl -X 'POST' \
  'http://localhost:8000/api/login' \
  -H 'accept: application/json' \
  -H 'Content-Type: application/json' \
  -d '{
  "username": "email1@sf6chat.com",
  "password": "388221"
}'
````

````
{
	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NjgwNTg0OTEsImV4cCI6MzI5OTI3MjgyOSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiNjM2YzhiODZiY2E0ZSJ9.J5LYEGkSudPUBv2T9gcn08TE87DYg4B9-JPe9N6CNuw4uQfuldlZR37F_LgJ9Z2Dg2U27JV6NcwIJbBPearWp-5w7ze--vqeRo2pffSFuTNZo_idou-3jkuLCMOoAh_p88SbBiZqlzBLtfRtEu6i9ZvW5nNxXdOMg6IJ_JnFPX-qrI8YB2T6QDqHPKkUIEvfutn7jv5lPAQdx_yeqq8xTXPJwOdo1pLB1QaO3QZlZU7o6dnwW9Quk641CFbHQQwaT0yWRT2_cxMhuUGjugIYnSKh5tdLxwUdUPPkkSOJnomMfrgVg6orANfLwNlCh4LCZMJ1GDFHPWGA4uhLZAIOIw",
	"data": {
		"id": 106,
		"username": "636c8b86bca4e",
		"lastname": "Kuvalis",
		"firstname": "Dorian"
	}
}
````

Utiliser le __JWT__ dans l'en-tete __BEARER__ ou dans le parametre d'url pour s'authentifier sur l'API.